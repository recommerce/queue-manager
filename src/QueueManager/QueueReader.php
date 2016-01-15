<?php

namespace Recommerce\QueueManager;

use Recommerce\QueueManager\Exception\QueueReaderException;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

class QueueReader implements QueueReaderInterface, EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    const NEXT_MESSAGE_START = 'QueueReader.getNextMessage.start';

    const NEXT_MESSAGE_END = 'QueueReader.getNextMessage.end';

    const BEFORE_RECEIVE_MESSAGE = 'QueueReader.getNextMessage.beforeReceiveMessage';

    const AFTER_RECEIVE_MESSAGE = 'QueueReader.getNextMessage.afterReceiveMessage';

    const DELETE_CURRENT_MESSAGE = 'QueueReader.deleteCurrentMessage.run';

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var array
     */
    private $cachedMessages = [];

    /**
     * @var MessageReceivedInterface
     */
    private $currentMessage;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param array $params
     * @return MessageReceivedInterface
     */
    public function getNextMessage(array $params = [])
    {
        $this
            ->getEventManager()
            ->trigger(self::NEXT_MESSAGE_START, $this, [
                'params' => $params
            ]);

        if (empty($this->cachedMessages)) {
            $this
                ->getEventManager()
                ->trigger(self::BEFORE_RECEIVE_MESSAGE, $this, [
                    'params' => $params
                ]);

            $messages = $this
                ->adapter
                ->receiveMessage($params);

            $this
                ->getEventManager()
                ->trigger(self::AFTER_RECEIVE_MESSAGE, $this, [
                    'params' => $params,
                    'messages' => $messages
                ]);

            $this->cachedMessages = $messages;
        }

        $this->currentMessage = (!empty($this->cachedMessages))
            ? array_shift($this->cachedMessages)
            : null;

        $this
            ->getEventManager()
            ->trigger(self::NEXT_MESSAGE_END, $this, [
                'params' => $params,
                'currentMessage' => $this->currentMessage
            ]);

        return $this->currentMessage;
    }

    /**
     * @param array $params
     * @throws QueueReaderException
     */
    public function deleteCurrentMessage(array $params = [])
    {
        if (!$this->currentMessage) {
            throw new QueueReaderException("Trying to remove non existing message. You should check you have message");
        }

        $this->deleteMessage($this->currentMessage, $params);
    }

    /**
     * @param MessageReceivedInterface $message
     * @param array $params
     */
    public function deleteMessage(MessageReceivedInterface $message, array $params = [])
    {
        $this
            ->getEventManager()
            ->trigger(self::DELETE_CURRENT_MESSAGE, $this, [
                'params' => $params
            ]);

        $this
            ->adapter
            ->deleteMessage($message, $params);
    }
}
