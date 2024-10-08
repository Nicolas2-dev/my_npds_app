<?php
/**
 * Npds - Modules/Users/contracts/OnlineInterface.php
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
interface OnlineInterface {

    /**
     * [Who_Online description]
     *
     * @return  [type]  [return description]
     */
    public function Who_Online();

    /**
     * [Who_Online_Sub description]
     *
     * @return  [type]  [return description]
     */
    public function Who_Online_Sub();
    
    /**
     * [Site_Load description]
     *
     * @return  [type]  [return description]
     */
    public function Site_Load();    

    /**
     * [online_members description]
     *
     * @return  [type]  [return description]
     */
    public function online_members();

}
