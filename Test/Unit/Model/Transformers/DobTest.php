<?php

namespace ClawRock\AgeVerification\Test\Unit\Model\Transformers;

use ClawRock\AgeVerification\Exception\RequiredAgeException;
use ClawRock\AgeVerification\Helper\Config;
use ClawRock\AgeVerification\Model\Transformers\Dob;
use ClawRock\MagentoTesting\TestCase;
use Magento\Framework\DataObject;
use Magento\Framework\Stdlib\DateTime\Filter\Date;

/**
 * @covers \ClawRock\AgeVerification\Model\Transformers\Dob
 */
class DobTest extends TestCase
{
    const DATE = '1990-01-01';

    /**
     * @var Date|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dateFilterMock;

    /**
     * @var Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var Dob
     */
    protected $model;

    protected function setUp()
    {
        $this->dateFilterMock = $this->getMockBuilder(Date::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->dateFilterMock->expects($this->once())->method('filter')->with(self::DATE)->willReturn(self::DATE);

        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->model = $this->createObject(Dob::class, [
            'dateFilter' => $this->dateFilterMock,
            'config' => $this->configMock,
        ]);
    }

    /**
     * @covers \ClawRock\AgeVerification\Model\Transformers\Dob::transform
     */
    public function testTransform()
    {
        $dataObj = new DataObject([Dob::DOB_FIELD => self::DATE]);
        $this->assertEquals(self::DATE, $this->model->transform($dataObj)->getFullDate('Y-m-d'));
    }

    /**
     * @covers \ClawRock\AgeVerification\Model\Transformers\Dob::transform
     */
    public function testRequiredAgeException()
    {
        $this->expectException(RequiredAgeException::class);
        $this->configMock->expects($this->once())->method('getRequiredAge')->willReturn(1000);
        $dataObj = new DataObject([Dob::DOB_FIELD => self::DATE]);
        $this->model->transform($dataObj);
    }
}
