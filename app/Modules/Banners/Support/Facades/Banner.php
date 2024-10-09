<?php

namespace App\Modules\Banners\Support\Facades;

use App\Modules\Banners\Library\BannerManager;

/**
 * Undocumented class
 */
class Banner
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
        $instance = BannerManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
