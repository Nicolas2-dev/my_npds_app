<?php

namespace Modules\Authors\Support\Facades;

use Modules\Authors\Library\AuthorsManager;


class Author
{

    public static function __callStatic($method, $parameters)
    {
        $instance = AuthorsManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
    
}
