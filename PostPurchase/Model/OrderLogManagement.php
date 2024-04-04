<?php

namespace Nortdaniel\PostPurchase\Model;

use Nortdaniel\PostPurchase\Api\OrderLogManagementInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;

/**
 *
 */
class OrderLogManagement implements OrderLogManagementInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var File
     */
    protected $file;

    /**
     *
     */
    const LOG_FILE_PATH = '/var/log/post_purchase.log';
    /**
     * @var string
     */
    private $orderIdKey = 'order_id';

    /**
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param File $file
     */
    public function __construct(
        SerializerInterface $serializer,
        LoggerInterface     $logger,
        File     $file
    )
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderLog($orderId)
    {
        $logFile = BP . self::LOG_FILE_PATH;

        try {
            $logContents = $this->file->fileGetContents($logFile);
            $logs = explode(PHP_EOL, $logContents);
            foreach ($logs as $logEntry) {
                if (!$logEntry) {
                        continue;
                }

                // Matches and extracts any JSON-like structure in the log entry
                preg_match('/\{(?:[^{}]|(?R))*\}/', $logEntry, $matches);
                if (empty($matches)) {
                        continue;
                }

                $logEntryJson = $matches[0];

                $data = $this->serializer->unserialize($logEntryJson);

                $logEntryJson = json_encode($data);

                if ($data[$this->orderIdKey] == $orderId) {
                    return $logEntryJson;
                }
            }
        } catch (\Exception $e) {
            $this->logger->critical('Error accessing order log', ['exception' => $e]);
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not retrieve order log'),
                $e
            );
        }

        return __('Log not found for Order ID: %1', $orderId);
    }
}
