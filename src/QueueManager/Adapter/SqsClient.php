<?php

namespace Recommerce\QueueManager\Adapter;

use Aws\Sqs\SqsClient as AwsSqsClient;
use Recommerce\QueueManager\AdapterInterface;
use Recommerce\QueueManager\Message;
use Recommerce\QueueManager\MessageReceivedInterface;
use Recommerce\QueueManager\MessageSentInterface;

/**
 * Class SqsClient
 *
 * @package Recommerce\QueueManager\Adapter
 * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html
 */
class SqsClient implements AdapterInterface
{
    const MESSAGE_ID = 'MessageId';
    const MESSAGE_BODY = 'Body';
    const MESSAGE_ATTRIBUTES = 'MessageAttributes';
    const QUEUE_URL = 'QueueUrl';
    const RECEIPT_HANDLE = 'ReceiptHandle';

    /**
     * @var AwsSqsClient
     */
    private $awsSqsClient;

    /**
     * @var string
     */
    private $queueUrl;

    /**
     * @var array
     */
    private $options;

    /**
     * @param AwsSqsClient $awsSqsClient
     * @param string $queueUrl
     * @param array $options
     */
    public function __construct(AwsSqsClient $awsSqsClient, $queueUrl, array $options = [])
    {
        $this->awsSqsClient = $awsSqsClient;
        $this->queueUrl = $queueUrl;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "Amazon SQS adapter on %s",
            $this->queueUrl
        );
    }

    /**
     * @return string
     */
    public function getQueueId()
    {
        return $this->queueUrl;
    }

    /**
     * @return MessageReceivedInterface[]
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#receivemessage
     */
    public function receiveMessage()
    {
        $params = [
            self::QUEUE_URL => $this->queueUrl,
        ];

        $params = array_merge($params, $this->options);

        $sqsResult = $this->awsSqsClient->receiveMessage($params);
        $sqsMessages = (empty($sqsResult['Messages']))
            ? []
            : $sqsResult['Messages'];

        return $this->createMessageObject($sqsMessages);
    }

    /**
     * @param array $sqsMessages
     * @return array
     */
    private function createMessageObject(array $sqsMessages)
    {
        $messages = [];

        foreach ($sqsMessages as $sqsMessage) {
            $message = (new Message())
                ->setId($sqsMessage[self::MESSAGE_ID])
                ->setBody($sqsMessage[self::MESSAGE_BODY])
                ->setReceptionRequestId($sqsMessage[self::RECEIPT_HANDLE]);

            if (!empty($sqsMessage[self::MESSAGE_ATTRIBUTES])) {
                $message->setAttributes($sqsMessage[self::MESSAGE_ATTRIBUTES]);
            }

            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * @param string $receiptHandle
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#deletemessage
     */
    public function deleteMessage($receiptHandle)
    {
        $params = [
            self::QUEUE_URL => $this->queueUrl,
            self::RECEIPT_HANDLE => $receiptHandle,
        ];

        $this->awsSqsClient->deleteMessage($params);
    }

    /**
     * @param string $messageBody
     * @param array $attributes
     * @return MessageSentInterface
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#sendmessage
     */
    public function sendMessage($messageBody, array $attributes = [])
    {
        $params = [
            self::QUEUE_URL => $this->queueUrl,
            'MessageBody' => $messageBody,
            self::MESSAGE_ATTRIBUTES => $attributes
        ];

        $sqsResult = $this->awsSqsClient->sendMessage($params);

        return (new Message())->setId($sqsResult[self::MESSAGE_ID]);
    }
}
