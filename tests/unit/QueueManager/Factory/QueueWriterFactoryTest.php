<?php

namespace Recommerce\QueueManager\Factory;

use Recommerce\QueueManager\AdapterInterface;
use Recommerce\QueueManager\QueueWriterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QueueWriterFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $instance;

    private $serviceManager;

    public function setUp()
    {
        $this->serviceManager = $this->getMock(ServiceLocatorInterface::class);
        $this->instance = new QueueWriterFactory();
    }

    public function testCreateService()
    {
        $adapter = $this->getMock(AdapterInterface::class);

        $this
            ->serviceManager
            ->expects($this->once())
            ->method('get')
            ->with('recommerce.queue-manager.adapter-client')
            ->willReturn($adapter);

        $this->assertInstanceOf(
            QueueWriterInterface::class,
            $this->instance->createService($this->serviceManager)
        );
    }
}
