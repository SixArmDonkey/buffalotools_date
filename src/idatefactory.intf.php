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

use DateTimeInterface;
use DateTimeZone;


/**
 * A factory that creates DateTime instances 
 */
interface IDateFactory
{

  /**
   * Create an instance of DateTimeInterface set to now within the supplied timezone.
   * @param string $timezone local timezone 
   * @return DateTimeInterface now as a date object 
   */
  public function now( string $timezone = 'UTC' ) : DateTimeInterface;
  
  
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
  public function createDateTime( string $data, string $timezone = 'UTC' ) : DateTimeInterface;
    
  
  /**
   * Retrieve the local time zone string.
   * @return string time zone 
   */
  public function getLocalTimeZone() : DateTimeZone;
  
  
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
  public function createIDateTime( string $data, string $timezone = 'UTC' ) : IDateTime;  
}
