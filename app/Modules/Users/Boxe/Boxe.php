<?php

use Npds\view\View;
use Npds\Http\Request;
use Npds\Support\Facades\DB;
use App\Modules\Users\Models\User;
use App\Modules\Theme\Support\Facades\Theme;


/**
 * Bloc Login
 * 
 * syntaxe : function#loginbox
 *
 * @return  [type]  [return description]
 */
function loginbox()
{
    global $user, $block_title;

    if (!$user) {
        Theme::themesidebox(
            ($block_title ?: __d('users', 'Se connecter')), 
            View::make('Modules/Users/Views/Boxe/Boxe_Login')
        );
    }
}

/**
 * Bloc membre
 * 
 * syntaxe : function#userblock
 *
 * @return  [type]  [return description]
 */
function userblock()
{
    global $user, $cookie, $block_title;

    if (($user) and ($cookie[8])) {
        $user_block = DBQ_select(
            DB::table('users')->select('ublock')->where('id', $cookie[0])->first(), 
            86400
        );

        Theme::themesidebox(($block_title ?: __d('users', 'Menu de {0}', $cookie[1])), $user_block['ublock']);
    }
}

/**
 * Bloc Online (Who_Online)
 * 
 * syntaxe : function#online
 *
 * @return  [type]  [return description]
 */
function online()
{
    global $user, $cookie, $block_title;

    DB::table('session')->where('time', '<', (time() - 300))->delete();

    $ip = Request::getip();

    $username = isset($cookie[1]) ? $cookie[1] : '';

    $data = [
        'username'  => (($username != $ip) ? $username : $ip),
        'time'      => time(),
        'host_addr' => $ip,
        'guest'     => (($username != $ip) ? 0 : 1),
    ];

    if ($username != $ip) {
        DB::table('session')->where('username', $username)->update($data);
    } else {
        DB::table('session')->insert($data);
    }

    if ($user) {
        $count_pmsg = User::where('uname', $username)->first();
    }

    $query = DB::table('session')->select('username');

    Theme::themesidebox(
        ($block_title ?: __d('users', 'Qui est en ligne ?')), 
        View::make('Modules/Users/Views/Boxe/Boxe_Online',
            [
                'user'              => $user,
                'count_msg'         => ($user ? $count_pmsg->priv_msg()->count() : 0),
                'username'          => $username,
                'guest_online_num'  => $query->where('guest', 1)->count(),
                'member_online_num' => $query->where('guest', 0)->count(),
            ]
        )
    );
}
