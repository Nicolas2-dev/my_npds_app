<?php

namespace Modules\News\Bootstrap;

use Npds\Foundation\AliasLoader;

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
    protected static $aliases = [
        // Modules News
        'NewsAutomated'     => 'Modules\News\Support\Facades\NewsAutomated',
        'News'              => 'Modules\News\Support\Facades\News',
        'NewsPublication'   => 'Modules\News\Support\Facades\NewsPublication',
        'NewsTopic'         => 'Modules\News\Support\Facades\NewsTopic',
        'NewsUltramode'     => 'Modules\News\Support\Facades\NewsUltramode',
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
        static::$module_path = dirname(dirname($directory)) . DS;
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
    public static function aliases_loader()
    {
        AliasLoader::getInstance(static::$aliases)->register();

        return static::$instance;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function load_constant()
    {
        include static::$module_path .'constants.php';

        return static::$instance;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function load_helper()
    {
        include static::$module_path .'Support'. DS .'helpers.php';

        return static::$instance;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function load_boxe()
    {
        include static::$module_path .'Boxe'. DS .'Boxe.php';

        return static::$instance;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function load_route_web()
    {
        include static::$module_path .'Routes'. DS .'web'. DS .'routes.php';

        return static::$instance;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function load_route_admin()
    {
        include static::$module_path .'Routes'. DS .'admin'. DS .'routes.php';

        return static::$instance;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function load_route_api()
    {
        // include static::$module_path .'Routes'. DS .'api'. DS .'routes.php';

        // return static::$instance;
    }

}
