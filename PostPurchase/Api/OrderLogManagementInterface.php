<?php

namespace Nortdaniel\PostPurchase\Api;

interface OrderLogManagementInterface
{
    /**
     * Get order log by ID.
     *
     * @param string $orderId
     * @return string
     */
    public function getOrderLog($orderId);
}
