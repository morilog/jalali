<?php

namespace Morilog\Jalali\Tests;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
use PHPUnit\Framework\TestCase;

final class JalalianTest extends TestCase
{
    public function testCreateFromConstructor()
    {
        $jDate = new Jalalian(1397, 1, 25);
        $this->assertTrue($jDate instanceof Jalalian);
        $this->assertEquals($jDate->getDay(), 25);
        $this->assertEquals($jDate->getYear(), 1397);
        $this->assertEquals($jDate->getMonth(), 1);

        $this->assertEquals($jDate->format('Y-m-d H:i:s'), '1397-01-25 00:00:00');
    }

    public function testGetDayOfYear()
    {
        $jDate = new Jalalian(1397, 1, 25);
        $this->assertEquals($jDate->getDayOfYear(), 25);

        $jDate = new Jalalian(1397, 5, 20);
        $this->assertEquals($jDate->getDayOfYear(), 144);

        $jDate = new Jalalian(1397, 7, 3);
        $this->assertEquals($jDate->getDayOfYear(), 189);

        $jDate = new Jalalian(1397, 12, 29);
        $this->assertEquals($jDate->getDayOfYear(), 365);

        $jDate = new Jalalian(1395, 12, 30);
        $this->assertTrue($jDate->isLeapYear());
        $this->assertEquals($jDate->getDayOfYear(), 366);
    }

    public function testModifiers()
    {
        $jDate = new Jalalian(1397, 1, 18);

        $this->assertEquals($jDate->addYears()->getYear(), 1398);
        $this->assertEquals($jDate->addMonths(11)->getMonth(), 12);
        $this->assertEquals($jDate->addMonths(11)->addDays(20)->getMonth(), 1);
        $this->assertEquals($jDate->subDays(8)->getNextMonth()->getMonth(), 2);

        $jDate = Jalalian::fromCarbon(Carbon::createFromDate(2019, 1, 1));
        $this->assertEquals($jDate->addMonths(4)->getYear(), 1398);

        $jDate = new Jalalian(1397, 1, 31);
        $this->assertEquals($jDate->addMonths(1)->getDay(), 31);
        $this->assertEquals($jDate->addYears(3)->getDay(), 31);
        $this->assertEquals($jDate->addMonths(36)->toString(), $jDate->addYears(3)->toString());
        $this->assertEquals($jDate->subYears(10)->toString(), (new Jalalian(1387, 1, 31))->toString());
        $this->assertTrue($jDate->subYears(2)->subMonths(34)->equalsTo(new Jalalian(1392, 03, 31)));

        $jDate = (new Jalalian(1397, 6, 11))->subMonths(1);
        $this->assertEquals($jDate->getMonth(), 5);

        $this->assertEquals((new Jalalian(1397, 7, 1))->subMonths(1)->getMonth(), 6);

        $jDate = Jalalian::now();
        $month = $jDate->getMonth();
        if ($month > 1) {
            $this->assertEquals($month - 1, $jDate->subMonths()->getMonth());
        }


        $jDate = Jalalian::fromFormat('Y-m-d', '1397-12-12');
        $this->assertEquals('1398-01-12', $jDate->addMonths(1)->format('Y-m-d'));

        $jDate = Jalalian::fromFormat('Y-m-d', '1397-11-30');
        $this->assertEquals('1397-12-29', $jDate->addMonths(1)->format('Y-m-d'));

        $jDate = Jalalian::fromFormat('Y-m-d', '1397-06-30');
        $this->assertEquals('1397-07-30', $jDate->addMonths(1)->format('Y-m-d'));

        $jDate = Jalalian::fromFormat('Y-m-d', '1397-06-31');
        $this->assertEquals('1397-07-30', $jDate->addMonths(1)->format('Y-m-d'));

        $jDate = Jalalian::fromFormat('Y-m-d', '1395-12-30');
        $this->assertEquals('1399-12-30', $jDate->addMonths(48)->format('Y-m-d'));

        $jDate = Jalalian::fromFormat('Y-m-d', '1395-12-30');
        $this->assertEquals('1398-12-29', $jDate->addMonths(36)->format('Y-m-d'));
    }

    public function testForge()
    {
        $jDate = Jalalian::forge(strtotime('now'));
        $this->assertTrue($jDate instanceof Jalalian);
        $this->assertTrue($jDate->getTimestamp() === strtotime('now'));

        $jDate = Jalalian::forge(1333857600);
        $this->assertEquals($jDate->toString(), '1391-01-20 04:00:00');

        $jDate = Jalalian::forge('last monday');
        $this->assertTrue($jDate instanceof Jalalian);

        $jDate = Jalalian::forge(1552608000);
        $this->assertEquals('1397-12-24', $jDate->format('Y-m-d'));
    }

