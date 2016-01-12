<?php

namespace Recommerce\QueueManager;

interface MessageSentInterface
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
}