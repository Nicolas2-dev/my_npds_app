<?php

namespace Modules\Minisites\Contracts;


interface MinisiteInterface {

    /**
     * Undocumented function
     *
     * @param [type] $typeL
     * @return void
     */
    public function minisite_win_upload($typeL);

    /**
     * Undocumented function
     *
     * @param [type] $blog_dir
     * @param [type] $op
     * @param [type] $startpage
     * @param [type] $action
     * @param [type] $adminblog
     * @return void
     */
    public function readnews($blog_dir, $op, $startpage, $action, $adminblog);

}
