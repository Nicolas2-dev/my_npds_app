<?php

namespace App\Modules\Blocks\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class BlocksKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Module Blocks
        'Block'         => 'App\Modules\Blocks\Support\Facades\Block',
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
