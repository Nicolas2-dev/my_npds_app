<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\LanguageManager;


class Language
{

    public static function __callStatic($method, $parameters)
    {
        $instance = LanguageManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}