<?php

namespace App\Modules\Blocks\Contracts;

/**
 * Undocumented interface
 */
interface BlockInterface {

    /**
     * [block_fonction description]
     *
     * @param   [type]  $title     [$title description]
     * @param   [type]  $contentX  [$contentX description]
     *
     * @return  [type]             [return description]
     */
    public function block_fonction($title, $contentX);
    
    /**
     * [fab_block description]
     *
     * @param   [type]  $title    [$title description]
     * @param   [type]  $member   [$member description]
     * @param   [type]  $content  [$content description]
     * @param   [type]  $Xcache   [$Xcache description]
     *
     * @return  [type]            [return description]
     */
    public function fab_block($title, $member, $content, $Xcache);

    /**
     * [leftblocks description]
     *
     * @param   [type]  $moreclass  [$moreclass description]
     *
     * @return  [type]              [return description]
     */
    public function leftblocks($moreclass);

    /**
     * [rightblocks description]
     *
     * @param   [type]  $moreclass  [$moreclass description]
     *
     * @return  [type]              [return description]
     */
    public function rightblocks($moreclass);

    /**
     * [oneblock description]
     *
     * @param   [type]  $Xid     [$Xid description]
     * @param   [type]  $Xblock  [$Xblock description]
     *
     * @return  [type]           [return description]
     */
    public function oneblock($Xid, $Xblock);

    /**
     * [Pre_fab_block description]
     *
     * @param   [type]  $Xid        [$Xid description]
     * @param   [type]  $Xblock     [$Xblock description]
     * @param   [type]  $moreclass  [$moreclass description]
     *
     * @return  [type]              [return description]
     */
    public function Pre_fab_block($Xid, $Xblock, $moreclass);
    
    /**
     * [niv_block description]
     *
     * @param   [type]  $Xcontent  [$Xcontent description]
     *
     * @return  [type]             [return description]
     */
    public function niv_block($Xcontent);
    
    /**
     * [autorisation_block description]
     *
     * @param   [type]  $Xcontent  [$Xcontent description]
     *
     * @return  [type]             [return description]
     */
    public function autorisation_block($Xcontent);    

}
