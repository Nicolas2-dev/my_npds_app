<?php

namespace Modules\Banners\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class BannersKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Module Banners
        'Banner'         => 'Modules\Banners\Support\Facades\Banner',
    ];

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function aliases_loader()
    {
        AliasLoader::getInstance(static::$aliases)->register();
    }

}
