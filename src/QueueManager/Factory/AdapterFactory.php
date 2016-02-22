<?php

namespace Recommerce\QueueManager\Factory;

use Recommerce\QueueManager\Exception\QueueReaderException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceManager
     * @return array|object
     * @throws QueueReaderException
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $config = $serviceManager->get('Config');

        if (empty($config['queue_client']['adapter'])) {
            throw new QueueReaderException(
                "Unable to find queue client adapter configuration"
            );
        }

        $factoryKey = 'recommerce.queue-manager.adapter.' . $config['queue_client']['adapter'];

        return $serviceManager->get($factoryKey);
    }
}
