<?php

namespace App\Modules\News\Contracts;


interface ArchiveInterface {

    /**
     * [archive description]
     *
     * @return  [type]  [return description]
     */
    public function archive($flags = array());

    /**
     * [adddirectories description]
     *
     * @param   [type]  $dirlist  [$dirlist description]
     *
     * @return  [type]            [return description]
     */
    public function adddirectories($dirlist);
    
    /**
     * [parsedirectories description]
     *
     * @param   [type]  $dirname  [$dirname description]
     *
     * @return  [type]            [return description]
     */
    public function parsedirectories($dirname);
    
    /**
     * [filewrite description]
     *
     * @param   [type]  $filename  [$filename description]
     * @param   [type]  $perms     [$perms description]
     *
     * @return  [type]             [return description]
     */
    public function filewrite($filename, $perms = null);
    
    /**
     * [extractfile description]
     *
     * @param   [type]  $filename  [$filename description]
     *
     * @return  [type]             [return description]
     */
    public function extractfile($filename);
    
    /**
     * [addfiles description]
     *
     * @param   [type]  $filelist  [$filelist description]
     *
     * @return  [type]             [return description]
     */
    public function addfiles($filelist);

    /**
     * [extract description]
     *
     * @param   [type]  $filename  [$filename description]
     *
     * @return  [type]             [return description]
     */
    public function extract($filename);
    
    /**
     * [arc_getdata description]
     *
     * @return  [type]  [return description]
     */
    public function arc_getdata();  

    /**
     * [error description]
     *
     * @param   [type]  $error  [$error description]
     *
     * @return  [type]          [return description]
     */
    public function error($error);    
    
}
