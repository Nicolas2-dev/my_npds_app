<?php

namespace App\Modules\Module\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;


class AdminModuleInstall extends AdminController
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
        // $f_meta_nom = 'modules';
        // $f_titre = __d('module', 'Gestion, Installation Modules');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        
        // $hlpfile = '';
        // $display = '';

        // // ************************
        // // * Affichage de la page *
        // // ************************
        // settype($subop, 'string');
        // settype($ModInstall, 'string');
        // settype($ModDesinstall, 'string');

        // if (!isset($try_Chmod))
        //     $try_Chmod = 0;

        // if ($ModInstall != '' && $ModDesinstall == '') {

        //     if ($subop == 'install')
        //         $result = sql_query("UPDATE modules SET minstall='1' WHERE mnom= '" . $ModInstall . "'");

        //     if (file_exists("modules/" . $ModInstall . "/install.conf.php"))
        //         include("modules/" . $ModInstall . "/install.conf.php");
        //     else {
        //         redirect_url("admin.php?op=modules");
        //         die();
        //     }

        //     $licence_file = file_exists("modules/" . $ModInstall . "/licence-" . Config::get('npds.language') . ".txt") ?
        //         'modules/' . $ModInstall . '/licence-' . Config::get('npds.language') . '.txt' :
        //         'modules/' . $ModInstall . '/licence-english.txt';

        //     settype($nmig, 'string');
        //     settype($icon, 'string');
        //     settype($affich, 'string');

        //     switch ($nmig) {
        //         case 'e2':
        //             nmig_License($licence_file, $name_module);
        //             break;

        //         case 'e3':
        //             if (isset($sql[0]) && $sql[0] != '') 
        //                 nmig_AlertSql($sql, $name_module);
        //             else 
        //                 echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e5\";\n//]]>\n</script>";
        //             break;

        //         case 'e4':
        //             if (isset($sql[0]) && $sql[0] != '') 
        //                 nmig_WriteSql($sql, $path_adm_module, $name_module, $affich, $icon);
        //             else 
        //                 echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e5\";\n//]]>\n</script>";
        //             break;

        //         case 'e5':
        //             if (isset($list_fich) && count($list_fich[0]) && $list_fich[0][0] != '') 
        //                 nmig_AlertConfig($list_fich);
        //             else 
        //                 echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e7\";\n//]]>\n</script>";
        //             break;

        //         case 'e6':
        //             if (isset($list_fich) && count($list_fich[0])) 
        //                 nmig_WriteConfig($list_fich, $try_Chmod);
        //             else 
        //                 echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e7\";\n//]]>\n</script>";
        //             break;

        //         case 'e7':
        //             if (isset($blocs) && count($blocs[0]) && $blocs[0][0] != '') 
        //                 nmig_AlertBloc($blocs, $name_module);
        //             else 
        //                 echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e9\";\n//]]>\n</script>";
        //             break;

        //         case 'e8':
        //             if (isset($blocs) && count($blocs[0]) && $blocs[0][0] != '') 
        //                 nmig_WriteBloc($blocs, $posbloc, $name_module);
        //             else 
        //                 echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e9\";\n//]]>\n</script>";
        //             break;

        //         case 'e9':
        //             if (isset($txtfin) && $txtfin != '') 
        //                 nmig_txt($txtfin);
        //             else 
        //                 echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e10\";\n//]]>\n</script>";
        //             break;

        //         case 'e10':
        //             if (!isset($end_link) || $end_link == '') 
        //                 $end_link = "admin.php?op=modules";

        //             nmig_End($name_module, $end_link);
        //             break;

        //         default:
        //             nmig_Start($name_module, $txtdeb);
        //             break;
        //     }
        // } elseif ($ModInstall == '' && $ModDesinstall != '') {
        //     if (file_exists("modules/" . $ModDesinstall . "/install.conf.php")) {
        //         include("modules/" . $ModDesinstall . "/install.conf.php");

        //         // we get the name of the tables !! a tester avec table prefixé
        //         settype($tabcreated, 'array');
        //         settype($tabinsert, 'array');
        //         settype($othertabinsert, 'array');
        //         settype($modulemetamot, 'array');

        //         foreach ($sql as $v) {
        //             if (preg_match('#CREATE TABLE( IF NOT EXISTS |\s)(\w+)#', $v, $rt))
        //                 $tabcreated[] = $rt[2];

        //             if (preg_match('#^INSERT INTO (\w+)#', $v, $ri)) {
        //                 $tabinsert[] = $ri[1];

        //                 if ($ri[1] == 'metalang') {
        //                     preg_match("#\sVALUES\s+\('(.[^']+)',\s+#", $v, $met);
        //                     $modulemetamot[] = $met[1];
        //                     // recupere la première valeur de VALUES pour cibler la def d'un metamot, pour les tables autres que metalang unimplemented ...
        //                 }
        //             }
        //         }

        //         foreach ($tabinsert as $v) {
        //             if (!in_array($v, $tabcreated))
        //                 $othertabinsert[] = $v;
        //         }

        //         //traitement des blocs avec fonctions de modules
        //         if ($blocs[1][0] != '') {
        //             preg_match('#^(include\#.[^\\|\s]+)#', $blocs[1][0], $rb);
        //             $tabsblocs = $rb[1];
        //         } else
        //             $tabsblocs = 'include#modules/' . $ModDesinstall . '/';

        //         $lbmod = sql_num_rows(sql_query("SELECT id FROM lblocks WHERE content LIKE '$tabsblocs%'"));
        //         $rbmod = sql_num_rows(sql_query("SELECT id FROM rblocks WHERE content LIKE '$tabsblocs%'"));

        //         $fonct = sql_num_rows(sql_query("SELECT fid FROM fonctions WHERE fnom='" . $ModDesinstall . "'"));

        //         if ($fonct > 0) 
        //             array_push($othertabinsert, 'fonctions', 'droits');
        //     }

        //     //nettoyage
        //     if ($subop == "desinst") {
        //         if (file_exists("modules/" . $ModDesinstall . "/install.conf.php")) {
        //             list($fid) = sql_fetch_row(sql_query("SELECT fid FROM fonctions WHERE fnom='" . $ModDesinstall . "'"));

        //             if (isset($fid) and $fid != '') {
        //                 sql_query("DELETE FROM droits WHERE d_fon_fid=" . $fid . "");
        //                 sql_query("DELETE FROM fonctions WHERE fnom='" . $ModDesinstall . "'");
        //             }

        //             // nettoyage table(s) créé(s)
        //             if (count($tabcreated) > 0) {
        //                 foreach ($tabcreated as $v) {
        //                     sql_query("DROP TABLE IF EXISTS `$v`;");
        //                 }
        //             }

        //             // nettoyage metamot
        //             if (count($modulemetamot) > 0) {
        //                 foreach ($modulemetamot as $v) {
        //                     sql_query("DELETE FROM metalang WHERE metalang.def='" . $v . "'");
        //                 }
        //             }

        //             // nettoyage blocs
        //             if ($tabsblocs != '') {
        //                 sql_query("DELETE FROM lblocks WHERE content LIKE '" . $tabsblocs . "%'");
        //                 sql_query("DELETE FROM rblocks WHERE content LIKE '" . $tabsblocs . "%'");
        //             }
        //         }

        //         // maj etat d'installation
        //         sql_query("UPDATE modules SET minstall='0' WHERE mnom= '" . $ModDesinstall . "'");
        //         redirect_url("admin.php?op=modules");
        //     }

        //     include("header.php");

        //     $display = '
        //         <hr />
        //         <h4 class="text-danger mb-3">' . __d('module', 'Désinstaller le module') . ' ' . $ModDesinstall . '.</h4>';

        //     if (file_exists("modules/" . $ModDesinstall . "/install.conf.php")) {
        //         $display .= '<div class="alert alert-danger">' . __d('module', 'Cette opération est irréversible elle va affecter votre base de données par la suppression de table(s) ou/et de ligne(s) et la suppression ou modification de certains fichiers.') . '<br /><br />';
                
        //         if (isset($tabcreated)) {
        //             $v = '';
        //             $display .= '<strong>' . __d('module', 'Suppression de table(s)') . '</strong><ul>';

        //             foreach ($tabcreated as $v) {
        //                 $display .= '<li>' . $v . '</li>';
        //             }

        //             $display .= '</ul>';
        //         }

        //         if (count($othertabinsert) > 0 or $tabsblocs != '') {
        //             $v = '';
        //             $display .= '<strong>' . __d('module', 'Modification de données dans table(s)') . '</strong><ul>';

        //             foreach ($othertabinsert as $v) {
        //                 $display .= '<li>' . $v . '</li>';
        //             }

        //             $display .= $lbmod > 0 ? '<li>lblocs</li>' : '';
        //             $display .= $rbmod > 0 ? ' <li>rblocs</li>' : '';
        //             $display .= '</ul>';
        //         }

        //         $display .= '
        //             </div>
        //             <div class="text-center mb-3">
        //                 <a href="JavaScript:history.go(-1)" class="btn btn-secondary me-2 mb-2">' . __d('module', 'Retour en arrière') . '</a><a href="admin.php?op=Module-Install&amp;ModDesinstall=' . $ModDesinstall . '&amp;subop=desinst" class="btn btn-danger mb-2">' . __d('module', 'Désinstaller le module') . '</a>
        //             </div>';
        //     } else {
        //         $display .= '
        //             <p><strong>' . __d('module', 'La désinstallation automatique des modules n'est pas prise en charge à l'heure actuelle.') . '</strong>
        //             <p>' . __d('module', 'Vous devez désinstaller le module manuellement. Pour cela, référez vous au fichier install.txt de l'archive du module, et faites les opérations inverses de celles décrites dans la section \"Installation manuelle\", et en partant de la fin.') . '
        //             <p>' . __d('module', 'Enfin, pour pouvoir réinstaller le module par la suite avec Module-Install, cliquez sur le bouton \"Marquer le module comme désinstallé\".') . '</p>
        //             <div class="text-center mb-3">
        //                 <a href="JavaScript:history.go(-1)" class="btn btn-secondary me-2 mb-2">' . __d('module', 'Retour en arrière') . '</a>
        //                 <a href="admin.php?op=Module-Install&amp;ModDesinstall=' . $ModDesinstall . '&amp;subop=desinst" class="btn btn-danger mb-2">' . __d('module', 'Marquer le module comme désinstallé') . '</a>
        //             </div>';
        //     }

        //     $display .= nmig_copyright();
        // }

        // GraphicAdmin($hlpfile);
        // adminhead($f_meta_nom, $f_titre);

        // $clspin = ' text-success';

        // if ($ModInstall == '' && $ModDesinstall != '')
        //     $clspin = ' text-danger';

        // echo $display;
        // adminfoot('', '', '', '');
    // }

    function nmig_copyright()
    {
        global $ModInstall, $ModDesinstall;
    
        $clspin = ' text-success';
    
        if ($ModInstall == '' && $ModDesinstall != '')
            $clspin = ' text-danger';
    
        $display = '
            <hr class="mt-4" />
            <div class="d-flex align-items-center">
                <div role="status" class="small">Installation by App Module Installer v2.0</div>
                <div class="spinner-border ms-auto ' . $clspin . '" aria-hidden="true"  style="width: 1.5rem; height: 1.5rem;"></div>
            </div>';
    
        return $display;
    }
    
    // e1
    function nmig_Start($name_module, $txtdeb)
    {
        global $ModInstall, $display;
    
        $display = '
        <hr />
        <div class="lead">' . $name_module . '</div>
        <hr />
        <div class="">';
    
        if (isset($txtdeb) && $txtdeb != '')
            $display .= aff_langue($txtdeb);
        else
            $display .= '
            <p class="lead">' . __d('module', 'Bonjour et bienvenue dans l\'installation automatique du module') . ' "' . $name_module . '"</p>
            <p>' . __d('module', 'Ce programme d\'installation va configurer votre site internet pour utiliser ce module.') . '</p>
            <p><em>' . __d('module', 'Cliquez sur \"Etape suivante\" pour continuer.') . '</em></p>';
    
        $display .= '
        </div>
        <div class="text-center">
            <a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e2" class="btn btn-primary">' . __d('module', 'Etape suivante') . '</a><br />
        </div>
        ' . nmig_copyright();
    }
    
    // e2
    function nmig_License($licence_file, $name_module)
    {
        global $ModInstall, $display;
    
        $myfile = fopen($licence_file, "r");
        $licence_text = fread($myfile, filesize($licence_file));
        fclose($myfile);
    
        $display = '
        <hr />
        <div class="lead">' . $name_module . '</div>
        <hr />
        <div class="mb-3">
            <p class="lead">' . __d('module', 'L\'utilisation de App et des modules est soumise à l\'acceptation des termes de la licence GNU/GPL :') . '</p>
            <div class="text-center">
                <textarea class="form-control" name="licence" rows="12" readonly="readonly">' . htmlentities($licence_text, ENT_QUOTES | ENT_IGNORE, "UTF-8") . '</textarea>
                <br /><a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e3" class="btn btn-primary">' . __d('module', 'Oui') . '</a>&nbsp;<a href="admin.php?op=modules" class="btn btn-danger">' . __d('module', 'Non') . '</a><br />
            </div>
        </div>
        ' . nmig_copyright();
    }
    
    //e3
    function nmig_AlertSql($sql, $name_module)
    {
        global $ModInstall, $display;
    
        $reqsql = '';
    
        foreach ($sql as $v) {
            preg_match('#^(CREATE TABLE |CREATE TABLE IF NOT EXISTS) (\w+)#', $v, $tables);
        }
    
        for ($i = 0; $i < count($sql); $i++) {
            for ($j = 0; $j < count($tables); $j++) {
                $sql[$i] = preg_replace("#$tables[$j]#i" . $tables[$j], $sql[$i]);
            }
    
            $reqsql .= '<pre class="language-sql"><code class="language-sql">' . $sql[$i] . '</code></pre><br />';
        }
    
        $display = '
        <hr />
        <div class="lead">' . $name_module . '</div>
        <hr />
        <div class="">
            <p class="lead">' . __d('module', 'Le programme d\'installation va maintenant exécuter le script SQL pour configurer la base de données MySql.') . '</p>
            <p>' . __d('module', 'Si vous le souhaitez, vous pouvez exécuter ce script vous même, si vous souhaitez par exemple l\'exécuter sur une autre base que celle du site. Dans ce cas, pensez à reparamétrer le fichier de configuration du module.') . '</p>
            <p>' . __d('module', 'Voici le script SQL :') . '</p>
        </div>
        ' . $reqsql . '
        <br />
        <div class="text-center">
            <a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e4" class="btn btn-primary">' . __d('module', 'Configurer MySql') . '</a>&nbsp;<a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e5" class="btn btn-danger">' . __d('module', 'Sauter cette étape') . '</a><br />
        </div>
        <br />
        ' . nmig_copyright();
    }
    
    // e4
    function nmig_WriteSql($sql, $path_adm_module, $name_module, $affich, $icon)
    {
        global $ModInstall, $display, $path_adm_module, $name_module, $affich, $icon;
    
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
    
                if ($ck)
                    sql_query("DELETE FROM fonctions WHERE fnom='" . $name_module . "'");
    
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
        ' . nmig_copyright();
    }
    
    // e5
    function nmig_AlertConfig($list_fich)
    {
        global $ModInstall, $display;
    
        $display = '
        <hr />
        <div class="mb-3">
            <p class="lead">' . __d('module', 'Le programme d\'installation va maintenant modifier le(s) fichier(s) suivant(s) :') . '</p>';
    
        for ($i = 0; $i < count($list_fich[0]); $i++) {
            $display .= '<code>' . $list_fich[0][$i] . '</code><br />';
        }
    
        $display .= '
        </div>
        <div class="text-center mb-3">
            <a class="btn btn-primary" href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e6">' . __d('module', 'Modifier le(s) fichier(s)') . '</a>
        </div>' . nmig_copyright();
    }
    
    // e6
    function nmig_WriteConfig($list_fich, $try_Chmod)
    {
        global $ModInstall, $display;
    
        $writeAllFiles = 1;
    
        $display = '
        <hr />
        <div class="mb-3">';
    
        $file_created = 0;
    
        for ($i = 0; $i < count($list_fich[0]); $i++) {
            if (!file_exists($list_fich[0][$i])) {
                $file = fopen($list_fich[0][$i], "w"); //change to debug i7 to i
                fclose($file);
                $file_created = 1;
            }
    
            if ($list_fich[0][$i] == "themes/default/include/body_onload.inc") {
                $file = fopen($list_fich[0][$i], "r");
                $txtconfig = fread($file, filesize($list_fich[0][$i]));
                fclose($file);
    
                $debut = strpos($list_fich[1][$i], "[nom]") + 5;
                $fin = strpos($list_fich[1][$i], "[/nom]");
    
                if (preg_match("#" . substr($list_fich[1][$i], $debut, $fin - $debut) . "#", $txtconfig)) {
                    $display .= '<p class="lead">' . __d('module', 'Les paramètres sont déjà inscrits dans le fichier') . '</p><code>' . $list_fich[0][$i] . '</code><br />';
                } else {
                    if ($try_Chmod)
                        chmod($list_fich[0][$i], 666);
    
                    $file = fopen($list_fich[0][$i], "r+");
                    fread($file, filesize($list_fich[0][$i]));
    
                    if (fwrite($file, $list_fich[1][$i])) {
                        fclose($file);
                        $display .= __d('module', 'Les paramètres ont été correctement écrits dans le fichier \"') . $list_fich[0][$i] . "\".<br />\n";
                    } else {
                        $writeAllFiles = 0;
    
                        $display .= __d('module', 'Impossible d\'écrire dans le fichier \"') . $list_fich[0][$i] . "\". " . __d('module', 'Veuillez éditer ce fichier manuellement ou réessayez en tentant de faire un chmod automatique sur le(s) fichier(s) concernés.') . "<br />";
                        $display .= __d('module', 'Voici le code à taper dans le fichier :') . "<br /><br />\n";
    
                        $display .= '</div>';
                        $display .= "<div class=\"code\">\n";
    
                        ob_start();
                            highlight_string($list_fich[1][$i]);
                            $display .= ob_get_contents();
                        ob_end_clean();
    
                        $display .= "<br />\n";
                    }
                }
            } else {
                $file = fopen($list_fich[0][$i], "r");
                $txtconfig = fread($file, filesize($list_fich[0][$i]));
                fclose($file);
    
                if (!$file_created) {
                    $debut = strpos($txtconfig, "?>");
                    $txtconfig = substr($txtconfig, 0, $debut - 1) . chr(13) . $list_fich[1][$i] . chr(13) . "?>";
                } else
                    $txtconfig = "<?php \n" . $list_fich[1][$i] . "\n ?>";
    
                if ($try_Chmod)
                    chmod($list_fich[0][$i], 666);
    
                $file = fopen($list_fich[0][$i], "w");
                fread($file, filesize($list_fich[0][$i]));
    
                if (fwrite($file, $txtconfig)) {
                    fclose($file);
                    $display .= __d('module', 'Les paramètres ont été correctement écrits dans le fichier \"') . $list_fich[0][$i] . "\".<br />\n";
                } else {
                    $writeAllFiles = 0;
                    $display .= __d('module', 'Impossible d\'écrire dans le fichier \"') . $list_fich[0][$i] . "\". " . __d('module', 'Veuillez éditer ce fichier manuellement ou réessayez en tentant de faire un chmod automatique sur le(s) fichier(s) concernés.') . "<br />\n";
                    $display .= __d('module', 'Voici le code à taper dans le fichier :') . "<br /><br />\n";
    
                    $display .= "</div>\n";
                    $display .= "<div class=\"code\">\n";
    
                    ob_start();
                        highlight_string($list_fich[1][$i]);
                        $display .= ob_get_contents();
                    ob_end_clean();
    
                    $display .= "<br />\n";
                }
            }
        }
    
        $display .= '
        </div>
        <div class="text-center mb-3">';
    
        $display .= !$writeAllFiles ?
            '<a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e6&amp;try_Chmod=1" class="text-danger">' . __d('module', 'Réessayer avec chmod automatique') . '</a>' :
            '<a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e7" class="btn btn-primary">' . __d('module', 'Etape suivante') . '</a>';
        
        $display .=  '</div>' . nmig_copyright();
    }
    
    // e7
    function nmig_AlertBloc($blocs, $name_module)
    {
        global $ModInstall, $display;
    
        $display = '
        <hr />
        <div class="lead">' . $name_module . '</div>
        <hr />
        <div class="">
            <p>' . __d('module', 'Vous pouvez choisir maintenant de créer automatiquement un(des) bloc(s) à droite ou à gauche. Cliquer sur \"Créer le(s) bloc(s) à gauche\" ou \"Créer le(s) bloc(s) à droite\" selon votre choix. (Vous pourrez changer leurs positions par la suite dans le panneau d\'administration --> Blocs)') . '</p>
            <p>' . __d('module', 'Si vous préférez créer vous même le(s) bloc(s), cliquez sur \'Sauter cette étape et afficher le code du(des) bloc(s)\' pour visualiser le code à taper dans le(s) bloc(s).') . '</p>
            <p>' . __d('module', 'Voici la description du(des) bloc(s) qui sera(seront) créé(s) :') . '</p>
        </div>';
    
        ob_start();
            echo '<ul>';
            
            for ($i = 0; $i < count($blocs[0]); $i++) {
                echo '<li>Bloc n&#xB0; ' . $i . ' : ' . $blocs[8][$i] . '</li>';
            }
    
            echo '</ul>';
    
            $display .= ob_get_contents();
        ob_end_clean();
    
        $display .= '
        <div class="text-center mb-3">
            <a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e8&amp;posbloc=l" class="btn btn-primary mt-2">' . __d('module', 'Créer le(s) bloc(s) à gauche') . '</a>
            <a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e8&amp;posbloc=r" class="btn btn-primary mt-2">' . __d('module', 'Créer le(s) bloc(s) à droite') . '</a>
            <a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e8&amp;posbloc=0" class="btn btn-danger mt-2">' . __d('module', 'Sauter cette étape') . '</a>
        </div>';
    
        $display .= nmig_copyright();
    }
    
    // e8
    function nmig_WriteBloc($blocs, $posbloc, $name_module)
    {
        global $ModInstall, $display;
    
        $display = '
        <hr />
        <div class="lead">' . $name_module . '</div>
        <hr />
        <div class="">';
    
        if ($posbloc) {
            if ($blocs[2] == '')
                $blocs[2] = $blocs[3];
    
            if ($posbloc == 'l')
                $posblocM = 'L';
    
            if ($posbloc == 'r')
                $posblocM = 'R';
    
            for ($i = 0; $i < count($blocs[0]) && !isset($erreur); $i++) {
                sql_query("INSERT INTO " . $posbloc . "blocks (`id`, `title`, `content`, `member`, `" . $posblocM . "index`, `cache`, `actif`, `aide`) VALUES (0, '" . $blocs[0][$i] . "', '" . $blocs[1][$i] . "', '" . $blocs[2][$i] . "', '" . $blocs[4][$i] . "', '" . $blocs[5][$i] . "', '" . $blocs[6][$i] . "', '" . $blocs[7][$i] . "');") or $erreur = sql_error();
            }
    
            if (isset($erreur)) {
                $display .= __d('module', 'Une erreur est survenue lors de la configuration automatique du(des) bloc(s). Mysql a répondu :');
    
                ob_start();
                    highlight_string($erreur);
                    $display .= ob_get_contents();
                ob_end_clean();
    
                $display .= __d('module', 'Veuillez configurer manuellement le(s) bloc(s).') . "<br /><br />\n";
                $display .= __d('module', 'Voici le code du(des) bloc(s) :') . "<br /><br />\n";
    
                ob_start();
                    for ($i = 0; $i < count($blocs[0]); $i++) {
                        echo "Bloc n&#xB0; " . $i . "<br />";
                        highlight_string($blocs[1][$i]);
                        echo "<br />\n";
                    }
    
                    $display .= ob_get_contents();
                ob_end_clean();
            } else
                $display .= '<div class=" alert alert-success">' . __d('module', 'La configuration du(des) bloc(s) a réussi !') . '</div>';
        } else {
            $display .= '<p><strong>' . __d('module', 'Vous avez choisi de configurer manuellement vos blocs. Voici le contenu de ceux-ci :') . '</strong></p>';
            
            ob_start();
                for ($i = 0; $i < count($blocs[0]); $i++) {
                    echo 'Bloc n&#xB0; ' . $i . '<br />
                    <code>' . $blocs[1][$i] . '</code>
                    <br />';
                }
                $display .= ob_get_contents();
            ob_end_clean();
        }
    
        $display .= '
        </div>
        <div class="text-center mt-3 mb-3">
            <a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e9" class="btn btn-primary">' . __d('module', 'Etape suivante') . '</a><br />
        </div>' . nmig_copyright();
    }
    
    // e9 étape à fusionner avec la 10 ....
    function nmig_txt($txtfin)
    {
        global $ModInstall, $display;
    
        $display = '
        <hr />
        <div class="lead mb-3">' . aff_langue($txtfin) . '</div>
        <div class="text-center mb-3">
            <a class="btn btn-primary" href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e10" >' . __d('module', 'Etape suivante') . '</a><br />
        </div>' . nmig_copyright();
    }
    
    // e10 étape à fusionner avec la 9 ....
    function nmig_End($name_module, $end_link)
    {
        global $ModInstall, $display;
    
        sql_query("UPDATE modules SET minstall='1' WHERE mnom='" . $ModInstall . "'");
    
        $display = '
        <hr /> 
        <div class="alert alert-success lead">' . __d('module', 'L\'installation automatique du module') . ' <b>' . $name_module . '</b> ' . __d('module', 'est terminée !') . '</div>
        <div class="mb-3">
            <a href="' . $end_link . '" class="btn btn-success">' . __d('module', 'Ok') . '</a>
        </div>
        ' . nmig_copyright();
    }
    
    function nmig_clean($ModDesinstall)
    {
        //==> a compléter
    }

}
