<?php

namespace Morilog\Jalali;

use Carbon\Exceptions\UnitException;
use Date;

/**
 * Trait Converter.
 *
 * Change date into different string formats and types and
 * handle the string cast.
 */
trait Converter
{

    /**
     * Format the instance as date
     *
     * @return string
     */
    public function toDateString()
    {
        return $this->format("Y/m/d");
    }

    /**
     * Format the instance as a readable date
     *
     * @return string
     */
    public function toFormattedDateString()
    {
        return $this->format('j F Y');
    }

    /**
     * Format the instance with the day, and a readable date
     *
     * @return string
     */
    public function toFormattedDayDateString()
    {
        return $this->format('l j F Y');
    }

    /**
     * Format the instance as time
     *
     * @param string $unitPrecision
     *
     * @return string
     */
    public function toTimeString($unitPrecision = 'second')
    {
        return $this->format(static::getTimeFormatByPrecision($unitPrecision));
    }

    /**
     * Format the instance as date and time
     *
     * @param string $unitPrecision
     *
     * @return string
     */
    public function toDateTimeString($unitPrecision = 'second')
    {
        return $this->format('Y/m/d ' . static::getTimeFormatByPrecision($unitPrecision));
    }

    /**
     * Format the instance as a readable date and time
     *
     * @param string $unitPrecision
     *
     * @return string
     */
    public function toFormattedDateTimeString($unitPrecision = 'second')
    {
        return $this->format('j F Y ' . static::getTimeFormatByPrecision($unitPrecision));
    }

    /**
     * Return a format from H:i to H:i:s.u according to given unit precision.
     *
     * @param string $unitPrecision "minute", "second", "millisecond" or "microsecond"
     *
     * @return string
     */
    public static function getTimeFormatByPrecision($unitPrecision)
    {
        switch (Date::singularUnit($unitPrecision)) {
            case 'minute':
                return 'H:i';
            case 'second':
                return 'H:i:s';
            case 'm':
            case 'millisecond':
                return 'H:i:s.v';
            case 'µ':
            case 'microsecond':
                return 'H:i:s.u';
        }

        throw new UnitException('Precision unit expected among: minute, second, millisecond and microsecond.');
    }

    /**
     * Format the instance as date and time T-separated with no timezone
     * echo Jalalian::now()->toDateTimeLocalString('minute'); // You can specify precision among: minute, second, millisecond and microsecond
     * ```
     *
     * @param string $unitPrecision
     *
     * @return string
     */
    public function toDateTimeLocalString($unitPrecision = 'second')
    {
        return $this->format('Y-m-d\T' . static::getTimeFormatByPrecision($unitPrecision));
    }

    /**
     * Format the instance with day, date and time
     *
     * @param string $unitPrecision
     *
     * @return string
     */
    public function toDayDateTimeString($unitPrecision = 'second')
    {
        return $this->format('l j F Y ' . static::getTimeFormatByPrecision($unitPrecision));
    }

    /**
     * Format the instance with the year, and a readable month
     *
     * @return string
     */
    public function toFormattedMonthYearString()
    {
        return $this->format('F Y');
    }

}
