<?php

namespace Modules\Sections\Controllers\Front;

use Npds\Supercache\SuperCacheEmpty;
use Modules\Npds\Core\FrontController;
use Npds\Supercache\SuperCacheManager;
use Modules\Npds\Support\Facades\Language;
use Modules\Sections\Support\Facades\Section;


class SectionsListSections extends FrontController
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
     * default: => listsections($rubric);
     * 
     * Undocumented function
     *
     * @param [type] $rubric
     * @return void
     */
    public function listsections($rubric)
    {
        global $admin;
    
        if (file_exists("sections.config.php")) {
            include("sections.config.php");
        }
    
        global $SuperCache;
    
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
    
        if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!$SuperCache)) {
    
            if ($rubric) {
                $sqladd = "AND rubid='" . $rubric . "'";
            } else {
                $sqladd = '';
            }
    
            if ($admin) {
                $result = sql_query("SELECT rubid, rubname, intro FROM rubriques WHERE rubname<>'Divers' AND rubname<>'Presse-papiers' $sqladd ORDER BY ordre");
                $nb_r = sql_num_rows($result);
            } else {
                $result = sql_query("SELECT rubid, rubname, intro FROM rubriques WHERE enligne='1' AND rubname<>'Divers' AND rubname<>'Presse-papiers' $sqladd ORDER BY ordre");
                $nb_r = sql_num_rows($result);
            }
    
            $aff = '';
    
            if ($rubric) {
                $aff .= '<span class="lead"><a href="sections.php" title="' . __d('sections', 'Retour à l\'index des rubriques') . '" data-bs-toggle="tooltip">Index</a></span><hr />';
            }

            $aff .= '<h2>' . __d('sections', 'Rubriques') . '<span class="float-end badge bg-secondary">' . $nb_r . '</span></h2>';
            
            if (sql_num_rows($result) > 0) {
                while (list($rubid, $rubname, $intro) = sql_fetch_row($result)) {
                    $result2 = sql_query("SELECT secid, secname, image, userlevel, intro FROM sections WHERE rubid='$rubid' ORDER BY ordre");
                    $nb_section = sql_num_rows($result2);
    
                    $aff .= '
                    <hr />
                    <h3>';
    
                    if ($nb_section !== 0) {
                        $aff .= '<a href="#" class="arrow-toggle text-primary" data-bs-toggle="collapse" data-bs-target="#rub-' . $rubid . '" ><i class="toggle-icon fa fa-caret-down"></i></a>';
                    } else {
                        $aff .= '<i class="fa fa-caret-down text-muted invisible "></i>';
                    }

                    $aff .= '
                        <a class="ms-2" href="sections.php?rubric=' . $rubid . '">' . Language::aff_langue($rubname) . '</a><span class=" float-end">#NEW#<span class="badge bg-secondary" title="' . __d('sections', 'Sous-rubrique') . '" data-bs-toggle="tooltip" data-bs-placement="left">' . $nb_section . '</span></span>
                    </h3>';
    
                    if ($intro != '') {
                        $aff .= '<p class="text-muted">' . Language::aff_langue($intro) . '</p>';
                    }

                    $aff .= '<div id="rub-' . $rubid . '" class="collapse" >';
    
                    while (list($secid, $secname, $image, $userlevel, $intro) = sql_fetch_row($result2)) {
                        $okprintLV1 = Section::autorisation_section($userlevel);
    
                        $aff1 = '';
                        $aff2 = '';
    
                        if ($okprintLV1) {
                            $result3 = sql_query("SELECT artid, title, counter, userlevel, timestamp FROM seccont WHERE secid='$secid' ORDER BY ordre");
                            $nb_art = sql_num_rows($result3);
    
                            $aff .= '
                            <div class="card card-body mb-2" id="rub_' . $rubid . 'sec_' . $secid . '">
                                <h4 class="mb-2">';
    
                            if ($nb_art !== 0) {
                                $aff .= '<a href="#" class="arrow-toggle text-primary" data-bs-toggle="collapse" data-bs-target="#sec' . $secid . '" aria-expanded="true" aria-controls="sec' . $secid . '"><i class="toggle-icon fa fa-caret-up"></i></a>&nbsp;';
                            }

                            $aff1 = Language::aff_langue($secname) . '<span class=" float-end">#NEW#<span class="badge bg-secondary" title="' . __d('sections', 'Articles') . '" data-bs-toggle="tooltip" data-bs-placement="left">' . $nb_art . '</span></span>';
                            
                            if ($image != '') {
                                if (file_exists("assets/images/sections/$image")) {
                                    $imgtmp = "assets/images/sections/$image";
                                } else {
                                    $imgtmp = $image;
                                }
    
                                $suffix = strtoLower(substr(strrchr(basename($image), '.'), 1));
                                $aff1 .= '<img class="img-fluid" src="' . $imgtmp . '" alt="' . Language::aff_langue($secname) . '" /><br />';
                            }
    
                            $aff1 .= '</h4>';
    
                            if ($intro != '') {
                                $aff1 .= '<p class="">' . Language::aff_langue($intro) . '</p>';
                            }

                            $aff2 = '
                            <div id="sec' . $secid . '" class="collapse show">
                            <div class="">';
    
                            $noartid = false;
    
                            while (list($artid, $title, $counter, $userlevel, $timestamp) = sql_fetch_row($result3)) {
                                $okprintLV2 = Section::autorisation_section($userlevel);
                                $nouveau = '';
    
                                if ($okprintLV2) {
                                    $noartid = true;
                                    $nouveau = 'oo';
    
                                    if ((time() - $timestamp) < (86400 * 7)) {
                                        $nouveau = '';
                                    }
    
                                    $aff2 .= '<a href="sections.php?op=viewarticle&amp;artid=' . $artid . '">' . Language::aff_langue($title) . '</a><span class="float-end"><small>' . __d('sections', 'lu : ') . ' ' . $counter . ' ' . __d('sections', 'Fois') . '</small>';
                                    
                                    if ($nouveau == '') {
                                        $aff2 .= '<i class="far fa-star ms-3 text-success"></i>';
                                        $aff1 = str_replace('#NEW#', '<span class="me-2 badge bg-success animated faa-flash">N</span>', $aff1);
                                        $aff = str_replace('#NEW#', '<span class="me-2 badge bg-success animated faa-flash">N</span>', $aff);
                                    }
    
                                    $aff2 .= '</span><br />';
                                }
                            }
    
                            $aff = str_replace('#NEW#', '', $aff);
                            $aff1 = str_replace('#NEW#', '', $aff1);
    
                            $aff2 .= '
                                </div>
                                </div>
                            </div>';
                        }
                        $aff .= $aff1 . $aff2;
                    }
                    $aff .= '</div>';
                }
            }
    
            // la sortie doit se faire en html !!!
            echo $aff; 
    
            // if ($rubric) {
            //     echo '<a class="btn btn-secondary" href="sections.php">'.__d('sections', 'Return to Sections Index').'</a>';
            // }
    
            sql_free_result($result);
        }
    
        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }
    }

}
