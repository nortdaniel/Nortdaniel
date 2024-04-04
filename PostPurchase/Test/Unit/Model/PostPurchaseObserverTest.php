<?php

namespace Nortdaniel\PostPurchase\Test\Unit\Model;


use PHPUnit\Framework\TestCase;
use Nortdaniel\PostPurchase\Observer\PostPurchaseObserver;
use Magento\Framework\Event\Observer;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order\Interceptor;
use Nortdaniel\PostPurchase\Logger\Logger;
use Magento\Framework\Serialize\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class PostPurchaseObserverTest
 *
 * Description: Testing the PostPurchaseObserver class and its methods.
 *
 */
class PostPurchaseObserverTest extends TestCase
{
    /**
     * @var PostPurchaseObserver
     */
    private $observerUnderTest;

    /**
     * @var Observer|MockObject
     */
    private $observerMock;

    /**
     * @var OrderInterface|MockObject
     */
    private $orderMock;

    /**
     * @var Logger|MockObject
     */
    private $loggerMock;

    /**
     * @var SerializerInterface|MockObject
     */
    private $serializerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->loggerMock = $this->getMockBuilder(Logger::class)->disableOriginalConstructor()->getMock();
        $this->serializerMock = $this->getMockBuilder(SerializerInterface::class)->getMock();
        $this->orderMock = $this->getMockBuilder(Interceptor::class)->disableOriginalConstructor()->getMock();
        $this->observerMock = $this->getMockBuilder(Observer::class)->disableOriginalConstructor()->getMock();

        $this->observerUnderTest = new PostPurchaseObserver(
            $this->loggerMock,
            $this->serializerMock
        );
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        unset(
            $this->serializerMock,
            $this->loggerMock,
            $this->orderMock,
            $this->observerMock,
            $this->observerUnderTest
        );
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $orderSerialized = '{"customer_id":"2","customer_email":"nortdaniel@gmail.com","customer_firstname":"Rene","customer_lastname":"Castillo","items":[{"product_id":"6","qty":1,"price":159}],"total":259,"billing_address":{"city":"Tepotzotlan","country_id":"MX","postcode":"54605","region":"Estado de M\u00e9xico","street":["Calle Sor Juana Ines de la cruz  Int. 3, Col Texcacoa"],"telephone":"5547163491"},"shipping_address":{"city":"Tepotzotlan","country_id":"MX","postcode":"54605","region":"Estado de M\u00e9xico","street":["Calle Sor Juana Ines de la cruz  Int. 3, Col Texcacoa"],"telephone":"5547163491"}}';

        $this->observerMock->method('getEvent')->willReturnSelf();
        $this->observerMock->method('getOrder')->willReturn($this->orderMock);
        $this->serializerMock->expects($this->once())
            ->method('serialize')
            ->with($this->orderMock)
            ->willReturn($orderSerialized);
        $this->loggerMock->expects($this->once())
            ->method('info')
            ->with($orderSerialized);

        $this->observerUnderTest->execute($this->observerMock);
    }
}
