<?php

namespace Modules\Messenger\Support\Facades;

use Modules\Messenger\Library\MessengerManager;


class Messenger
{

    public static function __callStatic($method, $parameters)
    {
        $instance = MessengerManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}