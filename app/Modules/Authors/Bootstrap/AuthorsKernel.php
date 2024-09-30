<?php

namespace App\Modules\Authors\Bootstrap;

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
        // Modules Author
        'Author'        => 'App\Modules\Authors\Support\Facades\Author',
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
