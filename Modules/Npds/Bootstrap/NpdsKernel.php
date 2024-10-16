<?php

namespace Modules\Npds\Bootstrap;

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
    public static function load_helper_metalang()
    {
        require static::$module_path . 'Support'. DS .'metalang.php';
    }

}
