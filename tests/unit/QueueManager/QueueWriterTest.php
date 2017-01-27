<?php

namespace Recommerce\QueueManager;

use PHPUnit\Framework\TestCase;

class QueueWriterTest extends TestCase
{
    private $instance;

    private $adapter;

    public function setUp()
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
        $this->instance = new QueueWriter($this->adapter);
    }

    public function testSendMessage()
    {
        $message = '{message_body}';
        $params = [
            'attr1' => 'lala',
            'attr2' => 'toto'
        ];
        $messageSent = $this->createMock(MessageSentInterface::class);

        $this
            ->adapter
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                $this->equalTo($message),
                $this->equalTo($params)
            )
            ->willReturn($messageSent);

        $this->assertSame(
            $messageSent,
            $this->instance->sendMessage($message, $params)
        );
    }
}
