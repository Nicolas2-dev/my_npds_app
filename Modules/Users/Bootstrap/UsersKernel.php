<?php

namespace Modules\Users\Bootstrap;

/**
 * Undocumented class
 */
class UsersKernel
{

    /**
     * 
     */
    protected static $module_path;

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $aliases = [
        'Online'        => 'Modules\Users\Support\Facades\Online',
        'User'          => 'Modules\Users\Support\Facades\User',
        'Avatar'        => 'Modules\Users\Support\Facades\Avatar',
        'UserMenu'      => 'Modules\Users\Support\Facades\UserMenu',
        'UserPopover'   => 'Modules\Users\Support\Facades\UserPopover',
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $boot_method = [
        //
    ];

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * Undocumented function
     */
    public function __construct($directory)
    {
        static::$module_path = $directory;
    }

    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance($directory)
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static($directory);
    }
    
}
