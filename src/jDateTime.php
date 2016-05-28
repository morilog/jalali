<?php
namespace Morilog\Jalali;

use Carbon\Carbon;

/**
 * Class jDateTime
 * @package Morilog\Jalali
 */
class jDateTime
{

    private static $temp;
    /**
     * Converts a Gregorian date to Jalali.
     *
     * @param $gy
     * @param $gm
     * @param $gd
     * @return array
     * 0: Year
     * 1: Month
     * 2: Day
     */
    public static function toJalali($gy, $gm, $gd)
    {
        return self::d2j(self::g2d($gy, $gm, $gd));
    }

    /**
     * Converts a Jalali date to Gregorian.
     *
     * @param int $jy
     * @param int $jm
     * @param int $jd
     * @return array
     * 0: Year
     * 1: Month
     * 2: Day
     */
    public static function toGregorian($jy, $jm, $jd)
    {
        return self::d2g(self::j2d($jy, $jm, $jd));
    }

    /**
     * Checks whether a Jalaali date is valid or not.
     *
     * @param int $jy
     * @param int $jm
     * @param int $jd
     * @return bool
     */
    public static function isValidateJalaliDate($jy, $jm, $jd)
    {
        return $jy >= -61 && $jy <= 3177
        && $jm >= 1 && $jm <= 12
        && $jd >= 1 && $jd <= self::jalaliMonthLength($jy, $jm);
    }

    /**
     * Checks whether a date is valid or not.
     *
     * @param $year
     * @param $month
     * @param $day
     * @param bool $isJalali
     * @return bool
     */
    public static function checkDate($year, $month, $day, $isJalali = true)
    {
        return $isJalali === true ? self::isValidateJalaliDate($year, $month, $day) : checkdate($month, $day, $year);
    }

    /**
     *  Is this a leap year or not?
     *
     * @param $jy
     * @return bool
     */
    public static function isLeapJalaliYear($jy)
    {
        return self::jalaliCal($jy)['leap'] === 0;
    }

    /**
     * Number of days in a given month in a Jalaali year.
     *
     * @param int $jy
     * @param int $jm
     * @return int
     */
    public static function jalaliMonthLength($jy, $jm)
    {
        if ($jm <= 6) {
            return 31;
        }

        if ($jm <= 11) {
            return 30;
        }

        return self::isLeapJalaliYear($jy) ? 30 : 29;
    }


    /**
     * This function determines if the Jalaali (Persian) year is
     * leap (366-day long) or is the common year (365 days), and
     * finds the day in March (Gregorian calendar) of the first
     * day of the Jalaali year (jy).
     *
     * @param int $jy Jalaali calendar year (-61 to 3177)
     * @return array
     * leap: number of years since the last leap year (0 to 4)
     * gy: Gregorian year of the beginning of Jalaali year
     * march: the March day of Farvardin the 1st (1st day of jy)
     * @see: http://www.astro.uni.torun.pl/~kb/Papers/EMP/PersianC-EMP.htm
     * @see: http://www.fourmilab.ch/documents/calendar/
     */
    public static function jalaliCal($jy)
    {
        $breaks = [-61, 9, 38, 199, 426, 686, 756, 818, 1111, 1181, 1210
            , 1635, 2060, 2097, 2192, 2262, 2324, 2394, 2456, 3178
        ];

        $breaksCount = count($breaks);

        $gy = $jy + 621;
        $leapJ = -14;
        $jp = $breaks[0];

        if ($jy < $jp || $jy >= $breaks[$breaksCount - 1]) {
            throw new \InvalidArgumentException('Invalid Jalali year : ' . $jy);
        }

        $jump = 0;

        for ($i = 1; $i < $breaksCount; $i += 1) {
            $jm = $breaks[$i];
            $jump = $jm - $jp;

            if ($jy < $jm) {
                break;
            }

            $leapJ = $leapJ + self::div($jump, 33) * 8 + self::div(self::mod($jump, 33), 4);

            $jp = $jm;
        }

        $n = $jy - $jp;

        $leapJ = $leapJ + self::div($n, 33) * 8 + self::div(self::mod($n, 33) + 3, 4);

        if (self::mod($jump, 33) === 4 && $jump - $n === 4) {
            $leapJ += 1;
        }

        $leapG = self::div($gy, 4) - self::div((self::div($gy, 100) + 1) * 3, 4) - 150;

        $march = 20 + $leapJ - $leapG;

        if ($jump - $n < 6) {
            $n = $n - $jump + self::div($jump + 4, 33) * 33;
        }

        $leap = self::mod(self::mod($n + 1, 33) - 1, 4);

        if ($leap === -1) {
            $leap = 4;
        }

        return [
            'leap' => $leap,
            'gy' => $gy,
            'march' => $march
        ];
    }

