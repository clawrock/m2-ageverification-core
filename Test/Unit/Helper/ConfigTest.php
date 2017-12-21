<?php

namespace ClawRock\AgeVerification\Test\Unit\Helper;

use ClawRock\AgeVerification\Helper\Config;
use ClawRock\MagentoTesting\TestCase;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;

/**
 * @covers \ClawRock\AgeVerification\Helper\Config
 */
class ConfigTest extends TestCase
{
    /**
     * @var Config
     */
    protected $object;

    protected function setUp()
    {
        $contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $contextMock->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->getScopeConfigMock());

        $this->object = $this->createObject(Config::class, [
            'context' => $contextMock
        ]);
    }

    /**
     * @covers \ClawRock\AgeVerification\Helper\Config::isEnabled
     */
    public function testIsEnabled()
    {
        $this->mockScopeConfigGetValue(Config::XPATH_ENABLED, '1');
        $this->assertTrue($this->object->isEnabled());
    }

    /**
     * @covers \ClawRock\AgeVerification\Helper\Config::getRequiredAge
     */
    public function testGetRequiredAge()
    {
        $this->mockScopeConfigGetValue(Config::XPATH_REQUIRED_AGE, '21');
        $this->assertEquals(21, $this->object->getRequiredAge());
    }

    /**
     * @covers \ClawRock\AgeVerification\Helper\Config::isMethodActive
     */
    public function testIsMethodActive()
    {
        $vendor = 'vendor';
        $code = 'code';

        $this->mockScopeConfigGetValue(sprintf(Config::XPATH_METHOD_ACTIVE, $vendor, $code), '1');
        $this->assertTrue($this->object->isMethodActive($vendor, $code));
    }

    /**
     * @covers \ClawRock\AgeVerification\Helper\Config::getMethods
     */
    public function testGetMethods()
    {
        $methods = [
            'vendor1' => ['method1', 'method2'],
            'vendor2' => ['method1', 'method2']
        ];

        $this->mockScopeConfigGetValue(Config::XPATH_MODELS, $methods, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        $this->assertEquals($methods, $this->object->getMethods());
    }
}
