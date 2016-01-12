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
     * @return MessageReceivedInterface[]
     */
    public function receiveMessage();

    /**
     * @param string $receiptHandle
     */
    public function deleteMessage($receiptHandle);

    /**
     * @param string $messageBody
     * @param array $attributes
     * @return MessageSentInterface
     */
    public function sendMessage($messageBody, array $attributes = []);
}
