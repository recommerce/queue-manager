<?php

namespace Recommerce\QueueManager\Factory;

use Interop\Container\ContainerInterface;
use Recommerce\QueueManager\QueueReader;
use Zend\ServiceManager\Factory\FactoryInterface;

class QueueReaderFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapter = $container->get('recommerce.queue-manager.adapter-client');

        return new QueueReader($adapter);
    }
}
