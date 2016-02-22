<?php

namespace Recommerce\QueueManager\Adapter;

use Recommerce\QueueManager\AdapterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoggerClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $instance;

    private $serviceManager;

    public function setUp()
    {
        $this->serviceManager = $this->getMock(ServiceLocatorInterface::class);
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
            ->serviceManager
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn($config);

        $client = $this->instance->createService($this->serviceManager);

        $this->assertInstanceOf(AdapterInterface::class, $client);
        $this->assertInstanceOf(LoggerClient::class, $client);
    }

    /**
     * @expectedException \Recommerce\QueueManager\Exception\QueueReaderException
     */
    public function testCreateServiceNoConfigException()
    {
        $this
            ->serviceManager
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn([]);

        $this->instance->createService($this->serviceManager);
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
            ->serviceManager
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn($config);

        $this->instance->createService($this->serviceManager);
    }
}
