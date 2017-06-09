<?php

namespace igancev\push;

interface RecipientTypeInterface
{
    /**
     * @param Message $message
     * @return void
     */
    public function addMessage(Message $message);

    /**
     * @return bool
     */
    public function send();

    /**
     * @return array
     */
    public function getErrors();
}
