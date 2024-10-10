<?php

namespace Modules\Chat\Support\Facades;

use Modules\Chat\Library\ChatManager;

/**
 * Undocumented class
 */
class Chat
{

    public static function __callStatic($method, $parameters)
    {
        $instance = ChatManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
