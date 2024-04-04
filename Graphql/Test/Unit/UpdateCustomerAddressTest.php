<?php

namespace Nortdaniel\Graphql\Test\Unit;


use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Customer\Api\AddressRepositoryInterface;
use Nortdaniel\Graphql\Model\Resolver\UpdateCustomerAddress;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class UpdateCustomerAddressTest extends TestCase
{
    /**
     * @var AddressRepositoryInterface|MockObject
     */
    private $addressRepository;

    /**
     * @var UpdateCustomerAddress
     */
    private $updateCustomerAddress;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->addressRepository = $this->getMockBuilder(AddressRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->updateCustomerAddress = new UpdateCustomerAddress(
            $this->addressRepository
        );
    }

    /**
     * @return void
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlInputException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testResolve(): void
    {
        $addressId = 1;
        $municipio = 'municipioTest';
        $colonia = 'coloniaTest';
        $args = [
            'input' => [
                'customer_address_id' => $addressId,
                'municipio' => $municipio,
                'colonia' => $colonia,
            ]
        ];

        $addressMock = $this->createMock(\Magento\Customer\Api\Data\AddressInterface::class);

        $this->addressRepository->expects($this->once())
            ->method('getById')
            ->with($addressId)
            ->willReturn($addressMock);

        $addressMock->expects($this->exactly(2))
            ->method('setCustomAttribute')
            ->withConsecutive(
                [$municipio, $municipio],
                [$colonia, $colonia]
            );

        $this->addressRepository->expects($this->once())
            ->method('save')
            ->willReturn($addressMock);

        $this->assertSame(
            ['success' => true],
            $this->updateCustomerAddress->resolve(
                $this->createMock(Field::class),
                $this->createMock($context = []),
                $this->createMock(ResolveInfo::class),
                $value = [],
                $args
            )
        );
    }
}
