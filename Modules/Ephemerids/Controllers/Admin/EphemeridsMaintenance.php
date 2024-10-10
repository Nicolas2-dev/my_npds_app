<?php

namespace Modules\Ephemerids\Controllers\Admin;

use Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class EphemeridsMaintenance extends AdminController
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
     * @param [type] $did
     * @param [type] $mid
     * @return void
     */
    public function Ephemeridsmaintenance($did, $mid)
    {
        $resultX = sql_query("SELECT eid, did, mid, yid, content FROM ephem WHERE did='$did' AND mid='$mid' ORDER BY yid ASC");
    
        if (!sql_num_rows($resultX)) 
            header("location: admin.php?op=Ephemerids");
    
        echo '
        <hr />
        <h3>' . __d('ephemerids', 'Maintenance des Ephémérides') . '</h3>
        <table data-toggle="table" data-striped="true" data-mobile-responsive="true" data-search="true" data-show-toggle="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right" >' . __d('ephemerids', 'Année') . '</th>
                    <th data-halign="center" >' . __d('ephemerids', 'Description') . '</th>
                    <th class="n-t-col-xs-2" data-halign="center" data-align="center" >' . __d('ephemerids', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        while (list($eid, $did, $mid, $yid, $content) = sql_fetch_row($resultX)) {
            echo '
                <tr>
                    <td>' . $yid . '</td>
                    <td>' . aff_langue($content) . '</td>
                    <td><a href="admin.php?op=Ephemeridsedit&amp;eid=' . $eid . '&amp;did=' . $did . '&amp;mid=' . $mid . '" title="' . __d('ephemerids', 'Editer') . '" data-bs-toggle="tooltip" ><i class="fa fa-edit fa-lg me-2"></i></a>&nbsp;<a href="admin.php?op=Ephemeridsdel&amp;eid=' . $eid . '&amp;did=' . $did . '&amp;mid=' . $mid . '" title="' . __d('ephemerids', 'Effacer') . '" data-bs-toggle="tooltip"><i class="fas fa-trash fa-lg text-danger"></i></a>
                </tr>';
        }
    
        echo '
                </tbody>
            </table>';
    
        adminfoot('', '', '', '');
    }

}
