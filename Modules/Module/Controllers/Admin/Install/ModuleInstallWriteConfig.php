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
     * e6
     *
     * @param [type] $list_fich
     * @param [type] $try_Chmod
     * @return void
     */
    public function nmig_WriteConfig($list_fich, $try_Chmod)
    {
        global $ModInstall, $display;
    
        if (isset($list_fich) && count($list_fich[0])) {

            if (!isset($try_Chmod)) {
                $try_Chmod = 0;
            }

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
                        if ($try_Chmod) {
                            chmod($list_fich[0][$i], 666);
                        }
        
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
                    } else {
                        $txtconfig = "<?php \n" . $list_fich[1][$i] . "\n ?>";
                    }
        
                    if ($try_Chmod) {
                        chmod($list_fich[0][$i], 666);
                    }
        
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
            
            $display .=  '</div>' . $this->nmig_copyright();
        } else {
            echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e7\";\n//]]>\n</script>";
        }
    }

}
