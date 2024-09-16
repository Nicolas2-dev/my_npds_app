<?php

namespace App\Modules\Ephemerids\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;


class AdminEphemerids extends AdminController
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
    protected $hlpfile = "";

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
    protected $f_meta_nom = '';


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
        $this->f_titre = __d('', '');

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
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    // public function __construct()
    // {
        // $f_meta_nom = 'Ephemerids';
        // $f_titre = __d('ephemerids', 'Ephémérides');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        

        // $hlpfile = "language/manuels/Config::get('npds.language')/ephem.html";

        // switch ($op) {
        //     case 'Ephemeridsedit':
        //         Ephemeridsedit($eid, $did, $mid);
        //         break;
        
        //     case 'Ephemeridschange':
        //         Ephemeridschange($eid, $did, $mid, $yid, $content);
        //         break;
        
        //     case 'Ephemeridsdel':
        //         Ephemeridsdel($eid, $did, $mid);
        //         break;
        
        //     case 'Ephemeridsmaintenance':
        //         Ephemeridsmaintenance($did, $mid);
        //         break;
        
        //     case 'Ephemeridsadd':
        //         Ephemeridsadd($did, $mid, $yid, $content);
        //         break;
        
        //     case 'Ephemerids':
        //         Ephemerids();
        //         break;
        // }
    // }

    function Ephemerids()
    {
        $nday = '1';
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('ephemerids', 'Ajouter un éphéméride') . '</h3>
        <form action="admin.php" method="post">
            <div class="row g-3 mb-3">
                <div class="col-sm-4">
                    <div class="form-floating">
                    <select class="form-select" id="did" name="did">';
    
        while ($nday <= 31) {
            echo '<option name="did">' . $nday . '</option>';
            $nday++;
        }
    
        echo '
                    </select>
                    <label for="did">' . __d('ephemerids', 'Jour') . '</label>
                    </div>
                </div>';
    
        $nmonth = "1";
    
        echo '
                <div class="col-sm-4">
                    <div class="form-floating">
                    <select class="form-select" id="mid" name="mid">';
    
        while ($nmonth <= 12) {
            echo '<option name="mid">' . $nmonth . '</option>';
            $nmonth++;
        }
    
        echo '
                    </select>
                    <label for="mid">' . __d('ephemerids', 'Mois') . '</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating">
                    <input class="form-control" type="number" id="yid" name="yid" maxlength="4" size="5" />
                    <label for="yid">' . __d('ephemerids', 'Année') . '</label>
                    </div>
                </div>
            </div>
            <div class="form-floating mb-3">
                <textarea name="content" class="form-control" style="height:120px;"></textarea>
                <label for="content">' . __d('ephemerids', 'Description de l\'éphéméride') . '</label>
            </div>
            <button class="btn btn-primary" type="submit">' . __d('ephemerids', 'Envoyer') . '</button>
            <input type="hidden" name="op" value="Ephemeridsadd" />
        </form>
        <hr />
        <h3 class="mb-3">' . __d('ephemerids', 'Maintenance des Ephémérides (Editer/Effacer)') . '</h3>
        <form action="admin.php" method="post">';
    
        $nday = "1";
    
        echo '
            <div class="row g-3">
                <div class="col-4">
                    <div class="form-floating mb-3">
                    <select class="form-select" id="did" name="did">';
    
        while ($nday <= 31) {
            echo '<option name="did">' . $nday . '</option>';
            $nday++;
        }
    
        echo '
                    </select>
                    <label for="did">' . __d('ephemerids', 'Jour') . '</label>
                    </div>
                </div>';
    
        $nmonth = "1";
    
        echo '
                <div class="col-4">
                    <div class="form-floating mb-3">
                    <select class="form-select" id="mid" name="mid">';
    
        while ($nmonth <= 12) {
            echo '<option name="mid">' . $nmonth . '</option>';
            $nmonth++;
        }
    
        echo '
                    </select>
                    <label for="mid">' . __d('ephemerids', 'Mois') . '</label>
                    </div>
                </div>
            </div>
            <input type="hidden" name="op" value="Ephemeridsmaintenance" />
            <button class="btn btn-primary" type="submit">' . __d('ephemerids', 'Editer') . '</button>
        </form>';
    
        adminfoot('', '', '', '');
    }
    
    function Ephemeridsadd($did, $mid, $yid, $content)
    {
        $content = stripslashes(FixQuotes($content) . "");
    
        sql_query("INSERT into ephem VALUES (NULL, '$did', '$mid', '$yid', '$content')");
    
        Header("Location: admin.php?op=Ephemerids");
    }
    
    function Ephemeridsmaintenance($did, $mid)
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
    
    function Ephemeridsdel($eid, $did, $mid)
    {
        sql_query("DELETE FROM ephem WHERE eid='$eid'");
    
        Header("Location: admin.php?op=Ephemeridsmaintenance&did=$did&mid=$mid");
    }
    
    function Ephemeridsedit($eid, $did, $mid)
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
    
    function Ephemeridschange($eid, $did, $mid, $yid, $content)
    {
        $content = stripslashes(FixQuotes($content) . "");
    
        sql_query("UPDATE ephem SET yid='$yid', content='$content' WHERE eid='$eid'");
    
        Header("Location: admin.php?op=Ephemeridsmaintenance&did=$did&mid=$mid");
    }

}
