<?php

namespace Recommerce\QueueManager;

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
        $adapter = $serviceManager->get('recommerce.queue-manager.adapter.sqs-client');

        return new QueueWriter($adapter);
    }
}
