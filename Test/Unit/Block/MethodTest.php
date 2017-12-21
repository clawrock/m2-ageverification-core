<?php

namespace ClawRock\AgeVerification\Test\Unit\Block;

use ClawRock\AgeVerification\Api\Data\MethodInterface;
use ClawRock\AgeVerification\Block\Method;
use ClawRock\MagentoTesting\TestCase;
use Magento\Directory\Helper\Data;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\DataObject;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\View\Element\Template\Context;

/**
 * @covers \ClawRock\AgeVerification\Block\Method
 */
class MethodTest extends TestCase
{
    /**
     * @var Method
     */
    protected $block;

    protected function setUp()
    {
        $contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $directoryHelperMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $jsonEncodeMock = $this->getMockBuilder(EncoderInterface::class)
            ->getMockForAbstractClass();

        $configCacheTypeMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $regionCollectionFactoryMock = $this->getMockBuilder(RegionCollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $countryCollectionFactoryMock = $this->getMockBuilder(CountryCollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->block = new Method(
            $contextMock,
            $directoryHelperMock,
            $jsonEncodeMock,
            $configCacheTypeMock,
            $regionCollectionFactoryMock,
            $countryCollectionFactoryMock
        );
    }

    /**
     * @covers \ClawRock\AgeVerification\Block\Method::setModel
     */
    public function testSetModel()
    {
        /** @var MethodInterface $model */
        $model = $this->getMockBuilder(MethodInterface::class)
            ->getMockForAbstractClass();

        $this->assertInstanceOf(Method::class, $this->block->setModel($model));
    }

    /**
     * @covers \ClawRock\AgeVerification\Block\Method::getFormData
     */
    public function testGetFormData()
    {
        $dataObj = new DataObject(['data' => 1]);

        $this->block->setFormData($dataObj);

        $this->assertEquals($dataObj, $this->block->getFormData());
    }
}
