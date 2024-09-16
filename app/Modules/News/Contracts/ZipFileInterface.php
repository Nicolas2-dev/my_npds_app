<?php

namespace App\Modules\News\Contracts;


interface ZipFileInterface {

    /**
     * [__construct description]
     *
     * @param   [type] $cwd    [$cwd description]
     * @param   [type] $flags  [$flags description]
     * @param   array          [ description]
     *
     * @return  [type]         [return description]
     */
    public function __construct($cwd = "./", $flags = array());

    /**
     * [addfile description]
     *
     * @param   [type] $data      [$data description]
     * @param   [type] $filename  [$filename description]
     * @param   [type] $flags     [$flags description]
     * @param   array             [ description]
     *
     * @return  [type]            [return description]
     */
    public function addfile($data, $filename, $flags = array());
    
    /**
     * [addfiles description]
     *
     * @param   [type]  $filelist  [$filelist description]
     *
     * @return  [type]             [return description]
     */
    public function addfiles($filelist);
    
    /**
     * [arc_getdata description]
     *
     * @return  [type]  [return description]
     */
    public function arc_getdata();
    
    /**
     * [filedownload description]
     *
     * @param   [type]  $filename  [$filename description]
     *
     * @return  [type]             [return description]
     */
    public function filedownload($filename);    
    
}
