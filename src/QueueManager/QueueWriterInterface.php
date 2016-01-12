<?php

namespace Recommerce\QueueManager;

interface QueueWriterInterface
{
    /**
     * @param string $message
     * @param array $params
     * @return MessageSentInterface
     */
    public function sendMessage($message, array $params = []);
}
