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

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;


/**
 * A wrapper that has a reference to a UTC date and the local counterpart.
 * 
 * Pass a UTC date to the constructor with a local time zone, and this will store a reference to the datetime in both 
 * timezones.
 */
class DateTimeWrapper implements IDateTime
{
  /**
   * UTC date
   * @var DateTimeInterface
   */
  private DateTimeInterface $utcDate;
  
  /**
   * Local date 
   * @var DateTimeInterface
   */
  private DateTimeInterface $localDate;
  
  
  /**
   * Date/time format string used in the __toString() method 
   * @var string
   */
  private string $toStringFormat;
  
  
  /**
   * Create a new DateTimeWrapper instance.
   * 
   * If the supplied date is not in utc, it will be converted to utc and stored in the utc date property.
   * The local date is always returns as the converted utc date set to the supplied $localZone timezone.
   * 
   * @param \buffalokiwi\buffalotools\date\DateTimeInterface $utcDate UTC date to store 
   * @param \buffalokiwi\buffalotools\date\DateTimeZone $localZone local zone 
   * @param string $toStringFormat Date/time format string used in the __toString() method 
   */
  public function __construct( DateTimeInterface $utcDate, DateTimeZone $localZone, string $toStringFormat = 'Y-m-d\TH:i:s\Z' )
  {    
    $z = new DateTimeZone( 'UTC' );
    $tz = $utcDate->getTimezone();
    $isUTC = ( $tz->getName() == 'UTC' || $tz->getName() == 'Z' );
    
    if ( $isUTC )
      $tz = $z;
    
    $dt = new DateTime( $utcDate->format( 'Y-m-d H:i:s' ), $tz );
    
    if ( $isUTC )
      $this->utcDate = DateTimeImmutable::createFromMutable( $dt );
    else
      $this->utcDate = DateTimeImmutable::createFromMutable( $dt->setTimezone( $z ));
    
    $this->localDate = $this->utcDate->setTimezone( $localZone );

    $this->toStringFormat = $toStringFormat;
  }
  
  
  /**
   * Retrieve the stored date in UTC
   * @return \DateTimeInterface date
   */
  public function getUTC() : DateTimeInterface
  {
    return $this->utcDate;
  }
  
  
  /**
   * Retrieve the stored date in the local time zone 
   * @return \DateTimeInterface date
   */
  public function getLocal() : DateTimeInterface
  {
    return $this->localDate;
  }

  
  /**
   * Convert the UTC datetime to a string.
   * Uses the format string passed in the constructor 
   * @return string formatted string 
   */
  public function __toString() : string 
  {
    return $this->utcDate->format( $this->format );
  }
}
