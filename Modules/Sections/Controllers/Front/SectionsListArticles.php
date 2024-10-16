<?php

namespace Modules\Sections\Controllers\Front;

use Npds\Routing\Url;
use Npds\Supercache\SuperCacheEmpty;
use Modules\Npds\Core\FrontController;
use Npds\Supercache\SuperCacheManager;
use Modules\Npds\Support\Facades\Language;
use Modules\Sections\Support\Facades\Section;


class SectionsListArticles extends FrontController
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
     * @param [type] $secid
     * @return void
     */
    public function listarticles($secid)
    {
        global $user, $prev;
    
        if (file_exists("sections.config.php")) {
            include("sections.config.php");
        }
    
        $result = sql_query("SELECT secname, rubid, image, intro, userlevel FROM sections WHERE secid='$secid'");
        list($secname, $rubid, $image, $intro, $userlevel) = sql_fetch_row($result);
    
        list($rubname) = sql_fetch_row(sql_query("SELECT rubname FROM rubriques WHERE rubid='$rubid'"));
    
        if ($sections_chemin == 1) {
            $chemin = '<span class="lead"><a href="sections.php" title="' . __d('sections', 'Retour à l\'index des rubriques') . '" data-bs-toggle="tooltip">Index</a>&nbsp;/&nbsp;<a href="sections.php?rubric=' . $rubid . '">' . Language::aff_langue($rubname) . '</a></span>';
        }

        $title =  Language::aff_langue($secname);
    
        global $SuperCache;
    
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
    
        if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!$SuperCache)) {
            $okprint1 = Section::autorisation_section($userlevel);
    
            if ($okprint1) {
                $result = sql_query("SELECT artid, secid, title, content, userlevel, counter, timestamp FROM seccont WHERE secid='$secid' ORDER BY ordre");
                $nb_art = sql_num_rows($result);
    
                if ($prev == 1) {
                    echo '<input class="btn btn-primary" type="button" value="' . __d('sections', 'Retour à l\'administration') . '" onclick="javascript:history.back()" /><br /><br />';
                }
    
                // function creer dans un helper si besoin
                if (function_exists("themesection_title")) {
                    themesection_title($title);
                } else {
                    echo $chemin . '
                    <hr />
                    <h3 class="mb-3">' . $title . '<span class="float-end"><span class="badge bg-secondary" title="' . __d('sections', 'Articles') . '" data-bs-toggle="tooltip" data-bs-placement="left">' . $nb_art . '</span></h3>';
                }
    
                if ($intro != '') {
                    echo Language::aff_langue($intro);
                }
    
                if ($image != '') {
                    if (file_exists("assets/images/sections/$image")) {
                        $imgtmp = "assets/images/sections/$image";
                    } else {
                        $imgtmp = $image;
                    }
    
                    $suffix = strtoLower(substr(strrchr(basename($image), '.'), 1));
    
                    echo '<p class="text-center"><img class="img-fluid" src="' . $imgtmp . '" alt="" /></p>';
                }
    
                echo '
                    <p>' . __d('sections', 'Voici les articles publiés dans cette rubrique.') . '</p>
                <div class="card card-body mb-3">';
    
                while (list($artid, $secid, $title, $content, $userlevel, $counter, $timestamp) = sql_fetch_row($result)) {
                    $okprint2 = Section::autorisation_section($userlevel);
    
                    if ($okprint2) {
                        $nouveau = 'oo';
    
                        if ((time() - $timestamp) < (86400 * 7)) {
                            $nouveau = '';
                        }
    
                        echo '
                        <div class="mb-1">
                        <a href="sections.php?op=viewarticle&amp;artid=' . $artid . '">' . Language::aff_langue($title) . '</a><small>
                        ' . __d('sections', 'lu : ') . ' ' . $counter . ' ' . __d('sections', 'Fois') . '</small><span class="float-end"><a href="sections.php?op=printpage&amp;artid=' . $artid . '" title="' . __d('sections', 'Page spéciale pour impression') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-print fa-lg"></i></a></span>';
                        
                        if ($nouveau == '') {
                            echo '&nbsp;<i class="fa fa-star-o text-success"></i>';
                        }
    
                        echo '</div>';
                    }
                }

                echo '</div>';
                // echo '<a class="btn btn-secondary" href="sections.php">'.__d('sections', 'Return to Sections Index').'</a>';
            } else {
                Url::redirect("sections.php");
            }
    
            sql_free_result($result);
        }
    
        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }
    }

}
