<?php


return array(

    'default' => array(
        'engine' => 'mysql',
        'driver'  => 'pdo_mysql',
        'config' => array(
            'host'        => 'localhost',
            'port'        => 3306,        // Not required, default is 3306
            'dbname'      => 'www.revolutionmini.local',
            'user'        => 'www.revolutionmini.local',
            'password'    => 'www.revolutionmini.local',
            'charset'     => 'utf8mb4',      // Not required, default and recommended is utf8.
            'return_type' => 'assoc',     // Not required, default is 'assoc'.
            'compress'    => false        // Changing to true will hugely improve the persormance on remote servers.
        )
    ),

    /** Extra connections can be added here, some examples: */
    'sqlite' => array(
        'engine' => 'sqlite',
        'driver'  => 'pdo_sqlite',
        'config' => array(
            'path'         => BASEPATH .'storage/persistent/database.sqlite',
            'return_type'  => 'object' // Not required, default is 'assoc'.
        )
    )

);
