<?php

namespace Nortdaniel\PostPurchase\Test\Unit\Model;


use Magento\Framework\Exception\CouldNotSaveException;
use Nortdaniel\PostPurchase\Model\OrderLogManagement;
use Magento\TestFramework\Serialize\Serializer;
use Psr\Log\LoggerInterface\Proxy;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Framework\Filesystem\DriverInterface;

/**
 *
 */
class OrderLogManagementTest extends TestCase
{
    /**
     * @var OrderLogManagement
     */
    protected $logManagement;

    /**
     * @var Serializer|MockObject
     */
    protected $serializerMock;

    /**
     * @var Proxy|MockObject
     */
    protected $loggerMock;

    /**
     * @var DriverInterface|MockObject
     */
    protected $fileMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->serializerMock = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loggerMock = $this->getMockBuilder(Proxy::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->fileMock = $this->getMockBuilder(DriverInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->logManagement = new OrderLogManagement(
            $this->serializerMock,
            $this->loggerMock,
            $this->fileMock
        );
    }

    /**
     * @return void
     * @throws CouldNotSaveException
     */
    public function testGetOrderLog()
    {
        $orderId = 1;
        $data = ['order_id' => $orderId];

        $this->fileMock->expects($this->once())
            ->method('fileGetContents')
            ->willReturn(serialize($data));
        $this->serializerMock->expects($this->once())
            ->method('unserialize')
            ->willReturn($data);

        $this->assertEquals(serialize($data), $this->logManagement->getOrderLog($orderId));
    }

    /**
     * @return void
     * @throws CouldNotSaveException
     */
    public function testGetOrderLogError()
    {
        $orderId = 1;

        $this->fileMock->expects($this->once())
            ->method('fileGetContents')
            ->willThrowException(new \Exception());
        $this->loggerMock->expects($this->once())
            ->method('critical');
        $this->expectException(CouldNotSaveException::class);

        $this->logManagement->getOrderLog($orderId);
    }
}
