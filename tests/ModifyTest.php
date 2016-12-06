<?php

use Carbon\Carbon;
use Morilog\Jalali\jDate;

class ModifyTest extends PHPUnit_Framework_TestCase
{
    /* ***************************************************************************
     *  Set Date and time with Modify function
     * ***************************************************************************
     */
    public function testSetDateTimeWithModify()
    {
        $jDate = jDate::create(1395, 1, 5, 20, 30, 0)->modify('1394-03-30 15:30:10');
        $this->assertSame('1394-03-30 15:30:10', $jDate->format('Y-m-d H:i:s'));
    }

    /* ***************************************************************************
     *  Year
     * ***************************************************************************
     */
    public function testAddAYear()
    {
        $jDate = jDate::create(1394, 3, 30, 20, 30, 0)->modify('+1 year');
        $this->assertSame('1395-03-30 20:30:00', $jDate->format('Y-m-d H:i:s'));
    }

    public function testAdd5Years()
    {
        $jDate = jDate::create(1394, 3, 30)->modify('+5 years');
        $this->assertSame('1399-03-30', $jDate->format('Y-m-d'));
    }

    public function testSub4Years()
    {
        $jDate = jDate::create(1394, 3, 30)->modify('-4 years');
        $this->assertSame('1390-03-30', $jDate->format('Y-m-d'));
    }

    /* ***************************************************************************
     *  Month
     * ***************************************************************************
     */
    public function testAdd1Month()
    {
        $jDate = jDate::create(1394, 3, 30)->modify('+1 month');
        $this->assertSame('1394-04-30', $jDate->format('Y-m-d'));
    }

    public function testAdd6Months()
    {
        $jDate = jDate::create(1394, 3, 31)->modify('+6 month');
        $this->assertSame('1394-10-01', $jDate->format('Y-m-d'));
        // This behaviour happens in original DateTime and Carbon too.
        // I don't know if it's ok to change this behaviour
        // in order to get "1394-09-30" or not.
    }

    public function testSub6Months()
    {
        $jDate = jDate::create(1394, 3, 10)->modify('-6 month');
        $this->assertSame('1393-09-10', $jDate->format('Y-m-d'));
    }

//    public function testSub2MonthsAgo()
//    {
//        $jDate = jDate::create(1394, 3, 10)->modify('-2 month ago');
//        $this->assertSame('1394-01-10', $jDate->format('Y-m-d'));
//    }

    /* ***************************************************************************
     *  Modify Days
     * ***************************************************************************
     */
    public function testAdd22Days()
    {
        $jDate = jDate::create(1394, 3, 10)->modify('22 days');
        $this->assertSame('1394-04-01', $jDate->format('Y-m-d'));
    }

    public function testSub1Days()
    {
        $jDate = jDate::create(1394, 3, 10)->modify('-11 Days');
        $this->assertSame('1394-02-30', $jDate->format('Y-m-d'));
    }

    /* ***************************************************************************
     *  Modify Weekdays
     * ***************************************************************************
     */
    public function testAdd2Weekdays()
    {
        $jDate = jDate::create(1395, 9, 10)->modify('+2 weekdays');
        $this->assertSame('1395-09-13', $jDate->format('Y-m-d'));
    }

    public function testAdd15Weekdays()
    {
        $jDate = jDate::create(1395, 9, 10)->modify('+15 weekdays');
        $this->assertSame('1395-09-28', $jDate->format('Y-m-d'));
    }

    public function testSub4Weekdays()
    {
        $jDate = jDate::create(1395, 9, 10)->modify('-4 weekdays');
        $this->assertSame('1395-09-06', $jDate->format('Y-m-d'));
    }

    public function testSub15Weekdays()
    {
        $jDate = jDate::create(1395, 9, 10)->modify('-15 weekdays');
        $this->assertSame('1395-08-23', $jDate->format('Y-m-d'));
    }

    /* ***************************************************************************
     *  Complicated Modify
     * ***************************************************************************
     */
    public function testAdd6YearsToADate()
    {
        $jDate = jDate::create()->modify('1394-03-30 +6 years');
        $this->assertSame('1400-03-30', $jDate->format('Y-m-d'));
    }

    public function testAdd2YearsToADateTime()
    {
        $jDate = jDate::create()->modify('1394-03-30 10:11:20 +2 years');
        $this->assertSame('1396-03-30 10:11:20', $jDate->format('Y-m-d H:i:s'));
    }

    public function testSetDateSub11DaysAdd3Months()
    {
        $jDate = jDate::create()->modify('1394-03-30 +3 months -11 days');
        $this->assertSame('1394-06-19', $jDate->format('Y-m-d'));
    }

