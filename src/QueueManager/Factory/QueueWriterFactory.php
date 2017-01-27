<?php

namespace Recommerce\QueueManager\Factory;

use Interop\Container\ContainerInterface;
use Recommerce\QueueManager\QueueWriter;
use Zend\ServiceManager\Factory\FactoryInterface;

class QueueWriterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapter = $container->get('recommerce.queue-manager.adapter-client');

        return new QueueWriter($adapter);
    }
}
