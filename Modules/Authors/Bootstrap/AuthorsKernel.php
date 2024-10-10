<?php

namespace Modules\Authors\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class AuthorsKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Module Authors
        'Author'        => 'Modules\Authors\Support\Facades\Author',
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
