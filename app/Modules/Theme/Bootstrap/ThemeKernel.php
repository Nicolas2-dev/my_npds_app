<?php

namespace App\Modules\Theme\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class ThemeKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Theme
        'Theme'         => 'App\Modules\Theme\Support\Facades\Theme',
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
