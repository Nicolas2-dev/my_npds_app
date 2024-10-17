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
    public function unsubUser()
    {
        $result = sql_query("SELECT uid FROM users WHERE uid='$chng_uid' OR uname='$chng_uid'");
        list($chng_uid) = sql_fetch_row($result);
    
        if ($chng_uid != 1) {
            sql_query("DELETE FROM subscribe WHERE uid='$chng_uid'");
    
            global $aid;
            Ecr_Log("security", "UnsubUser($chng_uid) by AID : $aid", "");
        }
    
        Header("Location: admin.php?op=mod_users");
    }

}
