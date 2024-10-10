<?php

use Modules\Module\Library\ModuleManager;


if (! function_exists('filtre_module'))
{
    /**
     * [filtre_module description]
     *
     * @param   [type]  $strtmp  [$strtmp description]
     *
     * @return  [type]           [return description]
     */
    function filtre_module($strtmp)
    {
        return ModuleManager::getInstance()->filtre_module($strtmp);
    }
}

// if (! function_exists('load_module'))
// {
//     /**
//      * [load_module description]
//      *
//      * @param   [type]  $ModPath   [$ModPath description]
//      * @param   [type]  $ModStart  [$ModStart description]
//      *
//      * @return  [type]             [return description]
//      */
//     function load_module($ModPath, $ModStart)
//     {
//         extract($GLOBALS);

//         return ModuleManager::getInstance()->load_module($ModPath, $ModStart);
//     }
// }
