<?php

namespace Modules\Npds\Bootstrap;

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
     * Undocumented function
     *
     * @return void
     */
    public static function aliases_loader()
    {
        AliasLoader::getInstance(static::$aliases)->register();
    }

}
