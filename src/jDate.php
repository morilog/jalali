<?php
namespace Morilog\Jalali;

/**
 * A LaravelPHP helper class for working w/ jalali dates.
 * by Sallar Kaboli <sallar.kaboli@gmail.com>
 *
 *
 * Based on Laravel-Date bundle
 * by Scott Travis <scott.w.travis@gmail.com>
 * http://github.com/swt83/laravel-date
 *
 *
 * @package     jDate
 * @author      Sallar Kaboli <sallar.kaboli@gmail.com>
 * @author      Morteza Parvini <m.parvini@outlook.com>
 * @link        http://
 * @basedon     http://github.com/swt83/laravel-date
 * @license     MIT License
 */
use Carbon\Carbon;

/**
 * Class jDate
 * @package Morilog\Jalali
 */
class jDate
{
    /**
     * @var Carbon
     */
    protected $dateTime;


    /**
     * @var array
     */
    protected $formats = array(
        'datetime' => '%Y-%m-%d %H:%M:%S',
        'date' => '%Y-%m-%d',
        'time' => '%H:%M:%S',
    );

    /**
     * @param string|null $str
     * @param null $timezone
     * @return $this
     */
    public static function forge($str = null, $timezone = null)
    {
        return new static($str, $timezone);
    }

    /**
     * Create a new jDate instance from a specific Jalali date and time.
     *
     * If any of $year, $month or $day are set to null their now() values will
     * be used.
     *
     * If $hour is null it will be set to its now() value and the default
     * values for $minute and $second will be their now() values.
     *
     * If no params are passed, now() values will be returned.
     *
     * @param null $year
     * @param null $month
     * @param null $day
     * @param null $hour
     * @param null $minute
     * @param null $second
     * @param null $tz
     * @return jDate
     */
    public static function create($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $tz = null)
    {
        $now = new jDate(null, $tz);

        $defaults = array_combine(array(
            'year',
            'month',
            'day',
            'hour',
            'minute',
            'second',
        ), explode('-', $now->format('Y-n-j-G-i-s')));

        $year = $year === null ? $defaults['year'] : $year;
        $month = $month === null ? $defaults['month'] : $month;
        $day = $day === null ? $defaults['day'] : $day;

        if ($hour === null) {
            $hour = $defaults['hour'];
            $minute = $minute === null ? $defaults['minute'] : $minute;
            $second = $second === null ? $defaults['second'] : $second;
        } else {
            $minute = $minute === null ? 0 : $minute;
            $second = $second === null ? 0 : $second;
        }

        static::fixWraps($year, $month, $day, $hour, $minute, $second);

        $gregorian = jDateTime::toGregorian($year, $month, $day);

        $year = str_pad($gregorian[0], 4, "0", STR_PAD_LEFT);
        $month = str_pad($gregorian[1], 2, "0", STR_PAD_LEFT);
        $day = str_pad($gregorian[2], 2, "0", STR_PAD_LEFT);
        $hour = str_pad($hour, 2, "0", STR_PAD_LEFT);
        $minute = str_pad($minute, 2, "0", STR_PAD_LEFT);
        $second = str_pad($second, 2, "0", STR_PAD_LEFT);

        $dateString = "{$year}-{$month}-{$day} {$hour}:{$minute}:{$second}";

        return static::forge($dateString, $tz);
    }

    /**
     * @param string|null $str
     * @param null $timezone
     */
    public function __construct($str = null, $timezone = null)
    {
        $this->dateTime = jDateTime::createDateTime($str, $timezone);
    }

    /**
     * @return Carbon
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param $format
     * @return bool|string
     */
    public function format($format)
    {
        // convert alias string
        if (in_array($format, array_keys($this->formats))) {
            $format = $this->formats[$format];
        }

        // if valid unix timestamp...
        if ($this->dateTime !== false) {
            return jDateTime::strftime($format, $this->dateTime->getTimestamp(), $this->dateTime->getTimezone());
        } else {
            return false;
        }
    }

    /**
     * @param string $str
     * @return $this
     */
    public function reforge($str)
    {
        $this->dateTime->modify($str);

        return $this;
    }

    /**
     * @return string
     */
    public function ago()
    {
        $now = time();
        $time = $this->getDateTime()->getTimestamp();

        // catch error
        if (!$time) {
            return false;
        }

        // build period and length arrays
        $periods = array('ثانیه', 'دقیقه', 'ساعت', 'روز', 'هفته', 'ماه', 'سال', 'قرن');
        $lengths = array(60, 60, 24, 7, 4.35, 12, 10);

        // get difference
        $difference = $now - $time;

        // set descriptor
        if ($difference < 0) {
            $difference = abs($difference); // absolute value
            $negative = true;
        }

        // do math
        for ($j = 0; $difference >= $lengths[$j] and $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        // round difference
        $difference = intval(round($difference));

        // return
        return number_format($difference) . ' ' . $periods[$j] . ' ' . (isset($negative) ? '' : 'پیش');
    }

    /**
     * @return bool|string
     */
    public function until()
    {
        return $this->ago();
    }
    
    /**
     * @return int
     */
    public function time()
    {
        return $this->dateTime->getTimestamp();
    }

}
