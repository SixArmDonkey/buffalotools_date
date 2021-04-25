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


/**
 * Settings used in various tests
 */
interface BuffaloToolsDateSettingsInterface
{
  /**
   * Format of DATE_TEST 
   */
  const DATE_FORMAT = 'Y-m-d H:i:s';
  
  /**
   * Second type of formatting used for datefactory tests.
   */
  const DATE_FORMAT2 = 'Y-m-d\TH:i:s\Z';
  
  /**
   * Test date as a string.
   * Use format defied by DATE_FORMAT
   */
  const DATE_TEST = '2021-01-01 00:00:00';
  
  /**
   * Test date matching date_format2
   */
  const DATE_TEST2 = '2021-01-01T00:00:00Z';
  
  /**
   * Test timezone 
   */
  const DATE_TIMEZONE = 'America/New_York';
  
  /**
   * UTC Timezone string 
   */
  const UTC_TIMEZONE = 'UTC';  
}