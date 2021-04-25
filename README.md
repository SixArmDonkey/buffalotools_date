# BuffaloKiwi Date Wrapper 

This package includes a simple date factory used for converting dates from various formats to instances of DateTimeInterface. 
and a wrapper for DateTime used for storing UTC and local time in a single object.

MIT License

---

## Installation

```
composer require buffalokiwi/buffalotools_date
```
  
---

## Overview

DateFactory is used to create instances of DateTimeInterface and IDateTime.  The idea is to pass the local timezone and a 
list of possible date format strings that will be encountered in some application.  Calling createDateTime() with 
a date string using any of the supplied formats (or formats supported by \DateTime) will return a valid DateTimeImmutable
object or throw an exception.

DateTimeWrapper is used to wrap an instance of DateTimeInterface and provides easy access to both UTC and Local time for 
a given DateTimeInterface object.


## Example

```php

$factory = new buffalokiwi\buffalotools\date\DateFactory( 'America/New_York' );

//..createDateTime returns whatever date you pass with the timezone set to whatever zone is supplied. 
//..Defaults to UTC.  No time zone conversions occurs within this method.
$utc = $factory->createDateTime( '2021-01-01 12:00:00' );        
print_r( $utc );
/*
DateTimeImmutable Object
(
    [date] => 2021-01-01 12:00:00.000000
    [timezone_type] => 3
    [timezone] => UTC
)
*/


//..Create a date time but set the time zone to new york.
$local = $factory->createDateTime( '2021-01-01 12:00:00', 'America/New_York' );
print_r( $local );
/*   
DateTimeImmutable Object
(
    [date] => 2021-01-01 12:00:00.000000
    [timezone_type] => 3
    [timezone] => America/New_York
)
*/


//..createIDateTime is the same as createDateTime, except it always returns the date in UTC.
//  If a non-UTC timezone is passed, the date is converted to UTC.
//  createIDateTime returns instances of IDateTime

//..Create a date time in UTC
$utc = $factory->createIDateTime( '2021-01-01 12:00:00' );
print_r( $utc->getUTC());
/*
DateTimeImmutable Object
(
    [date] => 2021-01-01 12:00:00.000000
    [timezone_type] => 3
    [timezone] => UTC
)
*/

print_r( $utc->getLocal());
/*
DateTimeImmutable Object
(
    [date] => 2021-01-01 07:00:00.000000
    [timezone_type] => 3
    [timezone] => America/New_York
)
*/


//..Create a local date time.  UTC will be a few hours in the future.
//..Using this method will set getLocal() to the supplied datetime.
$local = $factory->createIDateTime( '2021-01-01 12:00:00', 'America/New_York' );
print_r( $local->getUTC());
/*
DateTimeImmutable Object
(
    [date] => 2021-01-01 17:00:00.000000
    [timezone_type] => 3
    [timezone] => UTC
)
*/

print_r( $local->getLocal());
/*
DateTimeImmutable Object
(
    [date] => 2021-01-01 12:00:00.000000
    [timezone_type] => 3
    [timezone] => America/New_York
)
*/

```


The DateFactory has a method now() which can be used to return the current datetime in UTC or relative to UTC.


```php
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
```