    /**
     * @param $a
     * @param $b
     * @return float
     */
    public static function div($a, $b)
    {
        return ~~($a / $b);
    }

    /**
     * @param $a
     * @param $b
     * @return mixed
     */
    public static function mod($a, $b)
    {
        return $a - ~~($a / $b) * $b;
    }

    /**
     * @param $jdn
     * @return array
     */
    public static function d2g($jdn)
    {
        $j = 4 * $jdn + 139361631;
        $j += self::div(self::div(4 * $jdn + 183187720, 146097) * 3, 4) * 4 - 3908;
        $i = self::div(self::mod($j, 1461), 4) * 5 + 308;

        $gd = self::div(self::mod($i, 153), 5) + 1;
        $gm = self::mod(self::div($i, 153), 12) + 1;
        $gy = self::div($j, 1461) - 100100 + self::div(8 - $gm, 6);

        return [$gy, $gm, $gd];
    }

    /**
     * Calculates the Julian Day number from Gregorian or Julian
     * calendar dates. This integer number corresponds to the noon of
     * the date (i.e. 12 hours of Universal Time).
     * The procedure was tested to be good since 1 March, -100100 (of both
     * calendars) up to a few million years into the future.
     *
     * @param int $gy Calendar year (years BC numbered 0, -1, -2, ...)
     * @param int $gm Calendar month (1 to 12)
     * @param int $gd Calendar day of the month (1 to 28/29/30/31)
     * @return int Julian Day number
     */
    public static function g2d($gy, $gm, $gd)
    {
        return (
            self::div(($gy + self::div($gm - 8, 6) + 100100) * 1461, 4)
            + self::div(153 * self::mod($gm + 9, 12) + 2, 5)
            + $gd - 34840408
        ) - self::div(self::div($gy + 100100 + self::div($gm - 8, 6), 100) * 3, 4) + 752;

    }

    /**
     * Converts a date of the Jalaali calendar to the Julian Day number.
     *
     * @param int $jy Jalaali year (1 to 3100)
     * @param int $jm Jalaali month (1 to 12)
     * @param int $jd Jalaali day (1 to 29/31)
     * @return int  Julian Day number
     */
    public static function j2d($jy, $jm, $jd)
    {
        $jCal = self::jalaliCal($jy);

        return self::g2d($jCal['gy'], 3, $jCal['march']) + ($jm - 1) * 31 - self::div($jm, 7) * ($jm - 7) + $jd - 1;
    }


    /**
     * Converts the Julian Day number to a date in the Jalaali calendar.
     *
     * @param int $jdn Julian Day number
     * @return array
     * 0: Jalaali year (1 to 3100)
     * 1: Jalaali month (1 to 12)
     * 2: Jalaali day (1 to 29/31)
     */
    public static function d2j($jdn)
    {
        $gy = self::d2g($jdn)[0];
        $jy = $gy - 621;
        $jCal = self::jalaliCal($jy);
        $jdn1f = self::g2d($gy, 3, $jCal['march']);

        $k = $jdn - $jdn1f;

        if ($k >= 0) {
            if ($k <= 185) {
                $jm = 1 + self::div($k, 31);
                $jd = self::mod($k, 31) + 1;

                return [$jy, $jm, $jd];
            } else {
                $k -= 186;
            }
        } else {
            $jy -= 1;
            $k += 179;

            if ($jCal['leap'] === 1) {
                $k += 1;
            }
        }

        $jm = 7 + self::div($k, 30);
        $jd = self::mod($k, 30) + 1;

        return [$jy, $jm, $jd];
    }

    /**
     * Creates a DateTimeZone from a string or a DateTimeZone
     *
     * @param DateTimeZone|string|null $object
     *
     * @throws InvalidArgumentException
     *
     * @return DateTimeZone
     */
    protected static function safeCreateDateTimeZone($object)
    {
        if ($object === null) {
            // Don't return null... avoid Bug #52063 in PHP <5.3.6
            return new \DateTimeZone(date_default_timezone_get());
        }

        if ($object instanceof DateTimeZone) {
            return $object;
        }

        $tz = @timezone_open((string) $object);

        if ($tz === false) {
            throw new \InvalidArgumentException('Unknown or bad timezone ('.$object.')');
        }

        return $tz;
    }

