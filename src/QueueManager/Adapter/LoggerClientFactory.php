<?php

namespace Recommerce\QueueManager\Adapter;

use Recommerce\QueueManager\Exception\QueueReaderException;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoggerClientFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceManager
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        return self::createServiceFromConfig($serviceManager->get('Config'));
    }

    /**
     * @param array $config
     * @return LoggerClient
     * @throws QueueReaderException
     */
    public static function createServiceFromConfig(array $config)
    {
        $hasConfiguration = isset($config['queue_client']['file_path']);

        if (!$hasConfiguration) {
            throw new QueueReaderException("Unable to find config for logger client");
        }

        $logFile = $config['queue_client']['file_path'];
        $stream = @fopen($logFile, 'a', false);

        if (!$stream) {
            throw new QueueReaderException(
                sprintf(
                    "Unable to open log file '%s' for logger client",
                    $logFile
                )
            );
        }

        $writer = new Stream($stream);

        $logger = new Logger();
        $logger->addWriter($writer);

        return new LoggerClient($logger);
    }
}
