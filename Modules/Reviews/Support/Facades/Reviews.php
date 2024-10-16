<?php

namespace Modules\Reviews\Support\Facades;

use Modules\Reviews\Library\ReviewsManager;

/**
 * Undocumented class
 */
class Reviews
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
        $instance = ReviewsManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
