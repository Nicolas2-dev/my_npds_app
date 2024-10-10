<?php


return array(

    'storage'       => 'files', // Blank for auto
    'default_chmod' => 0777,    // 0777, 0666, 0644

    /*
     * Fall back when Driver is not supported.
     */
    'fallback'    => "files",

    'securityKey' => 'auto',
    'htaccess'    => true,
    'path'        => BASEPATH .'storage' .DS .'cache',

    'memcache' => array(
        array("127.0.0.1",11211,1),
    ),

    'redis' => array(
        'host'     => '127.0.0.1',
        'port'     => '',
        'password' => '',
        'database' => '',
        'timeout'  => ''
    ),

    'ssdb' => array(
        'host'     => '127.0.0.1',
        'port'     => 8888,
        'password' => '',
        'timeout'  => ''
    ),
    'extensions' => array(),
);
