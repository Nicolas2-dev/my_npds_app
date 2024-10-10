<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class FrontPrint extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {

    }

    public function index()
    {
        if (!empty($sid)) {
            $tab = explode(':', $sid);
        
            if ($tab[0] == "static") {
                settype($metalang, 'integer');
                settype($nl, 'integer');
        
                PrintPage("static", $metalang, $nl, $tab[1]);
            } else {
                settype($sid, 'integer');
                //settype ($archive, 'string');
        
                if (!isset($archive))
                    PrintPage("news", '', '', $sid);
                else
                    PrintPage("archive", '', '', $sid);
            }
        } elseif (!empty($lid)) {
            settype($lid, "integer");
            
            PrintPage("links", $DB, '', $lid);
        } else
            header("location: index.php");
        
    }

    function PrintPage($oper, $DB, $nl, $sid)
    {
        global $user, $cookie, $theme, $datetime;
    
        $aff = true;
    
        if ($oper == 'news') {
            $xtab = news_aff('libre', "WHERE sid='$sid'", 1, 1);
            list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[0];
    
            if ($topic != '') {
                $result2 = sql_query("SELECT topictext FROM topics WHERE topicid='$topic'");
                list($topictext) = sql_fetch_row($result2);
            } else
                $aff = false;
        }
    
        if ($oper == 'archive') {
            $xtab = news_aff('archive', "WHERE sid='$sid'", 1, 1);
            list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[0];
    
            if ($topic != '') {
                $result2 = sql_query("SELECT topictext FROM topics WHERE topicid='$topic'");
                list($topictext) = sql_fetch_row($result2);
            } else
                $aff = false;
        }
    
        if ($oper == 'links') {
            $DB = removeHack(stripslashes(htmlentities(urldecode($DB), ENT_NOQUOTES, cur_charset)));
    
            $result = sql_query("SELECT url, title, description, date FROM " . $DB . "links_links WHERE lid='$sid'");
            list($url, $title, $description, $time) = sql_fetch_row($result);
    
            $title = stripslashes($title);
            $description = stripslashes($description);
        }
    
        if ($oper == 'static') {
            if (preg_match('#^[a-z0-9_\.-]#i', $sid) and !stristr($sid, ".*://") and !stristr($sid, "..") and !stristr($sid, "../") and !stristr($sid, 'script') and !stristr($sid, "cookie") and !stristr($sid, 'iframe') and  !stristr($sid, 'applet') and !stristr($sid, 'object') and !stristr($sid, 'meta')) {
                if (file_exists("storage/static/$sid")) {
    
                    ob_start();
                        include("storage/static/$sid");
                        $remp = ob_get_contents();
                    ob_end_clean();
    
                    if ($DB)
                        $remp = meta_lang(aff_code(aff_langue($remp)));
    
                    if ($nl)
                        $remp = nl2br(str_replace(' ', '&nbsp;', htmlentities($remp, ENT_QUOTES, cur_charset)));
    
                    $title = $sid;
                } else
                    $aff = false;
            } else {
                $remp = '<div class="alert alert-danger">' . __d('news', 'Merci d\'entrer l\'information en fonction des spécifications') . '</div>';
                $aff = false;
            }
        }
    
        if ($aff == true) {
            $Titlesitename = 'Npds - ' . __d('news', 'Page spéciale pour impression') . ' / ' . $title;
    
            if (isset($time))
                formatTimestamp($time);
    
            include("storage/meta/meta.php");
    
            if (isset($user)) {
                if ($cookie[9] == '') 
                    $cookie[9] = Config::get('npds.Default_Theme');
    
                if (isset($theme)) 
                    $cookie[9] = $theme;
    
                $tmp_theme = $cookie[9];
    
                if (!$file = @opendir("themes/$cookie[9]")) {
                    $tmp_theme = Config::get('npds.Default_Theme');
                }
            } else {
                $tmp_theme = Config::get('npds.Default_Theme');
            }
    
            echo '<link rel="stylesheet" href="assets/shared/bootstrap/dist/css/bootstrap.min.css" />';
    
            echo '
            </head>
            <body>
                <div max-width="640" class="container p-1 n-hyphenate">
                    <div>';
    
            $pos = strpos(Config::get('npds.site_logo'), '/');
    
            if ($pos)
                echo '<img class="img-fluid d-block mx-auto" src="' . Config::get('npds.site_logo') . '" alt="website logo" />';
            else
                echo '<img class="img-fluid d-block mx-auto" src="assets/images/App/' . Config::get('npds.site_logo') . '" alt="website logo" />';
    
            echo '<h1 class="d-block text-center my-4">' . aff_langue($title) . '</h1>';
    
            if (($oper == 'news') or ($oper == 'archive')) {
                $hometext = meta_lang(aff_code(aff_langue($hometext)));
                $bodytext = meta_lang(aff_code(aff_langue($bodytext)));
    
                echo '
                    <span class="float-end text-capitalize" style="font-size: .8rem;"> ' . $datetime . '</span><br />
                    <hr />
                    <h2 class="mb-3">' . __d('news', 'Sujet : ') . ' ' . aff_langue($topictext) . '</h2>
                </div>
                <div>' . $hometext . '<br /><br />';
    
                if ($bodytext != '') {
                    echo $bodytext . '<br /><br />';
                }
    
                echo meta_lang(aff_code(aff_langue($notes)));
    
                echo '</div>';
    
                if ($oper == 'news') {
                    echo '
                    <hr />
                    <p class="text-center">' . __d('news', 'Cet article provient de') . ' ' . Config::get('npds.sitename') . '<br />
                    ' . __d('news', 'L\'url pour cet article est : ') . '
                    <a href="' . Config::get('npds.nuke_url') . '/article.php?sid=' . $sid . '">' . Config::get('npds.nuke_url') . '/article.php?sid=' . $sid . '</a>
                    </p>';
                } else {
                    echo '
                    <hr />
                    <p class="text-center">' . __d('news', 'Cet article provient de') . ' ' . Config::get('npds.sitename') . '<br />
                    ' . __d('news', 'L\'url pour cet article est : ') . '
                    <a href="' . Config::get('npds.nuke_url') . '/article.php?sid=' . $sid . '&amp;archive=1">' . Config::get('npds.nuke_url') . '/article.php?sid=' . $sid . '&amp;archive=1</a>
                    </p>';
                }
            }
    
            if ($oper == 'links') {
                echo '<span class="float-end text-capitalize" style="font-size: .8rem;">' . $datetime . '</span><br /><hr />';
    
                if ($url != '') {
                    echo '<h2 class="mb-3">' . __d('news', 'Liens') . ' : ' . $url . '</h2>';
                }
    
                echo '
                <div>' . aff_langue($description) . '</div>
                <hr />
                <p class="text-center">' . __d('news', 'Cet article provient de') . ' ' . Config::get('npds.sitename') . '<br />
                <a href="' . Config::get('npds.nuke_url') . '">' . Config::get('npds.nuke_url') . '</a></p>';
            }
    
            if ($oper == 'static') {
                echo '
                <div>
                    ' . $remp . '
                </div>
                <hr />
                <p class="text-center">' . __d('news', 'Cet article provient de') . ' ' . Config::get('npds.sitename') . '<br />
                <a href="' . Config::get('npds.nuke_url') . '/static.php?op=' . $sid . '&App=1">' . Config::get('npds.nuke_url') . '/static.php?op=' . $sid . '&App=1</a></p>';
            }
    
            echo '
                </div>
            </body>
            </html>';
        } else
            header("location: index.php");
    }

}