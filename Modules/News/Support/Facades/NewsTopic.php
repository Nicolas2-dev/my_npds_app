<?php

namespace Modules\News\Support\Facades;

use Modules\News\Library\NewsTopicManager;


class NewsTopic
{

    public static function __callStatic($method, $parameters)
    {
        $instance = NewsTopicManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}