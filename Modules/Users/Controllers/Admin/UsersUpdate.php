<?php

namespace Modules\Users\Controllers\Admin;

use Modules\Npds\Core\AdminController;
use Modules\Users\Support\Facades\User;
use Modules\Npds\Support\Facades\Mailer;
use Modules\Users\Support\Traits\UserMinisiteTrait;


class Users extends AdminController
{

    use UserMinisiteTrait;

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
     * @param [type] $chng_uid
     * @param [type] $chng_uname
     * @param [type] $chng_name
     * @param [type] $chng_url
     * @param [type] $chng_email
     * @param [type] $chng_femail
     * @param [type] $chng_user_from
     * @param [type] $chng_user_occ
     * @param [type] $chng_user_intrest
     * @param [type] $chng_user_viewemail
     * @param [type] $chng_avatar
     * @param [type] $chng_user_sig
     * @param [type] $chng_bio
     * @param [type] $chng_pass
     * @param [type] $chng_pass2
     * @param [type] $level
     * @param [type] $open_user
     * @param [type] $chng_groupe
     * @param [type] $chng_send_email
     * @param [type] $chng_is_visible
     * @param [type] $chng_mns
     * @param [type] $C1
     * @param [type] $C2
     * @param [type] $C3
     * @param [type] $C4
     * @param [type] $C5
     * @param [type] $C6
     * @param [type] $C7
     * @param [type] $C8
     * @param [type] $M1
     * @param [type] $M2
     * @param [type] $T1
     * @param [type] $T2
     * @param [type] $B1
     * @param [type] $raz_avatar
     * @param [type] $chng_rank
     * @param [type] $chng_lnl
     * @return void
     */
    public function updateUser($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_user_sig, $chng_bio, $chng_pass, $chng_pass2, $level, $open_user, $chng_groupe, $chng_send_email, $chng_is_visible, $chng_mns, $C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1, $raz_avatar, $chng_rank, $chng_lnl)
    {
        if (isset($add_group)) {
            $add_group = implode(',', $add_group);
        } else {
            $add_group = '';
        }

        if (sql_num_rows(sql_query("SELECT uname FROM users WHERE uid!='$chng_uid' AND uname='$chng_uname'")) > 0) {

            echo User::error_handler(__d('users', 'ERREUR : cet identifiant est déjà utilisé') . '<br />');

            return;
        }
    
        $tmp = 0;
    
        if ($chng_pass2 != '') {
            if ($chng_pass != $chng_pass2) {

                echo User::error_handler(__d('users', 'Désolé, les nouveaux Mots de Passe ne correspondent pas. Cliquez sur retour et recommencez') . '<br />');

                return;
            }
            $tmp = 1;
        }

        if (Mailer::checkdnsmail($chng_email) === false) {
            echo User::error_handler(__d('users', 'Erreur : DNS ou serveur de mail incorrect') . '<br />');

            return;
        }
    
        $result = sql_query("SELECT mns FROM users WHERE uid='$chng_uid'");
        list($tmp_mns) = sql_fetch_row($result);
    
        if ($tmp_mns == 0 and $chng_mns == 1) {
            $this->Minisites($chng_mns, $chng_uname);
        }
    
        if ($chng_send_email == '') {
            $chng_send_email = '0';
        }
    
        if ($chng_is_visible == '') {
            $chng_is_visible = '1';
        } else {
            $chng_is_visible = '0';
        }
    
        if ($raz_avatar) {
            $chng_avatar = "blank.gif";
        }

        if ($tmp == 0) {
            sql_query("UPDATE users SET uname='$chng_uname', name='$chng_name', email='$chng_email', femail='$chng_femail', url='$chng_url', user_from='$chng_user_from', user_occ='$chng_user_occ', user_intrest='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_user_sig', bio='$chng_bio', send_email='$chng_send_email', is_visible='$chng_is_visible', mns='$chng_mns', user_lnl='$chng_lnl' WHERE uid='$chng_uid'");
        }

        if ($tmp == 1) {
            $AlgoCrypt  = PASSWORD_BCRYPT;
            $min_ms     = 100;
            $options    = ['cost' => getOptimalBcryptCostParameter($chng_pass, $AlgoCrypt, $min_ms)];
            $hashpass   = password_hash($chng_pass, $AlgoCrypt, $options);
            $cpass      = crypt($chng_pass, $hashpass);
    
            sql_query("UPDATE users SET uname='$chng_uname', name='$chng_name', email='$chng_email', femail='$chng_femail', url='$chng_url', user_from='$chng_user_from', user_occ='$chng_user_occ', user_intrest='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_user_sig', bio='$chng_bio', send_email='$chng_send_email', is_visible='$chng_is_visible', mns='$chng_mns', pass='$cpass', hashkey='1', user_lnl='$chng_lnl' WHERE uid='$chng_uid'");
        }
    
        if ($chng_user_viewemail) {
            $attach = 1;
        } else {
            $attach = 0;
        }
    
        if ($open_user == '') {
            $open_user = 0;
        }
    
        if (preg_match('#[a-zA-Z_]#', $chng_groupe)) {
            $chng_groupe = '';
        }
    
        if ($chng_groupe != '') {
            $tab_groupe = explode(',', $chng_groupe);
    
            if ($tab_groupe) {
                foreach ($tab_groupe as $groupevalue) {
                    if (($groupevalue == "0") and ($groupevalue != '')) {
                        $chng_groupe = '';
                    }
    
                    if ($groupevalue == "1") {
                        $chng_groupe = '';
                    }
    
                    if ($groupevalue > "127") {
                        $chng_groupe = '';
                    }
                }
            }
        }
    
        sql_query("UPDATE users_status SET attachsig='$attach', level='$level', open='$open_user', groupe='$chng_groupe', rang='$chng_rank' WHERE uid='$chng_uid'");
        sql_query("UPDATE users_extend SET C1='$C1', C2='$C2', C3='$C3', C4='$C4', C5='$C5', C6='$C6', C7='$C7', C8='$C8', M1='$M1', M2='$M2', T1='$T1', T2='$T2', B1='$B1' WHERE uid='$chng_uid'");
    
        $contents = '';
        
        $filename = "storage/users_private/usersbadmail.txt";

        $handle = fopen($filename, "r");
    
        if (filesize($filename) > 0) {
            $contents = fread($handle, filesize($filename));
        }
    
        fclose($handle);
    
        $re = '/#' . $chng_uid . '\|(\d+)/m';
        $maj = preg_replace($re, '', $contents);
    
        $file = fopen("storage/users_private/usersbadmail.txt", 'w');
        fwrite($file, $maj);
        fclose($file);
    
        global $aid;
        Ecr_Log('security', "UpdateUser($chng_uid, $chng_uname) by AID : $aid", '');
    
        global $referer;
        if ($referer != "memberslist.php") {
            Header("Location: admin.php?op=mod_users");
        } else {
            Header("Location: memberslist.php");
        }
    }

}
