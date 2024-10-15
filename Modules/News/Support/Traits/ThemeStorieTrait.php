<?php

namespace Modules\News\Support\Traits;

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Date;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;

use function PHP81_BC\strftime;

/**
 * Undocumented trait
 */
trait ThemeStorieTrait
{

    /**
     * [themeindex description]
     *
     * @param   [type]  $aid         [$aid description]
     * @param   [type]  $informant   [$informant description]
     * @param   [type]  $time        [$time description]
     * @param   [type]  $title       [$title description]
     * @param   [type]  $counter     [$counter description]
     * @param   [type]  $topic       [$topic description]
     * @param   [type]  $thetext     [$thetext description]
     * @param   [type]  $notes       [$notes description]
     * @param   [type]  $morelink    [$morelink description]
     * @param   [type]  $topicname   [$topicname description]
     * @param   [type]  $topicimage  [$topicimage description]
     * @param   [type]  $topictext   [$topictext description]
     * @param   [type]  $id          [$id description]
     *
     * @return  [type]               [return description]
     */
    public function themeindex($aid, $informant, $time, $title, $counter, $topic, $thetext, $notes, $morelink, $topicname, $topicimage, $topictext, $id)
    {
        $inclusion = false;
    
        $theme      = with(get_instance())->template();
        $theme_dir  = with(get_instance())->template_dir();

        if (file_exists(theme_path($theme_dir .'/'. $theme . "/views/index-news.html"))) {
            $inclusion = theme_path($theme_dir .'/'. $theme . "/views/index-news.html");

        } elseif (file_exists(app_path("Themes/default/views/index-news.html"))) {
            $inclusion = app_path("Themes/default/views/index-news.html");

        } else {
            echo 'index-news.html manquant / not find !<br />';
            die();
        }
    
        $H_var = $this->local_var($thetext);
    
        if ($H_var != '') {
            ${$H_var} = true;
            $thetext = str_replace("!var!$H_var", "", $thetext);
        }
    
        if ($notes != '') {
            $notes = '<div class="note">' . __d('news', 'Note') . ' : ' . $notes . '</div>';
        }

        ob_start();
            include($inclusion);
            $Xcontent = ob_get_contents();
        ob_end_clean();
    
        $lire_la_suite = '';
    
        if ($morelink[0]) {
            $lire_la_suite = $morelink[1] . ' ' . $morelink[0] . ' | ';
        }

        $commentaire = '';
    
        if ($morelink[2]) {
            $commentaire = $morelink[2] . ' ' . $morelink[3] . ' | ';
        } else {
            $commentaire = $morelink[3] . ' | ';
        }
    
        $categorie = '';
    
        if ($morelink[6]) {
            $categorie = ' : ' . $morelink[6];
        }
    
        $morel = $lire_la_suite . $commentaire . $morelink[4] . ' ' . $morelink[5] . $categorie;
    
        $Xsujet = '';
    
        if ($topicimage != '') {
            if (!$imgtmp = $this->theme_image('topics/' . $topicimage)) {
                $imgtmp = Config::get('npds.tipath') . $topicimage;
            }
    
            $Xsujet = '<a href="search.php?query=&amp;topic=' . $topic . '"><img class="img-fluid" src="' . $imgtmp . '" alt="' . __d('news', 'Rechercher dans') . ' : ' . $topicname . '" title="' . __d('news', 'Rechercher dans') . ' : ' . $topicname . '<hr />' . $topictext . '" data-bs-toggle="tooltip" data-bs-html="true" /></a>';
        } else {
            $Xsujet = '<a href="search.php?query=&amp;topic=' . $topic . '"><span class="badge bg-secondary h1" title="' . __d('news', 'Rechercher dans') . ' : ' . $topicname . '<hr />' . $topictext . '" data-bs-toggle="tooltip" data-bs-html="true">' . $topicname . '</span></a>';
        }

        $App_METALANG_words = array(
            "'!N_publicateur!'i"    => $aid,
            "'!N_emetteur!'i"       => $this->userpopover($informant, 40, 2) . '<a href="user.php?op=userinfo&amp;uname=' . $informant . '">' . $informant . '</a>',
            "'!N_date!'i"           => Date::formatTimestamp($time),
            "'!N_date_y!'i"         => substr($time, 0, 4),
            "'!N_date_m!'i"         => strftime("%B", mktime(0, 0, 0, substr($time, 5, 2), 1, 2000), Config::get('npds.locale')),
            "'!N_date_d!'i"         => substr($time, 8, 2),
            "'!N_date_h!'i"         => substr($time, 11),
            "'!N_print!'i"          => $morelink[4],
            "'!N_friend!'i"         => $morelink[5],
            "'!N_nb_carac!'i"       => $morelink[0],
            "'!N_read_more!'i"      => $morelink[1],
            "'!N_nb_comment!'i"     => $morelink[2],
            "'!N_link_comment!'i"   => $morelink[3],
            "'!N_categorie!'i"      => $morelink[6],
            "'!N_titre!'i"          => $title,
            "'!N_texte!'i"          => $thetext,
            "'!N_id!'i"             => $id,
            "'!N_sujet!'i"          => $Xsujet,
            "'!N_note!'i"           => $notes,
            "'!N_nb_lecture!'i"     => $counter,
            "'!N_suite!'i"          => $morel
        );
    
        echo Metalang::meta_lang(Language::aff_langue(preg_replace(array_keys($App_METALANG_words), array_values($App_METALANG_words), $Xcontent)));
    }
    
