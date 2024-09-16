<?php

namespace App\Modules\News\Contracts;


interface GzFileInterface {

    /**
     * [addfile description]
     *
     * @param   [type]  $data      [$data description]
     * @param   [type]  $filename  [$filename description]
     * @param   [type]  $comment   [$comment description]
     *
     * @return  [type]             [return description]
     */
    public function addfile($data, $filename = null, $comment = null);

    /**
     * [extract description]
     *
     * @param   [type]  $data  [$data description]
     *
     * @return  [type]         [return description]
     */
    public function extract($data);
    
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
