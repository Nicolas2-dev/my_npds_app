<?php

namespace App\Modules\ReseauxSociaux\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class ReseauxKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules ReseauxSociaux
        'Reseaux'        => 'App\Modules\ReseauxSociaux\Support\Facades\Reseaux',
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
