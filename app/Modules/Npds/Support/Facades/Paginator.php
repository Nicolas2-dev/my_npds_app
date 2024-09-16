<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\PaginatorManager;


class Paginator
{

    public static function __callStatic($method, $parameters)
    {
        $instance = PaginatorManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}