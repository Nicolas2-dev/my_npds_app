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
    public function delUserConf()
    {
        $result = sql_query("SELECT uid, uname FROM users WHERE uid='$del_uid' or uname='$del_uid'");
        list($del_uid, $del_uname) = sql_fetch_row($result);

        if ($del_uid != 1) {
            sql_query("DELETE FROM users WHERE uid='$del_uid'");
            sql_query("DELETE FROM users_status WHERE uid='$del_uid'");
            sql_query("DELETE FROM users_extend WHERE uid='$del_uid'");
            sql_query("DELETE FROM subscribe WHERE uid='$del_uid'");

            //  Changer les articles et reviews pour les affecter à un pseudo utilisateurs  ( 0 comme uid et ' ' comme uname )
            sql_query("UPDATE stories SET informant=' ' WHERE informant='$del_uname'");
            sql_query("UPDATE reviews SET reviewer=' ' WHERE reviewer='$del_uname'");

            //include("modules/upload/upload.conf.php");

            if ($DOCUMENTROOT == '') {
                global $DOCUMENT_ROOT;

                if ($DOCUMENT_ROOT) {
                    $DOCUMENTROOT = $DOCUMENT_ROOT;
                } else {
                    $DOCUMENTROOT = $_SERVER['DOCUMENT_ROOT'];
                }
            }

            $user_dir = $DOCUMENTROOT . $racine . '/storage/users_private/' . $del_uname;

            // Supprimer son ministe s'il existe
            if (is_dir($user_dir . '/mns')) {
                $dir = opendir($user_dir . '/mns');
                
                while (false !== ($nom = readdir($dir))) {
                    if ($nom != '.' && $nom != '..' && $nom != '') {
                        @unlink($user_dir . '/mns/' . $nom);
                    }
                }

                closedir($dir);
                @rmdir($user_dir . '/mns');
            }

            // Mettre un fichier 'delete' dans sa home_directory si elle existe
            if (is_dir($user_dir)) {
                $fp = fopen($user_dir . '/delete', 'w');
                fclose($fp);
            }

            // Changer les posts, les commentaires, ... pour les affecter à un pseudo utilisateurs  ( 0 comme uid et ' ' comme uname)
            sql_query("UPDATE posts SET poster_id='0' WHERE poster_id='$del_uid'");

            // Met à jour les modérateurs des forums
            $pat = '#\b' . $del_uid . '\b#';

            $res = sql_query("SELECT forum_id, forum_moderator FROM forums");

            while ($row = sql_fetch_row($res)) {
                $tmp_moder = explode(',', $row[1]);

                if (preg_match($pat, $row[1])) {
                    unset($tmp_moder[array_search($del_uid, $tmp_moder)]);

                    sql_query("UPDATE forums SET forum_moderator='" . implode(',', $tmp_moder) . "' WHERE forum_id='$row[0]'");
                }
            }

            // Mise à jour du fichier badmailuser
            $contents = '';

            $filename = "storage/users_private/usersbadmail.txt";

            $handle = fopen($filename, "r");

            if (filesize($filename) > 0) {
                $contents = fread($handle, filesize($filename));
            }

            fclose($handle);

            $re = '/#' . $del_uid . '\|(\d+)/m';
            $maj = preg_replace($re, '', $contents);

            $file = fopen("storage/users_private/usersbadmail.txt", 'w');
            fwrite($file, $maj);
            fclose($file);

            global $aid;
            Ecr_Log('security', "DeleteUser($del_uid) by AID : $aid", '');
        }

        if ($referer != "memberslist.php") {
            Header("Location: admin.php?op=mod_users");
        } else {
            Header("Location: memberslist.php");
        }
    }

}
