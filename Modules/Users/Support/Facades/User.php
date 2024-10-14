<?php

namespace Modules\Users\Support\Facades;

use Modules\Users\Library\UserManager;


class User
{

    public static function __callStatic($method, $parameters)
    {
        $instance = UserManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}