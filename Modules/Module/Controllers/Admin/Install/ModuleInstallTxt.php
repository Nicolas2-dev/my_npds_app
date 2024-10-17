<?php

namespace Modules\Module\Controllers\Admin\Install;

use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;
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
     * e9 étape à fusionner avec la 10 ....
     *
     * @param [type] $txtfin
     * @return void
     */
    public function nmig_txt($txtfin)
    {
        global $ModInstall, $display;
    
        if (isset($txtfin) && $txtfin != '') {

            $display = '
            <hr />
            <div class="lead mb-3">' . Language::aff_langue($txtfin) . '</div>
            <div class="text-center mb-3">
                <a class="btn btn-primary" href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e10" >' . __d('module', 'Etape suivante') . '</a><br />
            </div>' . $this->nmig_copyright();
        } else {
            echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e10\";\n//]]>\n</script>";
        }
    }

}
