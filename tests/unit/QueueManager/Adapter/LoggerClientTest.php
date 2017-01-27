<?php

namespace Recommerce\QueueManager\Adapter;

use PHPUnit\Framework\TestCase;
use Recommerce\QueueManager\Message;
use Recommerce\QueueManager\MessageReceivedInterface;
use Zend\Log\LoggerInterface;

class LoggerClientTest extends TestCase
{
    private $instance;

    private $logger;

    public function setUp()
    {
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->instance = new LoggerClient($this->logger);
    }

    public function testToString()
    {
        $this->assertSame(
            "Logger adapter (fake adapter for testing purpose)",
            (string) $this->instance
        );
    }

    public function testGetQueueId()
    {
        $this->assertSame(
            "Logger",
            $this->instance->getQueueId()
        );
    }

    public function testReceiveMessage()
    {
        $options = [
            'opt1' => 'value1',
            'opt2' => 'value2'
        ];

        $message = sprintf(
            "Receive message called with : %s",
            json_encode($options)
        );

        $this
            ->logger
            ->expects($this->once())
            ->method("info")
            ->with($message)
            ->will($this->returnSelf());

        $this->assertInternalType(
            'array',
            $this->instance->receiveMessage($options)
        );
    }

    public function testDeleteMessage()
    {
        $options = [
            'opt1' => 'value1',
            'opt2' => 'value2'
        ];

        $receivedMessageId = '#238';

        $message = sprintf(
            "Delete message called for id '%s' with : %s",
            $receivedMessageId,
            json_encode($options)
        );

        $messageReceived = $this->createMock(MessageReceivedInterface::class);
        $messageReceived
            ->expects($this->once())
            ->method('getId')
            ->willReturn($receivedMessageId);

        $this
            ->logger
            ->expects($this->once())
            ->method('info')
            ->with($message)
            ->will($this->returnSelf());

        $this->assertNull(
            $this->instance->deleteMessage($messageReceived, $options)
        );
    }

    public function testSendMessage()
    {
        $messageBody = '<Message body>';

        $attributes = [
            'attr1' => 'val1',
            'attr2' => 'val2'
        ];

        $options = [
            'opt1' => 'val1',
            'opt2' => 'val2'
        ];

        $message = <<<EOT
Send message called with :
    - Message : %s
    - Attributes : %s
    - Options : %s
EOT;

        $expectedMessage = sprintf(
            $message,
            $messageBody,
            json_encode($attributes),
            json_encode($options)
        );

        $expectedObject = (new Message())
            ->setId('Fake received message')
            ->setBody($messageBody)
            ->setAttributes($attributes);

        $this
            ->logger
            ->expects($this->once())
            ->method('info')
            ->with($expectedMessage)
            ->will($this->returnSelf());

        $this->assertEquals(
            $expectedObject,
            $this->instance->sendMessage($messageBody, $attributes, $options)
        );
    }
}
