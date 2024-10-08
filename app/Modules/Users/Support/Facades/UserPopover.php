<?php

namespace App\Modules\Users\Support\Facades;

use App\Modules\Users\Library\UserPopoverManager;

/**
 * Undocumented class
 */
class UserPopover
{

    public static function __callStatic($method, $parameters)
    {
        $instance = UserPopoverManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
