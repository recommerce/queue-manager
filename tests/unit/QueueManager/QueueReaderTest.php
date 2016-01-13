<?php

namespace Recommerce\QueueManager;

class QueueReaderTest extends \PHPUnit_Framework_TestCase
{
    private $instance;

    private $eventManager;

    private $adapter;

    private $message1;

    private $message2;

    public function setUp()
    {
        $this->adapter = $this->getMock(AdapterInterface::class);
        $this->instance = new QueueReader($this->adapter);

        $this->eventManager = $this->getMock('Zend\EventManager\EventManagerInterface');

        $this->message1 = (new Message())
            ->setReceptionRequestId('dhsqj672')
            ->setBody('<json_content1>');

        $this->message2 = (new Message())
            ->setReceptionRequestId('dhsqj673')
            ->setBody('<json_content2>');
    }

    public function testGetNextMessage()
    {
        $params = ['attr1' => 'lala'];

        $this
            ->adapter
            ->expects($this->exactly(3))
            ->method('receiveMessage')
            ->withConsecutive(
                [$this->equalTo($params)],
                [],
                []
            )
            ->will(
                $this->onConsecutiveCalls(
                    [$this->message1],
                    [$this->message2],
                    null
                )
            );

        // Test event only on first call
        $this->eventManager
            ->expects($this->any())
            ->method('trigger')
            ->withConsecutive(
                [
                    $this->equalTo(QueueReader::NEXT_MESSAGE_START),
                    $this->equalTo($this->instance),
                    $this->equalTo([
                        'params' => $params
                    ])
                ],
                [
                    $this->equalTo(QueueReader::BEFORE_RECEIVE_MESSAGE),
                    $this->equalTo($this->instance),
                    $this->equalTo([
                        'params' => $params
                    ])
                ],
                [
                    $this->equalTo(QueueReader::AFTER_RECEIVE_MESSAGE),
                    $this->equalTo($this->instance),
                    $this->equalTo([
                        'params' => $params,
                        'messages' => [$this->message1]
                    ])
                ],
                [
                    $this->equalTo(QueueReader::NEXT_MESSAGE_END),
                    $this->equalTo($this->instance),
                    $this->equalTo([
                        'params' => $params,
                        'currentMessage' => $this->message1
                    ])
                ]
            );

        $this->instance->setEventManager($this->eventManager);

        $this->assertSame($this->message1, $this->instance->getNextMessage($params));
        $this->assertSame($this->message2, $this->instance->getNextMessage());
        $this->assertNull($this->instance->getNextMessage());
    }

    public function testGetNextMessageWithoutMessage()
    {
        $this->assertNull($this->instance->getNextMessage());
    }

    public function testDeleteCurrentMessage()
    {
        $params = ['attr1' => 'toto'];

        // Dependency : need to get message before delete it
        $this
            ->adapter
            ->expects($this->once())
            ->method('receiveMessage')
            ->willReturn([$this->message1]);

        $this->instance->getNextMessage();

        // Real test
        $this
            ->adapter
            ->expects($this->once())
            ->method('deleteMessage')
            ->with(
                $this->equalTo($this->message1),
                $this->equalTo($params)
            )
            ->willReturn(null);

        $this->eventManager
            ->expects($this->any())
            ->method('trigger')
            ->withConsecutive(
                [
                    $this->equalTo(QueueReader::DELETE_CURRENT_MESSAGE),
                    $this->equalTo($this->instance),
                    $this->equalTo([
                        'params' => $params
                    ])
                ]
            );

        $this->instance->setEventManager($this->eventManager);

        $this->assertNull($this->instance->deleteCurrentMessage($params));
    }

    /**
     * @expectedException \Recommerce\QueueManager\Exception\QueueReaderException
     */
    public function testDeleteCurrentMessageException()
    {
        $this->instance->deleteCurrentMessage();
    }
}
