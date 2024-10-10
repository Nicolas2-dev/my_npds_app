<?php

namespace Modules\Edito\Bootstrap;

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
        // Module Edito
        'Edito'         => 'Modules\Edito\Support\Facades\Edito',
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