    /**
     * @param $format
     * @param bool $stamp
     * @param DateTimeZone|string|null $timezone
     * @return mixed
     */
    public static function date($format, $stamp = false, $timezone = null)
    {
        $stamp = ($stamp !== false) ? $stamp : time();
        $timezone = is_null($timezone) ? config('app.timezone') : $timezone;
        $dateTime = new \DateTime('@' . $stamp, static::safeCreateDateTimeZone($timezone));


        //Find what to replace
        $chars = (preg_match_all('/([a-zA-Z]{1})/', $format, $chars)) ? $chars[0] : array();

        //Intact Keys
        $intact = array('B', 'h', 'H', 'g', 'G', 'i', 's', 'I', 'U', 'u', 'Z', 'O', 'P');
        $intact = self::filterArray($chars, $intact);
        $intactValues = array();

        foreach ($intact as $k => $v) {
            $intactValues[$k] = $dateTime->format($v);
        }
        //End Intact Keys

        //Changed Keys
        list($year, $month, $day) = array($dateTime->format('Y'), $dateTime->format('n'), $dateTime->format('j'));
        list($jYear, $jMonth, $jDay) = self::toJalali($year, $month, $day);

        $keys = array(
            'd',
            'D',
            'j',
            'l',
            'N',
            'S',
            'w',
            'z',
            'W',
            'F',
            'm',
            'M',
            'n',
            't',
            'L',
            'o',
            'Y',
            'y',
            'a',
            'A',
            'c',
            'r',
            'e',
            'T'
        );
        $keys = self::filterArray($chars, $keys, array('z'));
        $values = array();

        foreach ($keys as $k => $key) {

            $v = '';
            switch ($key) {
                //Day
                case 'd':
                    $v = sprintf("%02d", $jDay);
                    break;
                case 'D':
                    $v = self::getDayNames($dateTime->format('D'), true);
                    break;
                case 'j':
                    $v = $jDay;
                    break;
                case 'l':
                    $v = self::getDayNames($dateTime->format('l'));
                    break;
                case 'N':
                    $v = self::getDayNames($dateTime->format('l'), false, 1, true);
                    break;
                case 'S':
                    $v = 'ام';
                    break;
                case 'w':
                    $v = self::getDayNames($dateTime->format('l'), false, 1, true) - 1;
                    break;
                case 'z':
                    if ($jMonth > 6) {
                        $v = 186 + (($jMonth - 6 - 1) * 30) + $jDay;
                    } else {
                        $v = (($jMonth - 1) * 31) + $jDay;
                    }
                    self::$temp['z'] = $v;
                    break;
                //Week
                case 'W':
                    $v = is_int(self::$temp['z'] / 7) ? (self::$temp['z'] / 7) : intval(self::$temp['z'] / 7 + 1);
                    break;
                //Month
                case 'F':
                    $v = self::getMonthNames($jMonth);
                    break;
                case 'm':
                    $v = sprintf("%02d", $jMonth);
                    break;
                case 'M':
                    $v = self::getMonthNames($jMonth, true);
                    break;
                case 'n':
                    $v = $jMonth;
                    break;
                case 't':
                    $v = ($jMonth == 12) ? 29 : (($jMonth > 6 && $jMonth != 12) ? 30 : 31);
                    break;
                //Year
                case 'L':
                    $tmpObj = new \DateTime('@' . (time() - 31536000));
                    $v = $tmpObj->format('L');
                    break;
                case 'o':
                case 'Y':
                    $v = $jYear;
                    break;
                case 'y':
                    $v = $jYear % 100;
                    break;
                //Time
                case 'a':
                    $v = ($dateTime->format('a') == 'am') ? 'ق.ظ' : 'ب.ظ';
                    break;
                case 'A':
                    $v = ($dateTime->format('A') == 'AM') ? 'قبل از ظهر' : 'بعد از ظهر';
                    break;
                //Full Dates
                case 'c':
                    $v = $jYear . '-' . sprintf("%02d", $jMonth) . '-' . sprintf("%02d", $jDay) . 'T';
                    $v .= $dateTime->format('H') . ':' . $dateTime->format('i') . ':' . $dateTime->format('s') . $dateTime->format('P');
                    break;
                case 'r':
                    $v = self::getDayNames($dateTime->format('D'), true) . ', ' . sprintf("%02d",
                            $jDay) . ' ' . self::getMonthNames($jMonth, true);
                    $v .= ' ' . $jYear . ' ' . $dateTime->format('H') . ':' . $dateTime->format('i') . ':' . $dateTime->format('s') . ' ' . $dateTime->format('P');
                    break;
                //Timezone
                case 'e':
                    $v = $dateTime->format('e');
                    break;
                case 'T':
                    $v = $dateTime->format('T');
                    break;

            }
            $values[$k] = $v;

        }
        //End Changed Keys

        //Merge
        $keys = array_merge($intact, $keys);
        $values = array_merge($intactValues, $values);

        return strtr($format, array_combine($keys, $values));
    }

