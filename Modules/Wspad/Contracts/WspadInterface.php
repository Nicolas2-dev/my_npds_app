<?php
/**
 * Npds - Modules/Wspad/contracts/WspadInterface.php
 *
 * @author  Nicolas Devoy
 * @email   nicolas@nicodev.fr 
 * @version 1.0.0
 * @date    26 Septembre 2024
 */
namespace Modules\Wspad\Contracts;

/**
 * Undocumented interface
 */
interface WspadInterface {

    /**
     * Undocumented function
     *
     * @return void
     */
    public function Liste_Page();

    /**
     * Undocumented function
     *
     * @param [type] $page
     * @param [type] $ranq
     * @return void
     */
    public function Page($page, $ranq);

}
