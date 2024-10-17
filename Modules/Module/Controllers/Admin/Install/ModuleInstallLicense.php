<?php

namespace Modules\Module\Controllers\Admin\Install;

use Npds\Config\Config;
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
     * e2
     *
     * @param [type] $licence_file
     * @param [type] $name_module
     * @return void
     */
    public function nmig_License($licence_file, $name_module)
    {
        global $ModInstall, $display;
    
        if (isset($sql[0]) && $sql[0] != '') { 

            $licence_file = file_exists("modules/" . $ModInstall . "/licence-" . Config::get('npds.language') . ".txt") ?
                'modules/' . $ModInstall . '/licence-' . Config::get('npds.language') . '.txt' :
                'modules/' . $ModInstall . '/licence-english.txt';

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
            ' . $this->nmig_copyright();
        } else{ 
            echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e5\";\n//]]>\n</script>";
        }    
    }

}
