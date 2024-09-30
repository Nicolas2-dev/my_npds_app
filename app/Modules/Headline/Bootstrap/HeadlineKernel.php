<?php

namespace App\Modules\Headline\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class HeadlineKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Headline
        'Headline'        => 'App\Modules\Headline\Support\Facades\Headline',
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
