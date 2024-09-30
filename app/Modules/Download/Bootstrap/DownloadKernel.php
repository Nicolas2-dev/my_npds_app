<?php

namespace App\Modules\Download\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class DownloadKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Download
        'Download'      => 'App\Modules\Download\Support\Facades\Download',
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
