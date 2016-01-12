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
     * @return MessageReceivedInterface
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#receivemessage
     */
    public function receiveMessage()
    {
        $params = [
            'QueueUrl' => $this->queueUrl,
        ];

        $params = array_merge($params, $this->options);

        $sqsResult = $this->awsSqsClient->receiveMessage($params);

        return (new Message())
            ->setId($sqsResult['MessageId'])
            ->setBody($sqsResult['Body'])
            ->setReceptionRequestId($sqsResult['ReceiptHandle'])
            ->setAttributes($sqsResult['Attributes']);
    }

    /**
     * @param string $receiptHandle
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#deletemessage
     */
    public function deleteMessage($receiptHandle)
    {
        $params = [
            'QueueUrl' => $this->queueUrl,
            'ReceiptHandle' => $receiptHandle,
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
            'QueueUrl' => $this->queueUrl,
            'MessageBody' => $messageBody,
            'MessageAttributes' => $attributes
        ];

        $sqsResult = $this->awsSqsClient->sendMessage($params);

        return (new Message())->setId($sqsResult['MessageId']);
    }
}
