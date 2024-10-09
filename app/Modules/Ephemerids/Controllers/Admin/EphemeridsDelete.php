<?php

namespace App\Modules\Ephemerids\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class EphemeridsDelete extends AdminController
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
    protected $hlpfile = 'ephem';

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
    protected $f_meta_nom = 'Ephemerids';


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
        $this->f_titre = __d('ephemerids', 'Ephémérides');

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
     * @param [type] $eid
     * @param [type] $did
     * @param [type] $mid
     * @return void
     */
    public function Ephemeridsdel($eid, $did, $mid)
    {
        sql_query("DELETE FROM ephem WHERE eid='$eid'");
    
        Header("Location: admin.php?op=Ephemeridsmaintenance&did=$did&mid=$mid");
    }

}
