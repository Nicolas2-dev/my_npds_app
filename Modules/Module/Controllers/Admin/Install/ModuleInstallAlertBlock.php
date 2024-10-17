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
     * e7
     *
     * @param [type] $blocs
     * @param [type] $name_module
     * @return void
     */
    public function nmig_AlertBloc($blocs, $name_module)
    {
        global $ModInstall, $display;

        if (isset($blocs) && count($blocs[0]) && $blocs[0][0] != '') {
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
        
            $display .= $this->nmig_copyright();
        } else {
            echo "<script type=\"text/javascript\">\n//<![CDATA[\nwindow.location = \"admin.php?op=Module-Install&ModInstall=" . $ModInstall . "&nmig=e9\";\n//]]>\n</script>";
        }
    }

}
