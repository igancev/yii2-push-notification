<?php

namespace igancev\push;

use ApnsPHP_Log_Interface;

class FakeLogger implements ApnsPHP_Log_Interface
{
    /**
     * Logs a message.
     *
     * @param  $sMessage @type string The message.
     */
    public function log($sMessage)
    {
        // fake log
    }
}
