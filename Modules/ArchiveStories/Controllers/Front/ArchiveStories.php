<?php

namespace Modules\ArchiveStories\Controllers\Front;

use Npds\Config\Config;
use Npds\Supercache\SuperCacheEmpty;
use Npds\Supercache\SuperCacheManager;
use Modules\News\Support\Facades\News;
use Modules\Npds\Core\FrontController;
use Modules\Users\Support\Facades\User;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Paginator;

use function PHP81_BC\strftime;

/**
 * [UserLogin description]
 */
class ArchiveStories extends FrontController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();              
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * [after description]
     *
     * @param   [type]  $result  [$result description]
     *
     * @return  [type]           [return description]
     */
    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        if (!isset($start)) {
            $start = 0;
        }

        // Include cache manager
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }

        if (($cache_obj->get_Genereting_Output() == 1) 
        or ($cache_obj->get_Genereting_Output() == -1) 
        or (!$SuperCache)) {

            if ($arch_titre) {
                echo Language::aff_langue($arch_titre);
            }

            echo '
            <hr />
            <table id ="lst_art_arch" data-toggle="table"  data-striped="true" data-search="true" data-show-toggle="true" data-show-columns="true" data-mobile-responsive="true" data-icons-prefix="fa" data-buttons-class="outline-secondary" data-icons="icons">
                <thead>
                    <tr>
                        <th data-sortable="true" data-sorter="htmlSorter" data-halign="center" class="n-t-col-xs-4">' . __d('archivestories', 'Articles"') . '</th>
                        <th data-sortable="true" data-halign="center" data-align="right" class="n-t-col-xs-1">' . __d('archivestories', 'lus"') . '</th>
                        <th data-halign="center" data-align="right">' . __d('archivestories', 'Posté le"') . '</th>
                        <th data-sortable="true" data-halign="center" data-align="left">' . __d('archivestories', 'Auteur"') . '</th>
                        <th data-halign="center" data-align="center" class="n-t-col-xs-2">' . __d('archivestories', 'Fonctions"') . '</th>
                    </tr>
                </thead>
                <tbody>';

            if (!isset($count)) {
                
                $result0 = Q_select("SELECT COUNT(sid) AS count FROM stories WHERE archive='$arch'", 3600);

                $count = $result0[0];
                $count = $count['count'];
            }

            $nbPages = ceil($count / $maxcount);
            $current = 1;

            if ($start >= 1) {
                $current = $start / $maxcount;
            } elseif ($start < 1) {
                $current = 0;
            } else { 
                $current = $nbPages;
            }

            $xtab = $arch == 0 
                ? News::news_aff("libre", "WHERE archive='$arch' ORDER BY sid DESC LIMIT $start,$maxcount", $start, $maxcount) 
                : News::news_aff("archive", "WHERE archive='$arch' ORDER BY sid DESC LIMIT $start,$maxcount", $start, $maxcount);

            $ibid = 0;
            $story_limit = 0;

            while (($story_limit < $maxcount) and ($story_limit < sizeof($xtab))) {
                list($s_sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant) = $xtab[$story_limit];

                $story_limit++;

                $printP = '<a href="print.php?sid=' . $s_sid . '&amp;archive=' . $arch . '"><i class="fa fa-print fa-lg" title="' . __d('archivestories', 'Page spéciale pour impression"') . '" data-bs-toggle="tooltip" data-bs-placement="left"></i></a>';
                $sendF = '<a class="ms-4" href="friend.php?op=FriendSend&amp;sid=' . $s_sid . '&amp;archive=' . $arch . '"><i class="fa fa-at fa-lg" title="' . __d('archivestories', 'Envoyer cet article à un ami"') . '" data-bs-toggle="tooltip" data-bs-placement="left" ></i></a>';
            
                $sid = $s_sid;

                if ($catid != 0) {
                    $resultm = sql_query("SELECT title FROM stories_cat WHERE catid='$catid'");
                    list($title1) = sql_fetch_row($resultm);

                    $title = '<a href="article.php?sid=' . $sid . '&amp;archive=' . $arch . '" >' . Language::aff_langue(ucfirst($title)) . '</a> [ <a href="index.php?op=newindex&amp;catid=' . $catid . '">' . Language::aff_langue($title1) . '</a> ]';
                } else {
                    $title = '<a href="article.php?sid=' . $sid . '&amp;archive=' . $arch . '" >' . Language::aff_langue(ucfirst($title)) . '</a>';
                }

                setlocale(LC_TIME, Language::aff_langue(Config::get('npds.locale')));
                preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime);
                $datetime = strftime("%d-%m-%Y %H:%M:%S", mktime($datetime[4] + (int) Config::get('npds.gmt'), $datetime[5], $datetime[6], $datetime[2], $datetime[3], $datetime[1]));

                if (cur_charset != "utf-8") {
                    $datetime = ucfirst($datetime);
                } 

                echo '
                    <tr>
                    <td>' . $title . '</td>
                    <td>' . $counter . '</td>
                    <td><small>' . $datetime . '</small></td>
                    <td>' . User::userpopover($informant, 40, 2) . ' ' . $informant . '</td>
                    <td>' . $printP . $sendF . '</td>
                    </tr>';
            }

            echo '
                    </tbody>
                </table>
                <div class="d-flex my-3 justify-content-between flex-wrap">
                <ul class="pagination pagination-sm">
                    <li class="page-item disabled"><a class="page-link" href="#" >' . __d('archivestories', 'Nb. d\'articles') . ' ' . $count . ' </a></li>
                    <li class="page-item disabled"><a class="page-link" href="#" >' . $nbPages . ' ' . __d('archivestories', 'pages') . '</a></li>
                </ul>';

            echo Paginator::paginate('modules.php?ModPath=archive-stories&amp;ModStart=archive-stories&amp;start=', '&amp;count=' . $count, $nbPages, $current, 1, $maxcount, $start);
            
            echo '</div>';
        }

        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }
    }

}
