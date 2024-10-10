<?php

namespace Modules\Module\Support\Facades;

use Modules\Module\Library\ModuleManager;


class Module
{

    public static function __callStatic($method, $parameters)
    {
        $instance = ModuleManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}