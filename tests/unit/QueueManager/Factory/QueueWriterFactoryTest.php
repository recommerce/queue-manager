<?php

namespace Recommerce\QueueManager\Factory;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Recommerce\QueueManager\AdapterInterface;
use Recommerce\QueueManager\QueueWriterInterface;

class QueueWriterFactoryTest extends TestCase
{
    private $instance;

    private $container;

    public function setUp()
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->instance = new QueueWriterFactory();
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
            QueueWriterInterface::class,
            $this->instance->__invoke($this->container, 'a')
        );
    }
}
