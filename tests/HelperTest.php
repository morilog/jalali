<?php

namespace Morilog\Jalali\Tests;

use Morilog\Jalali\Jalalian;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function test_jdate_function()
    {
        $this->assertTrue(function_exists('jdate'));

        $jdate = jdate('now');
        $this->assertTrue($jdate instanceof Jalalian);
    }
}
