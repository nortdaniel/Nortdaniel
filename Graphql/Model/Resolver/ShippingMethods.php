<?php

namespace Nortdaniel\Graphql\Model\Resolver;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface;
use Magento\Shipping\Model\Config;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 *
 */
class ShippingMethods implements ResolverInterface
{
    /**
     * @var Config
     */
    protected $shippingConfig;

    /**
     * @param Config $shippingConfig
     */
    public function __construct(
        Config $shippingConfig
    ) {
        $this->shippingConfig = $shippingConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        /** @var StoreInterface $store */
        $store = $context->getExtensionAttributes()->getStore();

        try {
            $carriers = $this->shippingConfig->getActiveCarriers($store->getId());
            return $this->createActiveShippingMethods($carriers);
        } catch (\Exception $e) {
            $errorMessage = __('Error retrieving shipping methods: %1', $e->getMessage());
            throw new \GraphQL\Error\UserError($errorMessage);
        }
    }

    /**
     * @param $carriers
     * @return array
     */
    private function createActiveShippingMethods($carriers)
    {
        $activeShippingMethods = [];

        foreach ($carriers as $carrierCode => $carrierModel) {
            if ($carrierMethods = $carrierModel->getAllowedMethods()) {
                foreach ($carrierMethods as $methodCode => $methodTitle) {
                    $activeShippingMethods[] = [
                        'carrier_code' => $carrierCode,
                        'method_code' => $methodCode,
                        'carrier_title' => $carrierModel->getConfigData('title'),
                        'method_title' => $methodTitle,
                    ];
                }
            }
        }

        return $activeShippingMethods;
    }
}
