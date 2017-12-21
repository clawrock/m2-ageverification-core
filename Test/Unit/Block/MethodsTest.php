<?php

namespace ClawRock\AgeVerification\Test\Unit\Block;

use ClawRock\AgeVerification\Api\MethodRepositoryInterface;
use ClawRock\AgeVerification\Block\Method;
use ClawRock\AgeVerification\Block\Methods;
use ClawRock\AgeVerification\Helper\Config;
use ClawRock\MagentoTesting\TestCase;
use Magento\Customer\Block\Form\Register;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\LayoutInterface;

/**
 * @covers \ClawRock\AgeVerification\Block\Methods
 */
class MethodsTest extends TestCase
{
    /**
     * @var MethodRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $methodRepositoryMock;

    /**
     * @var LayoutInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $layoutMock;

    /**
     * @var Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var Methods
     */
    protected $block;

    protected function setUp()
    {
        $this->layoutMock = $this->getMockBuilder(LayoutInterface::class)
            ->getMockForAbstractClass();

        $contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $contextMock->method('getLayout')->willReturn($this->layoutMock);

        $this->methodRepositoryMock = $this->getMockBuilder(MethodRepositoryInterface::class)
            ->getMockForAbstractClass();

        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->block = $this->createObject(Methods::class, [
            'context' => $contextMock,
            'methodRepository' => $this->methodRepositoryMock,
            'config' => $this->configMock,
        ]);
    }

    /**
     * @covers \ClawRock\AgeVerification\Block\Methods::getAvailableMethods
     */
    public function testGetAvailableMethods()
    {
        $methods = ['method1', 'method2'];
        $this->methodRepositoryMock->expects($this->once())->method('getAll')->willReturn($methods);

        $this->assertEquals($methods, $this->block->getAvailableMethods());
    }

    /**
     * @covers \ClawRock\AgeVerification\Block\Methods::getMethodBlock
     */
    public function testGetMethodBlock()
    {
        $block = $this->getMockBuilder(Method::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->layoutMock->expects($this->once())->method('getChildName')->willReturn('block_name');
        $this->layoutMock->expects($this->once())->method('getBlock')->willReturn($block);

        $this->assertEquals($block, $this->block->getMethodBlock('block_name'));
    }

    /**
     * @covers \ClawRock\AgeVerification\Block\Methods::getFormData
     */
    public function testGetFormData()
    {
        $dataObj = new DataObject(['data' => 1]);
        $registerBlock = $this->getMockBuilder(Register::class)
            ->disableOriginalConstructor()
            ->getMock();

        $registerBlock->expects($this->once())->method('getFormData')->willReturn($dataObj);
        $this->layoutMock->expects($this->once())->method('getBlock')->willReturn($registerBlock);

        $this->assertEquals($dataObj, $this->block->getFormData());
    }

    /**
     * @covers \ClawRock\AgeVerification\Block\Methods::getFormData
     */
    public function testGetFormDataWithNoParent()
    {
        $this->layoutMock->expects($this->once())->method('getBlock')->willReturn(false);
        $this->assertEquals(new DataObject(), $this->block->getFormData());
    }

    public function testIsEnabled()
    {
        $this->configMock->expects($this->once())->method('isEnabled')->willReturn(true);
        $this->assertTrue($this->block->isEnabled());
    }
}
