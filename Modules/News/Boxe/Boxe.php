<?php

use Npds\Config\Config;
use Npds\Support\Facades\DB;
use Modules\News\Support\Facades\News;
use Modules\Npds\Support\Facades\Date;
use Modules\Theme\Support\Facades\Theme;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Auth;

 
/**
 * Bloc des Rubriques
 * 
 * syntaxe : function#bloc_rubrique
 *
 * @return  [type]  [return description]
 */
function bloc_rubrique()
{    
    global $block_title;

    $aff_block = false;

    foreach (DB::table('rubriques')
                ->select('rubid', 'rubname', 'ordre')
                ->where('enligne', 1)
                ->where('rubname', '<>', 'divers')
                ->orderBy('ordre')
                ->get() as $rubrique) {
                    
        $boxstuff = '<ul>';
        $boxstuff .= '<li><strong>' . Language::aff_langue($rubrique['rubname']) . '</strong></li>';

        foreach (DB::table('sections')
                    ->select('secid', 'secname', 'userlevel', 'ordre')
                    ->where('rubid', $rubrique['rubid'])
                    ->orderBy('ordre')
                    ->get() as $section) {

            if (DB::table('seccont')->select('artid')->where('secid', $section['secid'])->count() > 0) {
                
                $boxstuff .= '<ul>';
                $tmp_auto = explode(',', $section['userlevel']);

                foreach ($tmp_auto as $userlevel) {
                    $okprintLV1 = Auth::autorisation($userlevel);
                    
                    if ($okprintLV1) {
                        break;
                    }
                }

                if ($okprintLV1) {
                    $sec = Language::aff_langue($section['secname']);
                    $boxstuff .= '<li><a href="'. site_url('sections.php?op=listarticles&amp;secid=' . $section['secid']) . '">' . $sec . '</a></li>';
                }

                $aff_block = true;                
            }
        }

        $boxstuff .= '</ul>';
    }

    if ($aff_block) {
        Theme::themesidebox(($block_title == '' ? __d('rubriques', 'Rubriques') : $block_title), $boxstuff);
    }
}

/**
 * Bloc Anciennes News
 * 
 * syntaxe : function#oldNews
 * 
 * params#$storynum, lecture (affiche le NB de lecture) - facultatif
 *
 * @param   [type]  $storynum  [$storynum description]
 * @param   [type]  $typ_aff   [$typ_aff description]
 *
 * @return  [type]             [return description]
 */
function oldNews($storynum, $typ_aff = '')
{
    global $categories, $cat, $user, $cookie, $block_title;;

    $boxstuff = '<ul class="list-group">';

    if (($categories == 1) and ($cat != '')) {
        $sel = $user ? "WHERE catid='$cat'" : "WHERE catid='$cat' AND ihome=0";
    } else {
        $sel = $user ? '' : "WHERE ihome=0";
    }

    $sel =  "WHERE ihome=0"; // en dur pour test
    $vari = 0;

    $storynum = isset($cookie[3]) ? $cookie[3] : Config::get('npds.storyhome');

    $xtab = News::news_aff('old_news', $sel, $storynum, Config::get('npds.oldnum'));

    $story_limit    = 0;
    $time2          = 0;
    $a              = 0;

    while (($story_limit < Config::get('npds.oldnum')) and ($story_limit < sizeof($xtab))) {
        
        list($sid, $title, $time, $comments, $counter) = $xtab[$story_limit];
        
        $story_limit++;

        setlocale(LC_TIME, Language::aff_langue(Config::get('npds.locale')));

        preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime2);
        
        $datetime2 = strftime("" . __d('news', 'datestring2') . "", @mktime($datetime2[4], $datetime2[5], $datetime2[6], $datetime2[2], $datetime2[3], $datetime2[1]));

        if (Config::get('npds.language') != 'chinese') {
            $datetime2 = ucfirst($datetime2);
        }

        $comments = $typ_aff == 'lecture' 
            ? '<span class="badge rounded-pill bg-secondary ms-1" title="' . __d('news', 'Lu') . '" data-bs-toggle="tooltip">' . $counter . '</span>' 
            : '';

        if ($time2 == $datetime2) {
            $boxstuff .= '<li class="list-group-item list-group-item-action d-inline-flex justify-content-between align-items-center">
                    <a class="n-ellipses" href="'. site_url('article.php?sid=' . $sid) . '">
                        ' . Language::aff_langue($title) . '
                    </a>' . $comments . '</li>';

        } else {
            if ($a == 0) {
                $boxstuff .= '<li class="list-group-item fs-6">
                            ' . $datetime2 . '
                        </li>
                        <li class="list-group-item list-group-item-action d-inline-flex justify-content-between align-items-center">
                            <a href="'. site_url('article.php?sid=' . $sid) . '">
                                ' . Language::aff_langue($title) . '
                            </a>
                            ' . $comments . '
                        </li>';

                $time2 = $datetime2;
                $a = 1;
            } else {
                $boxstuff .= '<li class="list-group-item fs-6">
                            ' . $datetime2 . '
                        </li>
                        <li class="list-group-item list-group-item-action d-inline-flex justify-content-between align-items-center">
                            <a href="'. site_url('article.php?sid=' . $sid) . '">
                                ' . Language::aff_langue($title) . '
                            </a>
                            ' . $comments . '
                        </li>';

                $time2 = $datetime2;
            }
        }

        $vari++;

        if ($vari == Config::get('npds.oldnum')) {
            $storynum = isset($cookie[3]) ? $cookie[3] : Config::get('npds.storyhome');

            // $min = Config::get('npds.oldnum') + $storynum;

            $boxstuff .= '<li class="text-center mt-3" >
                    <a href="'. site_url('search.php?min=$min&amp;type=stories&amp;category='. $cat) .'">
                        <strong>' . __d('news', 'Articles plus anciens') . '</strong>
                    </a>
                </li>';
        }
    }

    $boxstuff .= '</ul>';

    if ($boxstuff == '<ul></ul>') {
        $boxstuff = '';
    }

    Theme::themesidebox(($block_title ?: __d('news', 'Anciens articles')), $boxstuff);
}
 
