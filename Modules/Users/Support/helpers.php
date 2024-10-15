<?php

use Modules\News\Support\Facades\News;
use Modules\Npds\Support\Facades\Auth;
use Modules\Users\Library\UserManager;
use Modules\Users\Library\OnlineManager;
use Modules\Groupes\Support\Facades\Groupe;



// deprecated function

// if (! function_exists('message_error'))
// {
//     function message_error($ibid, $op)
//     {
//         echo '
//         <h2>' . __d('users', 'Utilisateur') . '</h2>
//         <div class="alert alert-danger lead">';

//         echo $ibid;

//         if (($op == 'only_newuser') or ($op == 'new user') or ($op == 'finish')) {
//             hidden_form();
//             echo '
//                 <input type="hidden" name="op" value="only_newuser" />
//                 <button class="btn btn-secondary mt-2" type="submit">' . __d('users', 'Retour en arrière') . '</button>
//             </form>';
//         } else
//             echo '<a class="btn btn-secondary mt-4" href="javascript:history.go(-1)" title="' . __d('users', 'Retour en arrière') . '">' . __d('users', 'Retour en arrière') . '</a>';
        
//         echo '
//         </div>';

//     }
// }

// if (! function_exists('hidden_form'))
// {
//     function hidden_form() {
//         global $uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $vpass, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1,$charte,$user_lnl;
//         if (!$user_avatar) {$user_avatar="blank.gif";}
//         echo '
//         <form action="user.php" method="post">
//         <input type="hidden" name="uname" value="'.$uname.'" />
//         <input type="hidden" name="name" value="'.removeHack($name).'" />
//         <input type="hidden" name="email" value="'.$email.'" />
//         <input type="hidden" name="user_avatar" value="'.$user_avatar.'" />
//         <input type="hidden" name="user_from" value="'.StripSlashes(removeHack($user_from)).'" />
//         <input type="hidden" name="user_occ" value="'.StripSlashes(removeHack($user_occ)).'" />
//         <input type="hidden" name="user_intrest" value="'.StripSlashes(removeHack($user_intrest)).'" />
//         <input type="hidden" name="user_sig" value="'.StripSlashes(removeHack($user_sig)).'" />
//         <input type="hidden" name="user_viewemail" value="'.$user_viewemail.'" />
//         <input type="hidden" name="pass" value="'.removeHack($pass).'" />
//         <input type="hidden" name="user_lnl" value="'.removeHack($user_lnl).'" />
//         <input type="hidden" name="C1" value="'.StripSlashes(removeHack($C1)).'" />
//         <input type="hidden" name="C2" value="'.StripSlashes(removeHack($C2)).'" />
//         <input type="hidden" name="C3" value="'.StripSlashes(removeHack($C3)).'" />
//         <input type="hidden" name="C4" value="'.StripSlashes(removeHack($C4)).'" />
//         <input type="hidden" name="C5" value="'.StripSlashes(removeHack($C5)).'" />
//         <input type="hidden" name="C6" value="'.StripSlashes(removeHack($C6)).'" />
//         <input type="hidden" name="C7" value="'.StripSlashes(removeHack($C7)).'" />
//         <input type="hidden" name="C8" value="'.StripSlashes(removeHack($C8)).'" />
//         <input type="hidden" name="M1" value="'.StripSlashes(removeHack($M1)).'" />
//         <input type="hidden" name="M2" value="'.StripSlashes(removeHack($M2)).'" />
//         <input type="hidden" name="T1" value="'.StripSlashes(removeHack($T1)).'" />
//         <input type="hidden" name="T2" value="'.StripSlashes(removeHack($T2)).'" />
//         <input type="hidden" name="B1" value="'.StripSlashes(removeHack($B1)).'" />';
//     }
// }

