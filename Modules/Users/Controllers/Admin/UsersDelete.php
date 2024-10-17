<?php

namespace Modules\Users\Controllers\Admin;

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
     * Undocumented function
     *
     * @return void
     */
    public function delUser()
    {
        echo '
        <h3 class="text-danger mb-3">' . __d('users', 'Supprimer un utilisateur') . '</h3>
        <div class="alert alert-danger lead">' . __d('users', 'Etes-vous sûr de vouloir effacer') . ' ' . __d('users', 'Utilisateur') . ' <strong>' . $chng_uid . '</strong> ? <br />
            <a class="btn btn-danger mt-3" href="admin.php?op=delUserConf&amp;del_uid=' . $chng_uid . '&amp;referer=' . basename($referer) . '">' . __d('users', 'Oui') . '</a>';
        
        if (basename($referer) != "memberslist.php") {
            echo '<a class="btn btn-secondary mt-3" href="admin.php?op=mod_users">' . __d('users', 'Non') . '</a>';
        } else {
            echo '<a class="btn btn-secondary mt-3" href="memberslist.php">' . __d('users', 'Non') . '</a>';
        }
        
        echo '</div>';
    }

}
