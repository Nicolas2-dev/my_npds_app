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
        
        if (basename($referer) != "memberslist.php")
            echo '<a class="btn btn-secondary mt-3" href="admin.php?op=mod_users">' . __d('users', 'Non') . '</a>';
        else
            echo '<a class="btn btn-secondary mt-3" href="memberslist.php">' . __d('users', 'Non') . '</a>';

        echo '</div>';
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

            include("modules/upload/upload.conf.php");

            if ($DOCUMENTROOT == '') {
                global $DOCUMENT_ROOT;
                if ($DOCUMENT_ROOT)
                    $DOCUMENTROOT = $DOCUMENT_ROOT;
                else
                    $DOCUMENTROOT = $_SERVER['DOCUMENT_ROOT'];
            }

            $user_dir = $DOCUMENTROOT . $racine . '/storage/users_private/' . $del_uname;

            // Supprimer son ministe s'il existe
            if (is_dir($user_dir . '/mns')) {
                $dir = opendir($user_dir . '/mns');
                while (false !== ($nom = readdir($dir))) {
                    if ($nom != '.' && $nom != '..' && $nom != '')
                        @unlink($user_dir . '/mns/' . $nom);
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
            if (filesize($filename) > 0)
                $contents = fread($handle, filesize($filename));

            fclose($handle);

            $re = '/#' . $del_uid . '\|(\d+)/m';
            $maj = preg_replace($re, '', $contents);

            $file = fopen("storage/users_private/usersbadmail.txt", 'w');
            fwrite($file, $maj);
            fclose($file);

            global $aid;
            Ecr_Log('security', "DeleteUser($del_uid) by AID : $aid", '');
        }

        if ($referer != "memberslist.php")
            Header("Location: admin.php?op=mod_users");
        else
            Header("Location: memberslist.php");
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function addUser()
    {
        if (sql_num_rows(sql_query("SELECT uname FROM users WHERE uname='$add_uname'")) > 0) {
            global $hlpfile;

            include("header.php");

            GraphicAdmin($hlpfile);
            adminhead($f_meta_nom, $f_titre);

            echo error_handler('<i class="fa fa-exclamation me-2"></i>' . __d('users', 'ERREUR : cet identifiant est déjà utilisé') . '<br />');
            
            adminfoot('', '', '', '');

            return;
        }
        if (!($add_uname && $add_email && $add_pass) or (preg_match('#[^a-zA-Z0-9_-]#', $add_uname))) {
            global $hlpfile;

            include("header.php");

            GraphicAdmin($hlpfile);
            adminhead($f_meta_nom, $f_titre);

            echo error_handler(__d('users', 'Vous devez remplir tous les Champs') . '<br />'); // ce message n'est pas très précis ..
            
            adminfoot('', '', '', '');

            return;
        }

        include_once('functions.php');

        if (checkdnsmail($add_email) === false) {
            global $hlpfile, $f_meta_nom, $f_titre;

            include("header.php");

            GraphicAdmin($hlpfile);
            adminhead($f_meta_nom, $f_titre);

            echo error_handler(__d('users', 'Erreur : DNS ou serveur de mail incorrect') . '<br />');
            
            adminfoot('', '', '', '');
            
            return;
        }

        $AlgoCrypt = PASSWORD_BCRYPT;
        $min_ms = 100;
        $options = ['cost' => getOptimalBcryptCostParameter($add_pass, $AlgoCrypt, $min_ms)];
        $hashpass = password_hash($add_pass, $AlgoCrypt, $options);
        $add_pass = crypt($add_pass, $hashpass);

        if ($add_is_visible == '')
            $add_is_visible = '1';
        else
            $add_is_visible = '0';

        $user_regdate = time() + ((int) Config::get('npds.gmt') * 3600);
        $sql = 'INSERT INTO users ';

        $sql .= "(uid,name,uname,email,femail,url,user_regdate,user_from,user_occ,user_intrest,user_viewemail,user_avatar,user_sig,bio,pass,hashkey,send_email,is_visible,mns,theme) ";
        $sql .= "VALUES (NULL,'$add_name','$add_uname','$add_email','$add_femail','$add_url','$user_regdate','$add_user_from','$add_user_occ','$add_user_intrest','$add_user_viewemail','$add_avatar','$add_user_sig','$add_bio','$add_pass','1','$add_send_email','$add_is_visible','$add_mns','Config::get('npds.Default_Theme')+Config::get('npds.Default_Skin')')";
        $result = sql_query($sql);
        list($usr_id) = sql_fetch_row(sql_query("SELECT uid FROM users WHERE uname='$add_uname'"));
        
        $result = sql_query("INSERT INTO users_extend VALUES ('$usr_id','$C1','$C2','$C3','$C4','$C5','$C6','$C7','$C8','$M1','$M2','$T1','$T2', '$B1')");
        
        if ($add_user_viewemail)
            $attach = 1;
        else
            $attach = 0;

        if (isset($add_group)) 
            $add_group = implode(',', $add_group);
        else 
            $add_group = '';

        $result = sql_query("INSERT INTO users_status VALUES ('$usr_id','0','$attach','$chng_rank','$add_level','1','$add_group')");

        Minisites($add_mns, $add_uname);

        global $aid;
        Ecr_Log('security', "AddUser($add_name, $add_uname) by AID : $aid", '');

        Header("Location: admin.php?op=mod_users");
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
    
        echo auto_complete('membre', 'uname', 'users', 'chng_uid', '86400');
    
        echo '<hr />
        <h3 class="mb-3">' . __d('users', 'Fonctions') . '</h3>
        <a href="admin.php?op=checkdnsmail_users">' . __d('users', 'Contrôler les serveurs de mail de tous les utilisateurs') . '</a><br />
        <a href="admin.php?op=checkdnsmail_users&amp;page=0&amp;end=1">' . __d('users', 'Serveurs de mail incorrects') . '</a><br />';
    
        adminfoot('', '', '', '');
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function extractUserCSV()
    {
        $MSos = get_os();

        if ($MSos) {
            $crlf = "\r\n";
        } else {
            $crlf = "\n";
        }
    
        $deliminator = ';';
        $line = "UID;UNAME;NAME;URL;EMAIL;FEMAIL;C1;C2;C3;C4;C5;C6;C7;C8;M1;M2;T1;T2" . $crlf;
    
        $result = sql_query("SELECT uid, uname, name, url, email, femail FROM users WHERE uid!='1' ORDER BY uid");
    
        while ($temp_user = sql_fetch_row($result)) {
            foreach ($temp_user as $val) {
    
                $val = str_replace("\r\n", "\n", $val);
    
                if (preg_match("#[$deliminator\"\n\r]#", $val)) {
                    $val = '"' . str_replace('"', '""', $val) . '"';
                }
    
                $line .= $val . $deliminator;
            }
    
            $result2 = sql_query("SELECT C1, C2, C3, C4, C5, C6, C7, C8, M1, M2, T1, T2 FROM users_extend WHERE uid='$temp_user[0]'");
            $temp_user2 = sql_fetch_row($result2);
    
            if ($temp_user2) {
                foreach ($temp_user2 as $val2) {
                    $val2 = str_replace("\r\n", "\n", $val2);
    
                    if (preg_match("#[$deliminator\"\n\r]#", $val2))
                        $val2 = '"' . str_replace('"', '""', $val2) . '"';
    
                    $line .= $val2 . $deliminator;
                }
            }
    
            $line = substr($line, 0, (strlen($deliminator) * -1));
            $line .= $crlf;
        }
    
        send_file($line, "annuaire", "csv", $MSos);
    
        global $aid;
        Ecr_Log('security', "ExtractUserCSV() by AID : $aid", '');
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
        } else
            error_handler("Utilisateur inexistant !" . "<br />");
    
        adminfoot('', '', '', '');
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $chng_mns
     * @param [type] $chng_uname
     * @return void
     */
    public function Minisites($chng_mns, $chng_uname)
    {
        // Création de la structure pour les MiniSites dans storage/users_private/$chng_uname
        if ($chng_mns) {
            include("modules/upload/upload.conf.php");
    
            if ($DOCUMENTROOT == '') {
                global $DOCUMENT_ROOT;
                $DOCUMENTROOT = ($DOCUMENT_ROOT) ? $DOCUMENT_ROOT : $_SERVER['DOCUMENT_ROOT'];
            }
    
            $user_dir = $DOCUMENTROOT . $racine . "/storage/users_private/" . $chng_uname;
            $repertoire = $user_dir . "/mns";
    
            if (!is_dir($user_dir)) {
                @umask("0000");
    
                if (@mkdir($user_dir, 0777)) {
                    $fp = fopen($user_dir . '/index.html', 'w');
                    fclose($fp);
                    @umask("0000");
    
                    if (@mkdir($repertoire, 0777)) {
                        $fp = fopen($repertoire . '/index.html', 'w');
                        fclose($fp);
    
                        $fp = fopen($repertoire . '/.htaccess', 'w');
                        @fputs($fp, 'Deny from All');
                        fclose($fp);
                    }
                }
            } else {
                @umask("0000");
    
                if (@mkdir($repertoire, 0777)) {
                    $fp = fopen($repertoire . '/index.html', 'w');
                    fclose($fp);
    
                    $fp = fopen($repertoire . '/.htaccess', 'w');
                    @fputs($fp, "Deny from All");
                    fclose($fp);
                }
            }
    
            // copie de la matrice par défaut
            $directory = $racine . '/modules/blog/matrice';
            $handle = opendir($DOCUMENTROOT . $directory);
    
            while (false !== ($file = readdir($handle))) 
                $filelist[] = $file;
    
            asort($filelist);
    
            foreach ($filelist as $key => $file) {
                if ($file <> '.' and $file <> '..')
                    @copy($DOCUMENTROOT . $directory . '/' . $file, $repertoire . '/' . $file);
            }
    
            closedir($handle);
    
            unset($filelist);
    
            global $aid;
            Ecr_Log('security', "CreateMiniSite($chng_uname) by AID : $aid", '');
        }
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
        settype($add_user_viewemail, 'integer');
        settype($add_is_visible, 'string');
        settype($add_mns, 'integer');
        settype($B1, 'string');
        settype($raz_avatar, 'integer');
        settype($add_send_email, 'integer');

        if (isset($add_group)) 
            $add_group = implode(',', $add_group);
        else 
            $add_group = '';

        if (sql_num_rows(sql_query("SELECT uname FROM users WHERE uid!='$chng_uid' AND uname='$chng_uname'")) > 0) {

            echo error_handler(__d('users', 'ERREUR : cet identifiant est déjà utilisé') . '<br />');
    
            adminfoot('', '', '', '');
    
            return;
        }
    
        $tmp = 0;
    
        if ($chng_pass2 != '') {
            if ($chng_pass != $chng_pass2) {

                echo error_handler(__d('users', 'Désolé, les nouveaux Mots de Passe ne correspondent pas. Cliquez sur retour et recommencez') . '<br />');
    
                adminfoot('', '', '', '');
    
                return;
            }
            $tmp = 1;
        }

        if (checkdnsmail($chng_email) === false) {
            echo error_handler(__d('users', 'Erreur : DNS ou serveur de mail incorrect') . '<br />');
    
            adminfoot('', '', '', '');
    
            return;
        }
    
        $result = sql_query("SELECT mns FROM users WHERE uid='$chng_uid'");
        list($tmp_mns) = sql_fetch_row($result);
    
        if ($tmp_mns == 0 and $chng_mns == 1)
            Minisites($chng_mns, $chng_uname);
    
        if ($chng_send_email == '') 
            $chng_send_email = '0';
    
        if ($chng_is_visible == '')
            $chng_is_visible = '1';
        else
            $chng_is_visible = '0';
    
        if ($raz_avatar) 
            $chng_avatar = "blank.gif";
    
        if ($tmp == 0)
            sql_query("UPDATE users SET uname='$chng_uname', name='$chng_name', email='$chng_email', femail='$chng_femail', url='$chng_url', user_from='$chng_user_from', user_occ='$chng_user_occ', user_intrest='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_user_sig', bio='$chng_bio', send_email='$chng_send_email', is_visible='$chng_is_visible', mns='$chng_mns', user_lnl='$chng_lnl' WHERE uid='$chng_uid'");
        
        if ($tmp == 1) {
            $AlgoCrypt = PASSWORD_BCRYPT;
            $min_ms = 100;
            $options = ['cost' => getOptimalBcryptCostParameter($chng_pass, $AlgoCrypt, $min_ms)];
            $hashpass = password_hash($chng_pass, $AlgoCrypt, $options);
            $cpass = crypt($chng_pass, $hashpass);
    
            sql_query("UPDATE users SET uname='$chng_uname', name='$chng_name', email='$chng_email', femail='$chng_femail', url='$chng_url', user_from='$chng_user_from', user_occ='$chng_user_occ', user_intrest='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_user_sig', bio='$chng_bio', send_email='$chng_send_email', is_visible='$chng_is_visible', mns='$chng_mns', pass='$cpass', hashkey='1', user_lnl='$chng_lnl' WHERE uid='$chng_uid'");
        }
    
        if ($chng_user_viewemail)
            $attach = 1;
        else
            $attach = 0;
    
        if ($open_user == '') 
            $open_user = 0;
    
        if (preg_match('#[a-zA-Z_]#', $chng_groupe)) 
            $chng_groupe = '';
    
        if ($chng_groupe != '') {
            $tab_groupe = explode(',', $chng_groupe);
    
            if ($tab_groupe) {
                foreach ($tab_groupe as $groupevalue) {
                    if (($groupevalue == "0") and ($groupevalue != '')) 
                        $chng_groupe = '';
    
                    if ($groupevalue == "1") 
                        $chng_groupe = '';
    
                    if ($groupevalue > "127") 
                        $chng_groupe = '';
                }
            }
        }
    
        sql_query("UPDATE users_status SET attachsig='$attach', level='$level', open='$open_user', groupe='$chng_groupe', rang='$chng_rank' WHERE uid='$chng_uid'");
        sql_query("UPDATE users_extend SET C1='$C1', C2='$C2', C3='$C3', C4='$C4', C5='$C5', C6='$C6', C7='$C7', C8='$C8', M1='$M1', M2='$M2', T1='$T1', T2='$T2', B1='$B1' WHERE uid='$chng_uid'");
    
        $contents = '';
        $filename = "storage/users_private/usersbadmail.txt";
        $handle = fopen($filename, "r");
    
        if (filesize($filename) > 0)
            $contents = fread($handle, filesize($filename));
    
        fclose($handle);
    
        $re = '/#' . $chng_uid . '\|(\d+)/m';
        $maj = preg_replace($re, '', $contents);
    
        $file = fopen("storage/users_private/usersbadmail.txt", 'w');
        fwrite($file, $maj);
        fclose($file);
    
        global $aid;
        Ecr_Log('security', "UpdateUser($chng_uid, $chng_uname) by AID : $aid", '');
    
        global $referer;
        if ($referer != "memberslist.php")
            Header("Location: admin.php?op=mod_users");
        else
            Header("Location: memberslist.php");
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
    
        adminfoot('', '', '', '');
    }
    
    /**
     * case 'checkdnsmail_users': =>checkdnsmailusers();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function checkdnsmailusers()
    {
        global $page, $end, $autocont;

        if (!isset($page)) 
            $page = 1;
    
        if (!isset($end)) 
            $end = 0;
    
        settype($end, 'integer');
    
        $pagesize = 40;
        $min = $pagesize * ($page - 1);
        $max = $pagesize;
        $next_page = $page + 1;
    
        $resource = sql_query("SELECT COUNT(uid) FROM users WHERE uid>1;");
        list($total) = sql_fetch_row($resource);
    
        settype($total, 'integer');
    
        if (($page * $pagesize) > $total) 
            $end = 1;
    
        $result = sql_query("SELECT uid, uname, email FROM users WHERE uid>1 ORDER BY uid LIMIT $min,$max;");
        $userchecked = sql_num_rows($result);
    
        $wrongdnsmail = 0;
        $arrayusers = array();
        $image = '18.png';
    
        $subject = __d('users', 'Votre adresse Email est incorrecte.');
        $time = date(__d('users', 'dateinternal'), time() + ((int) Config::get('npds.gmt') * 3600));
        $message = __d('users', 'Votre adresse Email est incorrecte.') . ' (' . __d('users', 'DNS ou serveur de mail incorrect') . ').<br />' . __d('users', 'Tous vos abonnements vers cette adresse Email ont été suspendus.') . '<br /><a href="user.php?op=edituser">' . __d('users', 'Merci de fournir une nouvelle adresse Email valide.') . ' <i class="fa fa-user fa-2x align-middle fa-fw"></i></a><br />' . __d('users', 'Sans réponse de votre part sous 60 jours vous ne pourrez plus vous connecter en tant que membre sur ce site.') . ' ' . __d('users', 'Puis votre compte pourra être supprimé.') . '<br /><br />' . __d('users', 'Contacter l\'administration du site.') . '<a href="mailto:' . Config::get('npds.adminmail') . '" target="_blank"><i class="fa fa-at fa-2x align-middle fa-fw"></i>';
        
        $output = '';
        $contents = '';
    
        $filename = "storage/users_private/usersbadmail.txt";
        $handle = fopen($filename, "r");
        if (filesize($filename) > 0)
            $contents = fread($handle, filesize($filename));
        fclose($handle);
    
        $datenvoi = '';
        $datelimit = '';
    
        while (list($uid, $uname, $email) = sql_fetch_row($result)) {
            if (checkdnsmail($email) === true and isbadmailuser($uid) === true) {
                $re = '/#' . $uid . '\|(\d+)/m';
                $maj = preg_replace($re, '', $contents);
                $file = fopen("storage/users_private/usersbadmail.txt", 'w');
    
                fwrite($file, $maj);
                fclose($file);
            }
    
            if (checkdnsmail($email) === false) {
                if (isbadmailuser($uid) === false) {
    
                    $arrayusers[] = '#' . $uid . '|' . time();
    
                    //suspension des souscriptions
                    sql_query("DELETE FROM subscribe WHERE uid='$uid'");
    
                    global $aid;
                    Ecr_Log("security", "UnsubUser($uid) by AID : $aid", "");
    
                    //suspension de l'envoi des mails pour PM suspension lnl
                    sql_query("UPDATE users SET send_email='0', user_lnl='0' WHERE uid='$uid'");
    
                    //envoi private message
                    $sql = "INSERT INTO priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) VALUES ('$image', '$subject', '1', '$uid', '$time', '<br /><code>$email</code><br /><br />$message');";
                    sql_query($sql);
    
                    $datenvoi = date('d/m/Y');
                    $datelimit = date('d/m/Y', time() + 5184000);
                }
    
                if (isbadmailuser($uid) === true) {
                    $re = '/#' . $uid . '\|(\d+)/m';
                    preg_match($re, $contents, $res);
    
                    $datenvoi = date('d/m/Y', $res[1]);
                    $datelimit = date('d/m/Y', $res[1] + 5184000);
                }
    
                $wrongdnsmail++;
                $output .= '<li>' . __d('users', 'DNS ou serveur de mail incorrect') . ' : <a class="alert-link" href="admin.php?chng_uid=' . $uid . '&amp;op=modifyUser">' . $uname . '</a><span class="float-end"><i class="far fa-envelope me-1 align-middle"></i><small>' . $datenvoi . '</small><i class="fa fa-ban mx-1 align-middle"></i><small>' . $datelimit . '</small></span></li>';
            }
        }
    
        $file = fopen("storage/users_private/usersbadmail.txt", 'a+');
        fwrite($file, implode('', $arrayusers));
        fclose($file);
    
        $ck = '';
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('users', 'Contrôle des serveurs de mails') . '</h3>
        <div class="alert alert-success lead">';
    
        if ($end != 1) {
            if (!isset($autocont)) 
                $autocont = 0;
    
            settype($autocont, 'integer');
    
            if ($autocont == 1) {
                $ck = 'checked="checked"';
            } else {
                $ck = '';
            }
    
            echo '
            <div>' . __d('users', 'Serveurs de mails contrôlés') . '<span class="badge bg-success float-end">' . ($page * $pagesize) . '</span><br /></div>
            <a class="btn btn-success btn-sm mt-2" href="admin.php?op=checkdnsmail_users&amp;page=' . $next_page . '&amp;end=' . $end . '">Continuer</a>
            <hr />
            <div class="text-end"><input id="controlauto" ' . $ck . ' type="checkbox" /></div>
            <script type="text/javascript">
            //<![CDATA[
                $(function () {
                    check = $("#controlauto").is(":checked");
                    if(check)
                    setTimeout(function(){ document.location.href="admin.php?op=checkdnsmail_users&page=' . $next_page . '&end=' . $end . '&autocont=1"; }, 3000);
                });
                $("#controlauto").on("click", function(){
                    check = $("#controlauto").is(":checked");
                    if(check)
                    setTimeout(function(){ document.location.href="admin.php?op=checkdnsmail_users&page=' . $next_page . '&end=' . $end . '&autocont=1"; }, 3000);
                    else
                    setTimeout(function(){ document.location.href="admin.php?op=checkdnsmail_users&page=' . $next_page . '&end=' . $end . '&autocont=0"; }, 3000);
                });
            //]]>
            </script>';
        } else
            echo __d('users', 'Serveurs de mails contrôlés') . '<span class="badge bg-success float-end">' . $total . '</span>';
    
        echo'</div>';
    
        if ($end != 1) {
            if ($wrongdnsmail > 0) {
                echo '
                <div class="alert alert-danger">
                    <p class="lead">' . __d('users', 'DNS ou serveur de mail incorrect') . '<span class="badge bg-danger float-end">' . $wrongdnsmail . '</span></p>
                    <hr />
                    ' . __d('users', 'Toutes les souscriptions de ces utilisateurs ont été suspendues.') . '<br />
                    ' . __d('users', 'Un message privé leur a été envoyé sans réponse à ce message sous 60 jours ces utilisateurs ne pourront plus se connecter au site.') . '<br /><br />
                    <ul>' . $output . '</ul>
                </div>';
            } else
                echo '<div class="alert alert-success">OK</div>';
        }
    
        if ($end == 1) {
            $re = '/#(\d+)\|(\d+)/m';
            preg_match_all($re, $contents, $matches);
    
            $u = $matches[1];
            $t = $matches[2];
            $nbu = count($u);
    
            $unames = array();
            $whereInParameters  = implode(',', $u); //
    
            $result = sql_query("SELECT uid, uname FROM users WHERE uid IN ($whereInParameters)");
    
            while ($names = sql_fetch_array($result)) {
                $unames[] = $names['uname'];
                $uids[] = $names['uid'];
            }
        
            echo '
            <div class="alert alert-danger">
                <div class="lead">' . __d('users', 'DNS ou serveur de mail incorrect') . ' <span class="badge bg-danger float-end">' . $nbu . '</span></div>';
    
            if ($nbu > 0) {
                echo '
                    <hr />' . __d('users', 'Toutes les souscriptions de ces utilisateurs ont été suspendues.') . '<br />
                ' . __d('users', 'Un message privé leur a été envoyé sans réponse à ce message sous 60 jours ces utilisateurs ne pourront plus se connecter au site.') . '<br /><br />
                <ul>';
    
                for ($row = 0; $row < $nbu; $row++) {
                    $dateenvoi = date('d/m/Y', $t[$row]);
                    $datelimit = date('d/m/Y', $t[$row] + 5184000);
    
                    echo '<li>' . __d('users', 'DNS ou serveur de mail incorrect') . ' <i class="fa fa-user-o me-1 "></i> : <a class="alert-link" href="admin.php?chng_uid=' . $uids[$row] . '&amp;op=modifyUser">' . $unames[$row] . '</a><span class="float-end"><i class="far fa-envelope me-1 align-middle"></i><small>' . $dateenvoi . '</small><i class="fa fa-ban mx-1 align-middle"></i><small>' . $datelimit . '</small></span></li>';
                }
    
                echo '</ul>';
            }
            echo '</div>';
        }
    
        adminfoot('', '', '', '');
    }

}
