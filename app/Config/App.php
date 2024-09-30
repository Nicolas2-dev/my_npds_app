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

        // Core Npds Class
        'Config'        => 'Npds\Config\Config',
        'View'          => 'Npds\View\View',
        'DB'            => 'Npds\Support\Facades\DB',

        // Shared Class

        // Editeur
        'Editeur'       => 'Shared\Editeur\Support\Facades\Editeur',

    ),

);