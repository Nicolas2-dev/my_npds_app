<?php
/**
 * Npds - Modules/Users/contracts/AvatarInterface.php
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
interface AvatarInterface {

    /**
     * Undocumented function
     *
     * @param [type] $input
     * @return void
     */
    public function user_avatar_update($input);

    /**
     * Undocumented function
     *
     * @return void
     */
    public function url($temp_user);

    /**
     * Undocumented function
     *
     * @return void
     */
    public function directory();

}
