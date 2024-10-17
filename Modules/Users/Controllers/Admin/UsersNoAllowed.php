<?php

namespace Modules\Users\Controllers\Admin;

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
     *  case 'nonallowed_users': => nonallowedUsers();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function nonallowedUsers()
    {
        $newsuti = sql_query("SELECT u.uid, u.uname, u.name, u.user_regdate FROM users AS u LEFT JOIN users_status AS us ON u.uid = us.uid WHERE us.open='0' ORDER BY u.user_regdate DESC");
        
        echo '
        <hr />
        <h3>' . __d('users', 'Utilisateur(s) en attente de validation') . '<span class="badge bg-secondary float-end">' . sql_num_rows($newsuti) . '</span></h3>
        <table class="table table-no-bordered table-sm " data-toggle="table" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa" data-show-columns="true">
            <thead>
                <tr>
                    <th data-halign="center" data-align="center" class="n-t-col-xs-1" ><i class="fa fa-user-o fa-lg me-1 align-middle"></i>ID</th>
                    <th data-halign="center" data-sortable="true">' . __d('users', 'Identifiant') . '</th>
                    <th data-halign="center" data-align="left" data-sortable="true">' . __d('users', 'Name') . '</th>
                    <th data-halign="center" data-align="right">' . __d('users', 'Date') . '</th>
                    <th data-halign="center" data-align="center" class="n-t-col-xs-2" >' . __d('users', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        while ($unallowed_users = sql_fetch_assoc($newsuti)) {
            echo '
                <tr class="table-danger">
                    <td>' . $unallowed_users['uid'] . '</td>
                    <td>' . $unallowed_users['uname'] . '</td>
                    <td>' . $unallowed_users['name'] . '</td>
                    <td>' . date('d/m/Y @ h:m', $unallowed_users['user_regdate']) . '</td>
                    <td>
                    <a class="me-3" href="admin.php?chng_uid=' . $unallowed_users['uid'] . '&amp;op=modifyUser#add_open_user" ><i class="fa fa-edit fa-lg" title="' . __d('users', 'Edit') . '" data-bs-toggle="tooltip"></i></a>
                    </td>
                </tr>';
        }
    
        echo '
            </body>
        </table>';
    
        Css::adminfoot('', '', '', '');
    }
    
}