    public function testMaximumYearFormatting()
    {
        $jDate = Jalalian::fromFormat('Y-m-d', '1800-12-01');
        $this->assertEquals(1800, $jDate->getYear());
        $this->assertEquals($jDate->format('Y-m-d'), '1800-12-01');

        // issue-110
        $jDate = Jalalian::fromFormat('Y-m-d', '1416-12-01');
        $this->assertEquals(1416, $jDate->format('Y'));
    }

    public function testGetWeekOfMonth()
    {
        $jDate = new Jalalian(1400, 1, 8);
        $this->assertEquals($jDate->getWeekOfMonth(), 2);

        $jDate = new Jalalian(1400, 5, 13);
        $this->assertEquals($jDate->getWeekOfMonth(), 3);

        $jDate = new Jalalian(1390, 11, 11);
        $this->assertEquals($jDate->getWeekOfMonth(), 2);

        $jDate = new Jalalian(1395, 7, 20);
        $this->assertEquals($jDate->getWeekOfMonth(), 4);

        $jDate = new Jalalian(1401, 1, 5);
        $this->assertEquals($jDate->getWeekOfMonth(), 1);

        $jDate = new Jalalian(1390, 8, 7);
        $this->assertEquals($jDate->getWeekOfMonth(), 2);


        $jDate = new Jalalian(1390, 8, 27);
        $this->assertEquals($jDate->getWeekOfMonth(), 4);

        $jDate = new Jalalian(1390, 7, 1);
        $this->assertEquals($jDate->getWeekOfMonth(), 1);

        $jDate = new Jalalian(1390, 7, 2);
        $this->assertEquals($jDate->getWeekOfMonth(), 2);

        $jDate = new Jalalian(1390, 7, 30);
        $this->assertEquals($jDate->getWeekOfMonth(), 6);

        $jDate = new Jalalian(1390, 6, 15);
        $this->assertEquals($jDate->getWeekOfMonth(), 3);

        $jDate = new Jalalian(1390, 6, 25);
        $this->assertEquals($jDate->getWeekOfMonth(), 4);

        $jDate = new Jalalian(1390, 6, 26);
        $this->assertEquals($jDate->getWeekOfMonth(), 5);

        $jDate = new Jalalian(1401, 3, 7);
        $this->assertEquals($jDate->getWeekOfMonth(), 2);
    }

    public function testGetFirstDayOfWeek()
    {
        $jDate = new Jalalian(1401, 1, 23);
        $this->assertEquals($jDate->getFirstDayOfWeek()->format('Y-m-d'), '1401-01-20');

        $jDate = new Jalalian(1395, 4, 24);
        $this->assertEquals($jDate->getFirstDayOfWeek()->format('Y-m-d'), '1395-04-19');

        $jDate = new Jalalian(1398, 11, 7);
        $this->assertEquals($jDate->getFirstDayOfWeek()->format('Y-m-d'), '1398-11-05');

        $jDate = new Jalalian(1400, 8, 19);
        $this->assertEquals($jDate->getFirstDayOfWeek()->format('Y-m-d'), '1400-08-15');
    }

    public function testGetFirstDayOfMonth()
    {
        $jDate = new Jalalian(1401, 1, 23);
        $this->assertEquals($jDate->getFirstDayOfMonth()->format('Y-m-d'), '1401-01-01');

        $jDate = new Jalalian(1390, 5, 14);
        $this->assertEquals($jDate->getFirstDayOfMonth()->format('Y-m-d'), '1390-05-01');

        $jDate = new Jalalian(1399, 2, 29);
        $this->assertEquals($jDate->getFirstDayOfMonth()->format('Y-m-d'), '1399-02-01');

        $jDate = new Jalalian(1398, 10, 10);
        $this->assertEquals($jDate->getFirstDayOfMonth()->format('Y-m-d'), '1398-10-01');
    }

    public function testGetFirstDayOfYear()
    {
        $jDate = new Jalalian(1401, 6, 11);
        $this->assertEquals($jDate->getFirstDayOfYear()->format('Y-m-d'), '1401-01-01');

        $jDate = new Jalalian(1399, 11, 28);
        $this->assertEquals($jDate->getFirstDayOfYear()->format('Y-m-d'), '1399-01-01');

        $jDate = new Jalalian(1394, 1, 12);
        $this->assertEquals($jDate->getFirstDayOfYear()->format('Y-m-d'), '1394-01-01');

        $jDate = new Jalalian(1393, 9, 5);
        $this->assertEquals($jDate->getFirstDayOfYear()->format('Y-m-d'), '1393-01-01');
    }

    public function testAddDay()
    {
        $jDate = new Jalalian(1401, 6, 31);
        $this->assertEquals($jDate->addDay()->format('Y-m-d'), '1401-07-01');
    }

    public function testSubDay()
    {
        $jDate = new Jalalian(1401, 6, 1);
        $this->assertEquals($jDate->subDay()->format('Y-m-d'), '1401-05-31');
    }

