<?php

use Morilog\Jalali\jDate;

class CreateTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $jDate1 = jdate('2015-06-03');
        $jDate2 = jDate::create(1394, 3, 13);

        $this->assertSame($jDate1->format('Y-m-d H:i'), $jDate2->format('Y-m-d H:i'));
    }

    public function testCreateNow()
    {
        $jDate1 = jdate();
        $jDate2 = jDate::create();

        $this->assertSame($jDate1->format('Y-m-d H:i:s'), $jDate2->format('Y-m-d H:i:s'));
    }
}
