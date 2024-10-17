<?php

namespace Modules\Users\Controllers\Admin;

use Modules\Npds\Support\Facades\Js;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;


class Users extends AdminController
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
    protected $hlpfile = 'users';

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
    protected $f_meta_nom = 'mod_users';


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
        $this->f_titre = __d('users', 'Edition des Utilisateurs');

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
     * case 'mod_users': => displayUsers();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function displayUsers()
    {
        echo '
        <hr />
            <h3>' . __d('users', 'Extraire l\'annuaire') . '</h3>
            <form method="post" class="form-inline" action="admin.php">
                    <div class="mb-3">
                        <label class="me-2 mt-sm-3" for="op">' . __d('users', 'Format de fichier') . '</label>
                        <select class="form-select me-2 mt-sm-3" name="op">
                            <option value="extractUserCSV">' . __d('users', 'Au format CSV') . '</option>
                        </select>
                    </div>
                    <button class="btn btn-primary ms-2 mt-3" type="submit">' . __d('users', 'Ok') . ' </button>
            </form>
            <hr />
            <h3>' . __d('users', 'Rechercher utilisateur') . '</h3>
            <form method="post" class="form-inline" action="admin.php">
            <label class="me-2 mt-sm-1" for="chng_uid">' . __d('users', 'Identifiant Utilisateur') . '</label>
            <input class="form-control me-2 mt-sm-3 mb-2" type="text" id="chng_uid" name="chng_uid" size="20" maxlength="10" />
            <select class="form-select me-2 mt-sm-3 mb-2" name="op">
                <option value="modifyUser">' . __d('users', 'Modifier un utilisateur') . '</option>
                <option value="unsubUser">' . __d('users', 'Désabonner un utilisateur') . '</option>
                <option value="delUser">' . __d('users', 'Supprimer un utilisateur') . '</option>
            </select>
            <button class="btn btn-primary ms-sm-2 mt-sm-3 mb-2" type="submit" >' . __d('users', 'Ok') . ' </button>
            </form>';
    
        $chng_is_visible = 1;
    
        echo '
        <hr />
        <h3>' . __d('users', 'Créer utilisateur') . '</h3>';
    
        $op = 'displayUsers';
    
        include("library/sform/extend-user/adm_extend-user.php");
    
        echo Js::auto_complete('membre', 'uname', 'users', 'chng_uid', '86400');
    
        echo '<hr />
        <h3 class="mb-3">' . __d('users', 'Fonctions') . '</h3>
        <a href="admin.php?op=checkdnsmail_users">' . __d('users', 'Contrôler les serveurs de mail de tous les utilisateurs') . '</a><br />
        <a href="admin.php?op=checkdnsmail_users&amp;page=0&amp;end=1">' . __d('users', 'Serveurs de mail incorrects') . '</a><br />';
    
        Css::adminfoot('', '', '', '');
    }

}
