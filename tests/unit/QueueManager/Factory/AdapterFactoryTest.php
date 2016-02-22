<?php

namespace Recommerce\QueueManager\Factory;

use Recommerce\QueueManager\AdapterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $instance;

    private $serviceManager;

    public function setUp()
    {
        $this->serviceManager = $this->getMock(ServiceLocatorInterface::class);
        $this->instance = new AdapterFactory();
    }

    public function testCreateService()
    {
        $config = [
            'queue_client' => [
                'adapter' => 'sqs-client'
            ]
        ];

        $adapterClient = $this->getMock(AdapterInterface::class);

        $this
            ->serviceManager
            ->expects($this->any())
            ->method('get')
            ->with(
                $this->logicalOr(
                    'Config',
                    'recommerce.queue-manager.adapter.sqs-client'
                )
            )
            ->will(
                $this->returnValueMap([
                    ['Config', $config],
                    ['recommerce.queue-manager.adapter.sqs-client', $adapterClient]
                ])
            );

        $this->assertSame(
            $adapterClient,
            $this->instance->createService($this->serviceManager)
        );
    }

    /**
     * @expectedException \Recommerce\QueueManager\Exception\QueueReaderException
     */
    public function testCreateServiceConfigNotFound()
    {
        $config = [];

        $this
            ->serviceManager
            ->expects($this->any())
            ->method('get')
            ->with('Config')
            ->willReturn($config);

        $this->instance->createService($this->serviceManager);
    }
}
