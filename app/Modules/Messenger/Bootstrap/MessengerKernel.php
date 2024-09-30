<?php

namespace App\Modules\Messenger\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class MessengerKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Messenger
        'Messenger'     => 'App\Modules\Messenger\Support\Facades\Messenger',
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
