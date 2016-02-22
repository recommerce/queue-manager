<?php

namespace Recommerce\QueueManager\Adapter;

use Recommerce\QueueManager\AdapterInterface;
use Recommerce\QueueManager\Message;
use Recommerce\QueueManager\MessageReceivedInterface;
use Recommerce\QueueManager\MessageSentInterface;
use Zend\Log\LoggerInterface;

class LoggerClient implements AdapterInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "Logger adapter (fake adapter for testing purpose)";
    }

    /**
     * @return string
     */
    public function getQueueId()
    {
        return "Logger";
    }

    /**
     * @param array $options
     * @return MessageReceivedInterface[]
     */
    public function receiveMessage(array $options = [])
    {
        $this->logger->info(
            sprintf(
                "Receive message called with : %s",
                json_encode($options)
            )
        );

        return [];
    }

    /**
     * @param MessageReceivedInterface $message
     * @param array $options
     */
    public function deleteMessage(MessageReceivedInterface $message, array $options = [])
    {
        $this->logger->info(
            sprintf(
                "Delete message called for id '%s' with : %s",
                $message->getId(),
                json_encode($options)
            )
        );
    }

    /**
     * @param string $messageBody
     * @param array $attributes
     * @param array $options
     * @return MessageSentInterface
     */
    public function sendMessage($messageBody, array $attributes = [], array $options = [])
    {
        $message = <<<EOT
Send message called with :
    - Message : %s
    - Attributes : %s
    - Options : %s
EOT;

        $this->logger->info(
            sprintf(
                $message,
                $messageBody,
                json_encode($attributes),
                json_encode($options)
            )
        );

        return (new Message())
            ->setId('Fake received message')
            ->setBody($messageBody)
            ->setAttributes($attributes);
    }
}
