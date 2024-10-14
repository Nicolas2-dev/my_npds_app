<?php

namespace Modules\News\Support\Facades;

use Modules\News\Library\NewsAutomatedManager;


class NewsAutomated
{

    public static function __callStatic($method, $parameters)
    {
        $instance = NewsAutomatedManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}