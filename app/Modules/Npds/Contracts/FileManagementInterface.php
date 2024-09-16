<?php

namespace App\Modules\Npds\Contracts;


interface FileManagementInterface {

    /**
     * [file_size_format description]
     *
     * @param   [type]  $fileName   [$fileName description]
     * @param   [type]  $precision  [$precision description]
     *
     * @return  [type]              [return description]
     */
    public function file_size_format($fileName, $precision);

    /**
     * [file_size_auto description]
     *
     * @param   [type]  $fileName   [$fileName description]
     * @param   [type]  $precision  [$precision description]
     *
     * @return  [type]              [return description]
     */
    public function file_size_auto($fileName, $precision);
    
    /**
     * [file_size_option description]
     *
     * @param   [type]  $fileName  [$fileName description]
     * @param   [type]  $unitType  [$unitType description]
     *
     * @return  [type]             [return description]
     */
    public function file_size_option($fileName, $unitType);    

}
