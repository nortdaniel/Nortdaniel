<?php

namespace Nortdaniel\PostPurchase\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Nortdaniel\PostPurchase\Logger\Logger;
use Magento\Framework\Serialize\SerializerInterface;

/**
 *
 */
class PostPurchaseObserver implements ObserverInterface
{
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @param Logger $logger
     * @param SerializerInterface $serializer
     */
    public function __construct(Logger $logger, SerializerInterface $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var OrderInterface $order */
        $order = $observer->getEvent()->getOrder();
        $orderData = [
            'order_id' => $order->getEntityId(),
            'customer_id' => $order->getCustomerId(),
            'customer_email' => $order->getCustomerEmail(),
            'customer_firstname' => $order->getCustomerFirstname(),
            'customer_lastname' => $order->getCustomerLastname(),
            'items' => $this->getOrderItems($order),
            'total' => $order->getGrandTotal(),
            'billing_address' => $this->getAddressDetails($order->getBillingAddress()),
            'shipping_address' => $this->getAddressDetails($order->getShippingAddress())
        ];

        $this->logOrderData($orderData);
    }

    /**
     * @param OrderInterface $order
     * @return array
     */
    private function getOrderItems(OrderInterface $order)
    {
        $items = [];
        foreach ($order->getAllVisibleItems() as $item) {
            $items[] = [
                'product_id' => $item->getProductId(),
                'qty' => $item->getQtyOrdered(),
                'price' => $item->getPrice(),
            ];
        }
        return $items;
    }

    /**
     * @param $address
     * @return array
     */
    private function getAddressDetails($address)
    {
        return [
            'city' => $address->getCity(),
            'country_id' => $address->getCountryId(),
            'postcode' => $address->getPostcode(),
            'region' => $address->getRegion(),
            'street' => $address->getStreet(),
            'telephone' => $address->getTelephone()
        ];
    }

    /**
     * @param array $orderData
     * @return void
     */
    private function logOrderData(array $orderData): void
    {
        $orderSerialized = $this->serializer->serialize($orderData);
        $this->logger->info($orderSerialized);
    }
}
