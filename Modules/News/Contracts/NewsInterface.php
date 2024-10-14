<?php

namespace Modules\News\Contracts;


interface NewsInterface {

    /**
     * [ctrl_aff description]
     *
     * @param   [type]  $ihome  [$ihome description]
     * @param   [type]  $catid  [$catid description]
     *
     * @return  [type]          [return description]
     */
    public function ctrl_aff($ihome, $catid = 0);
    
    /**
     * [aff_news description]
     *
     * @param   [type]  $op       [$op description]
     * @param   [type]  $catid    [$catid description]
     * @param   [type]  $marqeur  [$marqeur description]
     *
     * @return  [type]            [return description]
     */
    public function aff_news($op, $catid, $marqeur);

    /**
     * [news_aff description]
     *
     * @param   [type]  $type_req  [$type_req description]
     * @param   [type]  $sel       [$sel description]
     * @param   [type]  $storynum  [$storynum description]
     * @param   [type]  $oldnum    [$oldnum description]
     *
     * @return  [type]             [return description]
     */    
    public function news_aff($type_req, $sel, $storynum, $oldnum);    

    /**
     * [prepa_aff_news description]
     *
     * @param   [type]  $op       [$op description]
     * @param   [type]  $catid    [$catid description]
     * @param   [type]  $marqeur  [$marqeur description]
     *
     * @return  [type]            [return description]
     */
    public function prepa_aff_news($op, $catid, $marqeur);  
    
}
