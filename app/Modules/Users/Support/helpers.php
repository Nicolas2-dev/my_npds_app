<?php

use App\Modules\Users\Library\UserManager;
use App\Modules\Users\Library\OnlineManager;


if (! function_exists('getusrinfo'))
{
    /**
     * [getusrinfo description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  [type]         [return description]
     */
    function getusrinfo($user)
    {
        return UserManager::getInstance()->getusrinfo($user);
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
