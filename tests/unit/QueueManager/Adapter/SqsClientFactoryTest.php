<?php

namespace Recommerce\QueueManager\Adapter;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Recommerce\QueueManager\AdapterInterface;

class SqsClientFactoryTest extends TestCase
{
    private $instance;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $container;

    public function setUp()
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->instance = new SqsClientFactory();
    }

    public function testCreateService()
    {
        $this
            ->container
            ->expects($this->once())
            ->method('get')
            ->withConsecutive(
                ['Config']
            )
            ->will(
                $this->returnValueMap([
                    [
                        'Config',
                        [
                            'queue_client' => [
                                'url' => 'http://myqueue.com',
                                'params' => [
                                    'region' => 'us-west-2',
                                    'version' => '2012-11-05'
                                ],
                                'options' => [

                                ]
                            ]
                        ]
                    ]
                ])
            );

        $client = $this->instance->__invoke($this->container, 'a');

        $this->assertInstanceOf(AdapterInterface::class, $client);
        $this->assertInstanceOf(SqsClient::class, $client);
    }

    /**
     * @expectedException \Recommerce\QueueManager\Exception\QueueReaderException
     */
    public function testCreateServiceException()
    {
        $this
            ->container
            ->expects($this->exactly(1))
            ->method('get')
            ->withConsecutive(
                ['Config']
            )
            ->will(
                $this->returnValueMap([
                    [
                        'Config',
                        []
                    ]
                ])
            );

        $this->instance->__invoke($this->container, 'a');
    }
}
