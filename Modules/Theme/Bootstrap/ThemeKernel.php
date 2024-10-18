<?php

namespace Modules\Theme\Bootstrap;

use Npds\Http\Request;
use Npds\Config\Config;
use Modules\Npds\Bootstrap\NpdsKernel;

/**
 * Undocumented class
 */
class ThemeKernel
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
        'Theme'         => 'Modules\Theme\Support\Facades\Theme',
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $boot_method = [
        // 'test_theme'
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
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function register_test_theme(Request $request, ThemeKernel $kernel)
    {
        // dump(
        //     Config::get('forum.config'), 
        //     Config::get('forum.config.allow_upload_forum')
        // );

        echo $kernel->good_theme();

        echo '<br>module Tehme good look!!';
    }
    
    /**
     * Undocumented function
     *
     * @return string
     */
    private function good_theme()
    {
        echo ('<br>module Tehme vraimen good ce truc !!!');
    }


}
