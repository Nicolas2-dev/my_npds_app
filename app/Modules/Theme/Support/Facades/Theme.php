<?php

namespace App\Modules\Theme\Support\Facades;

use App\Modules\Theme\Library\ThemeManager;


class Theme
{

    public static function __callStatic($method, $parameters)
    {
        $instance = ThemeManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}