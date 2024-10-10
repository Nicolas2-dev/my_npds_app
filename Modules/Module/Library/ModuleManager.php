<?php

namespace Modules\Module\Library;


use Modules\Module\Contracts\ModuleInterface;


class ModuleManager implements ModuleInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * [filtre_module description]
     *
     * @param   [type]  $strtmp  [$strtmp description]
     *
     * @return  [type]           [return description]
     */
    public function filtre_module($strtmp)
    {
        if (strstr($strtmp, '..') 
        || stristr($strtmp, 'script') 
        || stristr($strtmp, 'cookie') 
        || stristr($strtmp, 'iframe') 
        || stristr($strtmp, 'applet') 
        || stristr($strtmp, 'object')) {
            Access_Error();
        } else {
            return $strtmp != '' ? true : false;
        }
    }

    /**
     * [load_module description]
     *
     * @param   [type]  $ModPath   [$ModPath description]
     * @param   [type]  $ModStart  [$ModStart description]
     *
     * @return  [type]             [return description]
     */
    // public function load_module($ModPath, $ModStart)
    // {
    //     if (filtre_module($ModPath) and filtre_module($ModStart)) {
    //         if (file_exists("modules/$ModPath/$ModStart.php")) {
    //             include("modules/$ModPath/$ModStart.php");
                
    //             die();
    //         } else {
    //             Access_Error();
    //         }
    //     } else {
    //         Access_Error();
    //     }        
    // }

}
