<?php

namespace App\Modules\Users\Library\Traits;

use Npds\Routing\Url;
use Npds\Session\Session;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Facades\Cookie;

/**
 * Undocumented trait
 */
trait HiddenFormTrait
{

    function hidden_form()
    {
        global $uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $vpass, $C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1, $charte, $user_lnl;
        
        if (!$user_avatar) {
            $user_avatar = "blank.gif";
        }
    
        echo '
        <form action="user.php" method="post">
            <input type="hidden" name="uname" value="' . $uname . '" />
            <input type="hidden" name="name" value="' . removeHack($name) . '" />
            <input type="hidden" name="email" value="' . $email . '" />
            <input type="hidden" name="user_avatar" value="' . $user_avatar . '" />
            <input type="hidden" name="user_from" value="' . StripSlashes(removeHack($user_from)) . '" />
            <input type="hidden" name="user_occ" value="' . StripSlashes(removeHack($user_occ)) . '" />
            <input type="hidden" name="user_intrest" value="' . StripSlashes(removeHack($user_intrest)) . '" />
            <input type="hidden" name="user_sig" value="' . StripSlashes(removeHack($user_sig)) . '" />
            <input type="hidden" name="user_viewemail" value="' . $user_viewemail . '" />
            <input type="hidden" name="pass" value="' . removeHack($pass) . '" />
            <input type="hidden" name="user_lnl" value="' . removeHack($user_lnl) . '" />
            <input type="hidden" name="C1" value="' . StripSlashes(removeHack($C1)) . '" />
            <input type="hidden" name="C2" value="' . StripSlashes(removeHack($C2)) . '" />
            <input type="hidden" name="C3" value="' . StripSlashes(removeHack($C3)) . '" />
            <input type="hidden" name="C4" value="' . StripSlashes(removeHack($C4)) . '" />
            <input type="hidden" name="C5" value="' . StripSlashes(removeHack($C5)) . '" />
            <input type="hidden" name="C6" value="' . StripSlashes(removeHack($C6)) . '" />
            <input type="hidden" name="C7" value="' . StripSlashes(removeHack($C7)) . '" />
            <input type="hidden" name="C8" value="' . StripSlashes(removeHack($C8)) . '" />
            <input type="hidden" name="M1" value="' . StripSlashes(removeHack($M1)) . '" />
            <input type="hidden" name="M2" value="' . StripSlashes(removeHack($M2)) . '" />
            <input type="hidden" name="T1" value="' . StripSlashes(removeHack($T1)) . '" />
            <input type="hidden" name="T2" value="' . StripSlashes(removeHack($T2)) . '" />
            <input type="hidden" name="B1" value="' . StripSlashes(removeHack($B1)) . '" />';
    }

}
