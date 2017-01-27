<?php

namespace Recommerce\QueueManager;

use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testId()
    {
        $id = '1398';
        $message = new Message();

        $this->assertSame($message, $message->setId($id));
        $this->assertSame($id, $message->getId());
    }

    public function testBody()
    {
        $body = '1398';
        $message = new Message();

        $this->assertSame($message, $message->setBody($body));
        $this->assertSame($body, $message->getBody());
    }

    public function testAttributes()
    {
        $attributes = [
            'attr1' => 'lala',
            'attr2' => 'toto'
        ];
        $message = new Message();

        $this->assertSame($message, $message->setAttributes($attributes));
        $this->assertSame($attributes, $message->getAttributes());
    }

    public function testReceptionRequestId()
    {
        $receptionRequestId = '1398';
        $message = new Message();

        $this->assertSame($message, $message->setReceptionRequestId($receptionRequestId));
        $this->assertSame($receptionRequestId, $message->getReceptionRequestId());
    }
}
