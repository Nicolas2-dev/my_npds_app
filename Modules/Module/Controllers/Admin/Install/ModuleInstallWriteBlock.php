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
     * e8
     *
     * @param [type] $blocs
     * @param [type] $posbloc
     * @param [type] $name_module
     * @return void
     */
    public function nmig_WriteBloc($blocs, $posbloc, $name_module)
    {
        global $ModInstall, $display;
    
        if (isset($blocs) && count($blocs[0]) && $blocs[0][0] != '') {
            $display = '
            <hr />
            <div class="lead">' . $name_module . '</div>
            <hr />
            <div class="">';
        
            if ($posbloc) {
                if ($blocs[2] == '') {
                    $blocs[2] = $blocs[3];
                }
        
                if ($posbloc == 'l') {
                    $posblocM = 'L';
                }
        
                if ($posbloc == 'r') {
                    $posblocM = 'R';
                }
        
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
                } else {
                    $display .= '<div class=" alert alert-success">' . __d('module', 'La configuration du(des) bloc(s) a réussi !') . '</div>';
                }
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
            </div>' . $this->nmig_copyright();
        } else {
            echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e9\";\n//]]>\n</script>";
        }
    }

}
