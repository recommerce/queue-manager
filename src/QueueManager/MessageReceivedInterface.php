<?php

namespace Recommerce\QueueManager;

interface MessageReceivedInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getBody();

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body);

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes($attributes);

    /**
     * @return string
     */
    public function getReceptionRequestId();

    /**
     * @param string $receptionRequestId
     * @return $this
     */
    public function setReceptionRequestId($receptionRequestId);
}