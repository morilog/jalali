[![Build Status](https://travis-ci.org/morilog/jalali.svg?branch=master)](https://travis-ci.org/morilog/jalali)
morilog/jalali
======
- Jalali calendar is a solar calendar that was used in Persia, variants of which today are still in use in Iran as well as Afghanistan. [Read more on Wikipedia](http://en.wikipedia.org/wiki/Jalali_calendar) or see [Calendar Converter](http://www.fourmilab.ch/documents/calendar/).

- Calendar conversion is based on the [algorithm provided by Kazimierz M. Borkowski](http://www.astro.uni.torun.pl/~kb/Papers/EMP/PersianC-EMP.htm) and has a very good performance.

- CalendarUtils class was ported from [jalaali/jalaali-js](https://github.com/jalaali/jalaali-js)

## Version 3 features
- High human readable API
- DateTime manipulating API
- DateTime comparing API
- Immutable

## Installation Version 3.*
> If you are using version <= 2.*, please read [old docs](https://github.com/morilog/jalali/blob/v2.3.0/README.md)
#### Requirements:
- `php >= 7.0`

Run the Composer update command

    $ composer require morilog/jalali:3.*

<a name="basic-usage"></a>
## Basic Usage
In the current version, I introduced `Jalalian` class for manipulating Jalali date time
### Jalalian
In version >= 1.1,  you can use `jdate()` instead of `Jalalian::forge()`;
#### `now([$timestamp = null])`
``` php
// the default timestamp is Now
$date = \Morilog\Jalali\Jalalian::now()
// OR
$date = jdate();

// pass timestamps
$date = Jalalian::forge(1333857600);
// OR
$date = jdate(1333857600);

// pass human readable strings to make timestamps
$date = Jalalian::forge('last sunday');

// get the timestamp
$date = Jalalian::forge('last sunday')->getTimestamp(); // 1333857600

// format the timestamp
$date = Jalalian::forge('last sunday')->format('%B %d، %Y'); // دی 02، 1391
$date = Jalalian::forge('today')->format('%A, %d %B %y'); // جمعه، 23 اسفند 97

// get a predefined format
$date = Jalalian::forge('last sunday')->format('datetime'); // 1391-10-02 00:00:00
$date = Jalalian::forge('last sunday')->format('date'); // 1391-10-02
$date = Jalalian::forge('last sunday')->format('time'); // 00:00:00

// get relative 'ago' format
$date = Jalalian::forge('now - 10 minutes')->ago() // 10 دقیقه پیش
```

#### Methods api
---


```php
public static function now(\DateTimeZone $timeZone = null): Jalalian

$jDate = Jalalian::now();
```

---
```php
public static function fromCarbon(Carbon $carbon): Jalalian

$jDate = Jalalian::fromCarbon(Carbon::now());
```

---
```php
public static function fromFormat(string $format, string $timestamp, \DateTimeZone$timeZone = null): Jalalian 

$jDate = Jalalian::fromFormat('Y-m-d H:i:s', '1397-01-18 12:00:40');
```


---
```php
public static function forge($timestamp, \DateTimeZone $timeZone = null): Jalalian

// Alias fo fromDatetime
```

---
```php
public static function fromDateTime($dateTime, \DateTimeZone $timeZone = null): Jalalian

$jDate = Jalalian::fromDateTime(Carbon::now())
// OR 
$jDate = Jalalian::fromDateTime(new \DateTime());
// OR
$jDate = Jalalian::fromDateTime('yesterday');

```


---
```php
public function getMonthDays(): int

$date = (new Jalalian(1397, 1, 18))->getMonthDays() 
// output: 31
```

---
```php
public function getMonth(): int

$date = (new Jalalian(1397, 1, 18))->getMonth() 
// output: 1
```

---
```php
public function isLeapYear(): bool

$date = (new Jalalian(1397, 1, 18))->isLeapYear() 
// output: false

```

---
```php
public function getYear(): int

$date = (new Jalalian(1397, 1, 18))->getYear() 
// output: 1397
```

---
```php
public function subMonths(int $months = 1): Jalalian

$date = (new Jalalian(1397, 1, 18))->subMonths(1)->toString() 
// output: 1396-12-18 00:00:00

```

---
```php
public function subYears(int $years = 1): Jalalian

$date = (new Jalalian(1397, 1, 18))->subYears(1)->toString()
// output: 1396-01-18 00:00:00
```

---
```php
public function getDay(): int

$date = (new Jalalian(1397, 1, 18))->getDay() 
// output: 18

```

---
```php
public function getHour(): int

$date = (new Jalalian(1397, 1, 18, 12, 0, 0))->getHour() 
// output: 12


```

---
```php
public function getMinute(): int

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->getMinute() 
// output: 10

```

---
```php
public function getSecond(): int

$date = (new Jalalian(1397, 1, 18, 12, 10, 45))->getSecond() 
// output: 45
```

---
```php
public function getTimezone(): \DateTimeZone

// Get current timezone
```

---
```php
public function addMonths(int $months = 1): Jalalian

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->addMonths(1)->format('m') 
// output: 02

```

---
```php
public function addYears(int $years = 1): Jalalian

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->addYears(1)->format('Y') 
// output: 1398

```

---
```php
public function getDaysOf(int $monthNumber = 1): int

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->getDaysOf(1) 
// output: 31
```

---
```php
public function addDays(int $days = 1): Jalalian

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->addDays(1)->format('d') 
// output: 18

```

---
```php
public function toCarbon(): Carbon

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->toCarbon()->toDateTimeString() 
// output: 2018-04-07 12:10:00
```

---
```php
public function subDays(int $days = 1): Jalalian

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->subDays(10)->format('d') 
// output: 08
```

---
```php
public function addHours(int $hours = 1): Jalalian

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->addHours(1)->format('H') 
// output: 13

```

---
```php
public function subHours(int $hours = 1): Jalalian

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->subHours(1)->format('H') 
// output: 11

```

---
```php
public function addMinutes(int $minutes = 1): Jalalian

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->addMinutes(10)->format('i') 
// output: 22

```

---
```php
public function subMinutes(int $minutes = 1): Jalalian

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->subMinutes(10)->format('i') 
// output: 02

```

---
```php
public function addSeconds(int $secs = 1): Jalalian

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->addSeconds(10)->format('s') 
// output: 10

```

---
```php
public function subSeconds(int $secs = 1): Jalalian

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->subSeconds(10)->format('i:s') 
// output: 11:40


```

---
```php
public function equalsTo(Jalalian $other): bool

$date = (new Jalalian(1397, 1, 18, 12, 10, 0))->equalsTo(Jalalian::now()) 
// output: false

$date = Jalalian::now()->equalsTo(Jalalian::now()) 
// output: true

```

---
```php
public function equalsToCarbon(Carbon $carbon): bool

$date = Jalalian::now()->equalsToCarbon(Carbon::now())  
// output: true
```

---
```php
public function greaterThan(Jalalian $other): bool

$date = Jalalian::now()->greaterThan(Jalalian::now()->subDays(1)))  
// output: true
```

---
```php
public function greaterThanCarbon(Carbon $carbon): bool

$date = Jalalian::now()->greaterThanCarbon(Carbon::now()->subDays(1)))  
// output: true

```

---
```php
public function lessThan(Jalalian $other): bool

$date = Jalalian::now()->lessThan(Jalalian::now()->addDays(1)))  
// output: true

```

---
```php
public function lessThanCarbon(Carbon $carbon): bool

$date = Jalalian::now()->lessThanCarbon(Carbon::now()->addDays(1)))  
// output: true

```

---
```php
public function greaterThanOrEqualsTo(Jalalian $other): bool

$date = Jalalian::now()->greaterThan(Jalalian::now()->subDays(1)))  
// output: true

```

---
```php
public function greaterThanOrEqualsToCarbon(Carbon $carbon): bool

$date = Jalalian::now()->greaterThanOrEqualsToCarbon(Carbon::now()))  
// output: true

```

---
```php
public function lessThanOrEqualsTo(Jalalian $other): bool

$date = Jalalian::now()->lessThanOrEqualsTo(Jalalian::now()))  
// output: true

```

---
```php
public function lessThanOrEqualsToCarbon(Carbon $carbon): bool

$date = Jalalian::now()->lessThanOrEqualsToCarbon(Carbon::now()))  
// output: true

```

---
```php
public function isStartOfWeek(): bool

$date = (new Jalalian(1397, 6, 24))->isStartOfWeek()
// output: true

```
---
```php
public function getEndDayOfYear(): bool

$date = (new Jalalian(1397, 6, 24))->getEndDayOfYear()
// output: 1397, 12, 29

```
---
```php
public function getFirstDayOfMonth(): bool

$date = (new Jalalian(1397, 6, 24))->getEndDayOfYear()
// output: 1397, 6, 1

```
---
```php
public function getEndDayOfMonth(): bool

$date = (new Jalalian(1397, 6, 24))->getEndDayOfYear()
// output: 1397, 6, 30

```
---
```php
public function isSaturday(): bool

$date = (new Jalalian(1397, 6, 24))->isSaturday()
// output: true

```

---
```php
public function isDayOfWeek(int $day): bool

$date = (new Jalalian(1397, 6, 24))->isDayOfWeek(0)
// output: true

```

---
```php
public function isEndOfWeek(): bool

$date = (new Jalalian(1397, 6, 24))->isEndOfWeek()
// output: false

```

---
```php
public function isFriday(): bool

$date = (new Jalalian(1397, 6, 24))->isFriday()
// output: false

```

---
```php
public function isToday(): bool

$date = (new Jalalian(1397, 6, 24))->isToday()
// output: (!maybe) true

```

---
```php
public function isTomorrow(): bool

$date = (new Jalalian(1397, 6, 25))->isTomorrow()
// output: true

```

---
```php
public function isYesterday(): bool

$date = (new Jalalian(1397, 6, 23))->isYesterday()
// output: true

```

---
```php
public function isFuture(): bool

$date = (new Jalalian(1397, 6, 26))->isFuture()
// output: true

```

---
```php
public function isPast(): bool

$date = (new Jalalian(1397, 5, 24))->isPast()
// output: true

```

---
```php
public function toArray(): array
$date = (new Jalalian(1397, 6, 24))->toArray()
// output: (
//     [year] => 1397
//     [month] => 6
//     [day] => 24
//     [dayOfWeek] => 0
//     [dayOfYear] => 179
//     [hour] => 0
//     [minute] => 0
//     [second] => 0
//     [micro] => 0
//     [timestamp] => 1536969600
//     [formatted] => 1397-06-24 00:00:00
//     [timezone] =>
// )
```

---
```php
public function getDayOfWeek(): int

$date = (new Jalalian(1397, 5, 24))->getDayOfWeek()
// output: 0

```

---
```php
public function isSunday(): bool

$date = (new Jalalian(1397, 6, 24))->isSunday()
// output: false

```

---
```php
public function isMonday(): bool

$date = (new Jalalian(1397, 6, 26))->isMonday()
// output: true

```

---
```php
public function isTuesday(): bool

$date = (new Jalalian(1397, 6, 24))->isTuesday()
// output: false

```

---
```php
public function isWednesday(): bool

$date = (new Jalalian(1397, 6, 24))->isWednesday()
// output: false

```

---
```php
public function isThursday(): bool

$date = (new Jalalian(1397, 6, 22))->isThursday()
// output: true

```

---
```php
public function getDayOfYear(): int

$date = (new Jalalian(1397, 5, 24))->getDayOfYear()
// output: 179

```

---
```php
public function toString(): string
$date = (new Jalalian(1397, 5, 24))->isPast()
// output: 1397-05-24 00:00:00

```

---
```php
public function format(string $format): string

$date = (new Jalalian(1397, 5, 24))->format('y')
// output: 1397
// see php date formats

```

---
```php
public function __toString(): string

// Alias of toString()
```

---
```php
public function ago(): string

```

---
```php
public function getTimestamp(): int

```

---
```php
public function getNextWeek(): Jalalian

```

---
```php
public function getNextMonth(): Jalalian

```

---

### CalendarUtils
---


#### `checkDate($year, $month, $day, [$isJalali = true])`
```php
// Check jalali date
\Morilog\Jalali\CalendarUtils::checkDate(1391, 2, 30, true); // true

// Check jalali date
\Morilog\Jalali\CalendarUtils::checkDate(2016, 5, 7); // false

// Check gregorian date
\Morilog\Jalali\CalendarUtils::checkDate(2016, 5, 7, false); // true
```
---
#### `toJalali($gYear, $gMonth, $gDay)`
```php
\Morilog\Jalali\CalendarUtils::toJalali(2016, 5, 7); // [1395, 2, 18]
```
---
#### `toGregorian($jYear, $jMonth, $jDay)`
```php
\Morilog\Jalali\CalendarUtils::toGregorian(1395, 2, 18); // [2016, 5, 7]
```
---
#### `strftime($format, [$timestamp = false, $timezone = null])`
```php
CalendarUtils::strftime('Y-m-d', strtotime('2016-05-8')); // 1395-02-19
```
---
#### `createDateTimeFromFormat($format, $jalaiTimeString)`
```php
$Jalalian = '1394/11/25 15:00:00';

// get instance of \DateTime
$dateTime = \Morilog\Jalali\CalendarUtils::createDatetimeFromFormat('Y/m/d H:i:s', $Jalalian);

```
---
#### `createCarbonFromFormat($format, $jalaiTimeString)`
```php
$Jalalian = '1394/11/25 15:00:00';

// get instance of \Carbon\Carbon
$carbon = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d H:i:s', $Jalalian);

```
---
#### `convertNumbers($string)`
```php
// convert latin to persian
$date = \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime('2016-05-8')); // 1395-02-19
\Morilog\Jalali\CalendarUtils::convertNumbers($date); // ۱۳۹۵-۰۲-۱۹

// convert persian to latin
$dateString = \Morilog\Jalali\CalendarUtils::convertNumbers('۱۳۹۵-۰۲-۱۹', true); // 1395-02-19
\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $dateString)->format('Y-m-d'); //2016-05-8
```

---
#### `Carbon api-difference`

You can convert date/time to [briannesbitt/carbon](https://github.com/briannesbitt/carbon), thus being able to use it's [API](https://carbon.nesbot.com/docs/) to work with PHP DateTime class. 

##### [Difference](https://carbon.nesbot.com/docs/#api-difference) in months:

```php
// convert persian to Carbon
$date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', "1395-02-19")->toCarbon(); 
// ->toString() => Sun May 08 2016 00:00:00 GMT+0000

// Add 4 months to Carbon
$dateAdd4Months = $date->addMonths(4);

// Difference in months
$dateAdd4Months->DiffInMonths($date); //4
$dateAdd4Months->floatDiffInMonths($date); //4.0
```

---
## Formatting ##

For help in building your formats, checkout the [PHP strftime() docs](http://php.net/manual/en/function.strftime.php).

## Notes ##

The class relies on ``strtotime()`` to make sense of your strings, and ``strftime()`` to handle the formatting. Always check the ``time()`` output to see if you get false timestamps, it which case, means the class couldn't understand what you were asking it to do.

## License ##
- This bundle is created based on [Laravel-Date](https://github.com/swt83/laravel-date) by [Scott Travis](https://github.com/swt83) (MIT Licensed).
- [Jalali (Shamsi) DateTime](https://github.com/sallar/CalendarUtils) class included in the package is created by [Sallar Kaboli](http://sallar.me) and is released under the MIT License.
-  This package is created and modified by [Morteza Parvini](http://morilog.ir) for Laravel >= 5 and is released under the MIT License.
