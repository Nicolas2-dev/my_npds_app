<?php

namespace Modules\Module\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;


class Modules extends AdminController
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
    protected $hlpfile = 'modules';

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
    protected $f_meta_nom = 'modules';


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
        $this->f_titre = __d('module', 'Gestion, Installation Modules');

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
    public function index()
    {
        $handle = opendir('modules');
        
        $modlist = '';
        while (false !== ($file = readdir($handle))) {
            if (!@file_exists("modules/$file/kernel")) {
                if (is_dir("modules/$file") and ($file != '.') and ($file != '..')) {
                    $modlist .= "$file ";
                }
            }
        }
        
        closedir($handle);
        $modlist = explode(' ', rtrim($modlist));
        
        $whatondb = sql_query("SELECT mnom FROM modules");
        
        while ($row = sql_fetch_row($whatondb)) {
            if (!in_array($row[0], $modlist)) {
                sql_query("DELETE FROM modules WHERE mnom='" . $row[0] . "'");
            }
        }
        
        foreach ($modlist as $value) {
            $queryexiste = sql_query("SELECT mnom FROM modules WHERE mnom='" . $value . "'");
            $moexiste = sql_num_rows($queryexiste);
        
            if ($moexiste !== 1) {
                sql_query("INSERT INTO modules VALUES (NULL, '" . $value . "', '0')");
            }
        }

        echo '
            <hr />
            <h3>' . __d('module', 'Les modules') . '</h3>
            <table id="tad_modu" data-toggle="table" data-striped="false" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
                <thead>
                    <tr>
                        <th data-align="center" class="n-t-col-xs-1"><img class="adm_img" src="assets/images/admin/module.png" alt="icon_module" /></th>
                        <th data-sortable="true">' . __d('module', 'Nom') . '</th>
                        <th data-align="center" class="n-t-col-xs-2" >' . __d('module', 'Fonctions') . '</th>
                    </tr>
                </thead>
                <tbody>';
        
        $result = sql_query("SELECT * FROM modules ORDER BY mid");
        
        while ($row = sql_fetch_assoc($result)) {
        
            $icomod = '';
            $clatd = '';
        
            $icomod = file_exists("modules/" . $row["mnom"] . "/" . $row["mnom"] . ".png") ?
                '<img class="adm_img" src="modules/' . $row["mnom"] . '/' . $row["mnom"] . '.png" alt="icon_' . $row["mnom"] . '" title="" />' :
                '<img class="adm_img" src="assets/images/admin/module.png" alt="icon_module" title="" />';
            
            if ($row["minstall"] == 0) {
                $status_chngac = file_exists("modules/" . $row["mnom"] . "/install.conf.php") ?
                    '<a class="text-success" href="admin.php?op=Module-Install&amp;ModInstall=' . $row["mnom"] . '&amp;subop=install" ><i class="fa fa-compress fa-lg"></i><i class="fa fa-puzzle-piece fa-2x fa-rotate-90" title="' . __d('module', 'Installer le module') . '" data-bs-toggle="tooltip"></i></a>' :
                    '<a class="text-success" href="admin.php?op=Module-Install&amp;ModInstall=' . $row["mnom"] . '&amp;subop=install"><i class="fa fa-check fa-lg"></i><i class="fa fa fa-puzzle-piece fa-2x fa-rotate-90" title="' . __d('module', 'Pas d\'installeur disponible') . ' ' . __d('module', 'Marquer le module comme installé') . '" data-bs-toggle="tooltip"></i></a>';
                $clatd = 'table-danger';
            } else {
                $status_chngac =  file_exists("modules/" . $row["mnom"] . "/install.conf.php") ?
                    '<a class="text-danger" href="admin.php?op=Module-Install&amp;ModDesinstall=' . $row["mnom"] . '" ><i class="fa fa-expand fa-lg"></i><i class="fa fa fa-puzzle-piece fa-2x fa-rotate-90" title="' . __d('module', 'Désinstaller le module') . '" data-bs-toggle="tooltip"></i></a>' :
                    '<a class="text-danger" href="admin.php?op=Module-Install&amp;ModDesinstall=' . $row["mnom"] . '" ><i class="fa fa fa-ban fa-lg"></i><i class="fa fa fa-puzzle-piece fa-2x fa-rotate-90" title="' . __d('module', 'Marquer le module comme désinstallé') . '" data-bs-toggle="tooltip"</i></a>';
                $clatd = 'table-success';
            }
        
            echo '
                    <tr>
                        <td class="' . $clatd . '">' . $icomod . '</td>
                        <td class="' . $clatd . '">' . $row["mnom"] . '</td>
                        <td class="' . $clatd . '">' . $status_chngac . '</td>
                    </tr>';
        }
        
        echo '
                </tbody>
            </table>';
        
        Css::adminfoot('', '', '', '');        
    }

}
