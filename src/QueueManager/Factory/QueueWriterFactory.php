<?php

namespace Recommerce\QueueManager\Factory;

use Recommerce\QueueManager\QueueWriter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QueueWriterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceManager
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $adapter = $serviceManager->get('recommerce.queue-manager.adapter-client');

        return new QueueWriter($adapter);
    }
}
