<?php

namespace Recommerce\QueueManager;

interface QueueReaderInterface
{
    /**
     * @param array $params
     * @return MessageReceivedInterface
     */
    public function getNextMessage(array $params = []);

    /**
     * @param array $params
     */
    public function deleteCurrentMessage(array $params = []);
}
