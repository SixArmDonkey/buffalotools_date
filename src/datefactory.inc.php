<?php
/**
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * Copyright (c) 2012-2021 John Quinn <john@retail-rack.com>
 * 
 * @author John Quinn
 */

declare( strict_types=1 );

namespace buffalokiwi\buffalotools\date;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use InvalidArgumentException;


/**
 * DateFactory converts strings to DateTimeInterface instances based on a list of 
 * pre-configured formats.  
 * 
 * This always returns instances of DateTimeImmutable.
 */
class DateFactory implements IDateFactory
{
  /**
   * Local timezone string 
   * @var DateTimeZone
   */
  private $timezone;
  
  
  /**
   * UTC Timezone 
   * @var DateTimeZone
   */
  private DateTimeZone $utcTimezone;
  
  
  /**
   * Timezone as a string 
   * @var string
   */
  private $timezoneString;
  
  
  /**
   * Formats to try when converting from a string 
   * @var string[]
   */
  private $formats;
  
  
  /**
   * A singleton.
   * Try not to use this.
   * @var IDateFactory|null
   */
  private static ?IDateFactory $instance = null;
  
  
  /**
   * A happy little singleton anti-pattern.
   * This defaults to date_default_timezone_get, which may or may not be desirable.
   * @return IDateFactory
   */
  public static function getInstance() : IDateFactory
  {
    if ( self::$instance == null )
      self::$instance = new self();
    
    return self::$instance;
  }
  
  
  /**
   * Create a new DateFactory.
   * 
   * @param string $timezone local time zone.  Either the user's time zone or the server.  If this is passed as an empty 
   * string, then date_default_timezone_get() is used.
   * @param array $formats string[] An array of date/time format strings.  When a date string is passed to one of the create 
   * date methods, each format string will be used to convert the date string to a date object until the date is successfully
   * converted.  Formats are used in the order they were supplied.
   * @throws InvalidArgumentException if $formats is empty 
   */
  public function __construct( string $timezone = '', array $formats = [
      'Y-m-d H:i:s',
      'Y-m-d\TH:i:s\Z'
    ])
  {
    if ( empty( $formats ))
      throw new InvalidArgumentException( 'format must contain at least one date/time format string' );
    
    $this->timezoneString = ( empty( $timezone )) ? date_default_timezone_get() : $timezone;
    $this->timezone = new DateTimeZone( $this->timezoneString );
    $this->utcTimezone = new DateTimeZone( 'UTC' );
    $this->formats = $formats;
  }

  
  /**
   * Create an instance of DateTimeInterface set to now within the supplied timezone.
   * @param string $timezone local timezone 
   * @return DateTimeInterface now as a date object 
   */
  public function now( string $timezone = 'UTC' ) : DateTimeInterface
  {
    if ( $timezone == 'Z' )
      $timezone = 'UTC';
    
    $dt = new \DateTime( 'now', $this->utcTimezone );
    
    if ( $dt->getTimezone()->getName() != $timezone )
      $dt->setTimezone( new DateTimeZone( $timezone ));
    
    return DateTimeImmutable::createFromMutable( $dt );
  }
  
  
  /**
   * Create a DateTime object from some string.
   * 
   * This will attempt to convert $data to a DateTimeInterface using the format strings supplied in the
   * constructor.  
   * 
   * @param string $data Date string to parse
   * @param string $timezone Timezone of the passed date string in $data 
   * @return DateTimeInterface Converted date 
   */
  public function createDateTime( string $data, string $timezone = 'UTC' ) : DateTimeInterface
  {
    return $this->parse( $data, $timezone );
  }
  
  
  /**
   * Retrieve the local time zone string.
   * @return string time zone 
   */
  public function getLocalTimeZone() : \DateTimeZone
  {
    return $this->timezone;
  }
  
  
  /**
   * Create a IDateTime object from some string.
   * 
   * This will attempt to convert $data to a DateTimeInterface using the format strings supplied in the
   * constructor.  
   * 
   * @param string $data Date string to parse
   * @param string $timezone Timezone of the passed date string in $data 
   * @return DateTimeInterface Converted date 
   */  
  public function createIDateTime( string $data, string $timezone = 'UTC' ) : IDateTime
  {
    return new DateTimeWrapper( $this->createDateTime( $data, $timezone ), $this->timezone );    
  }
    
  
  /**
   * Create a DateTime object from some string.
   * 
   * This will attempt to convert $data to a DateTimeInterface using the format strings supplied in the
   * constructor.  
   * 
   * @param string $data Date string to parse
   * @param string $timezone Timezone of the passed date string in $data 
   * @return DateTimeInterface Converted date 
   */
  private function parse( string $data, string $timezone = 'UTC' ) : DateTimeImmutable
  {
    $tz = new DateTimeZone( $timezone );
    
    foreach( $this->formats as $format )
    {
      try {
        $res = DateTimeImmutable::createFromFormat( $format, $data, $tz );
        if ( $res === false )
          continue;
      } catch (Exception $ex) {
        //..Do nothing 
      }
    }
    
    return new DateTimeImmutable( $data, $tz );    
  }
}
