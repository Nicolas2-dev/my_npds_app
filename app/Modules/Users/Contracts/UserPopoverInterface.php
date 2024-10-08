<?php
/**
 * Npds - Modules/Users/contracts/UserPopoverInterface.php
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
interface UserPopoverInterface {

    /**
     * à partir du nom de l'utilisateur ($who)
     * 
     * @param [type] $who
     * @param [type] $dim 
     * @param [type] $avpop
     * @return void
     */
    public function userpopover($who, $dim, $avpop, $ldim ='');

}
