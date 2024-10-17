<?php

namespace Modules\Module\Controllers\Admin\Install;

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
     * e1
     *
     * @param [type] $name_module
     * @param [type] $txtdeb
     * @return void
     */
    public function nmig_Start($name_module, $txtdeb)
    {
        global $ModInstall, $display;
    
        if ($ModInstall != '') {

            if ($subop == 'install') {
                $result = sql_query("UPDATE modules SET minstall='1' WHERE mnom= '" . $ModInstall . "'");
            }

            if (file_exists("modules/" . $ModInstall . "/install.conf.php")) {
                include("modules/" . $ModInstall . "/install.conf.php");
            } else {
                redirect_url("admin.php?op=modules");
                die();
            }
        }

        $display = '
        <hr />
        <div class="lead">' . $name_module . '</div>
        <hr />
        <div class="">';
    
        if (isset($txtdeb) && $txtdeb != '') {
            $display .= aff_langue($txtdeb);
        } else {
            $display .= '
            <p class="lead">' . __d('module', 'Bonjour et bienvenue dans l\'installation automatique du module') . ' "' . $name_module . '"</p>
            <p>' . __d('module', 'Ce programme d\'installation va configurer votre site internet pour utiliser ce module.') . '</p>
            <p><em>' . __d('module', 'Cliquez sur \"Etape suivante\" pour continuer.') . '</em></p>';
        }

        $display .= '
        </div>
        <div class="text-center">
            <a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e2" class="btn btn-primary">' . __d('module', 'Etape suivante') . '</a><br />
        </div>
        ' . $this->nmig_copyright();
    }

}
