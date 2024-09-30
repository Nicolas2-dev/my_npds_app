<?php

namespace App\Modules\Chat\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class ChatKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Chat
        'chat'          => 'App\Modules\Chat\Support\Facades\Chat',
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
