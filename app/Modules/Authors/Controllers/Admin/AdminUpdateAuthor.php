<?php

namespace App\Modules\Authors\Controllers\Admin;

use Npds\Config\Config;
use App\Modules\Authors\Models\Author;
use App\Modules\Npds\Core\AdminController;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Npds\Support\Facades\Mailer;
use App\Modules\Npds\Support\Facades\Password;
use App\Modules\Authors\Support\Facades\Author as L_Author;

class AdminUpdateAuthor extends AdminController
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
    protected $hlpfile = "authors";

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
    protected $f_meta_nom = 'mod_authors';


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
        $this->f_titre = __d('authors', 'Administrateurs');

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
     * [updateadmin description]
     *
     * @param   [type]  $chng_aid          [$chng_aid description]
     * @param   [type]  $chng_name         [$chng_name description]
     * @param   [type]  $chng_email        [$chng_email description]
     * @param   [type]  $chng_url          [$chng_url description]
     * @param   [type]  $chng_radminsuper  [$chng_radminsuper description]
     * @param   [type]  $chng_pwd          [$chng_pwd description]
     * @param   [type]  $chng_pwd2         [$chng_pwd2 description]
     * @param   [type]  $ad_d_27           [$ad_d_27 description]
     * @param   [type]  $old_pwd           [$old_pwd description]
     *
     * @return  [type]                     [return description]
     */
    public function updateadmin($chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminsuper, $chng_pwd, $chng_pwd2, $ad_d_27, $old_pwd)
    {
        settype($chng_radminsuper, 'int');
        settype($ad_d_27, 'int');

        if (!($chng_aid && $chng_name && $chng_email))
            Header("Location: admin.php?op=mod_authors");
    
        if (Mailer::checkdnsmail($chng_email) === false) {
            echo L_Author::error_handler(__d('authors', 'ERREUR : DNS ou serveur de mail incorrect') . '<br />');
        }
    
        $result = sql_query("SELECT radminsuper FROM authors WHERE aid='$chng_aid'");
        list($ori_radminsuper) = sql_fetch_row($result);
    
        if (!$ori_radminsuper and $chng_radminsuper) {
            @copy("modules/f-manager/users/modele.admin.conf.php", "modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php");
            Author::deletedroits($chng_aid);
        }
    
        if ($ori_radminsuper and !$chng_radminsuper) {
            @unlink("modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php");
            Author::updatedroits($chng_aid);
        }
    
        if (file_exists("modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php") and $ad_d_27 != '27')
            @unlink("modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php");
    
        if (($chng_radminsuper or $ad_d_27 != '') and !file_exists("modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php")) {
            @copy("modules/f-manager/users/modele.admin.conf.php", "modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php");
        }
    
        if ($chng_pwd2 != '') {
            if ($chng_pwd != $chng_pwd2) {
                echo L_Author::error_handler(__d('authors', 'Désolé, les nouveaux Mots de Passe ne correspondent pas. Cliquez sur retour et recommencez') . '<br />');
            }
    
            $AlgoCrypt = PASSWORD_BCRYPT;
            $min_ms = 100;
            $options = ['cost' => Password::getOptimalBcryptCostParameter($chng_pwd, $AlgoCrypt, $min_ms)];
            $hashpass = password_hash($chng_pwd, $AlgoCrypt, $options);
            $chng_pwd = crypt($chng_pwd, $hashpass);
    
            if ($old_pwd) {
    
                $admin = Auth::chech('admin');

                $Xadmin = base64_decode($admin);
                $Xadmin = explode(':', $Xadmin);
                $aid    = urlencode($Xadmin[0]);

                $AIpwd = $Xadmin[1];
    
                if ($aid == $chng_aid) {
                    if (md5($old_pwd) == $AIpwd and $chng_pwd != '') {
                        $admin = base64_encode("$aid:" . md5($chng_pwd));
    
                        $admin_cook_duration = Config::get('npds.admin_cook_duration');

                        if ($admin_cook_duration <= 0) {
                            $admin_cook_duration = 1; 
                        }
    
                        $timeX = time() + (3600 * $admin_cook_duration);
    
                        Cookie::set('admin', $admin, $timeX);
                        Cookie::set('adm_exp', $timeX, $timeX);
                    }
                }
            }
    
            $result = $chng_radminsuper == 1 ?
                sql_query("UPDATE authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='$chng_radminsuper', pwd='$chng_pwd', hashkey='1' WHERE aid='$chng_aid'") :
                sql_query("UPDATE authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='0', pwd='$chng_pwd', hashkey='1' WHERE aid='$chng_aid'");
        } else {
            if ($chng_radminsuper == 1) {
                $result = sql_query("UPDATE authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='$chng_radminsuper' WHERE aid='$chng_aid'");
                
                Author::deletedroits($chng_aid);
            } else {
                $result = sql_query("UPDATE authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='0' WHERE aid='$chng_aid'");
                
                Author::deletedroits($chng_aid);
                Author::updatedroits($chng_aid);
            }
        }
    
        global $aid;
        Ecr_Log('security', "ModifyAuthor($chng_name) by AID : $aid", '');
    
        Header("Location: admin.php?op=mod_authors");
    }
    
}
