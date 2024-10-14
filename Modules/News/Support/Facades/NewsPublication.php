<?php

namespace Modules\News\Support\Facades;

use Modules\News\Library\NewsPublicationManager;


class NewsPublication
{

    public static function __callStatic($method, $parameters)
    {
        $instance = NewsPublicationManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}