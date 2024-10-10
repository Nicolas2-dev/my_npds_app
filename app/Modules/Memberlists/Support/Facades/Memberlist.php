<?php

namespace App\Modules\Memberlists\Support\Facades;

use App\Modules\Memberlists\Library\MemberlistManager;

/**
 * Undocumented class
 */
class Memberlist
{
    /**
     * Undocumented function
     *
     * @param [type] $method
     * @param [type] $parameters
     * @return void
     */
    public static function __callStatic($method, $parameters)
    {
        $instance = MemberlistManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
