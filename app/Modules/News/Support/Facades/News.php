<?php

namespace App\Modules\News\Support\Facades;

use App\Modules\News\Library\NewsManager;


class News
{

    public static function __callStatic($method, $parameters)
    {
        $instance = NewsManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}