<?php

namespace Recommerce\QueueManager;

class QueueWriter implements QueueWriterInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param string $message
     * @param array $params
     * @return MessageSentInterface
     */
    public function sendMessage($message, array $params = [])
    {
        return $this->adapter->sendMessage($message, $params);
    }
}
