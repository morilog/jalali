<?php

use Morilog\Jalali\jDateTime;
use Morilog\Jalali\jDate;

class jDateTimeTest extends PHPUnit_Framework_TestCase
{
    public function testCheckDate()
    {
        $this->assertTrue(jDateTime::checkDate(1391, 2, 30, true));
        $this->assertFalse(jDateTime::checkDate(1395, 13, 10, true));
        $this->assertFalse(jDateTime::checkDate(1395, 12, 31, true));
        $this->assertFalse(jDateTime::checkDate(2015, 12, 31, true));
    }

    public function testToJalali()
    {
        $this->assertTrue(jDateTime::toJalali(2016, 5, 7) === [1395, 2, 18]);
        $this->assertFalse(jDateTime::toJalali(2015, 5, 7) === [1394, 2, 18]);
    }

    public function testToGregorian()
    {
        $this->assertTrue(jDateTime::toGregorian(1395, 2, 18) === [2016, 5, 7]);
        $this->assertFalse(jDateTime::toGregorian(1394, 2, 18) === [2015, 5, 7]);
    }

    public function testIsLeapJalaliYear()
    {
        $this->assertTrue(jDateTime::isLeapJalaliYear(1395));
        $this->assertFalse(jDateTime::isLeapJalaliYear(1394));
    }

    public function testStrftime()
    {
        $this->assertTrue(jDateTime::strftime('Y-m-d', strtotime('2016-05-8')) === '1395-02-19');
        $this->assertTrue(jDateTime::convertNumbers(jDateTime::strftime('Y-m-d', strtotime('2016-05-8'))) === '۱۳۹۵-۰۲-۱۹');
        $this->assertFalse(jDateTime::strftime('Y-m-d', strtotime('2016-05-8')) === '۱۳۹۵-۰۲-۱۹');
        
        $this->assertTrue(date_default_timezone_set('Asia/Tehran'));
        $this->assertTrue(jDateTime::strftime('Y-m-d', strtotime('2016-05-8')) === '1395-02-19');
        $this->assertTrue(jDateTime::strftime('Y-m-d', (new \DateTime('2016-05-8', new \DateTimeZone("UTC")))->getTimestamp()) === '1395-02-19');
        
        $this->assertTrue(date_default_timezone_set('UTC'));
    }

    public function test_parseFromPersian()
    {
        $jalaliDate = '1393/03/27';
        $date = jDateTime::parseFromFormat('Y/m/d', $jalaliDate);

        $this->assertEquals(1393, $date['year']);
        $this->assertEquals(03, $date['month']);
        $this->assertEquals(27, $date['day']);

        $date = jDateTime::parseFromFormat('Y-m-d H:i:s', '1395-03-15 21:00:00');
        $this->assertEquals(21, $date['hour']);
        $this->assertEquals(0, $date['minute']);
        $this->assertEquals(0, $date['second']);
    }

    public function testCreateDateTimeFormFormat()
    {
        $jdate = '1394/11/25 15:00:00';
        $gDateTime = jDatetime::createDatetimeFromFormat('Y/m/d H:i:s', $jdate);

        $this->assertTrue($gDateTime instanceof \DateTime);

        $this->assertTrue('2016-02-14 15:00:00' === $gDateTime->format('Y-m-d H:i:s'));
    }

    public function testCreateCarbonFormFormat()
    {
        $jdate = '1394/11/25 15:00:00';
        $carbon = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $jdate);

        $this->assertTrue($carbon instanceof \Carbon\Carbon);
        $this->assertTrue($carbon->day === 14);
        $this->assertTrue('2016-02-14 15:00:00' === $carbon->format('Y-m-d H:i:s'));

        $jalaiDateFormatted = jDate::forge($carbon->toDateString())->format('Y-m-d H:i:s');
        $jalaiDateTimeFormatted = jDate::forge($carbon->toDateTimeString())->format('Y-m-d H:i:s');
        $this->assertFalse( $jalaiDateFormatted === '1394-11-25 15:00:00');
        $this->assertTrue( $jalaiDateTimeFormatted === '1394-11-25 15:00:00');

    }
}
