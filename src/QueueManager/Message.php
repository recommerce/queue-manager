<?php

namespace Recommerce\QueueManager;

class Message implements MessageSentInterface, MessageReceivedInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $receptionRequestId;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getReceptionRequestId()
    {
        return $this->receptionRequestId;
    }

    /**
     * @param string $receptionRequestId
     * @return $this
     */
    public function setReceptionRequestId($receptionRequestId)
    {
        $this->receptionRequestId = $receptionRequestId;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }
}
