<?php

namespace Modules\Edito\Support\Facades;

use Modules\edito\Library\EditoManager;


class Edito
{

    public static function __callStatic($method, $parameters)
    {
        $instance = EditoManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
