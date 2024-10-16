<?php

namespace Modules\News\Controllers\Front;

use Npds\Config\Config;
use Modules\News\Support\Facades\News;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Code;
use Modules\Npds\Support\Facades\Date;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;

/**
 * Undocumented class
 */
class NewsPrint extends FrontController
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
        if (!empty($sid)) {

            if (!isset($archive)) {
                $this->PrintPage("news", '', '', $sid);
            } else {
                $this->PrintPage("archive", '', '', $sid);
                }
        } else {
            header("location: index.php");
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $oper
     * @param [type] $DB
     * @param [type] $nl
     * @param [type] $sid
     * @return void
     */
    private function PrintPage($oper, $DB, $nl, $sid)
    {
        global $datetime;
    
        $aff = true;
    
        if ($oper == 'news') {
            $xtab = News::news_aff('libre', "WHERE sid='$sid'", 1, 1);
            list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[0];
    
            if ($topic != '') {
                $result2 = sql_query("SELECT topictext FROM topics WHERE topicid='$topic'");
                list($topictext) = sql_fetch_row($result2);
            } else
                $aff = false;
        }
    
        if ($oper == 'archive') {
            $xtab = News::news_aff('archive', "WHERE sid='$sid'", 1, 1);
            list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[0];
    
            if ($topic != '') {
                $result2 = sql_query("SELECT topictext FROM topics WHERE topicid='$topic'");
                list($topictext) = sql_fetch_row($result2);
            } else
                $aff = false;
        }
    
        if ($aff == true) {
            $Titlesitename = 'Npds - ' . __d('news', 'Page spéciale pour impression') . ' / ' . $title;
    
            if (isset($time)) {
                Date::formatTimestamp($time);
            }
    
            include("storage/meta/meta.php");
    
            // if (isset($user)) {
            //     if ($cookie[9] == '') {
            //         $cookie[9] = Config::get('npds.Default_Theme');
            //     }
    
            //     if (isset($theme)) {
            //         $cookie[9] = $theme;
            //     }
    
            //     $tmp_theme = $cookie[9];
    
            //     if (!$file = @opendir("themes/$cookie[9]")) {
            //         $tmp_theme = Config::get('npds.Default_Theme');
            //     }
            // } else {
            //     $tmp_theme = Config::get('npds.Default_Theme');
            // }
    
            echo '<link rel="stylesheet" href="'. site_url('assets/shared/bootstrap/dist/css/bootstrap.min.css') .'" />';
    
            echo '
            </head>
            <body>
                <div max-width="640" class="container p-1 n-hyphenate">
                    <div>';
    
            $pos = strpos(Config::get('npds.site_logo'), '/');
    
            if ($pos) {
                echo '<img class="img-fluid d-block mx-auto" src="' . Config::get('npds.site_logo') . '" alt="website logo" />';
            } else {
                echo '<img class="img-fluid d-block mx-auto" src="'. site_url('assets/images/npds/' . Config::get('npds.site_logo')) . '" alt="website logo" />';
            }

            echo '<h1 class="d-block text-center my-4">' . Language::aff_langue($title) . '</h1>';
    
            if (($oper == 'news') or ($oper == 'archive')) {
                $hometext = Metalang::meta_lang(Code::aff_code(Language::aff_langue($hometext)));
                $bodytext = Metalang::meta_lang(Code::aff_code(Language::aff_langue($bodytext)));
    
                echo '
                    <span class="float-end text-capitalize" style="font-size: .8rem;"> ' . $datetime . '</span><br />
                    <hr />
                    <h2 class="mb-3">' . __d('news', 'Sujet : ') . ' ' . Language::aff_langue($topictext) . '</h2>
                </div>
                <div>' . $hometext . '<br /><br />';
    
                if ($bodytext != '') {
                    echo $bodytext . '<br /><br />';
                }
    
                echo Metalang::meta_lang(Code::aff_code(Language::aff_langue($notes)));
    
                echo '</div>';
    
                if ($oper == 'news') {
                    echo '
                    <hr />
                    <p class="text-center">
                        ' . __d('news', 'Cet article provient de') . ' ' . Config::get('npds.sitename') . '
                        <br />
                        ' . __d('news', 'L\'url pour cet article est : ') . '
                        <a href="' . site_url('article.php?sid=' . $sid) . '">
                            ' . site_url('article.php?sid=' . $sid) . '
                        </a>
                    </p>';
                } else {
                    echo '
                    <hr />
                    <p class="text-center">
                        ' . __d('news', 'Cet article provient de') . ' ' . Config::get('npds.sitename') . '
                        <br />
                        ' . __d('news', 'L\'url pour cet article est : ') . '
                        <a href="' . site_url('article.php?sid=' . $sid . '&amp;archive=1').'">
                            ' . site_url('article?sid=' . $sid . '&amp;archive=1') .'
                        </a>
                    </p>';
                }
            }
    
            echo '
                </div>
            </body>
            </html>';
        } else {
            header("location: index.php");
        }
    }

}