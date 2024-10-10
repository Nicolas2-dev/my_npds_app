<?php

namespace Modules\Forum\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class ForumKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Module Forum
        'Forum'         => 'Modules\Forum\Support\Facades\Forum',
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
