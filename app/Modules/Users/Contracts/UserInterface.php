<?php
/**
 * Npds - Modules/Users/contracts/UserInterface.php
 *
 * @author  Nicolas Devoy
 * @email   nicolas@nicodev.fr 
 * @version 1.0.0
 * @date    26 Septembre 2024
 */
namespace App\Modules\Users\Contracts;

/**
 * Undocumented interface
 */
interface UserInterface {

    /**
     * [getuserinfo description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  [type]         [return description]
     */
    public function getuserinfo($user);

    /**
     * [AutoReg description]
     *
     * @return  [type]  [return description]
     */
    public function AutoReg();

    /**
     * [get_moderator description]
     *
     * @param   [type]  $user_id  [$user_id description]
     *
     * @return  [type]            [return description]
     */
    public function get_moderator($user_id);

    /**
     * [user_is_moderator description]
     *
     * @param   [type]  $uidX           [$uidX description]
     * @param   [type]  $passwordX      [$passwordX description]
     * @param   [type]  $forum_accessX  [$forum_accessX description]
     *
     * @return  [type]                  [return description]
     */
    public function user_is_moderator($uidX, $passwordX, $forum_accessX);

    /**
     * [get_userdata_from_id description]
     *
     * @param   [type]  $userid  [$userid description]
     *
     * @return  [type]           [return description]
     */
    public function get_userdata_from_id($userid);
    
    /**
     * [get_userdata_extend_from_id description]
     *
     * @param   [type]  $userid  [$userid description]
     *
     * @return  [type]           [return description]
     */
    public function get_userdata_extend_from_id($userid);    

    /**
     * [get_userdata description]
     *
     * @param   [type]  $username  [$username description]
     *
     * @return  [type]             [return description]
     */
    public function get_userdata($username);
    
    /**
     * Undocumented function
     *
     * @param [type] $userinfo
     * @return void
     */
    public function member_menu($userinfo);

}