    /**
     * @param $format
     * @param bool $stamp
     * @param DateTimeZone|string|null $timezone
     * @return mixed
     */
    public static function strftime($format, $stamp = false, $timezone = null)
    {
        $str_format_code = array(
            "%a",
            "%A",
            "%d",
            "%e",
            "%j",
            "%u",
            "%w",
            "%U",
            "%V",
            "%W",
            "%b",
            "%B",
            "%h",
            "%m",
            "%C",
            "%g",
            "%G",
            "%y",
            "%Y",
            "%H",
            "%I",
            "%l",
            "%M",
            "%p",
            "%P",
            "%r",
            "%R",
            "%S",
            "%T",
            "%X",
            "%z",
            "%Z",
            "%c",
            "%D",
            "%F",
            "%s",
            "%x",
            "%n",
            "%t",
            "%%",
        );

        $date_format_code = array(
            "D",
            "l",
            "d",
            "j",
            "z",
            "N",
            "w",
            "W",
            "W",
            "W",
            "M",
            "F",
            "M",
            "m",
            "y",
            "y",
            "y",
            "y",
            "Y",
            "H",
            "h",
            "g",
            "i",
            "A",
            "a",
            "h:i:s A",
            "H:i",
            "s",
            "H:i:s",
            "h:i:s",
            "H",
            "H",
            "D j M H:i:s",
            "d/m/y",
            "Y-m-d",
            "U",
            "d/m/y",
            "\n",
            "\t",
            "%",
        );

        //Change Strftime format to Date format
        $format = str_replace($str_format_code, $date_format_code, $format);

        //Convert to date
        return self::date($format, $stamp, $timezone);
    }

    private static function getDayNames($day, $shorten = false, $len = 1, $numeric = false)
    {
        switch (strtolower($day)) {
            case 'sat':
            case 'saturday':
                $ret = 'شنبه';
                $n = 1;
                break;
            case 'sun':
            case 'sunday':
                $ret = 'یکشنبه';
                $n = 2;
                break;
            case 'mon':
            case 'monday':
                $ret = 'دوشنبه';
                $n = 3;
                break;
            case 'tue':
            case 'tuesday':
                $ret = 'سه شنبه';
                $n = 4;
                break;
            case 'wed':
            case 'wednesday':
                $ret = 'چهارشنبه';
                $n = 5;
                break;
            case 'thu':
            case 'thursday':
                $ret = 'پنجشنبه';
                $n = 6;
                break;
            case 'fri':
            case 'friday':
                $ret = 'جمعه';
                $n = 7;
                break;
            default:
                $ret = '';
                $n = -1;
        }

        return ($numeric) ? $n : (($shorten) ? mb_substr($ret, 0, $len, 'UTF-8') : $ret);
    }

    private static function getMonthNames($month, $shorten = false, $len = 3)
    {
        $ret = '';
        switch ($month) {
            case '1':
                $ret = 'فروردین';
                break;
            case '2':
                $ret = 'اردیبهشت';
                break;
            case '3':
                $ret = 'خرداد';
                break;
            case '4':
                $ret = 'تیر';
                break;
            case '5':
                $ret = 'امرداد';
                break;
            case '6':
                $ret = 'شهریور';
                break;
            case '7':
                $ret = 'مهر';
                break;
            case '8':
                $ret = 'آبان';
                break;
            case '9':
                $ret = 'آذر';
                break;
            case '10':
                $ret = 'دی';
                break;
            case '11':
                $ret = 'بهمن';
                break;
            case '12':
                $ret = 'اسفند';
                break;
        }

        return ($shorten) ? mb_substr($ret, 0, $len, 'UTF-8') : $ret;
    }

