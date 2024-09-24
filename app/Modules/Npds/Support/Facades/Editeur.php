<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\EditeurManager;


/**
 * Undocumented class
 */
class Editeur
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
        $instance = EditeurManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
