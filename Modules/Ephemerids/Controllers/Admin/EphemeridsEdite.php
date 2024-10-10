<?php

namespace Modules\Ephemerids\Controllers\Admin;

use Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class EphemeridsEdite extends AdminController
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
    public function Ephemeridsedit($eid, $did, $mid)
    {
        $result = sql_query("SELECT yid, content FROM ephem WHERE eid='$eid'");
        list($yid, $content) = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3>' . __d('ephemerids', 'Editer éphéméride') . '</h3>
        <form action="admin.php" method="post">
            <div class="form-floating mb-3">
                <input class="form-control" type="number" name="yid" value="' . $yid . '" max="2500" />
                <label for="yid">' . __d('ephemerids', 'Année') . '</label>
            </div>
            <div class="form-floating mb-3">
                <textarea name="content" id="content" class="form-control" style="height:120px;">' . $content . '</textarea>
                <label for="content">' . __d('ephemerids', 'Description de l\'éphéméride') . '</label>
            </div>
            <input type="hidden" name="did" value="' . $did . '" />
            <input type="hidden" name="mid" value="' . $mid . '" />
            <input type="hidden" name="eid" value="' . $eid . '" />
            <input type="hidden" name="op" value="Ephemeridschange" />
            <button class="btn btn-primary" type="submit">' . __d('ephemerids', 'Envoyer') . '</button>
        </form>';
    
        adminfoot('', '', '', '');
    }

}
