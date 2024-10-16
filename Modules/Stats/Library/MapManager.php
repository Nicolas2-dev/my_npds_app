<?php

namespace Modules\Stats\Library;


use Modules\Npds\Support\Facades\Auth;
use Modules\Forum\Support\Facades\Forum;
use Modules\Stats\Contracts\MapInterface;
use Modules\Npds\Support\Facades\Language;


class MapManager implements MapInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function mapsections()
    {
        $tmp = '';
        $result = sql_query("SELECT rubid, rubname FROM rubriques WHERE enligne='1' AND rubname<>'Divers' AND rubname<>'Presse-papiers' ORDER BY ordre");
        
        if (sql_num_rows($result) > 0) {
            while (list($rubid, $rubname) = sql_fetch_row($result)) {
                if ($rubname != '')
                    $tmp .= '<li>' . Language::aff_langue($rubname);
    
                $result2 = sql_query("SELECT secid, secname, image, userlevel, intro FROM sections WHERE rubid='$rubid' AND (userlevel='0' OR userlevel='') ORDER BY ordre");
                
                if (sql_num_rows($result2) > 0) {
                    while (list($secid, $secname, $userlevel) = sql_fetch_row($result2)) {
                        if (Auth::autorisation($userlevel)) {
                            $tmp .= '<ul><li>' . Language::aff_langue($secname);
    
                            $result3 = sql_query("SELECT artid, title FROM seccont WHERE secid='$secid'");
                            while (list($artid, $title) = sql_fetch_row($result3)) {
                                $tmp .= "<ul><li><a href=\"sections.php?op=viewarticle&amp;artid=$artid\">" . Language::aff_langue($title) . '</a></li></ul>';
                            }
    
                            $tmp .= '</li>
                            </ul>';
                        }
                    }
                }
                $tmp .= '</li>';
            }
        }
    
        if ($tmp != '')
            echo '
                <h3>
                <a class="" data-bs-toggle="collapse" href="#collapseSections" aria-expanded="false" aria-controls="collapseSections">
                <i class="toggle-icon fa fa-caret-down"></i></a>&nbsp;' . __d('stats', 'Rubriques') . '
                <span class="badge bg-secondary float-end">' . sql_num_rows($result) . '</span>
                </h3>
            <div class="collapse" id="collapseSections">
                <div class="card card-body">
                <ul class="list-unstyled">' . $tmp . '</ul>
                </div>
            </div>
            <hr />';
    
        sql_free_result($result);
    
        if (isset($result2)) {
            sql_free_result($result2);
        }
    
        if (isset($result3)) {
            sql_free_result($result3);
        }
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function mapforum()
    {
        $tmp = '';
        $tmp .= Forum::RecentForumPosts_fab('', 10, 0, false, 50, false, '<li>', false);
    
        if ($tmp != '') {
            echo '
            <h3>
                <a data-bs-toggle="collapse" href="#collapseForums" aria-expanded="false" aria-controls="collapseForums"><i class="toggle-icon fa fa-caret-down"></i></a>&nbsp;' . __d('stats', 'Forums') . '
            </h3>
            <div class="collapse" id="collapseForums">
                <div class="card card-body">
                    ' . $tmp . '
                </div>
            </div>
            <hr />';
        }
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function maptopics()
    {
        $lis_top = '';
    
        $result = sql_query("SELECT topicid, topictext FROM topics ORDER BY topicname");
    
        while (list($topicid, $topictext) = sql_fetch_row($result)) {
            $result2    = sql_query("SELECT sid FROM stories WHERE topic='$topicid'");
            $nb_article = sql_num_rows($result2);
    
            $lis_top .= '<li><a href="search.php?query=&amp;topic=' . $topicid . '">' . Language::aff_langue($topictext) . '</a>&nbsp;<span class="">(' . $nb_article . ')</span></li>';
        }
    
        if ($lis_top != '') {
            echo '
            <h3>
                <a class="" data-bs-toggle="collapse" href="#collapseTopics" aria-expanded="false" aria-controls="collapseTopics"><i class="toggle-icon fa fa-caret-down"></i></a>&nbsp;' . __d('stats', 'Sujets') . '
                <span class="badge bg-secondary float-end">' . sql_num_rows($result) . '</span>
            </h3>
            <div class="collapse" id="collapseTopics">
                <div class="card card-body">
                    <ul class="list-unstyled">' . $lis_top . '</ul>
                </div>
            </div>
            <hr />';
        }
    
        sql_free_result($result);
        sql_free_result($result2);
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function mapcategories()
    {
        $lis_cat = '';
        $result = sql_query("SELECT catid, title FROM stories_cat ORDER BY title");
    
        while (list($catid, $title) = sql_fetch_row($result)) {
            $result2    = sql_query("SELECT sid FROM stories WHERE catid='$catid'");
            $nb_article = sql_num_rows($result2);
    
            $lis_cat .= '<li><a href="index.php?op=newindex&amp;catid=' . $catid . '">' . Language::aff_langue($title) . '</a> <span class="float-end badge bg-secondary"> ' . $nb_article . ' </span></li>' . "\n";
        }
    
        if ($lis_cat != '') {
            echo '
            <h3>
                <a class="" data-bs-toggle="collapse" href="#collapseCategories" aria-expanded="false" aria-controls="collapseCategories"><i class="toggle-icon fa fa-caret-down"></i></a>&nbsp;' . __d('stats', 'Catégories') . '
                <span class="badge bg-secondary float-end">' . sql_num_rows($result) . '</span>
            </h3>
            <div class="collapse" id="collapseCategories">
                <div class="card card-body">
                    <ul class="list-unstyled">' . $lis_cat . '</ul>
                </div>
            </div>
            <hr />';
        }
    
        sql_free_result($result);
    
        if (isset($result2)) {
            sql_free_result($result2);
        }
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function mapfaq()
    {
        $lis_faq = '';
    
        $result = sql_query("SELECT id_cat, categories FROM faqcategories ORDER BY id_cat ASC");
    
        while (list($id_cat, $categories) = sql_fetch_row($result)) {
            $catname = Language::aff_langue($categories);
            $lis_faq .= "<li><a href=\"faq.php?id_cat=$id_cat&amp;myfaq=yes&amp;categories=" . urlencode($catname) . "\">" . $catname . "</a></li>\n";
        }
    
        if ($lis_faq != '') {
            echo '
            <h3>
                <a class="" data-bs-toggle="collapse" href="#collapseFaq" aria-expanded="false" aria-controls="collapseFaq"><i class="toggle-icon fa fa-caret-down"></i></a>&nbsp;' . __d('stats', 'FAQ - Questions fréquentes') . '
                <span class="badge bg-secondary float-end">' . sql_num_rows($result) . '</span>
            </h3>
            <div class="collapse" id="collapseFaq">
                <div class="card card-body">
                    <ul class="">' . $lis_faq . '</ul>
                </div>
            </div>
            <hr />';
        }
    
        sql_free_result($result);
    }

}
