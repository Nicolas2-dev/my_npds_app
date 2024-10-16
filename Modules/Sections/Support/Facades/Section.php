<?php

namespace Modules\Sections\Support\Facades;

use Modules\Sections\Library\SectionManager;

/**
 * Undocumented class
 */
class Section
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
        $instance = SectionManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
