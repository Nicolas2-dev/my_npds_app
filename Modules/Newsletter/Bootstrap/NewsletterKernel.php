<?php

namespace Modules\Newsletter\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class NewsletterKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Newsletter
        'Newsletter'     => 'Modules\Newsletter\Support\Facades\Newsletter',
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