// if (! function_exists('message_pass'))
// {
//     function message_pass($ibid)
//     {
//         echo $ibid;
//     }
// }


    /**
     * Undocumented function
     *
     * @param [type] $uname
     * @return void
     */
    function user_articles($uname)
    {
        $xtab = News::news_aff("libre", "WHERE informant='$uname' ORDER BY sid DESC LIMIT 10", '', 10);

        if (!empty($xtab)) {
            $userinfo = '
            <h4 class="my-3">' . __d('users', 'Les derniers articles de') . ' ' . $uname . '.</h4>
            <div id="last_article_by" class="card card-body mb-3">';

            $story_limit = 0;
        
            while (($story_limit < 10) and ($story_limit < sizeof($xtab))) {
                list($sid, $catid, $aid, $title, $time) = $xtab[$story_limit];
        
                $story_limit++;
        
                $userinfo .= '
                <div class="d-flex">
                    <div class="p-2"><a href="article.php?sid=' . $sid . '">' . aff_langue($title) . '</a></div>
                    <div class="ms-auto p-2">' . $time . '</div>
                </div>';
            }
        
            return $userinfo .= '
            </div>';
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $uname
     * @param [type] $uid
     * @return void
     */
    function user_contributions($uname, $uid)
    {
        $result = sql_query("SELECT * FROM posts WHERE forum_id > 0 AND poster_id=$uid ORDER BY post_time DESC LIMIT 0,50");            
          
        if (!empty($result)) {

            $userinfo = '
            <h4 class="my-3">' . __d('users', 'Les dernières contributions de') . ' ' . $uname . '</h4>';
            
            $nbp = 10;
            $content = '';

            $j = 1;
     
            while (list($post_id, $post_text) = sql_fetch_row($result) and $j <= $nbp) {
                // Requete detail dernier post
                $res = sql_query("SELECT 
                    us.topic_id, us.forum_id, us.poster_id, us.post_time, 
                    uv.topic_title, 
                    ug.forum_name, ug.forum_type, ug.forum_pass, 
                    ut.uname 
                FROM 
                    posts us, 
                    forumtopics uv, 
                    forums ug, 
                    users ut 
                WHERE 
                    us.post_id = $post_id 
                    AND uv.topic_id = us.topic_id 
                    AND uv.forum_id = ug.forum_id 
                    AND ut.uid = us.poster_id LIMIT 1");
        
                list($topic_id, $forum_id, $poster_id, $post_time, $topic_title, $forum_name, $forum_type, $forum_pass, $uname) = sql_fetch_row($res);
        
                if (($forum_type == '5') or ($forum_type == '7')) {
                    $ok_affich = false;
                    $tab_groupe = Groupe::valid_group(Auth::check('user'));
                    $ok_affich = Groupe::groupe_forum($forum_pass, $tab_groupe);
                } else {
                    $ok_affich = true;
                }

                if ($ok_affich) {
                    // Nbre de postes par sujet
                    $TableRep = sql_query("SELECT * FROM posts WHERE forum_id > 0 AND topic_id = '$topic_id'");
        
                    $replys = sql_num_rows($TableRep) - 1;
                    $id_lecteur = isset($cookie[0]) ? $cookie[0] : '0';
        
                    $sqlR = "SELECT rid FROM forum_read WHERE topicid = '$topic_id' AND uid = '$id_lecteur' AND status != '0'";
        
                    if (sql_num_rows(sql_query($sqlR)) == 0) {
                        $image = '<a href="" title="' . __d('users', 'Non lu') . '" data-bs-toggle="tooltip"><i class="far fa-file-alt fa-lg faa-shake animated text-primary "></i></a>';
                    } else {
                        $image = '<a title="' . __d('users', 'Lu') . '" data-bs-toggle="tooltip"><i class="far fa-file-alt fa-lg text-primary"></i></a>';
                    }

                    $content .= '
                    <p class="mb-0 list-group-item list-group-item-action flex-column align-items-start" >
                        <span class="d-flex w-100 mt-1">
                        <span>' . $post_time . '</span>
                        <span class="ms-auto">
                        <span class="badge bg-secondary ms-1" title="' . __d('users', 'Réponses') . '" data-bs-toggle="tooltip" data-bs-placement="left">' . $replys . '</span>
                        </span>
                    </span>
                    <span class="d-flex w-100"><br /><a href="viewtopic.php?topic=' . $topic_id . '&forum=' . $forum_id . '" data-bs-toggle="tooltip" title="' . $forum_name . '">' . $topic_title . '</a><span class="ms-auto mt-1">' . $image . '</span></span>
                    </p>';
        
                    $j++;
                }
            }
        
            $userinfo .= $content;
            return $userinfo .= '<hr />';
        }
    }


// 

if (! function_exists('getuserinfo'))
{
    /**
     * [getuserinfo description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  [type]         [return description]
     */
    function getuserinfo($user)
    {
        return UserManager::getInstance()->getuserinfo($user);
    }
}

if (! function_exists('AutoReg'))
{
    /**
     * [AutoReg description]
     *
     * @return  [type]  [return description]
     */
    function AutoReg()
    {
        return UserManager::getInstance()->AutoReg();
    }
}

if (! function_exists('get_moderator'))
{
    /**
     * [get_moderator description]
     *
     * @param   [type]  $user_id  [$user_id description]
     *
     * @return  [type]            [return description]
     */
    function get_moderator($user_id)
    {
        return UserManager::getInstance()->get_moderator($user_id);
    }
}

if (! function_exists('user_is_moderator'))
{
    /**
     * [user_is_moderator description]
     *
     * @param   [type]  $uidX           [$uidX description]
     * @param   [type]  $passwordX      [$passwordX description]
     * @param   [type]  $forum_accessX  [$forum_accessX description]
     *
     * @return  [type]                  [return description]
     */
    function user_is_moderator($uidX, $passwordX, $forum_accessX)
    {
        return UserManager::getInstance()->user_is_moderator($uidX, $passwordX, $forum_accessX);
    }
}

if (! function_exists('get_userdata_from_id'))
{
    /**
     * [get_userdata_from_id description]
     *
     * @param   [type]  $userid  [$userid description]
     *
     * @return  [type]           [return description]
     */
    function get_userdata_from_id($userid)
    {
        return UserManager::getInstance()->get_userdata_from_id($userid);
    }
}

if (! function_exists('get_userdata_extend_from_id'))
{
    /**
     * [get_userdata_extend_from_id description]
     *
     * @param   [type]  $userid  [$userid description]
     *
     * @return  [type]           [return description]
     */
    function get_userdata_extend_from_id($userid)
    {
        return UserManager::getInstance()->get_userdata_extend_from_id($userid);
    }
}

if (! function_exists('get_userdata'))
{
    /**
     * [get_userdata description]
     *
     * @param   [type]  $username  [$username description]
     *
     * @return  [type]             [return description]
     */
    function get_userdata($username)
    {
        return UserManager::getInstance()->get_userdata($username);
    }
}

if (! function_exists('Who_Online'))
{
    /**
     * [Who_Online description]
     *
     * @return  [type]  [return description]
     */
    function Who_Online()
    {
        return OnlineManager::getInstance()->Who_Online();
    }
}

if (! function_exists('Who_Online_Sub'))
{
    /**
     * [Who_Online_Sub description]
     *
     * @return  [type]  [return description]
     */
    function Who_Online_Sub()
    {
        return OnlineManager::getInstance()->Who_Online_Sub();
    }
}

if (! function_exists('Site_Load'))
{
    /**
     * [Site_Load description]
     *
     * @return  [type]  [return description]
     */
    function Site_Load()
    {
        return OnlineManager::getInstance()->Site_Load();
    }
}

if (! function_exists('online_members'))
{
    /**
     * [online_members description]
     *
     * @return  [type]  [return description]
     */
    function online_members()
    {
        return OnlineManager::getInstance()->online_members();
    }
}
