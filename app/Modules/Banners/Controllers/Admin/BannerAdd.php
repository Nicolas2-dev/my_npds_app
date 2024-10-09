<?php

namespace App\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Support\Facades\Language;

/**
 * Undocumented class
 */
class Banners extends AdminController
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

    function BannersAdd($cid, $imptotal, $imageurl, $clickurl, $userlevel)
    {
        sql_query("INSERT INTO banner VALUES (NULL, '$cid', '$imptotal', '1', '0', '$imageurl', '$clickurl', '$userlevel', now())");
        
        Header("Location: admin.php?op=BannersAdmin");
    }

}
