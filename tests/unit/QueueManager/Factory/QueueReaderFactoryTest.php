<?php

namespace Recommerce\QueueManager\Factory;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Recommerce\QueueManager\AdapterInterface;
use Recommerce\QueueManager\QueueReaderInterface;

class QueueReaderFactoryTest extends TestCase
{
    private $instance;

    private $container;

    public function setUp()
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->instance = new QueueReaderFactory();
    }

    public function testCreateService()
    {
        $adapter = $this->createMock(AdapterInterface::class);

        $this
            ->container
            ->expects($this->once())
            ->method('get')
            ->with('recommerce.queue-manager.adapter-client')
            ->willReturn($adapter);

        $this->assertInstanceOf(
            QueueReaderInterface::class,
            $this->instance->__invoke($this->container, 'a')
        );
    }
}
