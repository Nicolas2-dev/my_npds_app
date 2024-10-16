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

}
