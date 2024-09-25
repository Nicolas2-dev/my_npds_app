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
            case 'instant_message':
                Form_instant_message($to_userid);
                break;
        
            case 'write_instant_message':
                settype($copie, 'string');
                settype($messages, 'string');
        
                if (isset($user)) {
                    $rowQ1 = Q_Select("SELECT uid FROM users WHERE uname='$cookie[1]'", 3600);
                    $uid = $rowQ1[0];
        
                    $from_userid = $uid['uid'];
        
                    if (($subject != '') or ($message != '')) {
                        $subject = FixQuotes($subject) . '';
                        $messages = FixQuotes($messages) . '';
                        writeDB_private_message($to_userid, '', $subject, $from_userid, $message, $copie);
                    }
                }
        
                Header("Location: index.php");
                break;
        

        }        
    }


}