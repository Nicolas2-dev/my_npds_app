<?php

namespace Modules\Users\Controllers\Admin;

use Npds\Config\Config;
use Modules\Npds\Core\AdminController;
use Modules\Users\Support\Facades\User;
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
     * @return void
     */
    public function addUser()
    {
        if (sql_num_rows(sql_query("SELECT uname FROM users WHERE uname='$add_uname'")) > 0) {

            echo User::error_handler('<i class="fa fa-exclamation me-2"></i>' . __d('users', 'ERREUR : cet identifiant est déjà utilisé') . '<br />');

            return;
        }
        if (!($add_uname && $add_email && $add_pass) or (preg_match('#[^a-zA-Z0-9_-]#', $add_uname))) {

            echo User::error_handler(__d('users', 'Vous devez remplir tous les Champs') . '<br />'); // ce message n'est pas très précis ..

            return;
        }

        if (Mailer::checkdnsmail($add_email) === false) {

            echo error_handler(__d('users', 'Erreur : DNS ou serveur de mail incorrect') . '<br />');
    
            return;
        }

        $AlgoCrypt  = PASSWORD_BCRYPT;
        $min_ms     = 100;
        $options    = ['cost' => getOptimalBcryptCostParameter($add_pass, $AlgoCrypt, $min_ms)];
        $hashpass   = password_hash($add_pass, $AlgoCrypt, $options);
        $add_pass   = crypt($add_pass, $hashpass);

        if ($add_is_visible == '') {
            $add_is_visible = '1';
        } else {
            $add_is_visible = '0';
        }

        $user_regdate = time() + ((int) Config::get('npds.gmt') * 3600);
        $sql = 'INSERT INTO users ';

        $sql .= "(uid,name,uname,email,femail,url,user_regdate,user_from,user_occ,user_intrest,user_viewemail,user_avatar,user_sig,bio,pass,hashkey,send_email,is_visible,mns,theme) ";
        $sql .= "VALUES (NULL,'$add_name','$add_uname','$add_email','$add_femail','$add_url','$user_regdate','$add_user_from','$add_user_occ','$add_user_intrest','$add_user_viewemail','$add_avatar','$add_user_sig','$add_bio','$add_pass','1','$add_send_email','$add_is_visible','$add_mns','Config::get('npds.Default_Theme')+Config::get('npds.Default_Skin')')";
        
        sql_query($sql);
        
        list($usr_id) = sql_fetch_row(sql_query("SELECT uid FROM users WHERE uname='$add_uname'"));
        
        sql_query("INSERT INTO users_extend VALUES ('$usr_id','$C1','$C2','$C3','$C4','$C5','$C6','$C7','$C8','$M1','$M2','$T1','$T2', '$B1')");
        
        if ($add_user_viewemail) {
            $attach = 1;
        } else {
            $attach = 0;
        }

        if (isset($add_group)) {
            $add_group = implode(',', $add_group);
        } else {
            $add_group = '';
        }

        sql_query("INSERT INTO users_status VALUES ('$usr_id','0','$attach','$chng_rank','$add_level','1','$add_group')");

        $this->Minisites($add_mns, $add_uname);

        global $aid;
        Ecr_Log('security', "AddUser($add_name, $add_uname) by AID : $aid", '');

        Header("Location: admin.php?op=mod_users");
    }

}
