<?php
/**
 * Npds - Modules/Users/boxe/Boxe.php
 *
 * @author  Nicolas Devoy
 * @email   nicolas@nicodev.fr 
 * @version 1.0.0
 * @date    26 Septembre 2024
 */

use Npds\view\View;
use Npds\Http\Request;
use Npds\Support\Facades\DB;
use App\Modules\Users\Models\User;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Theme\Support\Facades\Theme;

//use function time;

/**
 * Bloc Login
 * 
 * syntaxe : function#loginbox
 *
 * @return  void
 */
function loginbox(): void
{
    global $block_title;

    if (!Auth::guard('user')) {
        Theme::themesidebox(
            ($block_title ?: __d('users', 'Se connecter')), 
            View::make('Modules/Users/Views/Boxe/boxe_login')
        );
    }
}

/**
 * Bloc membre
 * 
 * syntaxe : function#userblock
 *
 * @return  void
 */
function userblock(): void
{
    global $block_title;

    if (Auth::guard('user') and Cookie::cookie_user(8)) {
        $user_block = DBQ_select(
            DB::table('users')->select('ublock')->where('uid', Cookie::cookie_user(0))->first(), 
            86400
        );

        Theme::themesidebox(($block_title ?: __d('users', 'Menu de {0}', Cookie::cookie_user(1))), $user_block['ublock']);
    }
}

/**
 * Bloc Online (Who_Online)
 * 
 * syntaxe : function#online
 *
 * @return  void
 */
function online(): void
{
    global $block_title;

    DB::table('session')->where('time', '<', (time() - 300))->delete();

    $username = Cookie::cookie_user(1) ?: '';

    $ip = Request::getip();

    $data = [
        'username'  => ($username != $ip ? $username : $ip),
        'time'      => time(),
        'host_addr' => $ip,
        'guest'     => ($username != $ip ? 0 : 1),
    ];

    if ($username != $ip) {
        DB::table('session')->where('username', $username)->update($data);
    } else {
        DB::table('session')->insert($data);
    }

    if ($user = Auth::guard('user')) {
        $count_pmsg = User::where('uname', $username)->first();
    }

    Theme::themesidebox(
        ($block_title ?: __d('users', 'Qui est en ligne ?')), 
        View::make('Modules/Users/Views/Boxe/boxe_online',
            [
                'user'              => $user,
                'count_msg'         => ($user ? $count_pmsg->priv_msg()->count() : 0),
                'username'          => $username,
                'guest_online_num'  => DB::table('session')->select('username')->where('guest', 1)->count(),
                'member_online_num' => DB::table('session')->select('username')->where('guest', 0)->count(),
            ]
        )
    );
}