    private static function filterArray($needle, $haystack, $always = array())
    {
        foreach ($haystack as $k => $v) {
            if (!in_array($v, $needle) && !in_array($v, $always)) {
                unset($haystack[$k]);
            }

        }
        

        return $haystack;
    }


    /**
     * @param $format
     * @param $date
     * @return array
     */
    public static function parseFromFormat($format, $date)
    {
        // reverse engineer date formats
        $keys = array(
            'Y' => array('year', '\d{4}'),
            'y' => array('year', '\d{2}'),
            'm' => array('month', '\d{2}'),
            'n' => array('month', '\d{1,2}'),
            'M' => array('month', '[A-Z][a-z]{3}'),
            'F' => array('month', '[A-Z][a-z]{2,8}'),
            'd' => array('day', '\d{2}'),
            'j' => array('day', '\d{1,2}'),
            'D' => array('day', '[A-Z][a-z]{2}'),
            'l' => array('day', '[A-Z][a-z]{6,9}'),
            'u' => array('hour', '\d{1,6}'),
            'h' => array('hour', '\d{2}'),
            'H' => array('hour', '\d{2}'),
            'g' => array('hour', '\d{1,2}'),
            'G' => array('hour', '\d{1,2}'),
            'i' => array('minute', '\d{2}'),
            's' => array('second', '\d{2}'),
        );

        // convert format string to regex
        $regex = '';
        $chars = str_split($format);
        foreach ($chars as $n => $char) {
            $lastChar = isset($chars[$n - 1]) ? $chars[$n - 1] : '';
            $skipCurrent = '\\' == $lastChar;
            if (!$skipCurrent && isset($keys[$char])) {
                $regex .= '(?P<' . $keys[$char][0] . '>' . $keys[$char][1] . ')';
            } else {
                if ('\\' == $char) {
                    $regex .= $char;
                } else {
                    $regex .= preg_quote($char);
                }
            }
        }

        $dt = array();
        $dt['error_count'] = 0;
        // now try to match it
        if (preg_match('#^' . $regex . '$#', $date, $dt)) {
            foreach ($dt as $k => $v) {
                if (is_int($k)) {
                    unset($dt[$k]);
                }
            }
            if (!jDateTime::checkdate($dt['month'], $dt['day'], $dt['year'], false)) {
                $dt['error_count'] = 1;
            }
        } else {
            $dt['error_count'] = 1;
        }
        $dt['errors'] = array();
        $dt['fraction'] = '';
        $dt['warning_count'] = 0;
        $dt['warnings'] = array();
        $dt['is_localtime'] = 0;
        $dt['zone_type'] = 0;
        $dt['zone'] = 0;
        $dt['is_dst'] = '';

        if (strlen($dt['year']) == 2) {
            $now = self::forge('now');
            $x = $now->format('Y') - $now->format('y');
            $dt['year'] += $x;
        }

        $dt['year'] = isset($dt['year']) ? (int)$dt['year'] : 0;
        $dt['month'] = isset($dt['month']) ? (int)$dt['month'] : 0;
        $dt['day'] = isset($dt['day']) ? (int)$dt['day'] : 0;
        $dt['hour'] = isset($dt['hour']) ? (int)$dt['hour'] : 0;
        $dt['minute'] = isset($dt['minute']) ? (int)$dt['minute'] : 0;
        $dt['second'] = isset($dt['second']) ? (int)$dt['second'] : 0;

        return $dt;
    }

    /**
     * @param $format
     * @param $str
     * @return \DateTime
     */
    public static function createDatetimeFromFormat($format, $str)
    {
        $pd = self::parseFromFormat($format, $str);
        $gd = self::toGregorian($pd['year'], $pd['month'], $pd['day']);
        $date = new \DateTime();
        $date->setDate($gd[0], $gd[1], $gd[2]);
        $date->setTime($pd['hour'], $pd['minute'], $pd['second']);

        return $date;
    }

    /**
     * @param $format
     * @param $str
     * @return Carbon
     */
    public static function createCarbonFromFormat($format, $str)
    {
        return Carbon::createFromTimestamp(self::createDatetimeFromFormat($format, $str)->getTimestamp());
    }

    /**
     * Convert Latin numbers to persian numbers
     *
     * @param string $string
     * @return string
     */
    public static function convertNumbers($string)
    {
        $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
        $english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");

        return str_replace($english_array, $farsi_array, $string);
    }
}


