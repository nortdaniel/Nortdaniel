<?php

namespace Nortdaniel\Graphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Directory\Api\CountryInformationAcquirerInterface;

/**
 *
 */
class CountryStates implements ResolverInterface
{
    /**
     * @var CountryInformationAcquirerInterface
     */
    protected $countryInformationAcquirer;

    /**
     * @param CountryInformationAcquirerInterface $countryInformationAcquirer
     */
    public function __construct(
        CountryInformationAcquirerInterface $countryInformationAcquirer
    )
    {
        $this->countryInformationAcquirer = $countryInformationAcquirer;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws GraphQlInputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['country_id'])) {
            throw new GraphQlInputException(__('Required parameter "country_id" is missing.'));
        }

        $countryId = $args['country_id'];
        $countryInfo = $this->countryInformationAcquirer->getCountryInfo($countryId);
        $regions = $countryInfo->getAvailableRegions();

        $result = [];
        foreach ($regions as $region) {
            $result[] = ['id' => $region->getId(), 'name' => $region->getName()];
        }

        // Añadir la opción "Seleccionar" al inicio del array
        array_unshift($result, ['id' => '', 'name' => 'Seleccionar']);

        return $result;
    }
}
