<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class FrontReadPmsgImm extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        // if ($SuperCache)
        //     $cache_obj = new cacheManager();
        // else
        //     $cache_obj = new SuperCacheEmpty();
        
        // include('auth.php');

        // settype($op, 'string');

        // switch ($op) {
        //     case 'new_msg':
        //         show_imm($op);
        //         break;
        
        //     case 'read_msg':
        //         read_imm($msg_id, $sub_op);
        //         break;
        
        //     case 'delete':
        //         sup_imm($msg_id);
        //         show_imm($op_orig);
        //         break;
                
        //     default:
        //         show_imm($op);
        //         break;
        // }
        

    }

    function cache_ctrl()
    {
        if (Config::get('npds.cache_verif')) {
            header("Expires: Sun, 01 Jul 1990 00:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-cache, must revalidate");
            header("Pragma: no-cache");
        }
    }
    
    function show_imm($op)
    {
        global $user, $allow_bbcode, $theme;
        
    
        if (!$user)
            Header("Location: user.php");
        else {
            $userX = base64_decode($user);
            $userdata = explode(':', $userX);
    
            if ($userdata[9] != '') {
                if (!$file = @opendir("themes/$userdata[9]"))
                    $theme = Config::get('npds.Default_Theme');
                else
                    $theme = $userdata[9];
            } else
                $theme = Config::get('npds.Default_Theme');
    
            include("themes/$theme/theme.php");
    
            $userdata = get_userdata($userdata[1]);
    
            $sql = ($op != 'new_msg') ?
                "SELECT * FROM priv_msgs WHERE to_userid = '" . $userdata['uid'] . "' AND read_msg='1' AND type_msg='0' AND dossier='...' ORDER BY msg_id DESC" :
                "SELECT * FROM priv_msgs WHERE to_userid = '" . $userdata['uid'] . "' AND read_msg='0' AND type_msg='0' ORDER BY msg_id ASC";
            $result = sql_query($sql);
            
            $pasfin = false;
            while ($myrow = sql_fetch_assoc($result)) {
                if ($pasfin == false) {
                    $pasfin = true;
                    cache_ctrl();
    
                    include("storage/meta/meta.php");
                    include("themes/default/include/header_head.inc");
    
                    echo import_css($theme, Config::get('npds.language'), '', '', '');
                    echo '
                    </head>
                    <body>
                        <div class="card card-body">';
                }
    
                $posterdata = get_userdata_from_id($myrow['from_userid']);
    
                echo '
                    <div class="card mb-3">
                    <div class="card-body">
                    <h3>' . __d('messenger', 'Message personnel') . ' ' . __d('messenger', 'de');
    
                if ($posterdata['uid'] == 1) {
                    echo ' <span class="text-muted">' . Config::get('npds.sitename') . '</span></h3>';
                }
    
                if ($posterdata['uid'] <> 1) 
                    echo '<span class="text-muted">' . $posterdata['uname'] . '</span></h3>';
    
                $myrow['subject'] = strip_tags($myrow['subject']);
    
                $posts = $posterdata['posts'];
    
                if ($posterdata['uid'] <> 1) 
                    echo member_qualif($posterdata['uname'], $posts, $posterdata['rang']);
    
                echo '<br /><br />';
    
                if (Config::get('npds.smilies')) {
                    if ($posterdata['user_avatar'] != '') {
                        if (stristr($posterdata['user_avatar'], "users_private")) {
                            $imgtmp = $posterdata['user_avatar'];
                        } else {
                            if ($ibid = theme_image("forum/avatar/" . $posterdata['user_avatar'])) {
                                $imgtmp = $ibid;
                            } else {
                                $imgtmp = "assets/images/forum/avatar/" . $posterdata['user_avatar'];
                            }
                        }
    
                        echo '<img class="btn-secondary img-thumbnail img-fluid n-ava" src="' . $imgtmp . '" alt="' . $posterdata['uname'] . '" />';
                    }
                }
    
                if (Config::get('npds.smilies')) {
                    if ($myrow['msg_image'] != '') {
                        if ($ibid = theme_image("forum/subject/" . $myrow['msg_image'])) {
                            $imgtmp = $ibid;
                        } else {
                            $imgtmp = "assets/images/forum/subject/" . $myrow['msg_image'];
                        }
                        echo '<img class="n-smil" src="' . $imgtmp . '"  alt="" />&nbsp;';
                    }
                }
    
                echo __d('messenger', 'Envoyé') . ' : ' . $myrow['msg_time'] . '&nbsp;&nbsp;&nbsp';
                echo '<h4>' . aff_langue($myrow['subject']) . '</h4>';
    
                $message = stripslashes($myrow['msg_text']);
    
                if ($allow_bbcode) {
                    $message = smilie($message);
                    $message = aff_video_yt($message);
                }
    
                $message = str_replace("[addsig]", "<br /><br />" . nl2br($posterdata['user_sig']), aff_langue($message));
    
                echo $message . '<br />';
    
                if ($posterdata['uid'] <> 1) {
                    if (!Config::get('npds.short_user')) {
                    }
                }
    
                echo '
                </div>
                <div class="card-footer">';
    
                if ($posterdata['uid'] <> 1)
                    echo '<a class="me-3" href="readpmsg_imm.php?op=read_msg&amp;msg_id=' . $myrow['msg_id'] . '&amp;op_orig=' . $op . '&amp;sub_op=reply" title="' . __d('messenger', 'Répondre') . '" data-bs-toggle="tooltip"><i class="fa fa-reply fa-lg me-1"></i>' . __d('messenger', 'Répondre') . '</a>';
                
                echo '
                    <a class="me-3" href="readpmsg_imm.php?op=read_msg&amp;msg_id=' . $myrow['msg_id'] . '&amp;op_orig=' . $op . '&amp;sub_op=read" title="' . __d('messenger', 'Lu') . '" data-bs-toggle="tooltip"><i class="far fa-check-square fa-lg"></i></a>
                    <a class="me-3" href="readpmsg_imm.php?op=delete&amp;msg_id=' . $myrow['msg_id'] . '&amp;op_orig=' . $op . '" title="' . __d('messenger', 'Effacer') . '" data-bs-toggle="tooltip"><i class="fas fa-trash fa-lg text-danger"></i></a>
                </div>
                </div>';
            }
    
            if ($pasfin != true) {
                cache_ctrl();
                echo '<body onload="self.close();">';
            }
        }
    
        echo '
                </div>
            </body>
        </html>';
    }
    
    function sup_imm($msg_id)
    {
        global $cookie;
    
        if (!$cookie)
            Header("Location: user.php");
        else {
            $sql = "DELETE FROM priv_msgs WHERE msg_id='$msg_id' AND to_userid='$cookie[0]'";
    
            if (!sql_query($sql))
                forumerror('0021');
        }
    }
    
    function read_imm($msg_id, $sub_op)
    {
        global $cookie;
    
        if (!$cookie)
            Header("Location: user.php");
        else {
            $sql = "UPDATE priv_msgs SET read_msg='1' WHERE msg_id='$msg_id' AND to_userid='$cookie[0]'";
    
            if (!sql_query($sql))
                forumerror('0021');
    
            if ($sub_op == 'reply') {
                echo "<script type=\"text/javascript\">
                   //<![CDATA[
                   window.location='replypmsg.php?reply=1&msg_id=$msg_id&userid=$cookie[0]&full_interface=short';
                   //]]>
                   </script>";
                die();
            }
    
            echo '<script type="text/javascript">
                //<![CDATA[
                window.location="readpmsg_imm.php?op=new_msg";
                //]]>
                </script>';
            die();
        }
    }

}