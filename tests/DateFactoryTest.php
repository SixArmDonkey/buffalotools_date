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

use buffalokiwi\buffalotools\date\DateFactory;
use PHPUnit\Framework\TestCase;


/**
 * Tests the DateFactory object.
 * 
 * 
 */
class DateFactoryTest extends TestCase implements BuffaloToolsDateSettingsInterface
{
  
  /**
   * The date factory test instance 
   * @var DateFactory
   */
  private DateFactory $factory;
  
  
  public function setUp() : void
  {
    $this->factory = $this->createInstance();
    
    
    $factory = new DateFactory( 'America/New_York' );
    
    //..Retrieve "now" in UTC
    print_r( $factory->now());
    /* 
    DateTimeImmutable Object
    (
        [date] => 2021-04-25 16:22:07.073529
        [timezone_type] => 3
        [timezone] => UTC
    )
    */
    
    
    //..Retrieve "now" in some time zone relative to UTC.
    //..Converts UTC to the supplied time zone 
    print_r( $factory->now( 'America/New_York' ));
    /*
    DateTimeImmutable Object
    (
        [date] => 2021-04-25 12:22:07.073529
        [timezone_type] => 3
        [timezone] => America/New_York
    )
    */
    
    die;
    
    
  
  }
  
  
  /**
   * Tests: __construct() and getLocalTimeZone()
   * 
   * 1) When $timezone is empty, test that the timezone is equal to date_default_timezone_get()
   * 2) When $timezone is supplied, test that the timezone is equal to the supplied timezone 
   * 3) When $formats is empty, test that an \InvalidArgumentException is thrown 
   * 4) If timezone is invalid, test than \Exception is thrown 
   * 
   * 
   * @return void
   */
  public function testConstructAndGetLocalTimezone() : void
  {
    date_default_timezone_set( self::DATE_TIMEZONE );
    
    $instance = $this->createInstance();    
    $this->assertEquals( self::DATE_TIMEZONE, $instance->getLocalTimeZone()->getName());
    
    $instance = $this->createInstance( self::UTC_TIMEZONE );
    $this->assertEquals( self::UTC_TIMEZONE, $instance->getLocalTimeZone()->getName());
    
    $this->expectException( \InvalidArgumentException::class );
    new DateFactory( '', [] );
    
    $this->expectException( \Exception::class );
    new DateFactory( 'InvalidTimezone', [self::DATE_FORMAT] );
  }
  
  
  /**
   * Tests: now()
   * 
   * 1) Test that now() is equal to now UTC
   * 2) Test that passing a local time zone to now() is equal to the local date/time
   * 
   * @return void
   */
  public function testNow() : void
  {
    $now = ( new DateTimeImmutable( 'now', new DateTimeZone( self::UTC_TIMEZONE )))->format( 'YmdH' );
    $this->assertEquals( $now, $this->factory->now()->format( 'YmdH' ));
    
    $lNow = ( new DateTimeImmutable( 'now', new DateTimeZone( self::DATE_TIMEZONE )))->format( 'YmdH' );
    $this->assertEquals( $lNow, $this->factory->now( self::DATE_TIMEZONE )->format( 'YmdH' ));    
  }
  
  
  /**
   * Tests: createDateTime()
   * 
   * 1) Test that passing a date string matching a format supplied to the constructor returns a matching date object 
   * 2) Test that passing a second date string with "Z" forces the timezone to Z
   * 3) Test that passing an invalid date throws an exception 
   * 4) Test that passing no timezone returns utc time 
   * 
   * @return void
   */
  public function testCreateDateTime() : void
  {
    $local = ( new DateTime( self::DATE_TEST, new DateTimeZone( self::DATE_TIMEZONE )));
    $this->assertEquals( $local->format( self::DATE_FORMAT ), $this->factory->createDateTime( self::DATE_TEST, self::DATE_TIMEZONE )->format( self::DATE_FORMAT ));
    $this->assertEquals( $local->getTimezone()->getName(), $this->factory->createDateTime( self::DATE_TEST, self::DATE_TIMEZONE )->getTimezone()->getName());
    
    
    $local = ( new DateTime( self::DATE_TEST2, new DateTimeZone( self::DATE_TIMEZONE )));
    $dt = $this->factory->createDateTime( self::DATE_TEST2, self::DATE_TIMEZONE );
    $this->assertEquals( $local->format( self::DATE_FORMAT2 ), $dt->format( self::DATE_FORMAT2 ));
    $this->assertEquals( $local->getTimezone()->getName(), $dt->getTimezone()->getName());
    $this->assertEquals( 'Z', $dt->getTimezone()->getName());
    
    $this->expectException( \Exception::class );    
    $this->factory->createDateTime( 'invalid' );
    
    $utc = ( new DateTime( self::DATE_TEST, new DateTimeZone( self::UTC_TIMEZONE )));
    $dt = $this->factory->createDateTime( self::DATE_TEST );
    $this->assertEquals( $utc->format( self::DATE_FORMAT ), $dt->format( self::DATE_FORMAT ));
    $this->assertEquals( $utc->getTimezone()->getName(), $dt->getTimezone()->getName());
  }
  
  
  /**
   * Tests: createLocalDateTime()
   * 
   * This simply calls createDateTime() with the local timezone instead of utc.
   * 
   * @return void
   */
  public function testCreateLocalDateTime() : void
  {
    $local = ( new DateTime( self::DATE_TEST, new DateTimeZone( self::DATE_TIMEZONE )));
    $dt = $this->factory->createLocalDateTime( self::DATE_TEST, self::DATE_TIMEZONE );
    $this->assertEquals( $local->format( self::DATE_FORMAT ), $dt->format( self::DATE_FORMAT ));
    $this->assertEquals( $local->getTimezone()->getName(), $dt->getTimezone()->getName());
    
    
    $local = ( new DateTime( self::DATE_TEST2, new DateTimeZone( self::DATE_TIMEZONE )));
    $dt = $this->factory->createLocalDateTime( self::DATE_TEST2, self::DATE_TIMEZONE );
    $this->assertEquals( $local->format( self::DATE_FORMAT2 ), $dt->format( self::DATE_FORMAT2 ));
    $this->assertEquals( $local->getTimezone()->getName(), $dt->getTimezone()->getName());
    $this->assertEquals( 'Z', $dt->getTimezone()->getName());
    
    $this->expectException( \Exception::class );    
    $this->factory->createDateTime( 'invalid' );
    
    $utc = ( new DateTime( self::DATE_TEST, new DateTimeZone( self::UTC_TIMEZONE )));
    $dt = $this->factory->createLocalDateTime( self::DATE_TEST );
    $this->assertEquals( $utc->format( self::DATE_FORMAT ), $dt->format( self::DATE_FORMAT ));
    $this->assertEquals( $utc->getTimezone()->getName(), $dt->getTimezone()->getName());    
  }
  
  
  /**
   * Tests: getLocalTimeZone()
   * 
   * Tests that the returned time zone string matches the one passed to the constructor 
   * @return void
   */
  public function testGetLocalTimeZone() : void
  {
    $this->assertEquals( self::DATE_TIMEZONE, $this->factory->getLocalTimeZone()->getName());
  }
  
  
  /**
   * Tests: createIDateTime()
   * 
   * 1) Test that passing UTC datetime returns the same UTC datetime
   * 2) Test that passing local datetime returns local datetime 
   * 3) Test that passing zulu time returns UTC 
   * 
   * @return void
   */
  public function testCreateIDateTime() : void
  {
    $utc = new DateTime( self::DATE_TEST, new DateTimeZone( self::UTC_TIMEZONE ));    
    $dt = $this->factory->createIDateTime( self::DATE_TEST );
    $this->assertEquals( $utc->format( self::DATE_FORMAT ), $dt->getUTC()->format( self::DATE_FORMAT ));
    $this->assertEquals( $utc->getTimezone()->getName(), $dt->getUTC()->getTimezone()->getName());
    
    
    $utc = (new DateTime( self::DATE_TEST, new DateTimeZone( self::DATE_TIMEZONE )))->setTimezone( new DateTimeZone( self::UTC_TIMEZONE ));
    $local = new DateTime( self::DATE_TEST, new DateTimeZone( self::DATE_TIMEZONE ));
    
    $dt = $this->factory->createIDateTime( self::DATE_TEST, self::DATE_TIMEZONE );
    $this->assertEquals( $utc->format( self::DATE_FORMAT ), $dt->getUTC()->format( self::DATE_FORMAT ));
    $this->assertEquals( $utc->getTimezone()->getName(), $dt->getUTC()->getTimezone()->getName());
    
    $this->assertEquals( $local->format( self::DATE_FORMAT ), $dt->getLocal()->format( self::DATE_FORMAT ));
    $this->assertEquals( $local->getTimezone()->getName(), $dt->getLocal()->getTimezone()->getName());
  }
  
  
  /**
   * Creates a date factory with local timezone and both date format strings 
   * @param string $timezone the timezone to use 
   * @return DateFactory
   */
  private function createInstance( string $timezone = self::DATE_TIMEZONE ) : DateFactory
  {
    return new DateFactory( $timezone, [self::DATE_FORMAT, self::DATE_FORMAT2] );    
  }
}
