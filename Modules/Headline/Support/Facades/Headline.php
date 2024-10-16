<?php

namespace Modules\Headline\Support\Facades;

use Modules\Headline\Library\HeadlineManager;



class Headline
{

    public static function __callStatic($method, $parameters)
    {
        $instance = HeadlineManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}