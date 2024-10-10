<?php

namespace Modules\Users\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class UsersKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Users
        'Online'        => 'Modules\Users\Support\Facades\Online',
        'User'          => 'Modules\Users\Support\Facades\User',
        'Avatar'        => 'Modules\Users\Support\Facades\Avatar',
        'UserMenu'      => 'Modules\Users\Support\Facades\UserMenu',
        'UserPopover'   => 'Modules\Users\Support\Facades\UserPopover',
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
