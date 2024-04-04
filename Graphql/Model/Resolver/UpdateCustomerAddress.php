<?php

namespace Nortdaniel\Graphql\Model\Resolver;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Customer\Api\AddressRepositoryInterface;

/**
 *
 */
class UpdateCustomerAddress implements ResolverInterface
{
    /**
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * @param AddressRepositoryInterface $addressRepository
     */
    public function __construct(AddressRepositoryInterface $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return true[]
     * @throws GraphQlInputException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->validateArguments($args);

        try {
            $address = $this->addressRepository->getById($args['input']['customer_address_id']);
            $this->updateExtensionAttributes($address, $args['input']['municipio'], $args['input']['colonia']);
            $this->addressRepository->save($address);
            return ['success' => true];
        } catch (\Exception $e) {
            throw new \GraphQL\Error\UserError(__('Error updating address: %1', $e->getMessage()));
        }
    }

    /**
     * @param array $args
     * @return void
     * @throws GraphQlInputException
     */
    private function validateArguments(array $args): void
    {
        if (empty($args['input']['customer_address_id'])) {
            throw new GraphQlInputException(__('Required parameter "customer_address_id" is missing.'));
        }
        if (empty($args['input']['municipio'])) {
            throw new GraphQlInputException(__('Required parameter "municipio" is missing.'));
        }
        if (empty($args['input']['colonia'])) {
            throw new GraphQlInputException(__('Required parameter "colonia" is missing.'));
        }
    }

    /**
     * @param $address
     * @param $municipio
     * @param $colonia
     * @return void
     */
    private function updateExtensionAttributes($address, $municipio, $colonia): void
    {
        $address->setCustomAttribute('municipio', $municipio);
        $address->setCustomAttribute('colonia', $colonia);
    }
}
