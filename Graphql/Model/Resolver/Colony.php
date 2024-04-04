<?php

namespace Nortdaniel\Graphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Customer\Api\AddressMetadataInterface;

/**
 *
 */
class Colony implements ResolverInterface
{
    const COLONY_CODE = 'colonia';

    /**
     * @var AddressMetadataInterface
     */
    protected $addressMetadata;

    /**
     * @param AddressMetadataInterface $addressMetadata
     */
    public function __construct(AddressMetadataInterface $addressMetadata)
    {
        $this->addressMetadata = $addressMetadata;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array
    {
        $attributeInfo = $this->addressMetadata->getAttributeMetadata(self::COLONY_CODE);
        $options = $attributeInfo->getOptions();

        $result = $this->getFilledResultArray($options);

        $this->addSelectOptionToArray($result);

        return $result;
    }

    /**
     * @param array $options
     * @return array
     */
    private function getFilledResultArray(array $options): array
    {
        $result = [];

        foreach ($options as $option) {
            $result[] = ['id' => $option->getValue(), 'name' => $option->getLabel()];
        }

        return $result;
    }

    /**
     * @param array $result
     * @return void
     */
    private function addSelectOptionToArray(array &$result): void
    {
        array_unshift($result, ['id' => '', 'name' => 'Seleccionar']);
    }
}
