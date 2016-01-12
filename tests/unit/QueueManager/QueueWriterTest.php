<?php

namespace Recommerce\QueueManager;

class QueueWriterTest extends \PHPUnit_Framework_TestCase
{
    private $instance;

    private $adapter;

    public function setUp()
    {
        $this->adapter = $this->getMock(AdapterInterface::class);
        $this->instance = new QueueWriter($this->adapter);
    }

    public function testSendMessage()
    {
        $message = '{message_body}';
        $params = [
            'attr1' => 'lala',
            'attr2' => 'toto'
        ];
        $messageSent = $this->getMock(MessageSentInterface::class);

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
