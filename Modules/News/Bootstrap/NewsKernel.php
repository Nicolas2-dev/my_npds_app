<?php

namespace Modules\News\Bootstrap;

/**
 * Undocumented class
 */
class NewsKernel
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
        'NewsAutomated'     => 'Modules\News\Support\Facades\NewsAutomated',
        'News'              => 'Modules\News\Support\Facades\News',
        'NewsPublication'   => 'Modules\News\Support\Facades\NewsPublication',
        'NewsTopic'         => 'Modules\News\Support\Facades\NewsTopic',
        'NewsUltramode'     => 'Modules\News\Support\Facades\NewsUltramode',
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
