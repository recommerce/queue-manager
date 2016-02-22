<?php

namespace Recommerce\QueueManager\Adapter;

use Recommerce\QueueManager\AdapterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SqsClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $instance;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serviceManager;

    public function setUp()
    {
        $this->serviceManager = $this->getMock(ServiceLocatorInterface::class);
        $this->instance = new SqsClientFactory();
    }

    public function testCreateService()
    {
        $this
            ->serviceManager
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

        $client = $this->instance->createService($this->serviceManager);

        $this->assertInstanceOf(AdapterInterface::class, $client);
        $this->assertInstanceOf(SqsClient::class, $client);
    }

    /**
     * @expectedException \Recommerce\QueueManager\Exception\QueueReaderException
     */
    public function testCreateServiceException()
    {
        $this
            ->serviceManager
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

        $this->instance->createService($this->serviceManager);
    }
}
