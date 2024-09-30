<?php

namespace App\Modules\Edito\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class EditoKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Edito
        'Edito'         => 'App\Modules\Edito\Support\Facades\Edito',
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
