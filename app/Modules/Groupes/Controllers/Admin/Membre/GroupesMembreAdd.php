<?php

namespace App\Modules\Groupes\Controllers\Admin\Membre;

use App\Modules\Npds\Core\AdminController;


class GroupesMembreAdd extends AdminController
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
    protected $hlpfile = 'groupes';

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
    protected $f_meta_nom = 'groupes';


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
        $this->f_titre = __d('groupes', 'Gestion des groupes');

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
     * @param [type] $gp
     * @return void
     */
    public function membre_add($gp)
    {
        echo '
        <hr />
        <h3>' . __d('groupes', 'Ajouter des membres') . ' / ' . __d('groupes', 'Groupe') . ' : ' . $gp . '</h3>
        <form id="groupesaddmb" class="admform" action="admin.php" method="post">
            <fieldset>
                <legend><i class="fa fa-users fa-2x text-muted"></i></legend>
                <div class="mb-3">
                    <label class="col-form-label" for="luname">' . __d('groupes', 'Liste des membres') . '</label>
                    <input type="text" class="form-control" id="luname" name="luname" maxlength="255" value="" required="required" />
                    <span class="help-block text-end"><span id="countcar_luname"></span></span>
                </div>
                <input type="hidden" name="op" value="membre_add_finish" />
                <input type="hidden" name="groupe_id" value="' . $gp . '" />
                <div class="mb-3">
                    <input class="btn btn-primary" type="submit" name="sub_op" value="' . __d('groupes', 'Sauver les modifications') . '" />
                </div>
            </fieldset>
        </form>';
    
        $arg1 = '
        var formulid = ["groupesaddmb"];
        inpandfieldlen("luname",255);
        ';
    
        echo (mysqli_get_client_info() <= '8.0') ?
            auto_complete_multi('membre', 'uname', 'users', 'luname', 'inner join users_status on users.uid=users_status.uid WHERE users.uid<>1 AND groupe NOT REGEXP \'[[:<:]]' . $gp . '[[:>:]]\'') :
            auto_complete_multi('membre', 'uname', 'users', 'luname', 'inner join users_status on users.uid=users_status.uid WHERE users.uid<>1 AND groupe NOT REGEXP \'\\b' . $gp . '\\b\'');
        
        adminfoot('fv', '', $arg1, '');
    }

}
