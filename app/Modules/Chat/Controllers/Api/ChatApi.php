<?php

namespace App\Modules\chat\Controllers\Api;

use Npds\Routing\Url;
use Npds\Http\Request;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Auth;

class ChatApi extends FrontController
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
     * Purge Chat Box
     */
    public function admin_chatbox_write()
    {
        if (Auth::guard('admin')) {
            
            $admin  = auth::check('admin');
            $adminX = base64_decode($admin);
            $adminR = explode(':', $adminX);
        
            $Q = sql_fetch_assoc(sql_query("SELECT * FROM authors WHERE aid='$adminR[0]' LIMIT 1"));
        
            if ($Q['radminsuper'] == 1) {
                if (Request::post('chatbox_clearDB') == 'OK') {
                    sql_query("DELETE FROM chatbox WHERE date <= " . (time() - (60 * 5)) . "");
                }
            }
        }
                
        Url::redirect('index');
    }

}
