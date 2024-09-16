<?php

namespace App\Modules\Users\Library;

use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Users\Contracts\UserInterface;


class UserManager implements UserInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * [getusrinfo description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  [type]         [return description]
     */
    public function getusrinfo($user)
    {
        

        $cookie = explode(':', base64_decode($user));

        $result = sql_query("SELECT pass 
                            FROM users 
                            WHERE uname='$cookie[1]'");

        list($pass) = sql_fetch_row($result);

        $userinfo = '';

        if (($cookie[2] == md5($pass)) and ($pass != '')) {
            $result = sql_query("SELECT id, name, uname, email, femail, url, user_avatar, user_occ, user_from, user_intrest, user_sig, user_viewemail, user_theme, pass, storynum, umode, uorder, thold, noscore, bio, ublockon, ublock, theme, commentmax, user_journal, send_email, is_visible, mns, user_lnl 
                                FROM users 
                                WHERE uname='$cookie[1]'");
            
            if (sql_num_rows($result) == 1) {
                $userinfo = sql_fetch_assoc($result);
            } else {
                echo '<strong>' . translate("Un problème est survenu") . '.</strong>';
            }
        }

        return $userinfo;
    }

    /**
     * [AutoReg description]
     *
     * @return  [type]  [return description]
     */
    public function AutoReg()
    {
        global $user;

        if (!Config::get('npds.AutoRegUser')) {
            if (isset($user)) {
                $cookie = explode(':', base64_decode($user));

                list($test) = sql_fetch_row(sql_query("SELECT open 
                                                    FROM users_status 
                                                    WHERE id='$cookie[0]'"));

                if (!$test) {
                    setcookie('user', '', 0);

                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    
    /**
     * [get_moderator description]
     *
     * @param   [type]  $user_id  [$user_id description]
     *
     * @return  [type]            [return description]
     */
    public function get_moderator($user_id)
    {
        
    
        $user_id = str_replace(",", "' or id='", $user_id);
    
        if ($user_id == 0)
            return ("None");
    
        $rowQ1 = Q_Select("SELECT uname FROM users WHERE id='$user_id'", 3600);
        $modslist = '';
    
        foreach ($rowQ1 as $modnames) {
            foreach ($modnames as $modname) {
                $modslist .= $modname . ' ';
            }
        }
    
        return (chop($modslist));
    }
    
    /**
     * [user_is_moderator description]
     *
     * @param   [type]  $uidX           [$uidX description]
     * @param   [type]  $passwordX      [$passwordX description]
     * @param   [type]  $forum_accessX  [$forum_accessX description]
     *
     * @return  [type]                  [return description]
     */
    public function user_is_moderator($uidX, $passwordX, $forum_accessX)
    {
        
    
        $result1 = sql_query("SELECT pass FROM users WHERE id = '$uidX'");
        $result2 = sql_query("SELECT level FROM users_status WHERE user_id = '$uidX'");
    
        $userX = sql_fetch_assoc($result1);
    
        $password = $userX['pass'];
    
        $userX = sql_fetch_assoc($result2);
    
        if ((md5($password) == $passwordX) and ($forum_accessX <= $userX['level']) and ($userX['level'] > 1))
            return ($userX['level']);
        else
            return (false);
    }
    
    /**
     * [get_userdata_from_id description]
     *
     * @param   [type]  $userid  [$userid description]
     *
     * @return  [type]           [return description]
     */
    public function get_userdata_from_id($userid)
    {
        
    
        $sql1 = "SELECT * FROM users WHERE id='$userid'";
        $sql2 = "SELECT * FROM users_status WHERE user_id='$userid'";
    
        if (!$result = sql_query($sql1))
            forumerror('0016');
    
        if (!$myrow = sql_fetch_assoc($result))
            $myrow = array("id" => 1);
        else
            $myrow = array_merge($myrow, (array)sql_fetch_assoc(sql_query($sql2)));
    
        return ($myrow);
    }
    
    /**
     * [get_userdata_extend_from_id description]
     *
     * @param   [type]  $userid  [$userid description]
     *
     * @return  [type]           [return description]
     */
    public function get_userdata_extend_from_id($userid)
    {
        
    
        $sql1 = "SELECT * FROM users_extend WHERE id='$userid'";
        /*   $sql2 = "SELECT * FROM users_status WHERE user_id='$userid'";
    
        if (!$result = sql_query($sql1))  
            forumerror('0016');
    
        if (!$myrow = sql_fetch_assoc($result))
            $myrow = array( "id" => 1);
        else
            $myrow=array_merge($myrow,(array)sql_fetch_assoc(sql_query($sql1)));
        */
    
        $myrow = (array)sql_fetch_assoc(sql_query($sql1));
    
        return ($myrow);
    }
    
    /**
     * [get_userdata description]
     *
     * @param   [type]  $username  [$username description]
     *
     * @return  [type]             [return description]
     */
    public function get_userdata($username)
    {
        
    
        $sql = "SELECT * FROM users WHERE uname='$username'";
    
        if (!$result = sql_query($sql))
            forumerror('0016');
    
        if (!$myrow = sql_fetch_assoc($result))
            $myrow = array("id" => 1);
    
        return ($myrow);
    }

    function message_error($ibid, $op)
    {
        include("header.php");
    
        echo '
        <h2>' . translate("Utilisateur") . '</h2>
        <div class="alert alert-danger lead">';
    
        echo $ibid;
    
        if (($op == 'only_newuser') or ($op == 'new user') or ($op == 'finish')) {
            hidden_form();
            echo '
                <input type="hidden" name="op" value="only_newuser" />
                <button class="btn btn-secondary mt-2" type="submit">' . translate("Retour en arrière") . '</button>
            </form>';
        } else
            echo '<a class="btn btn-secondary mt-4" href="javascript:history.go(-1)" title="' . translate("Retour en arrière") . '">' . translate("Retour en arrière") . '</a>';
        
        echo '
        </div>';
    
        include("footer.php");
    }
    
    function message_pass($ibid)
    {
        include("header.php");
        echo $ibid;
        include("footer.php");
    }
    
    function userCheck($uname, $email)
    {
        
    
        include_once('functions.php');
    
        $stop = '';
    
        if ((!$email) || ($email == '') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $email)))
            $stop = '<i class="fa fa-exclamation me-2"></i>' . translate("Erreur : Email invalide");
    
        if (strrpos($email, ' ') > 0)
            $stop = '<i class="fa fa-exclamation me-2"></i>' . translate("Erreur : une adresse Email ne peut pas contenir d'espaces");
    
        if (checkdnsmail($email) === false)
            $stop = translate("Erreur : DNS ou serveur de mail incorrect") . '!<br />';
    
        if ((!$uname) || ($uname == '') || (preg_match('#[^a-zA-Z0-9_-]#', $uname)))
            $stop = '<i class="fa fa-exclamation me-2"></i>' . translate("Erreur : identifiant invalide");
    
        if (strlen($uname) > 25)
            $stop = '<i class="fa fa-exclamation me-2"></i>' . translate("Votre surnom est trop long. Il doit faire moins de 25 caractères.");
    
        if (preg_match('#^(root|adm|linux|webmaster|admin|god|administrator|administrador|nobody|anonymous|anonimo|an€nimo|operator|dune|netadm)$#i', $uname))
            $stop = '<i class="fa fa-exclamation me-2"></i>' . translate("Erreur : nom existant.");
    
        if (strrpos($uname, ' ') > 0)
            $stop = '<i class="fa fa-exclamation me-2"></i>' . translate("Il ne peut pas y avoir d'espace dans le surnom.");
    
        if (sql_num_rows(sql_query("SELECT uname FROM users WHERE uname='$uname'")) > 0)
            $stop = '<i class="fa fa-exclamation me-2"></i>' . translate("Erreur : cet identifiant est déjà utilisé");
    
        if ($uname != 'edituser')
            if (sql_num_rows(sql_query("SELECT email FROM users WHERE email='$email'")) > 0)
                $stop = '<i class="fa fa-exclamation me-2"></i>' . translate("Erreur : adresse Email déjà utilisée");
    
        return ($stop);
    }
    
    function makePass()
    {
        $makepass = '';
    
        $syllables = 'Er@1,In@2,Ti#a3,D#un4,F_e5,P_re6,V!et7,J!o8,Ne%s9,A%l0,L*en1,So*n2,Ch$a3,I$r4,L^er5,Bo^6,Ok@7,!Tio8,N@ar9,0Sim,1P$le,2B*la,3Te!n,4T~oe,5Ch~o,6Co,7Lat,8Spe,9Ak,0Er,1Po,2Co,3Lor,4Pen,5Cil!,6Li!,7Ght,8_Wh,9_At,T#he0,#He1,@Ck2,Is@3,M1am@,B2o+,3No@,Fi-4,0Ve!,A9ny#,Wa7y$,P8ol%,Iti^6,Cs~5,Ra*,@Dio,+Sou,-Rce,!Sea,#Rch,$Pa,&Per,^Com,~Bo,*Sp,Eak1*,S2t~,Fi^,R3st&,Gr#,O5up@,!Boy,Ea!,Gle*,4Tr*,+A1il,B0i+,_Bl9e,Br8b_,P7ri#,De6e!,$Ka3y,1En$,2Be-,4Se-';
        $syllable_array = explode(',', $syllables);
        
        srand((float)microtime() * 1000000);
        
        for ($count = 1; $count <= 4; $count++) {
            if (rand() % 10 == 1)
                $makepass .= sprintf("%0.0f", (rand() % 50) + 1);
            else
                $makepass .= sprintf("%s", $syllable_array[rand() % 62]);
        }
    
        return ($makepass);
    }
    
    function showimage()
    {
        echo "
        <script type=\"text/javascript\">
        //<![CDATA[
        function showimage() {
        if (!document.images)
            return
            document.images.avatar.src=\n";
    
        if ($ibid = theme_image("forum/avatar/blank.gif"))
            $imgtmp = substr($ibid, 0, strrpos($ibid, "/") + 1);
        else
            $imgtmp = "assets/images/forum/avatar/";
    
        echo "'$imgtmp' + document.Register.user_avatar.options[document.Register.user_avatar.selectedIndex].value\n";
    
        echo "}
        //]]>
        </script>";
    }

    function hidden_form()
    {
        global $uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $vpass, $C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1, $charte, $user_lnl;
        
        if (!$user_avatar) {
            $user_avatar = "blank.gif";
        }
    
        echo '
        <form action="user.php" method="post">
            <input type="hidden" name="uname" value="' . $uname . '" />
            <input type="hidden" name="name" value="' . removeHack($name) . '" />
            <input type="hidden" name="email" value="' . $email . '" />
            <input type="hidden" name="user_avatar" value="' . $user_avatar . '" />
            <input type="hidden" name="user_from" value="' . StripSlashes(removeHack($user_from)) . '" />
            <input type="hidden" name="user_occ" value="' . StripSlashes(removeHack($user_occ)) . '" />
            <input type="hidden" name="user_intrest" value="' . StripSlashes(removeHack($user_intrest)) . '" />
            <input type="hidden" name="user_sig" value="' . StripSlashes(removeHack($user_sig)) . '" />
            <input type="hidden" name="user_viewemail" value="' . $user_viewemail . '" />
            <input type="hidden" name="pass" value="' . removeHack($pass) . '" />
            <input type="hidden" name="user_lnl" value="' . removeHack($user_lnl) . '" />
            <input type="hidden" name="C1" value="' . StripSlashes(removeHack($C1)) . '" />
            <input type="hidden" name="C2" value="' . StripSlashes(removeHack($C2)) . '" />
            <input type="hidden" name="C3" value="' . StripSlashes(removeHack($C3)) . '" />
            <input type="hidden" name="C4" value="' . StripSlashes(removeHack($C4)) . '" />
            <input type="hidden" name="C5" value="' . StripSlashes(removeHack($C5)) . '" />
            <input type="hidden" name="C6" value="' . StripSlashes(removeHack($C6)) . '" />
            <input type="hidden" name="C7" value="' . StripSlashes(removeHack($C7)) . '" />
            <input type="hidden" name="C8" value="' . StripSlashes(removeHack($C8)) . '" />
            <input type="hidden" name="M1" value="' . StripSlashes(removeHack($M1)) . '" />
            <input type="hidden" name="M2" value="' . StripSlashes(removeHack($M2)) . '" />
            <input type="hidden" name="T1" value="' . StripSlashes(removeHack($T1)) . '" />
            <input type="hidden" name="T2" value="' . StripSlashes(removeHack($T2)) . '" />
            <input type="hidden" name="B1" value="' . StripSlashes(removeHack($B1)) . '" />';
    }

    /**
     * [member_menu description]
     *
     * @param   [type]  $mns  [$mns description]
     * @param   [type]  $qui  [$qui description]
     *
     * @return  [type]        [return description]
     */
    public function member_menu($mns, $qui)
    {
        global $op;

        $ed_u = $op == 'edituser' ? 'active' : '';
        $cl_edj = $op == 'editjournal' ? 'active' : '';
        $cl_edh = $op == 'edithome' ? 'active' : '';
        $cl_cht = $op == 'chgtheme' ? 'active' : '';
        $cl_edjh = ($op == 'editjournal' or $op == 'edithome') ? 'active' : '';
        $cl_u = $_SERVER['REQUEST_URI'] == '/user' ? 'active' : '';
        $cl_pm = strstr($_SERVER['REQUEST_URI'], '/viewpmsg') ? 'active' : '';
        $cl_rs = ($_SERVER['QUERY_STRING'] == 'ModPath=reseaux-sociaux&ModStart=reseaux-sociaux' or $_SERVER['QUERY_STRING'] == 'ModPath=reseaux-sociaux&ModStart=reseaux-sociaux&op=EditReseaux') ? 'active' : '';
        
        $menu = '
        <ul class="nav nav-tabs d-flex flex-wrap"> 
            <li class="nav-item"><a class="nav-link ' . $cl_u . '" href="user.php" title="' . translate("Votre compte") . '" data-bs-toggle="tooltip" ><i class="fas fa-user fa-2x d-xl-none"></i><span class="d-none d-xl-inline"><i class="fas fa-user fa-lg"></i></span></a></li>
            <li class="nav-item"><a class="nav-link ' . $ed_u . '" href="user.php?op=edituser" title="' . translate("Vous") . '" data-bs-toggle="tooltip" ><i class="fas fa-user-edit fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;' . translate("Vous") . '</span></a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle tooltipbyclass ' . $cl_edjh . '" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" data-bs-html="true" title="' . translate("Editer votre journal") . '<br />' . translate("Editer votre page principale") . '"><i class="fas fa-edit fa-2x d-xl-none me-2"></i><span class="d-none d-xl-inline">Editer</span></a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item ' . $cl_edj . '" href="user.php?op=editjournal" title="' . translate("Editer votre journal") . '" data-bs-toggle="tooltip">' . translate("Journal") . '</a></li>
                    <li><a class="dropdown-item ' . $cl_edh . '" href="user.php?op=edithome" title="' . translate("Editer votre page principale") . '" data-bs-toggle="tooltip">' . translate("Page") . '</a></li>
                </ul>
            </li>';
    
        // include("modules/upload/config/upload.conf.php");
    
        // if (($mns) and ($autorise_upload_p)) {
        //     include_once("modules/blog/upload_minisite.php");
    
        //     $PopUp = win_upload("popup");
    
        //     $menu .= '
        //     <li class="nav-item dropdown">
        //         <a class="nav-link dropdown-toggle tooltipbyclass" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" title="' . translate("Gérer votre miniSite") . '"><i class="fas fa-desktop fa-2x d-xl-none me-2"></i><span class="d-none d-xl-inline">' . translate("MiniSite") . '</span></a>
        //         <ul class="dropdown-menu">
        //             <li><a class="dropdown-item" href="minisite.php?op=' . $qui . '" target="_blank">' . translate("MiniSite") . '</a></li>
        //             <li><a class="dropdown-item" href="javascript:void(0);" onclick="window.open(' . $PopUp . ')" >' . translate("Gérer votre miniSite") . '</a></li>
        //         </ul>
        //     </li>';
        // }
    
        $menu .= '
            <li class="nav-item"><a class="nav-link ' . $cl_cht . '" href="user.php?op=chgtheme" title="' . translate("Changer le thème") . '"  data-bs-toggle="tooltip" ><i class="fas fa-paint-brush fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;' . translate("Thème") . '</span></a></li>
            <li class="nav-item"><a class="nav-link ' . $cl_rs . '" href="modules.php?ModPath=reseaux-sociaux&amp;ModStart=reseaux-sociaux" title="' . translate("Réseaux sociaux") . '"  data-bs-toggle="tooltip" ><i class="fas fa-share-alt-square fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;' . translate("Réseaux sociaux") . '</span></a></li>
            <li class="nav-item"><a class="nav-link ' . $cl_pm . '" href="viewpmsg.php" title="' . translate("Message personnel") . '"  data-bs-toggle="tooltip" ><i class="far fa-envelope fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;' . translate("Message") . '</span></a></li>
            <li class="nav-item"><a class="nav-link " href="user.php?op=logout" title="' . translate("Déconnexion") . '" data-bs-toggle="tooltip" ><i class="fas fa-sign-out-alt fa-2x text-danger d-xl-none"></i><span class="d-none d-xl-inline text-danger">&nbsp;' . translate("Déconnexion") . '</span></a></li>
        </ul>
        <div class="mt-3"></div>';

        return $menu;
    }

    #autodoc userpopover($who, $dim, $avpop) : à partir du nom de l'utilisateur ($who) $avpop à 1 : affiche son avatar (ou avatar defaut) au dimension ($dim qui défini la class n-ava-$dim)<br /> $avpop à 2 : l'avatar affiché commande un popover contenant diverses info de cet utilisateur et liens associés
    public function userpopover($who, $dim, $avpop)
    {
        global $user;
    
        $result = sql_query("SELECT uname FROM users WHERE uname ='$who'");
    
        if (sql_num_rows($result)) {
    
            $temp_user = get_userdata($who);
    
            $socialnetworks = array();
            $posterdata_extend = array();
            $res_id = array();
    
            $my_rs = '';
    
            if (!Config::get('npds.short_user')) {
                if ($temp_user['uid'] != 1) {
    
                    $posterdata_extend = get_userdata_extend_from_id($temp_user['uid']);
    
                    include('modules/reseaux-sociaux/config/reseaux-sociaux.conf.php');
                    include('modules/geoloc/config/geoloc.conf');
    
                    if ($user or autorisation(-127)) {
                        if ($posterdata_extend['M2'] != '') {
    
                            $socialnetworks = explode(';', $posterdata_extend['M2']);
    
                            foreach ($socialnetworks as $socialnetwork) {
                                $res_id[] = explode('|', $socialnetwork);
                            }
    
                            sort($res_id);
                            sort($rs);
    
                            foreach ($rs as $v1) {
                                foreach ($res_id as $y1) {
                                    $k = array_search($y1[0], $v1);
    
                                    if (false !== $k) {
                                        $my_rs .= '<a class="me-2 " href="';
    
                                        if ($v1[2] == 'skype') {
                                            $my_rs .= $v1[1] . $y1[1] . '?chat';
                                        } else {
                                            $my_rs .= $v1[1] . $y1[1];
                                        }
    
                                        $my_rs .= '" target="_blank"><i class="fab fa-' . $v1[2] . ' fa-lg fa-fw mb-2"></i></a> ';
                                        break;
                                    } else {
                                        $my_rs .= '';
                                    }
                                }
                            }
                        }
                    }
                }
            }
    
            settype($ch_lat, 'string');
    
            $useroutils = '';
    
            if ($user or autorisation(-127)) {
                if ($temp_user['uid'] != 1 and $temp_user['uid'] != '') {
                    $useroutils .= '<li><a class="dropdown-item text-center text-md-start" href="user.php?op=userinfo&amp;uname=' . $temp_user['uname'] . '" target="_blank" title="' . translate("Profil") . '" ><i class="fa fa-lg fa-user align-middle fa-fw"></i><span class="ms-2 d-none d-md-inline">' . translate("Profil") . '</span></a></li>';
                }

                if ($temp_user['uid'] != 1 and $temp_user['uid'] != '') {
                    $useroutils .= '<li><a class="dropdown-item text-center text-md-start" href="powerpack.php?op=instant_message&amp;to_userid=' . urlencode($temp_user['uname']) . '" title="' . translate("Envoyer un message interne") . '" ><i class="far fa-lg fa-envelope align-middle fa-fw"></i><span class="ms-2 d-none d-md-inline">' . translate("Message") . '</span></a></li>';
                }

                if ($temp_user['femail'] != '') {
                    $useroutils .= '<li><a class="dropdown-item  text-center text-md-start" href="mailto:' . anti_spam($temp_user['femail'], 1) . '" target="_blank" title="' . translate("Email") . '" ><i class="fa fa-at fa-lg align-middle fa-fw"></i><span class="ms-2 d-none d-md-inline">' . translate("Email") . '</span></a></li>';
                }

                if ($temp_user['uid'] != 1 and array_key_exists($ch_lat, $posterdata_extend)) {
                    if ($posterdata_extend[$ch_lat] != '') {
                        $useroutils .= '<li><a class="dropdown-item text-center text-md-start" href="modules.php?ModPath=geoloc&amp;ModStart=geoloc&op=u' . $temp_user['uid'] . '" title="' . translate("Localisation") . '" ><i class="fas fa-map-marker-alt fa-lg align-middle fa-fw">&nbsp;</i><span class="ms-2 d-none d-md-inline">' . translate("Localisation") . '</span></a></li>';
                    }
                }
            }
    
            if ($temp_user['url'] != '') {
                $useroutils .= '<li><a class="dropdown-item text-center text-md-start" href="' . $temp_user['url'] . '" target="_blank" title="' . translate("Visiter ce site web") . '"><i class="fas fa-external-link-alt fa-lg align-middle fa-fw"></i><span class="ms-2 d-none d-md-inline">' . translate("Visiter ce site web") . '</span></a></li>';
            }

            if ($temp_user['mns']) {
                $useroutils .= '<li><a class="dropdown-item text-center text-md-start" href="minisite.php?op=' . $temp_user['uname'] . '" target="_blank" target="_blank" title="' . translate("Visitez le minisite") . '" ><i class="fa fa-lg fa-desktop align-middle fa-fw"></i><span class="ms-2 d-none d-md-inline">' . translate("Visitez le minisite") . '</span></a></li>';
            }

            if (stristr($temp_user['user_avatar'], 'users_private')) {
                $imgtmp = $temp_user['user_avatar'];
            } else {
                if ($ibid = theme_image('forum/avatar/' . $temp_user['user_avatar'])) {
                    $imgtmp = $ibid;
                } else {
                    $imgtmp = 'assets/images/forum/avatar/' . $temp_user['user_avatar'];
                }
            }
    
            $userpop = $avpop == 1 
                ? '<img class="btn-outline-primary img-thumbnail img-fluid n-ava-' . $dim . ' me-2" src="' . $imgtmp . '" alt="' . $temp_user['uname'] . '" loading="lazy" />' 
                : //'<a tabindex="0" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-html="true" data-bs-title="'.$temp_user['uname'].'" data-bs-content=\'<div class="list-group mb-3 text-center">'.$useroutils.'</div><div class="mx-auto text-center" style="max-width:170px;">'.$my_rs.'</div>\'></i><img data-bs-html="true" class="btn-outline-primary img-thumbnail img-fluid n-ava-'.$dim.' me-2" src="'.$imgtmp.'" alt="'.$temp_user['uname'].'" loading="lazy" /></a>' ;
                  '<div class="dropdown d-inline-block me-4 dropend">
                      <a class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                          <img class=" btn-outline-primary img-fluid n-ava-' . $dim . ' me-0" src="' . $imgtmp . '" alt="' . $temp_user['uname'] . '" />
                      </a>
                      <ul class="dropdown-menu bg-light">
                          <li><span class="dropdown-item-text text-center py-0 my-0">' . $this->userpopover($who, 64, 1) . '</span></li>
                          <li><h6 class="dropdown-header text-center py-0 my-0">' . $who . '</h6></li>
                          <li><hr class="dropdown-divider"></li>
                          ' . $useroutils . '
                          <li><hr class="dropdown-divider"></li>
                          <li><div class="mx-auto text-center" style="max-width:170px;">' . $my_rs . '</div>
                      </ul>
                  </div>';
    
            return $userpop;
        }
    }

    /**
     * [user_rank description]
     *
     * @param   [type]  $user_id [$userid description]
     *
     * @return  [type]           [return description]
     */
    public function user_rang($user_id)
    {
        $user_rang = DB::table('users_status')->select('rang')->where('user_id', $user_id)->first();

        if ($user_rang['rang']) {

            if ($rank = DBQ_Select(DB::table('config')->select('rank1', 'rank2', 'rank3', 'rank4', 'rank5')->first(), 86400)) {
                if (!empty($rank['rank1'])) {
                    $messR = 'rank' . $rank['rank'. $user_rang['rang']];
                } else {
                    $messR = '';
                }
            }

            if ($ibidR = Theme::theme_image("forum/rank/" . $user_rang['rang'] . ".png")) {
                $imgtmp = $ibidR;
            } else {
                $imgtmp = "assets/images/forum/rank/" . $user_rang['rang'] . ".png";
            }
            
            $rang_img = '<img src="' . site_url($imgtmp) . '" border="0" alt="' . aff_langue($messR) . '" title="' . aff_langue($messR) . '" loading="lazy" />';
        } else {
            $rang_img = '&nbsp;';
        } 
                
        return $rang_img;
    }

}
