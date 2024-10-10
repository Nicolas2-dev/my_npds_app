<?php

namespace Modules\Download\Bootstrap;

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
        // Module Download
        'Download'      => 'Modules\Download\Support\Facades\Download',
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
