<?php

namespace App\Modules\Memberlists\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class MemberlistKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Module Memberlists
        'Memberlist'         => 'App\Modules\Memberlists\Support\Facades\Memberlist',
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
