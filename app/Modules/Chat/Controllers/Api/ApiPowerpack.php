<?php

namespace App\Controllers\Api;

use App\Controllers\Core\FrontController;


class ApiPowerpack extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {

    }

    public function index()
    {
        settype($op, 'string');

        switch ($op) {

        
                // Instant Members Message
                // Purge Chat Box
            case 'admin_chatbox_write':
                if ($admin) {
                    $adminX = base64_decode($admin);
                    $adminR = explode(':', $adminX);
        
                    $Q = sql_fetch_assoc(sql_query("SELECT * FROM authors WHERE aid='$adminR[0]' LIMIT 1"));
        
                    if ($Q['radminsuper'] == 1)
                        if ($chatbox_clearDB == 'OK')
                            sql_query("DELETE FROM chatbox WHERE date <= " . (time() - (60 * 5)) . "");
                }
                
                Header("Location: index.php");
                break;
                // Purge Chat Box
        }        
    }


}