<?php

namespace App\Modules\Users\Support\Facades;

use App\Modules\Users\Library\UserMenuManager;

/**
 * Undocumented class
 */
class UserMenu
{

    public static function __callStatic($method, $parameters)
    {
        $instance = UserMenuManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
