<?php

namespace App\Modules\Users\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class ModuleUsersKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Users
        'Online'        => 'App\Modules\Users\Support\Facades\Online',
        'User'          => 'App\Modules\Users\Support\Facades\User',
        'Avatar'        => 'App\Modules\Users\Support\Facades\Avatar',
        'UserMenu'      => 'App\Modules\Users\Support\Facades\UserMenu',
        'UserPopover'   => 'App\Modules\Users\Support\Facades\UserPopover',
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
