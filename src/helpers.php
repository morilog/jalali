<?php

if (! function_exists('jdate')) {

    /**
     * @param string $str
     * @return \Morilog\Jalali\Jalalian
     */
    function jdate($str = null)
    {
        return \Morilog\Jalali\Jalalian::forge($str);
    }
}