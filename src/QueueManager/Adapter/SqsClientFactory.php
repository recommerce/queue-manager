<?php

namespace Recommerce\QueueManager\Adapter;

use Aws\Sqs\SqsClient as AwsSqsClient;
use Recommerce\QueueManager\Exception\QueueReaderException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SqsClientFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceManager
     * @return mixed
     * @throws QueueReaderException
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        return self::createServiceFromConfig($serviceManager->get('Config'));
    }

    /**
     * @param array $config
     * @return SqsClient
     * @throws QueueReaderException
     */
    public static function createServiceFromConfig(array $config)
    {
        $hasConfiguration = isset($config['queue_client']['url'])
            && isset($config['queue_client']['params']);

        if (!$hasConfiguration) {
            throw new QueueReaderException("Unable to find config for sqs client");
        }

        $queueUrl = $config['queue_client']['url'];
        $awsSqsParams = $config['queue_client']['params'];

        return new SqsClient(
            AwsSqsClient::factory($awsSqsParams),
            $queueUrl
        );
    }
}
