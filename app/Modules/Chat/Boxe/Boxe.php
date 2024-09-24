<?php

use Npds\view\View;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Crypt;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Blocks\Support\Facades\Block;
use App\Modules\Npds\Support\Facades\Emoticone;


/**
 * Chat Box
 * 
 * syntaxe : function#makeChatBox
 * 
 * params#chat_tous
 *
 * @param   [type]  $pour  [$pour description]
 *
 * @return  [type]         [return description]
 */
function makeChatBox($pour)
{
    global $long_chain, $block_title;

    if (!$long_chain) {
        $long_chain = 12;
    }

    $une_ligne = false;

    $auto = Block::autorisation_block('params#' . $pour);
    $dimauto = count($auto);

    $chat_box = [];

    if ($dimauto <= 1) {
        $counter = DB::table('chatbox')->select('message')->where('id', $auto[0])->count()-6;

        if ($counter < 0) {
            $counter = 0;
        }

        if ($chatbox = DB::table('chatbox')
                        ->select('username', 'message', 'dbname')
                        ->where('id', $auto[0])
                        ->orderBy('date', 'asc')
                        ->offset($counter)
                        ->limit(6)
                        ->get()) 
        { 

            $chat_box = [];
            
            foreach($chatbox as $chat) 
            {
                if (isset($chat['username'])) {
                    if ($chat['dbname'] == 1) {
                        $guard_and_list = false;
                        if ( (!is_null(Auth::guard('user'))) and (Config::get('npds.member_list') == 1) and (!is_null(Auth::guard('admin'))) ) { 
                            $guard_and_list = true;
                        }
                    }
                }

                $message = ((strlen($chat['message']) > $long_chain)  
                    ? Emoticone::smilie(stripslashes(substr($chat['message'], 0, $long_chain))) 
                    : Emoticone::smilie(stripslashes($chat['message']))
                );

                $une_ligne = true;

                $chat_box[] = [
                    'db_name'           => $chat['dbname'],
                    'db_username'       => $chat['username'],
                    'guard_and_list'    => $guard_and_list,
                    'username'          => substr($chat['username'], 0, 8),
                    'message'           => $message,
                ];
            }
        }

        $PopUp = JavaPopUp(site_url('chat.php?id=' . $auto[0] . '&amp;auto=' . Crypt::encrypt(serialize($auto[0]))), "chat" . $auto[0], 380, 480);

        $count_chatters = DB::table('chatbox')
                            ->selectDistinct('ip')
                            ->where('id', $auto[0])
                            ->where('date', '>=', (time() - (60 * 2)))
                            ->count();

    } else {
        if (count($auto) > 1) {
            $chat_box       = [];
            $count_chatters = 0;

            foreach ($auto as $autovalue) { 
                $chat_box[] = [
                    'autovalueX'        => $autovalueX = DBQ_select(DB::table('groupes')->select('groupe_id', 'groupe_name')->where('groupe_id', $autovalue)->first(), 3600),
                    'PopUp'             => JavaPopUp(site_url('chat.php?id=' . $autovalueX['groupe_id'] . '&auto=' . Crypt::encrypt(serialize($autovalueX['groupe_id']))), "chat" . $autovalueX['groupe_id'], 380, 480),
                    'count_chatters'    => DB::table('chatbox')
                                                ->selectDistinct('ip')
                                                ->where('id', $autovalueX['groupe_id'])
                                                ->where('date', '>=', (time() - (60 * 3)))
                                                ->count(),
                ];
            }
        }
    }

    Theme::themesidebox(
        ($block_title ?: __d('chat', 'Bloc Chat')), 
        View::make('Modules/Chat/Views/Boxe/Chat_Box', 
            compact('dimauto', 'une_ligne', 'pour', 'PopUp', 'count_chatters', 'chat_box', 'auto')
        )
    );
}
