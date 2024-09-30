<?php

namespace App\Modules\Users\Library;

use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Npds\Support\Facades\Mailer;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Npds\Support\Facades\Language;
use App\Modules\Users\Contracts\UserInterface;

/**
 * Undocumented class
 */
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
     * [getuserinfo description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  [type]         [return description]
     */
    public function getuserinfo($user)
    {
        $cookie = explode(':', base64_decode($user));

        $result = sql_query("SELECT pass 
                            FROM users 
                            WHERE uname='$cookie[1]'");

        list($pass) = sql_fetch_row($result);

        $userinfo = '';

        if (($cookie[2] == md5($pass)) and ($pass != '')) {
            $result = sql_query("SELECT uid, name, uname, email, femail, url, user_avatar, user_occ, user_from, user_intrest, user_sig, user_viewemail, user_theme, pass, storynum, umode, uorder, thold, noscore, bio, ublockon, ublock, theme, commentmax, user_journal, send_email, is_visible, mns, user_lnl 
                                FROM users 
                                WHERE uname='$cookie[1]'");
            
            if (sql_num_rows($result) == 1) {
                $userinfo = sql_fetch_assoc($result);
            } else {
                echo '<strong>' . __d('users', 'Un problème est survenu') . '.</strong>';
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
        $user = Auth::guard('user');

        if (!Config::get('npds.AutoRegUser')) {
            if (isset($user)) {
                $cookie = explode(':', base64_decode(Auth::check('user')));

                $status_temp = DB::table('users_status')->select('open')->where('uid', $cookie[0])->first();

                if (!$status_temp['open']) {
                    Cookie::set('user', '', 0);

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
        $user_id = str_replace(",", "' or uid='", $user_id);
    
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
        $result1 = sql_query("SELECT pass FROM users WHERE uid = '$uidX'");
        $result2 = sql_query("SELECT level FROM users_status WHERE uid = '$uidX'");
    
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
        $sql1 = "SELECT * FROM users WHERE uid='$userid'";
        $sql2 = "SELECT * FROM users_status WHERE uid='$userid'";
    
        if (!$result = sql_query($sql1))
            forumerror('0016');
    
        if (!$myrow = sql_fetch_assoc($result))
            $myrow = array("uid" => 1);
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
        $sql1 = "SELECT * FROM users_extend WHERE uid='$userid'";
        /*   $sql2 = "SELECT * FROM users_status WHERE uid='$userid'";
    
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
            $myrow = array("uid" => 1);
    
        return ($myrow);
    }

    function userCheck($uname, $email)
    {
        $stop = '';
    
        if ((!$email) || ($email == '') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $email)))
            $stop = __d('users', 'Erreur : Email invalide');
    
        if (strrpos($email, ' ') > 0)
            $stop = __d('users', 'Erreur : une adresse Email ne peut pas contenir d\'espaces');
    
        if (Mailer::checkdnsmail($email) === false)
            $stop = __d('users', 'Erreur : DNS ou serveur de mail incorrect !');
    
        if ((!$uname) || ($uname == '') || (preg_match('#[^a-zA-Z0-9_-]#', $uname)))
            $stop = __d('users', 'Erreur : identifiant invalide');
    
        if (strlen($uname) > 25)
            $stop = __d('users', 'Votre surnom est trop long. Il doit faire moins de 25 caractères.');
    
        if (preg_match('#^(root|adm|linux|webmaster|admin|god|administrator|administrador|nobody|anonymous|anonimo|an€nimo|operator|dune|netadm)$#i', $uname))
            $stop = __d('users', 'Erreur : nom existant.');
    
        if (strrpos($uname, ' ') > 0)
            $stop = __d('users', 'Il ne peut pas y avoir d\'espace dans le surnom.');
    
        if (sql_num_rows(sql_query("SELECT uname FROM users WHERE uname='$uname'")) > 0)
            $stop = __d('users', 'Erreur : cet identifiant est déjà utilisé');
    
        if ($uname != 'edituser')
            if (sql_num_rows(sql_query("SELECT email FROM users WHERE email='$email'")) > 0)
                $stop = __d('users', 'Erreur : adresse Email déjà utilisée');
    
        return $stop;
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
        $user_rang = DB::table('users_status')->select('rang')->where('uid', $user_id)->first();

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
            
            $rang_img = '<img src="' . site_url($imgtmp) . '" border="0" alt="' . Language::aff_langue($messR) . '" title="' . Language::aff_langue($messR) . '" loading="lazy" />';
        } else {
            $rang_img = '&nbsp;';
        } 
                
        return $rang_img;
    }

    /**
     * Undocumented function
     *
     * @param [type] $userinfo
     * @return void
     */
    public function member_menu($userinfo)
    {
        $op = Request::post('op') ?: Request::query('op');


        $ed_u = $op == 'edituser' ? 'active' : '';
        $cl_edj = $op == 'editjournal' ? 'active' : '';
        $cl_edh = $op == 'edithome' ? 'active' : '';
        $cl_cht = $op == 'chgtheme' ? 'active' : '';
        $cl_edjh = ($op == 'editjournal' or $op == 'edithome') ? 'active' : '';
        $cl_u = $op  == 'dashboard' ? 'active' : '';
        $cl_pm = $op == 'viewpmsg' ? 'active' : '';
        $cl_rs = $op == 'sociaux' ? 'active' : '';
        
        $menu = '
        <ul class="nav nav-tabs d-flex flex-wrap"> 
            <li class="nav-item"><a class="nav-link ' . $cl_u . '" href="'. site_url('user?op=dashboard') .'" title="' . __d('users', 'Votre compte') . '" data-bs-toggle="tooltip" ><i class="fas fa-user fa-2x d-xl-none"></i><span class="d-none d-xl-inline"><i class="fas fa-user fa-lg"></i></span></a></li>
            <li class="nav-item"><a class="nav-link ' . $ed_u . '" href="'. site_url('user/edituser?op=edituser') .'" title="' . __d('users', 'Vous') . '" data-bs-toggle="tooltip" ><i class="fas fa-user-edit fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;' . __d('users', 'Vous') . '</span></a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle tooltipbyclass ' . $cl_edjh . '" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" data-bs-html="true" title="' . __d('users', 'Editer votre journal') . '<br />' . __d('users', 'Editer votre page principale') . '"><i class="fas fa-edit fa-2x d-xl-none me-2"></i><span class="d-none d-xl-inline">Editer</span></a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item ' . $cl_edj . '" href="'. site_url('user/editjournal?op=editjournal') .'" title="' . __d('users', 'Editer votre journal') . '" data-bs-toggle="tooltip">' . __d('users', 'Journal') . '</a></li>
                    <li><a class="dropdown-item ' . $cl_edh . '" href="'. site_url('user/edithome?op=edithome') .'" title="' . __d('users', 'Editer votre page principale') . '" data-bs-toggle="tooltip">' . __d('users', 'Page') . '</a></li>
                </ul>
            </li>';
    
        if (($userinfo['mns']) and (Config::get('upload.config.autorise_upload_p'))) {
            
            $PopUp = minisite_win_upload("popup");
    
            $menu .= '
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle tooltipbyclass" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" title="' . __d('users', 'Gérer votre miniSite') . '"><i class="fas fa-desktop fa-2x d-xl-none me-2"></i><span class="d-none d-xl-inline">' . __d('users', 'MiniSite') . '</span></a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="minisite.php?op=' . $userinfo['uname'] . '" target="_blank">' . __d('users', 'MiniSite') . '</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="window.open(' . $PopUp . ')" >' . __d('users', 'Gérer votre miniSite') . '</a></li>
                </ul>
            </li>';
        }
    
        $menu .= '
            <li class="nav-item"><a class="nav-link ' . $cl_cht . '" href="'. site_url('user/chgtheme?op=chgtheme') .'" title="' . __d('users', 'Changer le thème') . '"  data-bs-toggle="tooltip" ><i class="fas fa-paint-brush fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;' . __d('users', 'Thème') . '</span></a></li>
            <li class="nav-item"><a class="nav-link ' . $cl_rs . '" href="'. site_url('reseaux?op=sociaux') .'" title="' . __d('users', 'Réseaux sociaux') . '"  data-bs-toggle="tooltip" ><i class="fas fa-share-alt-square fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;' . __d('users', 'Réseaux sociaux') . '</span></a></li>
            <li class="nav-item"><a class="nav-link ' . $cl_pm . '" href="'. site_url('messenger/viewpmsg?op=viewpmsgviewpmsg') .'" title="' . __d('users', 'Message personnel') . '"  data-bs-toggle="tooltip" ><i class="far fa-envelope fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;' . __d('users', 'Message') . '</span></a></li>
            <li class="nav-item"><a class="nav-link " href="'. site_url('user/logout?op=logout') .'" title="' . __d('users', 'Déconnexion') . '" data-bs-toggle="tooltip" ><i class="fas fa-sign-out-alt fa-2x text-danger d-xl-none"></i><span class="d-none d-xl-inline text-danger">&nbsp;' . __d('users', 'Déconnexion') . '</span></a></li>
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
    
            $socialnetworks     = array();
            $posterdata_extend  = array();
            $res_id             = array();
    
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
                    $useroutils .= '<li><a class="dropdown-item text-center text-md-start" href="user.php?op=userinfo&amp;uname=' . $temp_user['uname'] . '" target="_blank" title="' . __d('users', 'Profil') . '" ><i class="fa fa-lg fa-user align-middle fa-fw"></i><span class="ms-2 d-none d-md-inline">' . __d('users', 'Profil') . '</span></a></li>';
                }

                if ($temp_user['uid'] != 1 and $temp_user['uid'] != '') {
                    $useroutils .= '<li><a class="dropdown-item text-center text-md-start" href="powerpack.php?op=instant_message&amp;to_userid=' . urlencode($temp_user['uname']) . '" title="' . __d('users', 'Envoyer un message interne') . '" ><i class="far fa-lg fa-envelope align-middle fa-fw"></i><span class="ms-2 d-none d-md-inline">' . __d('users', 'Message') . '</span></a></li>';
                }

                if ($temp_user['femail'] != '') {
                    $useroutils .= '<li><a class="dropdown-item  text-center text-md-start" href="mailto:' . anti_spam($temp_user['femail'], 1) . '" target="_blank" title="' . __d('users', 'Email') . '" ><i class="fa fa-at fa-lg align-middle fa-fw"></i><span class="ms-2 d-none d-md-inline">' . __d('users', 'Email') . '</span></a></li>';
                }

                if ($temp_user['uid'] != 1 and array_key_exists($ch_lat, $posterdata_extend)) {
                    if ($posterdata_extend[$ch_lat] != '') {
                        $useroutils .= '<li><a class="dropdown-item text-center text-md-start" href="modules.php?ModPath=geoloc&amp;ModStart=geoloc&op=u' . $temp_user['uid'] . '" title="' . __d('users', 'Localisation') . '" ><i class="fas fa-map-marker-alt fa-lg align-middle fa-fw">&nbsp;</i><span class="ms-2 d-none d-md-inline">' . __d('users', 'Localisation') . '</span></a></li>';
                    }
                }
            }
    
            if ($temp_user['url'] != '') {
                $useroutils .= '<li><a class="dropdown-item text-center text-md-start" href="' . $temp_user['url'] . '" target="_blank" title="' . __d('users', 'Visiter ce site web') . '"><i class="fas fa-external-link-alt fa-lg align-middle fa-fw"></i><span class="ms-2 d-none d-md-inline">' . __d('users', 'Visiter ce site web') . '</span></a></li>';
            }

            if ($temp_user['mns']) {
                $useroutils .= '<li><a class="dropdown-item text-center text-md-start" href="minisite.php?op=' . $temp_user['uname'] . '" target="_blank" target="_blank" title="' . __d('users', 'Visitez le minisite') . '" ><i class="fa fa-lg fa-desktop align-middle fa-fw"></i><span class="ms-2 d-none d-md-inline">' . __d('users', 'Visitez le minisite') . '</span></a></li>';
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



}
