<?php

namespace Modules\Geoloc\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class GeolocKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Geoloc
        'Geoloc'        => 'Modules\Geoloc\Support\Facades\Geoloc',
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
