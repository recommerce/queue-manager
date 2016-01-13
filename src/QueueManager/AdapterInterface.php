<?php

namespace Recommerce\QueueManager;

interface AdapterInterface
{
    /**
     * @return string
     */
    public function __toString();

    /**
     * @return string
     */
    public function getQueueId();

    /**
     * @param array $options
     * @return MessageReceivedInterface[]
     */
    public function receiveMessage(array $options = []);

    /**
     * @param MessageReceivedInterface $message
     * @param array $options
     */
    public function deleteMessage(MessageReceivedInterface $message, array $options = []);

    /**
     * @param string $messageBody
     * @param array $attributes
     * @param array $options
     * @return MessageSentInterface
     */
    public function sendMessage($messageBody, array $attributes = [], array $options = []);
}
