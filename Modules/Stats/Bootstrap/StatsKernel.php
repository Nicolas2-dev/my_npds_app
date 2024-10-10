<?php

namespace Modules\Stats\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class StatsKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Stats
        'Stat'          => 'Modules\Stats\Support\Facades\Stat',
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
