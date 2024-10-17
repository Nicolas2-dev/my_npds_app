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
     * e5
     *
     * @param [type] $list_fich
     * @return void
     */
    public function nmig_AlertConfig($list_fich)
    {
        global $ModInstall, $display;

        if (isset($list_fich) && count($list_fich[0]) && $list_fich[0][0] != '') {
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
            </div>' . $this->nmig_copyright();
        } else {
            echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e7\";\n//]]>\n</script>";
        }
    }

}
