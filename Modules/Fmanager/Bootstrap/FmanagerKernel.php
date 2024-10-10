<?php

namespace Modules\Fmanager\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class FmanagerKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Module fmanager
        'fmanager'         => 'Modules\fmanager\Support\Facades\fmanager',
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
