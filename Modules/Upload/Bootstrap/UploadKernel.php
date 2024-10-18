<?php

namespace Modules\Upload\Bootstrap;

/**
 * Undocumented class
 */
class UploadKernel
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
        'NpdsUpload'     => 'Modules\Upload\Support\Facades\NpdsUpload',
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
