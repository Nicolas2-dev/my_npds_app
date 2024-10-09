<?php

namespace App\Modules\Banners\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class BannerChange extends AdminController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = 'banners';

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = 'BannersAdmin';


    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        $this->f_titre = __d('banners', 'Administration des bannières');

        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * [after description]
     *
     * @param   [type]  $result  [$result description]
     *
     * @return  [type]           [return description]
     */
    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * Undocumented function
     *
     * @param [type] $bid
     * @param [type] $cid
     * @param [type] $imptotal
     * @param [type] $impadded
     * @param [type] $imageurl
     * @param [type] $clickurl
     * @param [type] $userlevel
     * @return void
     */
    public function BannerChange($bid, $cid, $imptotal, $impadded, $imageurl, $clickurl, $userlevel)
    {
        $imp = $imptotal + $impadded;

        sql_query("UPDATE banner SET cid='$cid', imptotal='$imp', imageurl='$imageurl', clickurl='$clickurl', userlevel='$userlevel' WHERE bid='$bid'");
        
        Header("Location: admin.php?op=BannersAdmin");
    }

}
