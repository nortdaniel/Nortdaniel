<?php

namespace Nortdaniel\Overprice\Plugin;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @class ModifySearchResultPricePlugin
 */
class ModifySearchResultPricePlugin
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Overprice config path
     */
    const OVERPRICE_CONFIG_PATH = 'settings/general/overprice';

    /**
     * Constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * AfterGetPrice plugin
     *
     * @param Product $subject
     * @param float $result
     * @return float
     */
    public function afterGetPrice(Product $subject, $result)
    {
        $overprice = $this->scopeConfig->getValue(
            self::OVERPRICE_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );

        return $result + $overprice;
    }
}
