<?php

namespace Modules\Sections\Controllers\Front;

use Npds\Supercache\SuperCacheEmpty;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Code;
use Npds\Supercache\SuperCacheManager;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;
use Modules\Sections\Support\Facades\Section;


class SectionsViewArticles extends FrontController
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
     * @param [type] $artid
     * @param [type] $page
     * @return void
     */
    public function viewarticle($artid, $page)
    {
        if (Section::verif_aff($artid)) {

            global $prev, $user, $numpage;
        
            $numpage = $page;
        
            if (file_exists("sections.config.php")) {
                include("sections.config.php");
            }
        
            if ($page == '') {
                sql_query("UPDATE seccont SET counter=counter+1 WHERE artid='$artid'");
            }
        
            $result_S = sql_query("SELECT artid, secid, title, content, counter, userlevel FROM seccont WHERE artid='$artid'");
            list($artid, $secid, $title, $Xcontent, $counter, $userlevel) = sql_fetch_row($result_S);
        
            list($secid, $secname, $rubid) = sql_fetch_row(sql_query("SELECT secid, secname, rubid FROM sections WHERE secid='$secid'"));
        
            list($rubname) = sql_fetch_row(sql_query("SELECT rubname FROM rubriques WHERE rubid='$rubid'"));
        
            $tmp_auto = explode(',', $userlevel);
        
            foreach ($tmp_auto as $userlevel) {
                $okprint = Section::autorisation_section($userlevel);
        
                if ($okprint) {
                    break;
                }
            }
        
            if ($okprint) {
                $pindex = substr(substr($page, 5), 0, -1);
        
                if ($pindex != '') {
                    $pindex = __d('sections', 'Page') . ' ' . $pindex;
                }
        
                if ($sections_chemin == 1) {
                    $chemin = '<span class="lead"><a href="sections.php">Index</a>&nbsp;/&nbsp;<a href="sections.php?rubric=' . $rubid . '">' . Language::aff_langue($rubname) . '</a>&nbsp;/&nbsp;<a href="sections.php?op=listarticles&amp;secid=' . $secid . '">' . aff_langue($secname) . '</a></span>';
                }

                $title = Language::aff_langue($title);
        
                global $SuperCache;
        
                if ($SuperCache) {
                    $cache_obj = new SuperCacheManager();
                    $cache_obj->startCachingPage();
                } else {
                    $cache_obj = new SuperCacheEmpty();
                }
        
                if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!$SuperCache)) {
                    $words = sizeof(explode(' ', $Xcontent));
        
                    if ($prev == 1) {
                        echo '<input class="btn btn-secondary" type="button" value="' . __d('sections', 'Retour à l\'administration') . '" onclick="javascript:history.back()" /><br /><br />';
                    }
        
                    // function a creer dans le helper si besoin
                    if (function_exists("themesection_title")) {
                        themesection_title($title);
                    } else {
                        echo $chemin . '
                        <hr />
                        <h3 class="mb-2">' . $title . '<span class="small text-muted"> - ' . $pindex . '</span></h3>
                        <p><span class="text-muted small">(' . $words . ' ' . __d('sections', 'mots dans ce texte )') . '&nbsp;-&nbsp;
                        ' . __d('sections', 'lu : ') . ' ' . $counter . ' ' . __d('sections', 'Fois') . '</span><span class="float-end"><a href="sections.php?op=printpage&amp;artid=' . $artid . '" title="' . __d('sections', 'Page spéciale pour impression') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-print fa-lg ms-3"></i></a></span></p><hr />';
                        preg_match_all('#\[page.*\]#', $Xcontent, $rs);
                    }

                    $ndepages = count($rs[0]);
        
                    if ($page != '') {
                        $Xcontent = substr($Xcontent, strpos($Xcontent, $page) + strlen($page));
                        $multipage = true;
                    } else {
                        $multipage = false;
                    }
        
                    $pos_page = strpos($Xcontent, '[page');
                    $longueur = mb_strpos($Xcontent, ']', $pos_page, 'iso-8859-1') - $pos_page + 1;
        
                    if ($pos_page) {
                        $pageS = substr($Xcontent, $pos_page, $longueur);
                        $Xcontent = substr($Xcontent, 0, $pos_page);
        
                        $Xcontent .= '
                        <nav class="d-flex mt-3">
                        <ul class="mx-auto pagination pagination-sm">
                        <li class="page-item disabled"><a class="page-link" href="#">' . $ndepages . ' pages</a></li>';
        
                        if ($pageS !== '[page0]') {
                            $Xcontent .= '<li class="page-item"><a class="page-link" href="sections.php?op=viewarticle&amp;artid=' . $artid . '">' . __d('sections', 'Début de l\'article') . '</a></li>';
                        }

                        $Xcontent .= '
                            <li class="page-item active"><a class="page-link">' . preg_replace('#\[(page)(.*)(\])#', '\1 \2', $pageS) . '</a></li>
                            <li class="page-item"><a class="page-link" href="sections.php?op=viewarticle&amp;artid=' . $artid . '&amp;page=' . $pageS . '" >' . __d('sections', 'Page suivante') . '</a></li>
                        </ul>
                        </nav>';
                    } elseif ($multipage) {
                        $Xcontent .= '
                        <nav class="d-flex mt-3">
                        <ul class="mx-auto pagination pagination-sm">
                        <li class="page-item"><a class="page-link" href="sections.php?op=viewarticle&amp;artid=' . $artid . '&amp;page=[page0]">' . __d('sections', 'Début de l\'article') . '</a></li>
                        </ul>
                        </nav>';
                    }
        
                    $Xcontent = Code::aff_code(Language::aff_langue($Xcontent));
                    echo '<div id="art_sect">' . Metalang::meta_lang($Xcontent) . '</div>';
        
                    $artidtempo = $artid;
                    if ($rubname != 'Divers') {
                     
                        // echo '<hr /><p><a class="btn btn-secondary" href="sections.php">'. __d('sections', 'Return to Sections Index') .'</a></p>'; 
        
                        // echo '<h4>***<strong>'. __d('sections', 'Back to chapter:')  .'</strong></h4>';
                        // echo '<ul class="list-group"><li class="list-group-item"><a href="sections.php?op=listarticles&amp;secid='.$secid.'">'. Language::aff_langue($secname) .'</a></li></ul>';

        
                        $result3 = sql_query("SELECT artid, secid, title, userlevel FROM seccont WHERE (artid<>'$artid' AND secid='$secid') ORDER BY ordre");
                        $nb_article = sql_num_rows($result3);
        
                        if ($nb_article > 0) {
                            echo '
                            <h4 class="my-3">' . __d('sections', 'Autres publications de la sous-rubrique') . '<span class="badge bg-secondary float-end">' . $nb_article . '</span></h4>
                            <ul class="list-group">';
        
                            while (list($artid, $secid, $title, $userlevel) = sql_fetch_row($result3)) {
                                $okprint2 = Section::autorisation_section($userlevel);
        
                                if ($okprint2) {
                                    echo '<li class="list-group-item list-group-item-action"><a href="sections.php?op=viewarticle&amp;artid=' . $artid . '">' . Language::aff_langue($title) . '</a></li>';
                                }
                            }
                            echo '</ul>';
                        }
                    }
        
                    $artid = $artidtempo;
                    $resultconnexe = sql_query("SELECT id2 FROM compatsujet WHERE id1='$artid'");
        
                    if (sql_num_rows($resultconnexe) > 0) {
                        echo '
                        <h4 class="my-3">' . __d('sections', 'Cela pourrait vous intéresser') . '<span class="badge bg-secondary float-end">' . sql_num_rows($resultconnexe) . '</span></h4>
                        <ul class="list-group">';
        
                        while (list($connexe) = sql_fetch_row($resultconnexe)) {
                            $resultpdtcompat = sql_query("SELECT artid, title, userlevel FROM seccont WHERE artid='$connexe'");
                            list($artid2, $title, $userlevel) = sql_fetch_row($resultpdtcompat);
        
                            $okprint2 = Section::autorisation_section($userlevel);
        
                            if ($okprint2) {
                                echo '<li class="list-group-item list-group-item-action"><a href="sections.php?op=viewarticle&amp;artid=' . $artid2 . '">' . Language::aff_langue($title) . '</a></li>';
                            }
                        }
        
                        echo '</ul>';
                    }
                }
        
                sql_free_result($result_S);
        
                if ($SuperCache) {
                    $cache_obj->endCachingPage();
                }
        
            } else {
                header("Location: sections.php");

                $this->viewarticle($artid, $page);
            }
        } else {
            header('location: sections.php');
        }
    }

}
