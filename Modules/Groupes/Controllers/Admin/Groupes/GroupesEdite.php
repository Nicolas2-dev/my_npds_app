<?php

namespace Modules\Groupes\Controllers\Admin\Groupes;

use Modules\Npds\Core\AdminController;


class GroupesEdite extends AdminController
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
     * @param [type] $groupe_id
     * @return void
     */
    public function groupe_edit($groupe_id)
    {
        $result = sql_fetch_assoc(sql_query("SELECT groupe_name, groupe_description FROM groupes WHERE groupe_id='$groupe_id'"));
    
        if ($groupe_id != 'groupe_add')
            echo '
            <hr />
            <h3>' . __d('groupes', 'Modifier le groupe') . ' : ' . $groupe_id . '</h3>';
        else
            echo '
            <hr />
            <h3>' . __d('groupes', 'Créer un groupe.') . '</h3>';
    
        echo '
        <form class="admform" id="groupesaddmod" action="admin.php" method="post">
            <fieldset>
                <legend><i class="fas fa-users fa-2x text-muted"></i></legend>' . "\n";
    
        if ($groupe_id != 'groupe_add')
            echo '<input type="hidden" name="groupe_id" value="' . $groupe_id . '" />';
        else
            echo '
                <div class="mb-3">
                    <label for="inp_gr_id" class="admform">ID</label>
                    <input id="inp_gr_id" type="number" min="2" max="126" class="form-control" name="groupe_id" value="" required="required"/><span class="help-block">(2...126)</span>
                </div>';
    
        echo '
                <div class="mb-3">
                    <label class="col-form-label" for="grname">' . __d('groupes', 'Nom') . '</label>
                    <input type="text" class="form-control" id="grname" name="groupe_name" maxlength="1000" value="';
    
        echo isset($result) ? $result['groupe_name'] : '';
    
        echo '" placeholder="' . __d('groupes', 'Nom du groupe') . '" required="required" />
                    <span class="help-block text-end"><span id="countcar_grname"></span></span>
                </div>
                <div class="mb-3">
                    <label class="col-form-label" for="grdesc">' . __d('groupes', 'Description') . '</label>
                    <textarea class="form-control" name="groupe_description" id="grdesc" rows="11" placeholder="' . __d('groupes', 'Description du groupe') . '" required="required">';
            
        echo isset($result) ? $result['groupe_description'] : '';
    
        echo '</textarea>
                </div>';
    
        if ($groupe_id != 'groupe_add')
            echo '<input type="hidden" name="op" value="groupe_maj" />';
        else
            echo '<input type="hidden" name="op" value="groupe_add_finish" />';
    
        echo '
                <div class="mb-3">
                    <input class="btn btn-primary" type="submit" name="sub_op" value="' . __d('groupes', 'Sauver les modifications') . '" />
                </div>
            </fieldset>
        </form>';
    
        $arg1 = '
        var formulid = ["groupesaddmod"];
        inpandfieldlen("grname",1000);
        ';
    
        adminfoot('fv', '', $arg1, '');
    }

}
