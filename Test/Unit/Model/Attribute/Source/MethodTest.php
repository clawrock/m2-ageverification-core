<?php

namespace ClawRock\AgeVerification\Test\Unit\Model\Attribute\Source;

use ClawRock\AgeVerification\Api\Data\MethodInterface;
use ClawRock\AgeVerification\Api\MethodRepositoryInterface;
use ClawRock\AgeVerification\Model\Attribute\Source\Method;
use ClawRock\MagentoTesting\TestCase;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

/**
 * @covers \ClawRock\AgeVerification\Model\Attribute\Source\Method
 */
class MethodTest extends TestCase
{
    const METHOD_TITLE = 'method_title';

    /**
     * @var AbstractAttribute|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $attributeMock;

    /**
     * @var MethodInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $methodModelMock;

    /**
     * @var MethodRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $methodRepositoryMock;

    /**
     * @var Method
     */
    protected $model;

    protected function setUp()
    {
        $this->attributeMock = $this->getMockBuilder(AbstractAttribute::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->methodModelMock = $this->getMockBuilder(MethodInterface::class)
            ->getMockForAbstractClass();
        $this->methodModelMock->method('getTitle')->willReturn(self::METHOD_TITLE);

        $this->methodRepositoryMock = $this->getMockBuilder(MethodRepositoryInterface::class)
            ->getMockForAbstractClass();
        $this->methodRepositoryMock->method('getAll')->willReturn(['method_code' => $this->methodModelMock]);

        $this->model = $this->createObject(Method::class, [
            'methodRepository' => $this->methodRepositoryMock,
        ]);

        $this->model->setAttribute($this->attributeMock);
    }

    /**
     * @covers \ClawRock\AgeVerification\Model\Attribute\Source\Method::getAllOptions
     */
    public function testGetAllOptions()
    {
        $this->assertEquals([
            ['label' => ' ', 'value' => ''],
            ['label' => self::METHOD_TITLE, 'value' => 'method_code'],
        ], $this->model->getAllOptions());
    }

    /**
     * @covers \ClawRock\AgeVerification\Model\Attribute\Source\Method::getOptionArray
     */
    public function testGetOptionArray()
    {
        $this->assertEquals([
            '' => ' ',
            'method_code' => self::METHOD_TITLE,
        ], $this->model->getOptionArray());
    }
}
