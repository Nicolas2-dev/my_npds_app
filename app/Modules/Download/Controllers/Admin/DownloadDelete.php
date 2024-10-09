<?php

namespace App\Modules\download\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class DownloadDelete extends AdminController
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
    protected $hlpfile = 'downloads';

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
    protected $f_meta_nom = 'DownloadAdmin';


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
        $this->f_titre = __d('download', 'Téléchargements');

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
     * @param integer $ok
     * @return void
     */
    public function DownloadDel($did, $ok = 0)
    {
        global $f_meta_nom;

        if ($ok == 1) {
            sql_query("DELETE FROM downloads WHERE did='$did'");

            Header("Location: admin.php?op=DownloadAdmin");
        } else {
            global $hlpfile, $f_titre;

            echo ' 
            <div class="alert alert-danger">
                <strong>' . __d('download', 'ATTENTION : êtes-vous sûr de vouloir supprimer ce fichier téléchargeable ?') . '</strong>
            </div>
            <a class="btn btn-danger" href="admin.php?op=DownloadDel&amp;did=' . $did . '&amp;ok=1" >' . __d('download', 'Oui') . '</a>&nbsp;<a class="btn btn-secondary" href="admin.php?op=DownloadAdmin" >' . __d('download', 'Non') . '</a>';
            
            adminfoot('', '', '', '');
        }
    }

}
