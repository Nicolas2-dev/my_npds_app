<?php

namespace App\Modules\Chat\Support\Facades;

use App\Modules\Chat\Library\ChatManager;

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
