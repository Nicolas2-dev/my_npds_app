<?php

namespace App\Modules\Module\Contracts;


interface ModuleInterface {

    /**
     * [filtre_module description]
     *
     * @param   [type]  $strtmp  [$strtmp description]
     *
     * @return  [type]           [return description]
     */
    public function filtre_module($strtmp);

    /**
     * [load_module description]
     *
     * @param   [type]  $ModPath   [$ModPath description]
     * @param   [type]  $ModStart  [$ModStart description]
     *
     * @return  [type]             [return description]
     */
    //public function load_module($ModPath, $ModStart);

}
