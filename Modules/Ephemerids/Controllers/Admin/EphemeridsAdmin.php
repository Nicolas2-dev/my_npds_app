<?php

namespace Modules\Ephemerids\Controllers\Admin;

use Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class EphemeridsAdmin extends AdminController
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
     * @return void
     */
    public function Ephemerids()
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

}
