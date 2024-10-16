<?php

namespace Modules\ReseauxSociaux\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;


/**
 * Undocumented class
 */
class ReseauxSociaux extends AdminController
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
    protected $hlpfile = 'social';

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
    protected $f_meta_nom = 'reseaux-sociaux';


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
        $this->f_titre = __d('reseauxsociaux', 'Module') . ' : ' . $this->module;

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
     * Undocumented function
     *
     * @param [type] $f_meta_nom
     * @param [type] $f_titre
     * @return void
     */
    public function ListReseaux($f_meta_nom, $f_titre)
    {
        if (file_exists("modules/ReseauxSociaux/reseaux-sociaux.conf.php")) {
            include("modules/ReseauxSociaux/reseaux-sociaux.conf.php");
        }

        echo '
        <hr />
        <h3><a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=ReseauxSociaux&amp;ModStart=' . $ModStart . '&amp;subop=AddReseaux"><i class="fa fa-plus-square"></i></a>&nbsp;' . __d('reseauxsociaux', 'Ajouter') . '</h3>
        <table id ="lst_rs_adm" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">
            <thead>
                <tr>
                    <th class="n-t-col-xs-3" data-sortable="true" data-halign="center" data-align="right">' . __d('reseauxsociaux', 'Nom') . '</th>
                    <th class="n-t-col-xs-5" data-sortable="true" data-halign="center">' . __d('reseauxsociaux', 'URL') . '</th>
                    <th class="n-t-col-xs-1" data-halign="center" data-align="center">' . __d('reseauxsociaux', 'Icône') . '</th>
                    <th class="n-t-col-xs-2" data-halign="center" data-align="center">' . __d('reseauxsociaux', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($rs as $v1) {
            echo '
                <tr>
                    <td>' . $v1[0] . '</td>
                    <td>' . $v1[1] . '</td>
                    <td><i class="fab fa-' . $v1[2] . ' fa-2x text-muted align-middle"></i></td>
                    <td>
                    <a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=ReseauxSociaux&amp;ModStart=' . $ModStart . '&amp;subop=EditReseaux&amp;rs_id=' . urlencode($v1[0]) . '&amp;rs_url=' . urlencode($v1[1]) . '&amp;rs_ico=' . urlencode($v1[2]) . '" ><i class="fa fa-edit fa-lg me-2 align-middle" title="' . __d('reseauxsociaux', 'Editer') . '" data-bs-toggle="tooltip" data-bs-placement="left"></i></a>
                    <a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=ReseauxSociaux&amp;ModStart=' . $ModStart . '&amp;subop=DeleteReseaux&amp;rs_id=' . urlencode($v1[0]) . '&amp;rs_url=' . urlencode($v1[1]) . '&amp;rs_ico=' . urlencode($v1[2]) . '" ><i class="fas fa-trash fa-lg text-danger align-middle" title="' . __d('reseauxsociaux', 'Effacer') . '" data-bs-toggle="tooltip"></i></a>
                    </td>
                </tr>';
        }

        echo '
            </tbody>
        </table>';

        Css::adminfoot('', '', '', '');
    }

}
