<?php

namespace Modules\Module\Controllers\Admin\Desinstall;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Module\Support\Traits\ModuleInstallCopyrightTrait;


class ModuleDesinstall extends AdminController
{

    use ModuleInstallCopyrightTrait;
    
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
    protected $hlpfile = '';

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
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __uuuconstruct()
    {
        if ($ModDesinstall != '') {
            
            if (file_exists("modules/" . $ModDesinstall . "/install.conf.php")) {
                include("modules/" . $ModDesinstall . "/install.conf.php");

                foreach ($sql as $v) {
                    if (preg_match('#CREATE TABLE( IF NOT EXISTS |\s)(\w+)#', $v, $rt)) {
                        $tabcreated[] = $rt[2];
                    }

                    if (preg_match('#^INSERT INTO (\w+)#', $v, $ri)) {
                        $tabinsert[] = $ri[1];

                        if ($ri[1] == 'metalang') {
                            preg_match("#\sVALUES\s+\('(.[^']+)',\s+#", $v, $met);
                            $modulemetamot[] = $met[1];
                            // recupere la première valeur de VALUES pour cibler la def d'un metamot, pour les tables autres que metalang unimplemented ...
                        }
                    }
                }

                foreach ($tabinsert as $v) {
                    if (!in_array($v, $tabcreated)) {
                        $othertabinsert[] = $v;
                    }
                }

                // traitement des blocs avec fonctions de modules
                if ($blocs[1][0] != '') {
                    preg_match('#^(include\#.[^\\|\s]+)#', $blocs[1][0], $rb);
                    $tabsblocs = $rb[1];
                } else {
                    $tabsblocs = 'include#modules/' . $ModDesinstall . '/';
                }

                $lbmod = sql_num_rows(sql_query("SELECT id FROM lblocks WHERE content LIKE '$tabsblocs%'"));
                $rbmod = sql_num_rows(sql_query("SELECT id FROM rblocks WHERE content LIKE '$tabsblocs%'"));

                $fonct = sql_num_rows(sql_query("SELECT fid FROM fonctions WHERE fnom='" . $ModDesinstall . "'"));

                if ($fonct > 0) {
                    array_push($othertabinsert, 'fonctions', 'droits');
                }
            }

            //nettoyage
            if ($subop == "desinst") {
                if (file_exists("modules/" . $ModDesinstall . "/install.conf.php")) {
                    list($fid) = sql_fetch_row(sql_query("SELECT fid FROM fonctions WHERE fnom='" . $ModDesinstall . "'"));

                    if (isset($fid) and $fid != '') {
                        sql_query("DELETE FROM droits WHERE d_fon_fid=" . $fid . "");
                        sql_query("DELETE FROM fonctions WHERE fnom='" . $ModDesinstall . "'");
                    }

                    // nettoyage table(s) créé(s)
                    if (count($tabcreated) > 0) {
                        foreach ($tabcreated as $v) {
                            sql_query("DROP TABLE IF EXISTS `$v`;");
                        }
                    }

                    // nettoyage metamot
                    if (count($modulemetamot) > 0) {
                        foreach ($modulemetamot as $v) {
                            sql_query("DELETE FROM metalang WHERE metalang.def='" . $v . "'");
                        }
                    }

                    // nettoyage blocs
                    if ($tabsblocs != '') {
                        sql_query("DELETE FROM lblocks WHERE content LIKE '" . $tabsblocs . "%'");
                        sql_query("DELETE FROM rblocks WHERE content LIKE '" . $tabsblocs . "%'");
                    }
                }

                // maj etat d'installation
                sql_query("UPDATE modules SET minstall='0' WHERE mnom= '" . $ModDesinstall . "'");

                redirect_url("admin.php?op=modules");
            }

            $display = '
                <hr />
                <h4 class="text-danger mb-3">' . __d('module', 'Désinstaller le module') . ' ' . $ModDesinstall . '.</h4>';

            if (file_exists("modules/" . $ModDesinstall . "/install.conf.php")) {
                $display .= '<div class="alert alert-danger">' . __d('module', 'Cette opération est irréversible elle va affecter votre base de données par la suppression de table(s) ou/et de ligne(s) et la suppression ou modification de certains fichiers.') . '<br /><br />';
                
                if (isset($tabcreated)) {
                    $v = '';
                    $display .= '<strong>' . __d('module', 'Suppression de table(s)') . '</strong><ul>';

                    foreach ($tabcreated as $v) {
                        $display .= '<li>' . $v . '</li>';
                    }

                    $display .= '</ul>';
                }

                if (count($othertabinsert) > 0 or $tabsblocs != '') {
                    $v = '';
                    $display .= '<strong>' . __d('module', 'Modification de données dans table(s)') . '</strong><ul>';

                    foreach ($othertabinsert as $v) {
                        $display .= '<li>' . $v . '</li>';
                    }

                    $display .= $lbmod > 0 ? '<li>lblocs</li>' : '';
                    $display .= $rbmod > 0 ? ' <li>rblocs</li>' : '';
                    $display .= '</ul>';
                }

                $display .= '
                    </div>
                    <div class="text-center mb-3">
                        <a href="JavaScript:history.go(-1)" class="btn btn-secondary me-2 mb-2">' . __d('module', 'Retour en arrière') . '</a><a href="admin.php?op=Module-Install&amp;ModDesinstall=' . $ModDesinstall . '&amp;subop=desinst" class="btn btn-danger mb-2">' . __d('module', 'Désinstaller le module') . '</a>
                    </div>';
            } else {
                $display .= '
                    <p><strong>' . __d('module', 'La désinstallation automatique des modules n\'est pas prise en charge à l\'heure actuelle.') . '</strong>
                    <p>' . __d('module', 'Vous devez désinstaller le module manuellement. Pour cela, référez vous au fichier install.txt de l\'archive du module, et faites les opérations inverses de celles décrites dans la section \"Installation manuelle\", et en partant de la fin.') . '
                    <p>' . __d('module', 'Enfin, pour pouvoir réinstaller le module par la suite avec Module-Install, cliquez sur le bouton \"Marquer le module comme désinstallé\".') . '</p>
                    <div class="text-center mb-3">
                        <a href="JavaScript:history.go(-1)" class="btn btn-secondary me-2 mb-2">' . __d('module', 'Retour en arrière') . '</a>
                        <a href="admin.php?op=Module-Install&amp;ModDesinstall=' . $ModDesinstall . '&amp;subop=desinst" class="btn btn-danger mb-2">' . __d('module', 'Marquer le module comme désinstallé') . '</a>
                    </div>';
            }

            $display .= $this->nmig_copyright();
        }

        echo $display;
       
        Css::adminfoot('', '', '', '');
    }

}
