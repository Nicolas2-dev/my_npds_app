<?php

use Npds\Config\Config;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Users\Support\Facades\Online;


/**
 * Message à un membre
 *
 * syntaxe : function#instant_members_message
 * 
 * @return  [type]  [return description]
 */
function instant_members_message()
{
    global $long_chain, $cookie, $block_title;
    
    if (!$long_chain) {
        $long_chain = 13;
    }

    if (Auth::guard('user')) {
        $boxstuff = '<ul class="">';

        $ibid = Online::online_members();

        for ($i = 1; $i <= $ibid[0]; $i++) {

            if (Config::get('npds.member_invisible')) {
                if (Auth::guard('admin')) {
                    $and = '';
                } else {
                    if ($ibid[$i]['username'] == $cookie[1]) {
                        $and = '';
                    } else {
                        $and = "AND is_visible=1";
                    }
                }
            } else {
                $and = '';
            }

            $result = sql_query("SELECT id FROM users WHERE uname='" . $ibid[$i]['username'] . "' $and");
            list($userid) = sql_fetch_row($result);

            if ($userid) {

                $new_messages = sql_num_rows(sql_query("SELECT id FROM priv_msgs WHERE user_id = '$userid' AND read_msg='0' AND type_msg='0'"));
                
                $icon = '';
                
                if ($new_messages > 0) {

                    $li = '<i class="fa fa-envelope fa-lg faa-shake animated" title="' . __d('messenger', 'Nouveau') . '<span class=\'rounded-pill bg-danger ms-2\'>' . $new_messages . '</span>" data-bs-html="true" data-bs-toggle="tooltip"></i>';

                    if ($ibid[$i]['username'] == $cookie[1]) {
                        $icon = '<a href="javascript:void(0);" onclick="window.open(' . JavaPopUp("readpmsg_imm.php?op=new_msg", "IMM", 600, 500) . ');">' . $li . '</a>';
                    } else {
                        $icon = $li;
                    }

                } else {
                    $messages = sql_num_rows(sql_query("SELECT id FROM priv_msgs WHERE user_id = '$userid' AND type_msg='0' AND dossier='...'"));
                    
                    if ($messages > 0) {

                        $li = '<i class="far fa-envelope-open fa-lg " title="' . __d('messenger', 'Nouveau') . ' : ' . $new_messages . '" data-bs-toggle="tooltip"></i></a>';

                        if ($ibid[$i]['username'] == $cookie[1]) {
                            $icon = '<a href="javascript:void(0);" onclick="window.open(' . JavaPopUp(site_url('readpmsg_imm.php?op=msg'), "IMM", 600, 500) . ');">'. $li .'</a>';
                        } else {
                            $icon = $li;
                        }
                    } 
                }

                $N = $ibid[$i]['username'];

                if (strlen($N) > $long_chain) {
                    $M = substr($N, 0, $long_chain) . '.';
                } else {
                    $M = $N;
                }

                $timex = time() - $ibid[$i]['time'];

                if ($timex >= 60) {
                    $timex = '<i class="fa fa-plug text-muted" title="' . $ibid[$i]['username'] . ' ' . __d('messenger', 'n\'est pas connecté') . '" data-bs-toggle="tooltip" data-bs-placement="right"></i>&nbsp;';
                } else {
                    $timex = '<i class="fa fa-plug faa-flash animated text-primary" title="' . $ibid[$i]['username'] . ' ' . __d('messenger', 'est connecté') . '" data-bs-toggle="tooltip" data-bs-placement="right" ></i>&nbsp;';
                }

                // suppression temporaire ... rank
                // $image_rang = User::user_rang($userid);

                $boxstuff .= '
                <li class="">
                    ' . $timex . '&nbsp;
                    <a href="'. site_url('powerpack.php?op=instant_message&amp;to_userid=' . $N) . '" title="' . __d('messenger', 'Envoyer un message interne') . '" data-bs-toggle="tooltip" >
                        ' . $M . '
                    </a>
                    <span class="float-end">' .  $icon . '</span>
                </li>';
                // suppression temporaire ... rank  '.$image_rang.'
            } 
        }

        $boxstuff .= '</ul>';

        Theme::themesidebox(($block_title ?: __d('messenger', 'M2M bloc')), $boxstuff);
    } else {
        if (Auth::guard('admin')) {
            $ibid = Online::online_members();

            if ($ibid[0]) {
                $boxstuff = '<ul class="">';
                
                for ($i = 1; $i <= $ibid[0]; $i++) {
                    $N = $ibid[$i]['username'];
                    $M = strlen($N) > $long_chain ? substr($N, 0, $long_chain) . '.' : $N;

                    $boxstuff .= $M . '<br />';
                }

                $boxstuff .= '</ul>';

                Theme::themesidebox(($block_title ?: __d('messenger', 'M2M bloc')), $boxstuff);
            }
        }
    }
}
