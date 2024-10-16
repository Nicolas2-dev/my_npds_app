<?php

namespace Modules\Messenger\Controllers\Front;

use Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class MessengerReadPmsg extends FrontController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
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
    public function index()
    {
        if ($SuperCache)
            $cache_obj = new cacheManager();
        else
            $cache_obj = new SuperCacheEmpty();
        
        if (!$user)
            Header('Location: user.php');
        else {
        
            $userX = base64_decode($user);
            $userdata = explode(':', $userX);
            $userdata = get_userdata($userdata[1]);
        
            settype($start, 'integer');
            settype($type, 'string');
            settype($dossier, 'string');
        
            if ($type == 'outbox')
                $sql = "SELECT * FROM priv_msgs WHERE from_userid='" . $userdata['uid'] . "' AND type_msg='1' ORDER BY msg_id DESC LIMIT $start,1";
            else {
                if ($dossier == 'All') 
                    $ibid = '';
                else 
                    $ibid = "AND dossier='$dossier'";
        
                if (!$dossier) 
                    $ibid = "AND dossier='...'";
        
                $sql = "SELECT * FROM priv_msgs WHERE to_userid='" . $userdata['uid'] . "' AND type_msg='0' $ibid ORDER BY msg_id DESC LIMIT $start,1";
            }
        
            $resultID = sql_query($sql);
        
            if (!$resultID)
                forumerror('0005');
            else {
                $myrow = sql_fetch_assoc($resultID);
        
                if ($myrow['read_msg'] != '1') {
                    $sql = "UPDATE priv_msgs SET read_msg='1' WHERE msg_id='" . $myrow['msg_id'] . "'";
                    $result = sql_query($sql);
        
                    if (!$result)
                        forumerror('0005');
                }
            }
        
            $myrow['subject'] = strip_tags($myrow['subject']);
        
            if ($dossier == 'All') 
                $Xdossier = __d('messenger', 'Tous les sujets');
            else 
                $Xdossier = StripSlashes($dossier);
        
            echo '
                <h3>' . __d('messenger', 'Message personnel') . '</h3>
                <hr />';
        
            if (!sql_num_rows($resultID))
                echo '<div class="alert alert-danger lead">' . __d('messenger', 'Vous n\'avez aucun message.') . '</div>';
            else {
                echo '
                <p class="lead">
                    <a href="viewpmsg.php">' . __d('messenger', 'Messages personnels') . '</a>&nbsp;&raquo;&raquo;&nbsp;' . $Xdossier . '&nbsp;&raquo;&raquo;&nbsp;' . aff_langue($myrow['subject']) . '
                </p>
                <div class="card mb-3">
                    <div class="card-header">';
        
                if ($type == 'outbox')
                    $posterdata = get_userdata_from_id($myrow['to_userid']);
                else
                    $posterdata = get_userdata_from_id($myrow['from_userid']);
        
                $posts = $posterdata['posts'];
        
                if ($posterdata['uid'] <> 1) {
                    $socialnetworks = array();
                    $posterdata_extend = array();
                    $res_id = array();
        
                    $my_rs = '';
        
                    if (!Config::get('npds.short_user')) {
                        $posterdata_extend = get_userdata_extend_from_id($posterdata['uid']);
        
                        include('modules/reseaux-sociaux/reseaux-sociaux.conf.php');
        
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
                                        $my_rs .= '<a class="me-3" href="';
        
                                        if ($v1[2] == 'skype') 
                                            $my_rs .= $v1[1] . $y1[1] . '?chat';
                                        else 
                                            $my_rs .= $v1[1] . $y1[1];
        
                                        $my_rs .= '" target="_blank"><i class="fab fa-' . $v1[2] . ' fa-2x text-primary"></i></a> ';
                                        break;
                                    } else 
                                        $my_rs .= '';
                                }
                            }
                            $my_rsos[] = $my_rs;
                        } else 
                            $my_rsos[] = '';
                    }
        
                    $useroutils = '';
                    $useroutils .= '<hr />';
        
                    if ($posterdata['uid'] != 1 and $posterdata['uid'] != '')
                        $useroutils .= '<a class="list-group-item text-primary" href="user.php?op=userinfo&amp;uname=' . $posterdata['uname'] . '" target="_blank" title="' . __d('messenger', 'Profil') . '" data-bs-toggle="tooltip"><i class="fa fa-2x fa-user align-middle"></i><span class="ms-3 d-none d-md-inline">' . __d('messenger', 'Profil') . '</span></a>';
                    
                    if ($posterdata['uid'] != 1)
                        $useroutils .= '<a class="list-group-item text-primary" href="powerpack.php?op=instant_message&amp;to_userid=' . $posterdata["uname"] . '" title="' . __d('messenger', 'Envoyer un message interne') . '" data-bs-toggle="tooltip"><i class="far fa-envelope fa-2x align-middle "></i><span class="ms-3 d-none d-md-inline">' . __d('messenger', 'Message') . '</span></a>';
                    
                    if ($posterdata['femail'] != '')
                        $useroutils .= '<a class="list-group-item text-primary" href="mailto:' . anti_spam($posterdata['femail'], 1) . '" target="_blank" title="' . __d('messenger', 'Email') . '" data-bs-toggle="tooltip"><i class="fa fa-at fa-2x align-middle"></i><span class="ms-3 d-none d-md-inline">' . __d('messenger', 'Email') . '</span></a>';
                    
                    if ($posterdata['url'] != '')
                        $useroutils .= '<a class="list-group-item text-primary" href="' . $posterdata['url'] . '" target="_blank" title="' . __d('messenger', 'Visiter ce site web') . '" data-bs-toggle="tooltip"><i class="fas fa-2x fa-external-link-alt align-middle"></i><span class="ms-3 d-none d-md-inline">' . __d('messenger', 'Visiter ce site web') . '</span></a>';
                    
                    if ($posterdata['mns'])
                        $useroutils .= '<a class="list-group-item text-primary" href="minisite.php?op=' . $posterdata['uname'] . '" target="_blank" target="_blank" title="' . __d('messenger', 'Visitez le minisite') . '" data-bs-toggle="tooltip"><i class="fa fa-2x fa-desktop align-middle"></i><span class="ms-3 d-none d-md-inline">' . __d('messenger', 'Visitez le minisite') . '</span></a>';
                }
        
                //         if (Config::get('npds.smilies')) {
                if ($posterdata['user_avatar'] != '') {
                    if (stristr($posterdata['user_avatar'], "users_private"))
                        $imgtmp = $posterdata['user_avatar'];
                    else
                        if ($ibid = theme_image("forum/avatar/" . $posterdata['user_avatar'])) 
                            $imgtmp = $ibid;
                        else 
                            $imgtmp = "assets/images/forum/avatar/" . $posterdata['user_avatar'];
        
                    if ($posterdata['uid'] <> 1) {
                        $aff_reso = isset($my_rsos[0]) ? $my_rsos[0] : '';
        
                        echo '<a style="position:absolute; top:1rem;" tabindex="0" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-html="true" data-bs-title="' . $posterdata['uname'] . '" data-bs-content=\'' . member_qualif($posterdata['uname'], $posts, $posterdata['rang']) . '<br /><div class="list-group">' . $useroutils . '</div><hr />' . $aff_reso . '\'><img class=" btn-secondary img-thumbnail img-fluid n-ava" src="' . $imgtmp . '" alt="' . $posterdata['uname'] . '" /></a>';
                    } else
                        echo '<a style="position:absolute; top:1rem;" tabindex="0" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title=\'<i class="fa fa-cogs fa-lg"></i>\'><img class=" btn-secondary img-thumbnail img-fluid n-ava" src="' . $imgtmp . '" alt="' . $posterdata['uname'] . '" /></a>';
                }
                //      }
        
                if ($posterdata['uid'] <> 1)
                    echo '&nbsp;<span style="position:absolute; left:6em;" class="text-muted"><strong>' . $posterdata['uname'] . '</strong></span>';
                else
                    echo '&nbsp;<span style="position:absolute; left:6em;" class="text-muted"><strong>' . Config::get('npds.sitename') . '</strong></span>';
        
                echo '<span class="float-end">';
        
                if (Config::get('npds.smilies')) {
                    if ($myrow['msg_image'] != '') {
                        if ($ibid = theme_image("forum/subject/" . $myrow['msg_image'])) {
                            $imgtmp = $ibid;
                        } else {
                            $imgtmp = "assets/images/forum/subject/" . $myrow['msg_image'];
                        }
        
                        echo '<img class="n-smil" src="' . $imgtmp . '" alt="icon_post" />';
                    } else {
                        if ($ibid = theme_image("forum/subject/00.png")) 
                            $imgtmpPI = $ibid;
                        else 
                            $imgtmpPI = "assets/images/forum/subject/00.png";
        
                        echo '<img class="n-smil" src="' . $imgtmpPI . '" alt="icon_post" />';
                    }
                }
        
                echo '</span>
                        </div>
                        <div class="card-body">
                        <div class="card-text pt-2">
                            <div class="text-end small">' . __d('messenger', 'Envoyé') . ' : ' . $myrow['msg_time'] . '</div>
                            <hr /><strong>' . aff_langue($myrow['subject']) . '</strong><br />';
        
                $message = stripslashes($myrow['msg_text']);
        
                if ($allow_bbcode) {
                    $message = smilie($message);
                    $message = aff_video_yt($message);
                }
        
                $message = str_replace('[addsig]', '<br />' . nl2br($posterdata['user_sig']), aff_langue($message));
        
                echo $message;
        
                echo '
                        </div>
                        </div>
                    </div>';
        
                $previous = $start - 1;
                $next = $start + 1;
        
                if ($type == 'outbox')
                    $tmpx = '&amp;type=outbox';
                else
                    $tmpx = '&amp;dossier=' . urlencode(StripSlashes($dossier));
        
                echo '<ul class="pagination d-flex justify-content-center">';
        
                if ($type != 'outbox') {
                    if ($posterdata['uid'] <> 1)
                        echo '
                        <li class="page-item">
                        <a class="page-link" href="replypmsg.php?reply=1&amp;msg_id=' . $myrow['msg_id'] . '"><span class="d-none d-md-inline"></span><i class="fa fa-reply fa-lg me-2"></i><span class="d-none d-md-inline">' . __d('messenger', 'Répondre') . '</span></a>
                        </li>';
                }
        
                if ($previous >= 0) echo '
                    <li class="page-item">
                    <a class="page-link" href="readpmsg.php?start=' . $previous . '&amp;total_messages=' . $total_messages . $tmpx . '" >
                        <span class="d-none d-md-inline">' . __d('messenger', 'Message précédent') . '</span>
                        <span class="d-md-none" title="' . __d('messenger', 'Message précédent') . '" data-bs-toggle="tooltip"><i class="fa fa-angle-double-left fa-lg"></i></span>
                    </a>
                    </li>';
        
                else echo '
                    <li class="page-item">
                    <a class="page-link disabled" href="#">
                        <span class="d-none d-md-inline">' . __d('messenger', 'Message précédent') . '</span>
                        <span class="d-md-none" title="' . __d('messenger', 'Message précédent') . '" data-bs-toggle="tooltip"><i class="fa fa-angle-double-left fa-lg"></i></span>
                    </a>
                    </li>';
        
                if ($next < $total_messages) echo '
                    <li class="page-item" >
                    <a class="page-link" href="readpmsg.php?start=' . $next . '&amp;total_messages=' . $total_messages . $tmpx . '" >
                        <span class="d-none d-md-inline">' . __d('messenger', 'Message suivant') . '</span>
                        <span class="d-md-none" title="' . __d('messenger', 'Message suivant') . '" data-bs-toggle="tooltip"><i class="fa fa-angle-double-right fa-lg"></i></span>
                    </a>
                    </li>';
                else echo '
                    <li class="page-item">
                    <a class="page-link disabled" href="#">
                        <span class="d-none d-md-inline">' . __d('messenger', 'Message suivant') . '</span>
                        <span class="d-md-none" title="' . __d('messenger', 'Message suivant') . '" data-bs-toggle="tooltip"><i class="fa fa-angle-double-right fa-lg"></i></span>
                    </a>
                    </li>';
        
                echo '
                    <li class="page-item">
                    <a class="page-link" data-bs-toggle="collapse" href="#sortbox"><i class="fa fa-cogs fa-lg" title="' . __d('messenger', 'Classer ce message') . '" data-bs-toggle="tooltip"></i></a>
                    </li>';
        
                if ($type != 'outbox')
                    echo '
                    <li class="page-item"><a class="page-link " href="replypmsg.php?delete=1&amp;msg_id=' . $myrow['msg_id'] . '" title="' . __d('messenger', 'Supprimer ce message') . '" data-bs-toggle="tooltip"><i class="fas fa-trash fa-lg text-danger"></i></a></li>';
                else
                    echo '
                    <li class="page-item"><a class="page-link " href="replypmsg.php?delete=1&amp;msg_id=' . $myrow['msg_id'] . '&amp;type=outbox"  title="' . __d('messenger', 'Supprimer ce message') . '" data-bs-toggle="tooltip"><i class="fas fa-trash fa-lg text-danger"></i></a></li>';
                
                echo '
                </ul>';
        
                if ($type != 'outbox') {
                    $sql = "SELECT DISTINCT dossier FROM priv_msgs WHERE to_userid='" . $userdata['uid'] . "' AND type_msg='0' ORDER BY dossier";
                    $result = sql_query($sql);
        
                    echo '
                    <div class="collapse" id="sortbox">
                        <div class="card card-body" >
                        <p class="lead">' . __d('messenger', 'Classer ce message') . '</p>
                            <form action="replypmsg.php" method="post">
                            <div class="mb-3 row">
                                <label class="col-form-label col-sm-4" for="dossier">' . __d('messenger', 'Sujet') . '</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="dossier" name="dossier">';
        
                    while (list($dossier) = sql_fetch_row($result)) {
                        echo '<option value="' . $dossier . '">' . $dossier . '</option>';
                    }
        
                    echo '
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-form-label col-sm-4" for="nouveau_dossier">' . __d('messenger', 'Nouveau dossier/sujet') . '</label>
                                <div class="col-sm-8">
                                    <input type="texte" class="form-control" id="nouveau_dossier" name="nouveau_dossier" value="" size="24" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-8 ms-sm-auto">
                                    <input type="hidden" name="msg_id" value="' . $myrow['msg_id'] . '" />
                                    <input type="hidden" name="classement" value="1" />
                                    <button type="submit" class="btn btn-primary" name="classe">OK</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>';
                }
            }
        }
    }

}
