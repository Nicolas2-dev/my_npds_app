<?php

namespace Modules\Headline\Bootstrap;

/**
 * Undocumented class
 */
class HeadlineKernel
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
        'Headline'        => 'Modules\Headline\Support\Facades\Headline',
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
