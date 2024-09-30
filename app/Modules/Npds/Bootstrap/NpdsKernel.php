<?php

namespace App\Modules\Npds\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class NpdsKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules Npds
        'Auth'          => 'App\Modules\Npds\Support\Facades\Auth',    
        'Code'          => 'App\Modules\Npds\Support\Facades\Code',    
        'Cookie'        => 'App\Modules\Npds\Support\Facades\Cookie',    
        'Crypt'         => 'App\Modules\Npds\Support\Facades\Crypt',    
        'Css'           => 'App\Modules\Npds\Support\Facades\Css',    
        'Date'          => 'App\Modules\Npds\Support\Facades\Date',    
        'Emoticone'     => 'App\Modules\Npds\Support\Facades\Emoticone',    
        'Hack'          => 'App\Modules\Npds\Support\Facades\Hack',    
        'Js'            => 'App\Modules\Npds\Support\Facades\Js',    
        'Language'      => 'App\Modules\Npds\Support\Facades\Language',    
        'Log'           => 'App\Modules\Npds\Support\Facades\Log',    
        'Mailer'        => 'App\Modules\Npds\Support\Facades\Mailer',    
        'Metalang'      => 'App\Modules\Npds\Support\Facades\Metalang',    
        'Paginator'     => 'App\Modules\Npds\Support\Facades\Paginator',    
        'Password'      => 'App\Modules\Npds\Support\Facades\Password',    
        'Pixel'         => 'App\Modules\Npds\Support\Facades\Pixel',    
        'Session'       => 'App\Modules\Npds\Support\Facades\Session',    
        'Sform'         => 'App\Modules\Npds\Support\Facades\Sform',    
        'Spam'          => 'App\Modules\Npds\Support\Facades\Spam',    
        'Sanitize'      => 'App\Modules\Npds\Support\Sanitize',    
        'Subscribe'     => 'App\Modules\Npds\Support\Facades\Subscribe',    
        'Url'           => 'App\Modules\Npds\Support\Facades\Url',
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
