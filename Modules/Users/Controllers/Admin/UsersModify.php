<?php

namespace Modules\Users\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Users\Support\Facades\User;


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
     * @param [type] $chng_user
     * @return void
     */
    public function modifyUser($chng_user)
    {
        $result = sql_query("SELECT uid, uname, name, url, email, femail, user_from, user_occ, user_intrest, user_viewemail, user_avatar, user_sig, bio, pass, send_email, is_visible, mns, user_lnl FROM users WHERE uid='$chng_user' OR uname='$chng_user'");
        
        if (sql_num_rows($result) > 0) {
            list($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_user_sig, $chng_bio, $chng_pass, $chng_send_email, $chng_is_visible, $mns, $user_lnl) = sql_fetch_row($result);
            
            echo '
            <hr />
            <h3>' . __d('users', 'Modifier un utilisateur') . ' : ' . $chng_uname . ' / ' . $chng_uid . '</h3>';
    
            $op = 'ModifyUser';
    
            $result = sql_query("SELECT level, open, groupe, attachsig, rang FROM users_status WHERE uid='$chng_uid'");
            list($chng_level, $open_user, $groupe, $attach, $chng_rank) = sql_fetch_row($result);
    
            $result = sql_query("SELECT C1, C2, C3, C4, C5, C6, C7, C8, M1, M2, T1, T2, B1 FROM users_extend WHERE uid='$chng_uid'");
            list($C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1) = sql_fetch_row($result);
    
            include("library/sform/extend-user/adm_extend-user.php");
        } else {
            User::error_handler("Utilisateur inexistant !" . "<br />");
        }
    
        Css::adminfoot('', '', '', '');
    }

}
