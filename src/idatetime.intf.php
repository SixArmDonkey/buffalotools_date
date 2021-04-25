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


/**
 * A wrapper for a date/time. 
 * An easy way to access utc and local time.
 */
interface IDateTime
{
  /**
   * Retrieve the stored date in UTC
   * @return \DateTimeInterface date
   */
  public function getUTC() : DateTimeInterface;
  
  
  /**
   * Retrieve the stored date in the local time zone 
   * @return \DateTimeInterface date
   */
  public function getLocal() : DateTimeInterface;
}
