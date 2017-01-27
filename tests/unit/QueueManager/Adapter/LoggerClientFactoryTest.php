<?php

namespace Recommerce\QueueManager\Adapter;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Recommerce\QueueManager\AdapterInterface;

class LoggerClientFactoryTest extends TestCase
{
    private $instance;

    private $container;

    public function setUp()
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->instance = new LoggerClientFactory();
    }

    public function testCreateService()
    {
        $config = [
            'queue_client' => [
                'file_path' => '/tmp/recommerce-queue-manager-logger-client'
            ]
        ];

        $this
            ->container
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn($config);

        $client = $this->instance->__invoke($this->container, 'a');

        $this->assertInstanceOf(AdapterInterface::class, $client);
        $this->assertInstanceOf(LoggerClient::class, $client);
    }

    /**
     * @expectedException \Recommerce\QueueManager\Exception\QueueReaderException
     */
    public function testCreateServiceNoConfigException()
    {
        $this
            ->container
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn([]);

        $this->instance->__invoke($this->container, 'a');
    }

    /**
     * @expectedException \Recommerce\QueueManager\Exception\QueueReaderException
     */
    public function testCreateServiceInvalidFileException()
    {
        $config = [
            'queue_client' => [
                'file_path' => '/imaginaryDir/imaginaryFile'
            ]
        ];

        $this
            ->container
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn($config);

        $this->instance->__invoke($this->container, 'a');
    }
}
