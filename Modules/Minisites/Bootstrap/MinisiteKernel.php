<?php

namespace Modules\Minisites\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class MinisiteKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Module Minisites
        'Minisite'         => 'Modules\Minisites\Support\Facades\Minisite',
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
