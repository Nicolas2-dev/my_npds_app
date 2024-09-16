<?php

namespace Npds\Support\Facades;

use Npds\Database\Query\Builder as QueryBuilder;

class DB
{

    public static function __callStatic($method, $parameters)
    {
        $instance = new QueryBuilder();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}
