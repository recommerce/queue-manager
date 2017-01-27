<?php

namespace Recommerce\QueueManager\Factory;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Recommerce\QueueManager\AdapterInterface;

class AdapterFactoryTest extends TestCase
{
    private $instance;

    private $container;

    public function setUp()
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->instance = new AdapterFactory();
    }

    public function testCreateService()
    {
        $config = [
            'queue_client' => [
                'adapter' => 'sqs-client'
            ]
        ];

        $adapterClient = $this->createMock(AdapterInterface::class);

        $this
            ->container
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
            $this->instance->__invoke($this->container, 'a')
        );
    }

    /**
     * @expectedException \Recommerce\QueueManager\Exception\QueueReaderException
     */
    public function testCreateServiceConfigNotFound()
    {
        $config = [];

        $this
            ->container
            ->expects($this->any())
            ->method('get')
            ->with('Config')
            ->willReturn($config);

        $this->instance->__invoke($this->container, 'a');
    }
}