    public function testSetDateAdd14weekday()
    {
        $jDate = jDate::create()->modify('1395-01-14 +14 weekdays');
        $this->assertSame('1395-01-30', $jDate->format('Y-m-d'));
    }

    /* ***************************************************************************
     *  Set Date By String
     * ***************************************************************************
     */
    public function testSetFullDateByString()
    {
        $carbon = Carbon::create()->modify('1 December 2016');
        $this->assertSame('2016-12-01', $carbon->format('Y-m-d'));

        $jDate = jDate::create()->modify('11 azar 1390');
        $this->assertSame('1390-09-11', $jDate->format('Y-m-d'));
    }

    public function testSetYearAndMonthByString()
    {
        $carbon = Carbon::create()->modify('january 2016');
        $this->assertSame('2016-01-01', $carbon->format('Y-m-d'));

        $jDate = jDate::create()->modify('esfand 1390');
        $this->assertSame('1390-12-01', $jDate->format('Y-m-d'));
    }

    public function testSetMonthAndDayOfCurrentYearByString()
    {
        $year = jDate::create()->format('Y');

        $jDate = jDate::create()->modify('1 Farvardin');
        $this->assertSame("$year-01-01", $jDate->format('Y-m-d'));
    }

    public function testSetFullDateByStringAndAdd3Days()
    {
        $carbon = Carbon::create()->modify('1 March 2016 +3 days');
        $this->assertSame('2016-03-04', $carbon->format('Y-m-d'));

        $jDate = jDate::create()->modify('1 azar 1395 +3 days');
        $this->assertSame('1395-09-04', $jDate->format('Y-m-d'));
    }


    /* ***************************************************************************
     *  Next | previous | current
     * ***************************************************************************
     */
    public function testNextYear()
    {
        $nextYear = jDate::create()->modify('+1 year')->format('Y-m-d');

        $jDate = jDate::create()->modify('next year');
        $this->assertSame($nextYear, $jDate->format('Y-m-d'));
    }

    public function testPreviousMonth()
    {
        $previousMonth = jDate::create()->modify('-1 month')->format('Y-m-d');

        $jDate = jDate::create()->modify('previous month');
        $this->assertSame($previousMonth, $jDate->format('Y-m-d'));
    }

    public function testNextDay()
    {
        $nextDay = jDate::create()->modify('+1 day')->format('Y-m-d');

        $jDate = jDate::create()->modify('next day');
        $this->assertSame($nextDay, $jDate->format('Y-m-d'));
    }

    /* ***************************************************************************
     *  ( first | last ) day of
     * ***************************************************************************
     */
    public function testFirstOfThisMonth()
    {
	    $carbon = Carbon::create(2016,11,10)->modify('first day of this month');
	    $this->assertSame('2016-11-01', $carbon->format('Y-m-d'));
	    $this->assertNotSame('00:00:00', $carbon->format('H:i:s'));

        $jDate = jDate::create(1395,5,22)->modify('first day of this month');
        $this->assertSame("1395-05-01", $jDate->format('Y-m-d'));
	    $this->assertNotSame('00:00:00', $jDate->format('H:i:s'));
    }

    public function testFirstOfNextMonth()
    {
	    $jDate = jDate::create(1395,5,22)->modify('first day of next month');
	    $this->assertSame("1395-06-01", $jDate->format('Y-m-d'));
    }

    public function testFirstDayOfEsfand1357()
    {
	    $jDate = jDate::create()->modify('first day of esfand 1395');
	    $this->assertSame("1395-12-01", $jDate->format('Y-m-d'));
    }

	public function testLastDayOfThisMonth()
	{
		$carbon = Carbon::create(2016,11,10)->modify('last day of this month');
		$this->assertSame('2016-11-30', $carbon->format('Y-m-d'));
		$this->assertNotSame('23:59:59', $carbon->format('H:i:s'));

		$jDate = jDate::create(1395,8,7)->modify('last day of this month');
		$this->assertSame("1395-08-30", $jDate->format('Y-m-d'));
		$this->assertNotSame('23:59:59', $jDate->format('H:i:s'));
	}

	public function testLastDayOfNextMonth()
	{
		$jDate = jDate::create(1395,8,7)->modify('last day of next month');
		$this->assertSame("1395-09-30", $jDate->format('Y-m-d'));
	}

    public function testLastDayOfEsfand1357()
    {
        $jDate = jDate::create()->modify('last day of esfand 1357');
        $this->assertSame("1357-12-29", $jDate->format('Y-m-d'));

	    $jDate = jDate::create()->modify('last day of esfand 1395');
	    $this->assertSame("1395-12-30", $jDate->format('Y-m-d'));
    }

}
