<?php

namespace Recommerce\QueueManager\Factory;

use Interop\Container\ContainerInterface;
use Recommerce\QueueManager\Exception\QueueReaderException;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        if (empty($config['queue_client']['adapter'])) {
            throw new QueueReaderException(
                "Unable to find queue client adapter configuration"
            );
        }

        $factoryKey = 'recommerce.queue-manager.adapter.' . $config['queue_client']['adapter'];

        return $container->get($factoryKey);
    }
}