    /**
     * [themearticle description]
     *
     * @param   [type]  $aid           [$aid description]
     * @param   [type]  $informant     [$informant description]
     * @param   [type]  $time          [$time description]
     * @param   [type]  $title         [$title description]
     * @param   [type]  $thetext       [$thetext description]
     * @param   [type]  $topic         [$topic description]
     * @param   [type]  $topicname     [$topicname description]
     * @param   [type]  $topicimage    [$topicimage description]
     * @param   [type]  $topictext     [$topictext description]
     * @param   [type]  $id            [$id description]
     * @param   [type]  $previous_sid  [$previous_sid description]
     * @param   [type]  $next_sid      [$next_sid description]
     * @param   [type]  $archive       [$archive description]
     *
     * @return  [type]                 [return description]
     */
    public function themearticle($aid, $informant, $time, $title, $thetext, $topic, $topicname, $topicimage, $topictext, $id, $previous_sid, $next_sid, $archive)
    {
        global $counter, $boxtitle, $boxstuff, $user;
    
        $inclusion = false;
    
        $theme      = with(get_instance())->template();
        $theme_dir  = with(get_instance())->template_dir();

        if (file_exists(theme_path($theme_dir .'/'. $theme . "/views/detail-news.html"))) {
            $inclusion = theme_path($theme_dir .'/'. $theme . "/views/detail-news.html");

        } elseif (file_exists(app_path("Themes/default/views/detail-news.html"))) {
            $inclusion = app_path("Themes/default/views/detail-news.html");

        } else {
            echo 'detail-news.html manquant / not find !<br />';
            die();
        }
    
        $H_var = $this->local_var($thetext);
    
        if ($H_var != '') {
            ${$H_var} = true;
            $thetext = str_replace("!var!$H_var", '', $thetext);
        }
    
        ob_start();
            include($inclusion);
            $Xcontent = ob_get_contents();
        ob_end_clean();
    
        if ($previous_sid) {
            $prevArt = '<a href="article.php?sid=' . $previous_sid . '&amp;archive=' . $archive . '" ><i class="fa fa-chevron-left fa-lg me-2" title="' . __d('news', 'Précédent') . '" data-bs-toggle="tooltip"></i><span class="d-none d-sm-inline">' . __d('news', 'Précédent') . '</span></a>';
        } else {
            $prevArt = '';
        }

        if ($next_sid) {
            $nextArt = '<a href="article.php?sid=' . $next_sid . '&amp;archive=' . $archive . '" ><span class="d-none d-sm-inline">' . __d('news', 'Suivant') . '</span><i class="fa fa-chevron-right fa-lg ms-2" title="' . __d('news', 'Suivant') . '" data-bs-toggle="tooltip"></i></a>';
        } else {
            $nextArt = '';
        }
    
        $printP = '<a href="print.php?sid=' . $id . '" title="' . __d('news', 'Page spéciale pour impression') . '" data-bs-toggle="tooltip"><i class="fa fa-2x fa-print"></i></a>';
        $sendF = '<a href="friend.php?op=FriendSend&amp;sid=' . $id . '" title="' . __d('news', 'Envoyer cet article à un ami') . '" data-bs-toggle="tooltip"><i class="fa fa-2x fa-at"></i></a>';
    
        if (!$imgtmp = $this->theme_image('topics/' . $topicimage)) {
            $imgtmp = Config::get('npds.tipath') . $topicimage;
        }
    
        $timage = $imgtmp;
    
        $App_METALANG_words = array(
            "'!N_publicateur!'i"        => $aid,
            "'!N_emetteur!'i"           => $this->userpopover($informant, 40, 2) . '<a href="user.php?op=userinfo&amp;uname=' . $informant . '"><span class="">' . $informant . '</span></a>',
            "'!N_date!'i"               => Date::formatTimestamp($time),
            "'!N_date_y!'i"             => substr($time, 0, 4),
            "'!N_date_m!'i"             => strftime("%B", mktime(0, 0, 0, substr($time, 5, 2), 1, 2000), Config::get('npds.locale')),
            "'!N_date_d!'i"             => substr($time, 8, 2),
            "'!N_date_h!'i"             => substr($time, 11),
            "'!N_print!'i"              => $printP,
            "'!N_friend!'i"             => $sendF,
            "'!N_boxrel_title!'i"       => $boxtitle,
            "'!N_boxrel_stuff!'i"       => $boxstuff,
            "'!N_titre!'i"              => $title,
            "'!N_id!'i"                 => $id,
            "'!N_previous_article!'i"   => $prevArt,
            "'!N_next_article!'i"       => $nextArt,
            "'!N_sujet!'i"              => '<a href="search.php?query=&amp;topic=' . $topic . '"><img class="img-fluid" src="' . $timage . '" alt="' . __d('news', 'Rechercher dans') . '&nbsp;' . $topictext . '" /></a>',
            "'!N_texte!'i"              => $thetext,
            "'!N_nb_lecture!'i"         => $counter
        );
    
        echo Metalang::meta_lang(Language::aff_langue(preg_replace(array_keys($App_METALANG_words), array_values($App_METALANG_words), $Xcontent)));
    }

}
