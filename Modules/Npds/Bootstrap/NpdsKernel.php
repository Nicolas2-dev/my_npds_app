<?php

namespace Modules\Npds\Bootstrap;

use Npds\Http\Request;

/**
 * Undocumented class
 */
class NpdsKernel
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
        'Auth'          => 'Modules\Npds\Support\Facades\Auth',    
        'Code'          => 'Modules\Npds\Support\Facades\Code',    
        'Cookie'        => 'Modules\Npds\Support\Facades\Cookie',    
        'Crypt'         => 'Modules\Npds\Support\Facades\Crypt',    
        'Css'           => 'Modules\Npds\Support\Facades\Css',    
        'Date'          => 'Modules\Npds\Support\Facades\Date',    
        'Emoticone'     => 'Modules\Npds\Support\Facades\Emoticone',    
        'Hack'          => 'Modules\Npds\Support\Facades\Hack',    
        'Js'            => 'Modules\Npds\Support\Facades\Js',    
        'Language'      => 'Modules\Npds\Support\Facades\Language',    
        'Log'           => 'Modules\Npds\Support\Facades\Log',    
        'Mailer'        => 'Modules\Npds\Support\Facades\Mailer',    
        'Metalang'      => 'Modules\Npds\Support\Facades\Metalang',    
        'Paginator'     => 'Modules\Npds\Support\Facades\Paginator',    
        'Password'      => 'Modules\Npds\Support\Facades\Password',    
        'Pixel'         => 'Modules\Npds\Support\Facades\Pixel',    
        'Session'       => 'Modules\Npds\Support\Facades\Session',    
        'Sform'         => 'Modules\Npds\Support\Facades\Sform',    
        'Spam'          => 'Modules\Npds\Support\Facades\Spam',    
        'Sanitize'      => 'Modules\Npds\Support\Sanitize',    
        'Subscribe'     => 'Modules\Npds\Support\Facades\Subscribe',    
        'Url'           => 'Modules\Npds\Support\Facades\Url',
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $boot_method = [
        'helper_metalang',
        // 'test'
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
     * Load helpers metalang
     */
    public static function register_helper_metalang()
    {
        require static::$module_path . 'Support'. DS .'metalang.php';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function register_test(Request $request, NpdsKernel $kernel)
    {
        // dump(
        //     Config::get('forum.config'), 
        //     Config::get('forum.config.allow_upload_forum')
        // );

        echo $kernel->good();

        echo '<br>Npds good look!!';
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function good()
    {
        echo ('<br>Npds vraimen good ce truc !!!');
    }

}
