<?php

namespace Modules\Newsletter\Support\Facades;

use Modules\Newsletter\Library\NewsletterManager;

/**
 * Undocumented class
 */
class Newsletter
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
        $instance = NewsletterManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
