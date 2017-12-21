<?php

namespace ClawRock\AgeVerification\Test\Unit\Model\Values;

use ClawRock\AgeVerification\Model\Values\Date;
use ClawRock\MagentoTesting\TestCase;

/**
 * @covers \ClawRock\AgeVerification\Model\Values\Date
 */
class DateTest extends TestCase
{
    /**
     * @covers \ClawRock\AgeVerification\Model\Values\Date::getDay
     * @covers \ClawRock\AgeVerification\Model\Values\Date::getMonth
     * @covers \ClawRock\AgeVerification\Model\Values\Date::getYear
     * @covers \ClawRock\AgeVerification\Model\Values\Date::getFullDate
     */
    public function testValue()
    {
        $dt = new \DateTime();
        $date = new Date($dt);

        $this->assertEquals($dt->format('d'), $date->getDay());
        $this->assertEquals($dt->format('m'), $date->getMonth());
        $this->assertEquals($dt->format('Y'), $date->getYear());
        $this->assertEquals($dt->format('Y-m-d'), $date->getFullDate('Y-m-d'));
    }
}
