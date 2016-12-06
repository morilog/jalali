<?php

use Morilog\Jalali\jDate;

if (! function_exists('jdate')) {

    /**
     * @param string $str
     * @return jDate
     */
    function jdate($str = null)
    {
        return jDate::forge($str);
    }
}

if (! function_exists('jdate_modify')) {

    /**
     * @param jDate $jDate
     * @param string $modify
     * @return jDate
     * @internal param string $str
     */
    function jdate_modify(jDate &$jDate, $modify)
    {
        $jDate = $jDate->modify($modify);
    }
}
