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
     * e10 étape à fusionner avec la 9 ....
     *
     * @param [type] $name_module
     * @param [type] $end_link
     * @return void
     */
    public function nmig_End($name_module, $end_link)
    {
        global $ModInstall, $display;
    
        if (!isset($end_link) || $end_link == '') {
            $end_link = "admin.php?op=modules";
        }

        sql_query("UPDATE modules SET minstall='1' WHERE mnom='" . $ModInstall . "'");
    
        $display = '
        <hr /> 
        <div class="alert alert-success lead">' . __d('module', 'L\'installation automatique du module') . ' <b>' . $name_module . '</b> ' . __d('module', 'est terminée !') . '</div>
        <div class="mb-3">
            <a href="' . $end_link . '" class="btn btn-success">' . __d('module', 'Ok') . '</a>
        </div>
        ' . $this->nmig_copyright();
    }

}
