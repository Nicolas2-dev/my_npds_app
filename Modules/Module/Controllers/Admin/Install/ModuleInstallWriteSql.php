<?php

namespace Modules\Module\Controllers\Admin\Install;

use Modules\Npds\Core\AdminController;
use Modules\Module\Support\Traits\ModuleInstallCopyrightTrait;


class ModuleInstall extends AdminController
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
     * e4
     *
     * @param [type] $sql
     * @param [type] $path_adm_module
     * @param [type] $name_module
     * @param [type] $affich
     * @param [type] $icon
     * @return void
     */
    public function nmig_WriteSql($sql, $path_adm_module, $name_module, $affich, $icon)
    {
        global $ModInstall, $display, $path_adm_module, $name_module, $affich, $icon;
    
        if (isset($sql[0]) && $sql[0] != '') {

            $reqsql = '';
        
            $display = '
            <hr />
            <div class="lead">' . $name_module . '</div>
            <hr />
            <div class="">';
        
            for ($i = 0; $i < count($sql) && !isset($erreur); $i++) {
                sql_query($sql[$i]) or $erreur = sql_error();
            }
        
            if (isset($erreur)) {
                $display .= '
                <div class="alert alert-danger">
                    <p>' . __d('module', 'Une erreur est survenue lors de l\'exécution du script SQL. Mysql a répondu :') . '</p>
                    <p><strong>' . $erreur . '</strong></p>
                    <p>' . __d('module', 'Veuillez l\'exécuter manuellement via phpMyAdmin.') . '</p>
                </div>
                <p>' . __d('module', 'Voici le script SQL :') . '</p>';
        
                for ($i = 0; $i < count($sql); $i++) {
                    $reqsql .= '<pre class="language-sql"><code class="language-sql">' . $sql[$i] . '</code></pre><br />';
                }
        
                $display .= $reqsql;
                $display .= "<br />\n";
            } else {
                if ($path_adm_module != '') {
        
                    //controle si on a pas déja la fonction (si oui on efface sinon on renseigne)
                    $ck = sql_query("SELECT fnom FROM fonctions WHERE fnom = '" . $name_module . "'");
        
                    if ($ck) { 
                        sql_query("DELETE FROM fonctions WHERE fnom='" . $name_module . "'");
                    }

                    sql_query("INSERT INTO fonctions (fid,fnom,fdroits1,fdroits1_descr,finterface,fetat,fretour,fretour_h,fnom_affich,ficone,furlscript,fcategorie,fcategorie_nom,fordre) VALUES (0, '" . $ModInstall . "', 0, '', 1, 1, '', '', '" . $affich . "', '" . $icon . "', 'href=\"admin.php?op=Extend-Admin-SubModule&ModPath=" . $ModInstall . "&ModStart=" . $path_adm_module . "\"', 6, 'Modules', 0)") or sql_error();
                    
                    $ibid = sql_last_id();
                    
                    sql_query("UPDATE fonctions SET fdroits1 = " . $ibid . " WHERE fid=" . $ibid . "");
        
                    //==> ajout des alertesadmin
                    if (file_exists("modules/" . $name_module . "/admin/adm_alertes.php")) {
                        include("modules/" . $name_module . "/admin/adm_alertes.php");
        
                        if (count($reqalertes) != 0) {
                            foreach ($reqalertes as $v) {
                                sql_query("INSERT INTO fonctions (fid,fnom,fdroits1,fdroits1_descr,finterface,fetat,fretour,fretour_h,fnom_affich,ficone,furlscript,fcategorie,fcategorie_nom,fordre) VALUES (0, '" . $ModInstall . "', " . $ibid . ", '', 1, 1, '', '', '" . $affich . "', '" . $icon . "', 'href=\"admin.php?op=Extend-Admin-SubModule&ModPath=" . $ModInstall . "&ModStart=" . $path_adm_module . "\"', 9, 'Modules', 0)") or sql_error();
                            }
                        }
                    }
                    //<== ajout des alertesadmin
                }
        
                $display .= '<p class="text-success"><strong>' . __d('module', 'La configuration de la base de données MySql a réussie !') . '</strong></p>';
            }
        
            $display .= '
            </div>
            <div class="text-center">
            <br /><a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e5" class="btn btn-primary">' . __d('module', 'Etape suivante') . '</a><br />
            </div><br />
            ' . $this->nmig_copyright();
        } else {
            echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e5\";\n//]]>\n</script>";
        }
    }

}
