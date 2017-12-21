<?php

namespace ClawRock\AgeVerification\Test\Unit\Observer;

use ClawRock\AgeVerification\Api\Data\MethodInterface;
use ClawRock\AgeVerification\Api\Data\PersistableResultInterface;
use ClawRock\AgeVerification\Api\MethodRepositoryInterface;
use ClawRock\AgeVerification\Exception\CustomerAuthenticationException;
use ClawRock\AgeVerification\Exception\MissingMethodException;
use ClawRock\AgeVerification\Exception\RequiredAgeException;
use ClawRock\AgeVerification\Helper\Config;
use ClawRock\AgeVerification\Observer\AuthenticateCustomer;
use ClawRock\MagentoTesting\TestCase;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;

/**
 * @covers \ClawRock\AgeVerification\Observer\AuthenticateCustomer
 */
class AuthenticateCustomerTest extends TestCase
{
    /**
     * @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * @var Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var MethodInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $methodMock;

    /**
     * @var PersistableResultInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultMock;

    /**
     * @var MethodRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $methodRepositoryMock;

    /**
     * @var Customer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $customerMock;

    /**
     * @var Observer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventMock;

    /**
     * @var AuthenticateCustomer
     */
    protected $observer;

    /**
     * @var array
     */
    protected $requestParams = ['param1' => 1, 'param2' => 2];

