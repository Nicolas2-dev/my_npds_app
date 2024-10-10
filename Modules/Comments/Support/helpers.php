<?php

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Date;


// a transferer dans library ici provisoirement

    /**
     * Undocumented function
     *
     * @param [type] $uname
     * @param [type] $uid
     * @return void
     */
    function user_commentaires($uname, $uid)
    {
        $file = '';
        $handle = opendir(module_path('Comments/Config'));

        while (false !== ($file = readdir($handle))) {
            if (!preg_match('#_config\.php$#i', $file)) {
                continue;
            }

            $topic = "#topic#";

            $forum      = Config::get('comments.' . explode('.php', $file)[0] . '.forum');
            $url_ret    = Config::get('comments.' . explode('.php', $file)[0] . '.url_ret');

            // include(module_path('Comments/Config/'.$file));

            // $filelist[$forum] = $url_ret;

            $filelist[$forum] = $url_ret;
        }

        closedir($handle);

        $result = sql_query("SELECT topic_id, forum_id, post_text, post_time FROM posts WHERE forum_id<0 and poster_id='$uid' ORDER BY post_time DESC LIMIT 0,10");

        $userinfo = '
            <h4 class="my-3">' . __d('users', 'Les derniers commentaires de') . ' ' . $uname . '.</h4>
            <div id="last_comment_by" class="card card-body mb-3">';

        $url = '';


        while (list($topic_id, $forum_id, $post_text, $post_time) = sql_fetch_row($result)) {


            // vd($filelist[$forum_id]($topic_id, 0));

            $url = str_replace("#topic#", $topic_id, $filelist[$forum_id]($topic_id, 0));
            $userinfo .= '<p><a href="' . $url . '">' . __d('users', 'Posté : ') . Date::convertdate($post_time) . '</a></p>';

            $message = smilie(stripslashes($post_text));
            $message = aff_video_yt($message);
            $message = str_replace('[addsig]', '', $message);

            if (stristr($message, "<a href")) {
                $message = preg_replace('#_blank(")#i', '_blank\1 class=\1noir\1', $message);
            }

            $userinfo .= nl2br($message) . '<hr />';
        }

        return $userinfo .= '
            </div>';
    }