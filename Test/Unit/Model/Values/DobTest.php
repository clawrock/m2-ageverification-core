<?php

namespace ClawRock\AgeVerification\Test\Unit\Model\Values;

use ClawRock\AgeVerification\Model\Values\Dob;
use ClawRock\MagentoTesting\TestCase;

/**
 * @covers \ClawRock\AgeVerification\Model\Values\Dob
 */
class DobTest extends TestCase
{
    const DATE = '1990-01-01';

    /**
     * @covers \ClawRock\AgeVerification\Model\Values\Dob::isOfAge
     * @covers \ClawRock\AgeVerification\Model\Values\Dob::getAge
     */
    public function testValue()
    {
        $dt = new \DateTime(self::DATE);
        $dob = new Dob($dt);

        $yearsDiff = $dt->diff(new \DateTime('now', $dt->getTimezone()))->y;
        $age = $dob->getAge();

        $this->assertEquals($yearsDiff, $age);
        $this->assertTrue($dob->isOfAge($age));
    }
}
