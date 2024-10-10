<?php

namespace Modules\Users\Library\Traits;

use Npds\Routing\Url;
use Npds\Session\Session;
use Npds\Support\Facades\DB;
use Modules\Npds\Support\Facades\Cookie;

/**
 * Undocumented trait
 */
trait UserLogoutTrait
{

    public function logout($mess_logout = true)
    {
        global $user, $user_language;
        
        $user_cookie = Cookie::cookie_user(1);

        if ($user_cookie != '') {
            DB::table('session')->where('username', $user_cookie)->delete();
        }
    
        Cookie::set('user', '', 0);
        unset($user);
    
        Cookie::set('user_language', '', 0);
        unset($user_language);
    
        if ($mess_logout) {
            Session::set('logout', ['type' => 'warning', 'text' => __d('users', 'Vous ete maintenant déconecter de votre compte.')]);            
        }
        
        return Url::redirect('index');
    }

}
