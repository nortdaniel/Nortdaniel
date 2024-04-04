<?php

namespace Nortdaniel\ShippingMethod\Test\Unit;

use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory as RateMethodFactory;
use PHPUnit\Framework\TestCase;
use Nortdaniel\ShippingMethod\Model\Carrier\ShippingMethod;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\ResultFactory as RateResultFactory;

/**
 *
 */
class ShippingMethodTest extends TestCase
{
    /**
     * @var ShippingMethod
     */
    private $shippingMethod;
    /**
     * @var Method|(Method&object&\PHPUnit\Framework\MockObject\MockObject)|(Method&\PHPUnit\Framework\MockObject\MockObject)|(object&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    private $methodMock;

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->methodMock = $this->createMock(Method::class);
        $rateResultFactoryMock = $this->createMock(RateResultFactory::class);
        $rateMethodFactoryMock = $this->createMock(RateMethodFactory::class);

        $rateResultFactoryMock->method('create')
            ->willReturn($this->methodMock);

        $rateMethodFactoryMock->method('create')
            ->willReturn($this->methodMock);

        $this->shippingMethod = new ShippingMethod(
            $rateResultFactoryMock,
            $rateMethodFactoryMock
        );
    }

    /**
     * @return void
     */
    public function testCreateMethod()
    {
        $this->methodMock->expects($this->once())
            ->method('setCarrier')
            ->with($this->equalTo('nortdaniel_fixed'))
            ->willReturnSelf();

        $method = $this->shippingMethod->createMethod();

        $this->assertEquals($this->methodMock, $method);
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCollectRates()
    {
        $request = $this->createMock(RateRequest::class);

        $result = $this->shippingMethod->collectRates($request);

        $this->assertInstanceOf('\Magento\Shipping\Model\Rate\Result', $result);

        $this->shippingMethod->setData('active', false);
        $result = $this->shippingMethod->collectRates($request);

        $this->assertSame(false, $result);
    }
}