    public function testGetLastWeek()
    {
        $jDate = new Jalalian(1401, 6, 8);
        $this->assertEquals($jDate->getLastWeek()->format('Y-m-d'), '1401-06-01');
    }

    public function testGetLastMonth()
    {
        $jDate = new Jalalian(1401, 6, 8);
        $this->assertEquals($jDate->getLastMonth()->format('Y-m-d'), '1401-05-08');
    }

    public function testGetFirstDayOfQuarter()
    {
        $jDate = new Jalalian(1402, 1, 25);
        $this->assertEquals('1402-01-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 2, 25);
        $this->assertEquals('1402-01-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 3, 25);
        $this->assertEquals('1402-01-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 4, 25);
        $this->assertEquals('1402-04-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 5, 25);
        $this->assertEquals('1402-04-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 6, 25);
        $this->assertEquals('1402-04-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 7, 25);
        $this->assertEquals('1402-07-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 8, 25);
        $this->assertEquals('1402-07-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 9, 25);
        $this->assertEquals('1402-07-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 10, 25);
        $this->assertEquals('1402-10-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 11, 19);
        $this->assertEquals('1402-10-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 12, 25);
        $this->assertEquals('1402-10-01', $jDate->getFirstDayOfQuarter()->format('Y-m-d'));

    }

    public function testGetEndDayOfWeek()
    {
        /*
         *  --------------------------------
         *       Day 1402 (March 2024)
         *  --------------------------------
         *    Sat Sun Mon Tue Wed Thu Fri
         *  --------------------------------
         *                             1
         *     2   3   4   5   6   7   8
         *     9  10  11  12  13  14  15
         *    16  17  18  19  20  21  22
         *    23  24  25  26  27  28  29
         *    30
         *  -------------------------------
         */

        $jDate = new Jalalian(1402, 10, 25);
        $this->assertEquals('1402-10-29', $jDate->getEndDayOfWeek()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 10, 29);
        $this->assertEquals('1402-10-29', $jDate->getEndDayOfWeek()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 10, 23);
        $this->assertEquals('1402-10-29', $jDate->getEndDayOfWeek()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 12, 29);
        $this->assertEquals('1403-01-03', $jDate->getEndDayOfWeek()->format('Y-m-d'));

    }

    public function testGetEndDayOfMonth()
    {
        /*
         *  --------------------------------
         *       Day 1402 (March 2024)
         *  --------------------------------
         *    Sat Sun Mon Tue Wed Thu Fri
         *  --------------------------------
         *                             1
         *     2   3   4   5   6   7   8
         *     9  10  11  12  13  14  15
         *    16  17  18  19  20  21  22
         *    23  24  25  26  27  28  29
         *    30
         *  -------------------------------
         */

        $jDate = new Jalalian(1402, 10, 25);
        $this->assertEquals('1402-10-30', $jDate->getEndDayOfMonth()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 04, 12);
        $this->assertEquals('1402-04-31', $jDate->getEndDayOfMonth()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 12, 25);
        $this->assertEquals('1402-12-29', $jDate->getEndDayOfMonth()->format('Y-m-d'));
    }

    public function testGetEndDayOfYear()
    {
        $jDate = new Jalalian(1402, 10, 25);
        $this->assertEquals('1402-12-29', $jDate->getEndDayOfYear()->format('Y-m-d'));

        // LeapYear
        $jDate = new Jalalian(1403, 10, 25);
        $this->assertEquals('1403-12-30', $jDate->getEndDayOfYear()->format('Y-m-d'));
    }

    public function testGetEndDayOfQuarter()
    {
        $jDate = new Jalalian(1402, 10, 25);
        $this->assertEquals('1402-12-29', $jDate->getEndDayOfQuarter()->format('Y-m-d'));

        $jDate = new Jalalian(1402, 2, 25);
        $this->assertEquals('1402-03-31', $jDate->getEndDayOfQuarter()->format('Y-m-d'));


        $jDate = new Jalalian(1402, 6, 01);
        $this->assertEquals('1402-06-31', $jDate->getEndDayOfQuarter()->format('Y-m-d'));


        $jDate = new Jalalian(1402, 9, 01);
        $this->assertEquals('1402-09-30', $jDate->getEndDayOfQuarter()->format('Y-m-d'));
    }

    public function testGetQuarter()
    {
        $jDate = new Jalalian(1402, 10, 25);
        $this->assertEquals(4, $jDate->getQuarter());

        $jDate = new Jalalian(1402, 1, 01);
        $this->assertEquals(1, $jDate->getQuarter());


        $jDate = new Jalalian(1402, 05, 24);
        $this->assertEquals(2, $jDate->getQuarter());

        $jDate = new Jalalian(1402, 7, 23);
        $this->assertEquals(3, $jDate->getQuarter());
    }
}
