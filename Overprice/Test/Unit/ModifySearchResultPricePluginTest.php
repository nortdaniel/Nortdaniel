<?php

namespace Nortdaniel\Overprice\Test\Unit;


use Nortdaniel\Overprice\Plugin\ModifySearchResultPricePlugin;
use Magento\TestFramework\App\ApiMutableScopeConfig;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ModifySearchResultPricePluginTest extends TestCase
{
    /**
     * @var ModifySearchResultPricePlugin
     */
    protected $plugin;

    /**
     * @var ApiMutableScopeConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $scopeConfig;

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->scopeConfig = $this->createMock(ApiMutableScopeConfig::class);
        $this->plugin = new ModifySearchResultPricePlugin($this->scopeConfig);
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testAfterGetPrice()
    {
        $product = $this->createMock(Product::class);
        $price = 100.0;
        $overpriceValue = 10.0;

        $this->scopeConfig->method('getValue')
            ->with(ModifySearchResultPricePlugin::OVERPRICE_CONFIG_PATH)
            ->willReturn($overpriceValue);

        $modifiedPrice = $this->plugin->afterGetPrice($product, $price);

        $this->assertEquals($price + $overpriceValue, $modifiedPrice, 'Price should be modified by adding overprice value');
    }
}
