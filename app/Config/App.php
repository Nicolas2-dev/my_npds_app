<?php


return array(

    /**
     * Debug Mode
     */
    'debug' => true, 

    /**
     * The Website URL.
     */
    'url' => 'http://www.revolutionmini.local/',

    /**
     * Website Name.
     */
    'name' => 'Npds Mini Framework',

    /**
     * The default Timezone for your website.
     * http://www.php.net/manual/en/timezones.php
     */
    'timezone' => 'Europe/Paris',

    /**
     * The registered Class Aliases.
     */
    'aliases' => array(

        // core

        // Npds Class
        'Config'        => 'Npds\Config\Config',
        'View'          => 'Npds\View\View',
        'DB'            => 'Npds\Support\Facades\DB',

        // module

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

        // Modules Theme
        'Theme'         => 'App\Modules\Theme\Support\Facades\Theme',

        // Modules Edito
        'Edito'         => 'App\Modules\Edito\Support\Facades\Edito',

        // Modules Block
        'Block'         => 'App\Modules\Blocks\Support\Facades\Block',

        // Modules Chat
        'chat'          => 'App\Modules\Chat\Support\Facades\Chat',

        // Modules Stats
        'Stat'          => 'App\Modules\Stats\Support\Facades\Stat',

        // Modules Download
        'Download'      => 'App\Modules\Download\Support\Facades\Download',

        // Modules Forum
        'Forum'         => 'App\Modules\Forum\Support\Facades\Forum',

        // Modules News
        'News'          => 'App\Modules\News\Support\Facades\News',

        // Modules Module
        'Module'        => 'App\Modules\Module\Support\Facades\Module',

        // Modules Groupe
        'Groupe'        => 'App\Modules\Groupes\Support\Facades\Groupe',

        // Modules Messenger
        'Messenger'     => 'App\Modules\Messenger\Support\Facades\Messenger',

        // Modules Users
        'Online'        => 'App\Modules\Users\Support\Facades\Online',
        'User'          => 'App\Modules\Users\Support\Facades\User',
        'Avatar'        => 'App\Modules\Users\Support\Facades\Avatar',
        'UserMenu'      => 'App\Modules\Users\Support\Facades\UserMenu',

        // Modules Author
        'Author'        => 'App\Modules\Authors\Support\Facades\Author',

        // Headline
        'Headline'        => 'App\Modules\Headline\Support\Facades\Headline',

        // shared

        // Editeur
        'Editeur'       => 'Shared\Editeur\Support\Facades\Editeur',

    ),

);