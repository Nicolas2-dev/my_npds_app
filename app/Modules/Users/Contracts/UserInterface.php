<?php

namespace App\Modules\Users\Contracts;


interface UserInterface {

    /**
     * [getusrinfo description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  [type]         [return description]
     */
    public function getusrinfo($user);

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
     * [member_menu description]
     *
     * @param   [type]  $mns  [$mns description]
     * @param   [type]  $qui  [$qui description]
     *
     * @return  [type]        [return description]
     */
    public function member_menu($mns, $qui);

}
