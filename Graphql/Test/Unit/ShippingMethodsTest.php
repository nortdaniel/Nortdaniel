<?php

namespace Nortdaniel\Graphql\Test\Unit;


use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Nortdaniel\Graphql\Model\Resolver\ShippingMethods;
use Magento\Store\Model\Store\Interceptor;
use Magento\Shipping\Model\Config;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Test for ShippingMethods class
 */
class ShippingMethodsTest extends TestCase
{
    /**
     * @var ShippingMethods
     */
    private $model;

    /**
     * @var Config|\PHPUnit\Framework\MockObject\MockObject
     */
    private $configMock;

    /**
     * @var ValueFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $valueFactoryMock;

    /**
     * @var Field|\PHPUnit\Framework\MockObject\MockObject
     */
    private $fieldMock;

    /**
     * @var ResolveInfo|\PHPUnit\Framework\MockObject\MockObject
     */
    private $infoMock;

    /**
     * @var ContextInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $contextMock;

    /**
     * @var Interceptor|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeMock;

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->model = $objectManager->getObject(
            ShippingMethods::class,
            [
                'config' => $this->configMock,
            ]
        );

        $this->fieldMock = $this->createMock(Field::class);
        $this->contextMock = $this->getMockForAbstractClass(ContextInterface::class);
        $this->infoMock = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeMock = $this->createMock(Interceptor::class);

        $this->valueFactoryMock = $this->getMockBuilder(ValueFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Test the 'resolve' method.
     */
    public function testResolve(): void
    {
        $this->contextMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($this->storeMock);

        $this->storeMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $this->configMock->expects($this->once())
            ->method('getActiveCarriers')
            ->with(1)
            ->willReturn([]);

        $this->model->resolve(
            $this->fieldMock,
            $this->contextMock,
            $this->infoMock
        );
    }

    /**
     * @expectedException \GraphQL\Error\UserError
     */
    public function testResolveThrowsException(): void
    {
        $this->contextMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($this->storeMock);

        $this->storeMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $this->configMock->expects($this->once())
            ->method('getActiveCarriers')
            ->with(1)
            ->willThrowException(new \Exception());

        $this->model->resolve(
            $this->fieldMock,
            $this->contextMock,
            $this->infoMock
        );
    }
}