    protected function setUp()
    {
        $this->requestMock = $this->getMockBuilder(RequestInterface::class)
            ->getMockForAbstractClass();

        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->methodMock = $this->getMockBuilder(MethodInterface::class)
            ->getMockForAbstractClass();

        $this->resultMock = $this->getMockBuilder(PersistableResultInterface::class)
            ->getMockForAbstractClass();

        $this->methodRepositoryMock = $this->getMockBuilder(MethodRepositoryInterface::class)
            ->getMockForAbstractClass();

        $this->customerMock = $this->getMockBuilder(Customer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventMock = $this->getMockBuilder(Observer::class)
            ->setMethods(['getCustomer'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->eventMock->method('getCustomer')->willReturn($this->customerMock);

        $this->observer = $this->createObject(AuthenticateCustomer::class, [
            'request' => $this->requestMock,
            'config' => $this->configMock,
            'methodRepository' => $this->methodRepositoryMock,
        ]);
    }

    /**
     * @covers \ClawRock\AgeVerification\Observer\AuthenticateCustomer::execute
     */
    public function testAvDisabled()
    {
        $this->configMock->expects($this->once())->method('isEnabled')->willReturn(false);
        $this->methodMock->expects($this->never())->method('authenticate');

        $this->observer->execute($this->eventMock);
    }

    /**
     * @covers \ClawRock\AgeVerification\Observer\AuthenticateCustomer::execute
     */
    public function testNoAvMethod()
    {
        $this->expectException(LocalizedException::class);
        $this->configMock->expects($this->once())->method('isEnabled')->willReturn(true);
        $this->requestMock->expects($this->once())->method('getParam')->willReturn(null);
        $this->methodMock->expects($this->never())->method('authenticate');

        $this->observer->execute($this->eventMock);
    }

    /**
     * @covers \ClawRock\AgeVerification\Observer\AuthenticateCustomer::execute
     */
    public function testMethodNotFound()
    {
        $this->expectException(LocalizedException::class);
        $this->configMock->expects($this->once())->method('isEnabled')->willReturn(true);
        $this->requestMock->expects($this->once())->method('getParam')->willReturn('av_method');
        $this->methodMock->expects($this->never())->method('authenticate');
        $this->methodRepositoryMock->expects($this->once())
            ->method('getByCode')
            ->willThrowException(new MissingMethodException());

        $this->observer->execute($this->eventMock);
    }

    /**
     * @covers \ClawRock\AgeVerification\Observer\AuthenticateCustomer::execute
     */
    public function testCustomerIsAuthorized()
    {
        $this->configMock->expects($this->once())->method('isEnabled')->willReturn(true);
        $this->requestMock->expects($this->once())->method('getParam')->willReturn('av_method');
        $this->methodRepositoryMock->expects($this->once())
            ->method('getByCode')
            ->willReturn($this->methodMock);
        $this->methodMock->expects($this->once())->method('isCustomerAuthenticated')->willReturn(true);
        $this->methodMock->expects($this->never())->method('authenticate');

        $this->observer->execute($this->eventMock);
    }

    /**
     * @covers \ClawRock\AgeVerification\Observer\AuthenticateCustomer::execute
     */
    public function testRequiredAgeException()
    {
        $this->expectException(LocalizedException::class);
        $this->configMock->expects($this->once())->method('isEnabled')->willReturn(true);
        $this->requestMock->expects($this->once())->method('getParam')->willReturn('av_method');
        $this->methodRepositoryMock->expects($this->once())->method('getByCode')->willReturn($this->methodMock);
        $this->requestMock->expects($this->once())->method('getParams')->willReturn($this->requestParams);
        $this->methodMock->expects($this->once())->method('isCustomerAuthenticated')->willReturn(false);
        $this->methodMock->expects($this->once())
            ->method('authenticate')
            ->willThrowException(new RequiredAgeException());

        $this->observer->execute($this->eventMock);
    }

    /**
     * @covers \ClawRock\AgeVerification\Observer\AuthenticateCustomer::execute
     */
    public function testAuthenticationException()
    {
        $this->expectException(LocalizedException::class);
        $this->configMock->expects($this->once())->method('isEnabled')->willReturn(true);
        $this->requestMock->expects($this->once())->method('getParam')->willReturn('av_method');
        $this->methodRepositoryMock->expects($this->once())->method('getByCode')->willReturn($this->methodMock);
        $this->requestMock->expects($this->once())->method('getParams')->willReturn($this->requestParams);
        $this->methodMock->expects($this->once())->method('isCustomerAuthenticated')->willReturn(false);
        $this->methodMock->expects($this->once())
            ->method('authenticate')
            ->willThrowException(new CustomerAuthenticationException());

        $this->observer->execute($this->eventMock);
    }

    /**
     * @covers \ClawRock\AgeVerification\Observer\AuthenticateCustomer::execute
     */
    public function testVerificationFailure()
    {
        $this->expectException(LocalizedException::class);
        $this->configMock->expects($this->once())->method('isEnabled')->willReturn(true);
        $this->requestMock->expects($this->once())->method('getParam')->willReturn('av_method');
        $this->methodRepositoryMock->expects($this->once())->method('getByCode')->willReturn($this->methodMock);
        $this->requestMock->expects($this->once())->method('getParams')->willReturn($this->requestParams);
        $this->methodMock->expects($this->once())->method('isCustomerAuthenticated')->willReturn(false);
        $this->methodMock->expects($this->once())
            ->method('authenticate')
            ->willThrowException(new \Exception());

        $this->observer->execute($this->eventMock);
    }

    /**
     * @covers \ClawRock\AgeVerification\Observer\AuthenticateCustomer::execute
     */
    public function testAuthentication()
    {
        $this->configMock->expects($this->once())->method('isEnabled')->willReturn(true);
        $this->requestMock->expects($this->once())->method('getParam')->willReturn('av_method');
        $this->methodRepositoryMock->expects($this->once())->method('getByCode')->willReturn($this->methodMock);
        $this->methodMock->expects($this->once())->method('isCustomerAuthenticated')->willReturn(false);
        $this->requestMock->expects($this->once())->method('getParams')->willReturn($this->requestParams);
        $this->methodMock->expects($this->once())->method('authenticate')->willReturn($this->resultMock);
        $this->resultMock->expects($this->once())->method('persistInCustomerData');

        $this->observer->execute($this->eventMock);
    }
}
