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

use buffalokiwi\buffalotools\date\DateTimeWrapper;
use PHPUnit\Framework\TestCase;


/**
 * Tests for the DateTimeWrapper class 
 */
class DateTimeWrapperTest extends TestCase implements BuffaloToolsDateSettingsInterface
{
 
  
  /**
   * The test instance 
   * @var DateTimeWrapper
   */
  private DateTimeWrapper $instance;
  
  
  /**
   * UTC Time for testing 
   * @var DateTimeInterface
   */
  private DateTimeInterface $utc;
  
  
  /**
   * Local time for testing 
   * @var DateTimeInterface
   */
  private DateTimeInterface $local;
  
  
  /**
   * Set up the test DateTimeWrapper instance 
   * @return void
   */
  public function setUp() : void
  {
    $tz = new DateTimeZone( self::DATE_TIMEZONE );
    
    
    $utc = new DateTime( self::DATE_TEST, new DateTimeZone( self::UTC_TIMEZONE ));
    $this->local = $utc->setTimezone( $tz );
    
    $this->utc = DateTimeImmutable::createFromFormat( 
      self::DATE_FORMAT, 
      self::DATE_TEST, 
      new DateTimeZone( self::UTC_TIMEZONE )
    );
    
    $this->instance = new DateTimeWrapper( $this->utc, $tz );
  }
    
  
  /**
   * Test that the utc date is equal to the utc date stored in the wrapper object
   * @return void
   */
  public function testGetUTC() : void
  {
    $this->assertEquals( $this->utc->format( self::DATE_FORMAT ), $this->instance->getUTC()->format( self::DATE_FORMAT ));
  }
  
  
  /**
   * Test that the local date is equal to the local date stored in the wrapper object
   * @return void
   */
  public function testGetLocal() : void
  {
    $this->assertEquals( $this->local->format( self::DATE_FORMAT ), $this->instance->getLocal()->format( self::DATE_FORMAT ));
  }
  
  
  /**
   * Test that the utc date is equal to the output of __toString() 
   * @return void
   */  
  public function testToString() : void
  {
    $this->assertEquals( $this->utc->format( 'Y-m-d\TH:i:s\Z' ), (string)$this->instance );
  }
}