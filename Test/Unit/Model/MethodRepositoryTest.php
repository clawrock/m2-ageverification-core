<?php

namespace ClawRock\AgeVerification\Test\Unit\Model;

use ClawRock\AgeVerification\Api\Data\MethodInterface;
use ClawRock\AgeVerification\Exception\MissingMethodException;
use ClawRock\AgeVerification\Helper\Config;
use ClawRock\AgeVerification\Model\MethodRepository;
use ClawRock\MagentoTesting\TestCase;
use Magento\Framework\ObjectManagerInterface;

/**
 * @covers \ClawRock\AgeVerification\Model\MethodRepository
 */
class MethodRepositoryTest extends TestCase
{
    const METHOD_MODEL = 'method_model';

    /**
     * @var Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var ObjectManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManagerMock;

    /**
     * @var MethodInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $methodModelMock;

    /**
     * @var array
     */
    protected $methods = [
        'vendor1' => ['method1' => self::METHOD_MODEL, 'method2' => self::METHOD_MODEL],
        'vendor2' => ['method1' => self::METHOD_MODEL, 'method2' => self::METHOD_MODEL],
    ];

    /**
     * @var MethodRepository
     */
    protected $model;

    protected function setUp()
    {
        $this->methodModelMock = $this->getMockBuilder(MethodInterface::class)
            ->getMockForAbstractClass();

        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerMock = $this->getMockBuilder(ObjectManagerInterface::class)
            ->getMockForAbstractClass();

        $this->objectManagerMock->expects($this->any())
            ->method('get')
            ->with(self::METHOD_MODEL)
            ->willReturn($this->methodModelMock);

        $this->model = $this->createObject(MethodRepository::class, [
            'config' => $this->configMock,
            'objectManager' => $this->objectManagerMock,
        ]);
    }

    /**
     * @covers \ClawRock\AgeVerification\Model\MethodRepository::getAll
     */
    public function testGetAll()
    {
        $this->configMock->expects($this->once())->method('getMethods')->willReturn($this->methods);
        $this->configMock->method('isMethodActive')->willReturn(true);

        $this->assertEquals([
            'vendor1_method1' => $this->methodModelMock,
            'vendor1_method2' => $this->methodModelMock,
            'vendor2_method1' => $this->methodModelMock,
            'vendor2_method2' => $this->methodModelMock,
        ], $this->model->getAll());
    }

    /**
     * @covers \ClawRock\AgeVerification\Model\MethodRepository::getAll
     */
    public function testGetAllNotActive()
    {
        $this->configMock->expects($this->once())->method('getMethods')->willReturn($this->methods);
        $this->configMock->method('isMethodActive')->willReturn(false);

        $this->assertEquals([], $this->model->getAll());
    }

    /**
     * @covers \ClawRock\AgeVerification\Model\MethodRepository::getByCode
     */
    public function testGetByCode()
    {
        $this->configMock->expects($this->once())->method('getMethods')->willReturn($this->methods);
        $this->configMock->method('isMethodActive')->willReturn(true);

        $this->assertEquals($this->methodModelMock, $this->model->getByCode('vendor1_method1'));
    }

    /**
     * @covers \ClawRock\AgeVerification\Model\MethodRepository::getByCode
     */
    public function testMethodNotFound()
    {
        $this->expectException(MissingMethodException::class);
        $this->configMock->expects($this->once())->method('getMethods')->willReturn($this->methods);
        $this->configMock->method('isMethodActive')->willReturn(true);

        $this->model->getByCode('not_existing_method_code');
    }
}
