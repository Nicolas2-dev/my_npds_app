<?php

namespace Modules\Stats\Bootstrap;

/**
 * Undocumented class
 */
class StatsKernel
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
        'Stat'          => 'Modules\Stats\Support\Facades\Stat',
        'Map'           => 'Modules\Stats\Support\Facades\Map',
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
