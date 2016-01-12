<?php

namespace Recommerce\QueueManager;

use Zend\ServiceManager\ServiceLocatorInterface;

class QueueReaderFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $instance;

    private $serviceManager;

    public function setUp()
    {
        $this->serviceManager = $this->getMock(ServiceLocatorInterface::class);
        $this->instance = new QueueReaderFactory();
    }

    public function testCreateService()
    {
        $adapter = $this->getMock(AdapterInterface::class);

        $this
            ->serviceManager
            ->expects($this->once())
            ->method('get')
            ->with('recommerce.queue-manager.adapter.sqs-client')
            ->willReturn($adapter);

        $this->assertInstanceOf(
            QueueReaderInterface::class,
            $this->instance->createService($this->serviceManager)
        );
    }
}