/**
 * Bloc BigStory
 * 
 * syntaxe : function#bigstory
 *
 * @return  [type]  [return description]
 */
function bigstory()
{
    global $block_title;

    $content    = '';
    $today      = getdate();
    $day        = $today['mday'];

    if ($day < 10) {
        $day = "0$day";
    }

    $month = $today['mon'];

    if ($month < 10) {
        $month = "0$month";
    }

    $year   = $today['year'];
    $tdate  = "$year-$month-$day";

    $xtab   = News::news_aff("big_story", "WHERE (time LIKE '%$tdate%')", 1, 1);

    if (sizeof($xtab)) {
        list($fsid, $ftitle) = $xtab[0];
    } else {
        $fsid = '';
        $ftitle = '';
    }

    $content .= ($fsid == '' and $ftitle == '') 
        ? '<span class="fw-semibold">' . __d('news', 'Il n\'y a pas encore d\'article du jour.') . '</span>' 
        : '<span class="fw-semibold">' . __d('news', 'L\'article le plus consulté aujourd\'hui est :') . '</span>
            <br /><br />
            <a href="'. site_url('article.php?sid=' . $fsid) . '">
                ' . Language::aff_langue($ftitle) . '
            </a>';

    Theme::themesidebox(($block_title ?: __d('news', 'Article du Jour')), $content);
}
 
/**
 * Bloc de gestion des catégories
 *
 * syntaxe : function#category
 * 
 * @return  [type]  [return description]
 */
function category()
{
    global $cat, $block_title;

    $result = sql_query("SELECT catid, title FROM stories_cat ORDER BY title");
    $numrows = sql_num_rows($result);

    if ($numrows == 0) {
        return;
    } else {
        $boxstuff = '<ul>';

        while (list($catid, $title) = sql_fetch_row($result)) {

            $result2 = sql_query("SELECT sid FROM stories WHERE catid='$catid' LIMIT 0,1");
            $numrows = sql_num_rows($result2);

            if ($numrows > 0) {

                $res = sql_query("SELECT time FROM stories WHERE catid='$catid' ORDER BY sid DESC LIMIT 0,1");
                list($time) = sql_fetch_row($res);

                $boxstuff .= $cat == $catid 
                    ? '<li><strong>' . Language::aff_langue($title) . '</strong></li>' 
                    : '<li class="list-group-item list-group-item-action hyphenate">
                            <a href="'. site_url('index.php?op=newcategory&amp;catid=' . $catid) . '" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right" title="' . __d('news', 'Dernière contribution') . ' <br />' . Date::formatTimestamp($time) . ' ">
                                ' . Language::aff_langue($title) . '
                            </a>
                        </li>';
            }
        }

        $boxstuff .= '</ul>';

        Theme::themesidebox(($block_title ?: __d('news', 'Catégories')), $boxstuff);
    }
}
