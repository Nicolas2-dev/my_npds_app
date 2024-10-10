<?php

namespace Modules\Module\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class ModuleKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Module
        'Module'        => 'Modules\Module\Support\Facades\Module',
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
