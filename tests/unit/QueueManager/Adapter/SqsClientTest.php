<?php

namespace Recommerce\QueueManager\Adapter;

use Aws\Sqs\SqsClient as AwsSqsClient;
use Recommerce\QueueManager\Message;
use Recommerce\QueueManager\MessageReceivedInterface;
use Recommerce\QueueManager\MessageSentInterface;

class SqsClientTest extends \PHPUnit_Framework_TestCase
{
    private $instance;

    private $awsSqsClient;

    private $queueUrl = 'http://queueurl.com';

    public function setUp()
    {
        $this->awsSqsClient = $this
            ->getMockBuilder(AwsSqsClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['receiveMessage', 'deleteMessage', 'sendMessage'])
            ->getMock();

        $this->instance = new SqsClient(
            $this->awsSqsClient,
            $this->queueUrl
        );
    }

    public function testToString()
    {
        $this->assertSame(
            "Amazon SQS adapter on {$this->queueUrl}",
            (string) $this->instance
        );
    }

    public function testGetQueueId()
    {
        $this->assertSame(
            $this->queueUrl,
            $this->instance->getQueueId()
        );
    }

    public function testReceiveMessage()
    {
        $params = [
            'QueueUrl' => $this->queueUrl
        ];

        $messageId = 'FGT-2389';
        $body = "{message}";
        $receiptHandle = '789687-KJHD';
        $attributes = [
            'attr1' => 'lala',
            'attr2' => 'toto'
        ];

        $sqsResult = [
            'Messages' => [
                [
                    'MessageId' => $messageId,
                    'Body' => $body,
                    'ReceiptHandle' => $receiptHandle,
                    'MessageAttributes' => $attributes
                ]
            ]
        ];

        $expectedMessage = (new Message())
            ->setId($messageId)
            ->setBody($body)
            ->setReceptionRequestId($receiptHandle)
            ->setAttributes($attributes);

        $this
            ->awsSqsClient
            ->expects($this->once())
            ->method('receiveMessage')
            ->with($params)
            ->willReturn($sqsResult);

        $messages = $this->instance->receiveMessage();

        $this->assertInternalType('array', $messages);
        $this->assertEquals($expectedMessage, $messages[0]);
        $this->assertInstanceOf(MessageReceivedInterface::class, $messages[0]);
    }

    public function testDeleteMessage()
    {
        $receiptHandle = '789687-KJHD';

        $expectedParams = [
            'QueueUrl' => $this->queueUrl,
            'ReceiptHandle' => $receiptHandle
        ];

        $this
            ->awsSqsClient
            ->expects($this->once())
            ->method('deleteMessage')
            ->with($this->equalTo($expectedParams))
            ->willReturn(null);

        $this->assertNull($this->instance->deleteMessage($receiptHandle));
    }

    public function testSendMessage()
    {
        $messageId = 'GRT-6372';
        $messageBody = '{message_body}';

        $attributes = [
            'attr1' => 'lala',
            'attr2' => 'toto'
        ];

        $expectedParams = [
            'QueueUrl' => $this->queueUrl,
            'MessageBody' => $messageBody,
            'MessageAttributes' => $attributes
        ];

        $expectedMessage = (new Message())->setId($messageId);

        $sqsResult = [
            'MessageId' => $messageId,
        ];

        $this
            ->awsSqsClient
            ->expects($this->once())
            ->method('sendMessage')
            ->with($expectedParams)
            ->willReturn($sqsResult);

        $message = $this
            ->instance
            ->sendMessage($messageBody, $attributes);

        $this->assertEquals($expectedMessage, $message);
        $this->assertInstanceOf(MessageSentInterface::class, $message);
    }
}
