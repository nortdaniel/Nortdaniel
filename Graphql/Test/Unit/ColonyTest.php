<?php

namespace Nortdaniel\Graphql\Test\Unit;


use PHPUnit\Framework\TestCase;
use Nortdaniel\Graphql\Model\Resolver\Colony;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Eav\Api\Data\AttributeOptionInterface;


/**
 *
 */
class ColonyTest extends TestCase
{
    /**
     * @var AddressMetadataInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $addressMetadataMock;

    /**
     * @var Field|\PHPUnit\Framework\MockObject\MockObject
     */
    private $fieldMock;

    /**
     * @var Colony
     */
    private $colonyModel;

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->addressMetadataMock = $this->createMock(AddressMetadataInterface::class);
        $this->fieldMock = $this->createMock(Field::class);

        $this->colonyModel = new Colony($this->addressMetadataMock);
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testResolve()
    {
        $this->addressMetadataMock->expects($this->once())
            ->method('getAttributeMetadata')
            ->with(Colony::COLONY_CODE)
            ->willReturnSelf();

        $optionMock = $this->createMock(AttributeOptionInterface::class);

        $this->addressMetadataMock->expects($this->once())
            ->method('getOptions')
            ->willReturn([$optionMock]);

        $optionMock->expects($this->once())
            ->method('getValue')
            ->willReturn('OptionValue');

        $optionMock->expects($this->once())
            ->method('getLabel')
            ->willReturn('OptionLabel');

        $result = $this->colonyModel->resolve($this->fieldMock, null, $this->createMock(ResolveInfo::class));

        $this->assertEquals([['id' => '', 'name' => 'Seleccionar'], ['id' => 'OptionValue', 'name' => 'OptionLabel']], $result);
    }
}
