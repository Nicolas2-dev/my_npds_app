<?php

namespace Modules\Messenger\Controllers\Api;

use Modules\Npds\Core\FrontController;
use Modules\Messenger\Support\Facades\Messenger;

/**
 * Undocumented class
 */
class ApiMessenger extends FrontController
{

    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        //
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function instant_message()
    {
        Messenger::Form_instant_message($to_userid);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function write_instant_message()
    {
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
    } 

}
