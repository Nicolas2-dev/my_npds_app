<?php

use App\Modules\Users\Library\UserManager;
use App\Modules\Users\Library\OnlineManager;



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
