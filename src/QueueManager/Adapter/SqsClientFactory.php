<?php

namespace Recommerce\QueueManager\Adapter;

use Aws\Sqs\SqsClient as AwsSqsClient;
use Interop\Container\ContainerInterface;
use Recommerce\QueueManager\Exception\QueueReaderException;
use Zend\ServiceManager\Factory\FactoryInterface;

class SqsClientFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $this->createServiceFromConfig($container->get('Config'));
    }

    /**
     * @param array $config
     * @return SqsClient
     * @throws QueueReaderException
     */
    public function createServiceFromConfig(array $config)
    {
        $hasConfiguration = isset($config['queue_client']['url'])
            && isset($config['queue_client']['params'])
            && isset($config['queue_client']['options']);

        if (!$hasConfiguration) {
            throw new QueueReaderException("Unable to find config for sqs client");
        }

        $queueUrl = $config['queue_client']['url'];
        $awsSqsParams = $config['queue_client']['params'];
        $options = $config['queue_client']['options'];

        return new SqsClient(
            AwsSqsClient::factory($awsSqsParams),
            $queueUrl,
            $options
        );
    }
}
