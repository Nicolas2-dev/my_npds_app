<?php

namespace App\Modules\News\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class NewsKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules News
        'News'          => 'App\Modules\News\Support\Facades\News',
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
