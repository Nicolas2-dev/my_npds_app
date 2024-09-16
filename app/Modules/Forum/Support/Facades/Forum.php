<?php

namespace App\Modules\Forum\Support\Facades;

use App\Modules\Forum\Library\ForumManager;


class Forum
{

    public static function __callStatic($method, $parameters)
    {
        $instance = ForumManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}