<?php

namespace Modules\Groupes\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class GroupesKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Groupe
        'Groupe'        => 'Modules\Groupes\Support\Facades\Groupe',
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
