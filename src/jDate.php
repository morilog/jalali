<?php namespace Morilog\Jalali;

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
 * @link        http://
 * @basedon     http://github.com/swt83/laravel-date
 * @license     MIT License
 */

class jDate
{
    protected $time;

    protected $formats = array(
        'datetime' => '%Y-%m-%d %H:%M:%S',
        'date' => '%Y-%m-%d',
        'time' => '%H:%M:%S',
    );

    public static function forge($str = null)
    {
        $class = __CLASS__;
        return new $class($str);
    }

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

    public function time()
    {
        return $this->time;
    }

    public function format($str, $convertNumbersToPersian = false)
    {
        // convert alias string
        if (in_array($str, array_keys($this->formats))) {
            $str = $this->formats[$str];
        }

        // if valid unix timestamp...
        if ($this->time !== false) {
            return jDateTime::strftime($str, $this->time, $convertNumbersToPersian);
        } else {
            return false;
        }
    }

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

    public function ago()
    {
        $now = time();
        $time = $this->time();

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

    public function until()
    {
        return $this->ago();
    }

    /**
     * @param $format
     * @param $date
     * @return array
     */
    public function parseFromFormat($format, $date)
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
            } else if ('\\' == $char) {
                $regex .= $char;
            } else {
                $regex .= preg_quote($char);
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

    public function carbonFromFormat($format, $str)
    {
        $jd = new jDate();
        $pd = $jd->parseFromFormat($format, $str);
        $gd = jDateTime::toGregorian($pd['year'], $pd['month'], $pd['day']);
        $carbon = Carbon::createFromDate();
        $carbon->setDateTime($gd[0], $gd[1], $gd[2], $pd['hour'], $pd['minute'], $pd['second']);
        return $carbon;

    }
}
