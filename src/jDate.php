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

/**
 * Class jDate
 * @package Morilog\Jalali
 */
class jDate
{
    /**
     * @var int
     */
    protected $time;

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
     * @return $this
     */
    public static function forge($str = null)
    {
        $class = __CLASS__;

        return new $class($str);
    }

    /**
     * @param string|null $str
     */
    public function __construct($str = null)
    {
        if ($str === null) {
            $this->time = time();
        } else {
            if (is_numeric($str)) {
                $this->time = $str;
            } else {
                $time = strtotime($str);

                if (!$time) {
                    $this->time = false;
                } else {
                    $this->time = $time;
                }
            }
        }
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $str
     * @param DateTimeZone $timezone
     * @return bool|string
     */
    public function format($str, $timezone = null)
    {
        // convert alias string
        if (in_array($str, array_keys($this->formats))) {
            $str = $this->formats[$str];
        }

        // if valid unix timestamp...
        if ($this->time !== false) {
            return jDateTime::strftime($str, $this->time, $timezone);
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
        if ($this->time !== false) {
            // amend the time
            $time = strtotime($str, $this->time);

            // if conversion fails...
            if (!$time) {
                // set time as false
                $this->time = false;
            } else {
                // accept time value
                $this->time = $time;
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function ago()
    {
        $now = time();
        $time = $this->getTime();

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
    

    
}
