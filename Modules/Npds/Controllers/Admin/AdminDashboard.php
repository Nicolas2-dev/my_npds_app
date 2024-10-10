<?php

namespace Modules\Npds\Controllers\Admin;

use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use Modules\Npds\Core\AdminController;


class AdminDashboard extends AdminController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = "admin";

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected  $short_menu_admin = false;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = false;


    /**
     * Call the parent construct
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
     * [adminMain description]
     *
     * @param   [type]  $deja_affiches  [$deja_affiches description]
     *
     * @return  [type]                  [return description]
     */
    public function adminMain()
    {
        $admart = Config::get('npds.admart');

        $dashboard = '';

        $nbre_articles = DB::table('stories')->select('sid')->count();
    
        $deja_affiches = Request::query('deja_affiches', 0);

        $result = sql_query("SELECT sid, title, hometext, topic, informant, time, archive, catid, ihome FROM stories ORDER BY sid DESC LIMIT $deja_affiches, $admart");
    
        $O = Db::table('stories')
        ->select('sid', 'title', 'hometext', 'topic', 'informant', 'time', 'archive', 'catid', 'ihome')
        ->orderBy('sid', 'desc')
        ->offset($deja_affiches)
        ->limit($admart)
        ->get();


// vd($O, sql_num_rows($result), $admart);


        $nbPages = ceil($nbre_articles / $admart);
        $current = 1;
    
        if ($deja_affiches >= 1) {
            $current = $deja_affiches / $admart;
        } else if ($deja_affiches < 1) {
            $current = 0;
        } else {
            $current = $nbPages;
        }
    
        $start = ($current * $admart);
    
        if ($nbre_articles) {
            $dashboard .=  '
            <table id ="lst_art_adm" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-buttons-class="outline-secondary" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">
                <thead>
                    <tr>
                    <th data-sortable="true" data-halign="center" data-align="right" class="n-t-col-xs-1">ID</th>
                    <th data-halign="center" data-sortable="true" data-sorter="htmlSorter" class="n-t-col-xs-5">' . __d('npds', 'Titre') . '</th>
                    <th data-sortable="true" data-halign="center" class="n-t-col-xs-4">' . __d('npds', 'Sujet') . '</th>
                    <th data-halign="center" data-align="center" class="n-t-col-xs-2">' . __d('npds', 'Fonctions') . '</th>
                    </tr>
                </thead>
                <tbody>';
    



            $i = 0;
            while ((list($sid, $title, $hometext, $topic, $informant, $time, $archive, $catid, $ihome) = sql_fetch_row($result)) and ($i < $admart)) {
                $affiche = false;
    
                $result2 = sql_query("SELECT topicadmin, topictext, topicimage FROM topics WHERE topicid='$topic'");
                list($topicadmin, $topictext, $topicimage) = sql_fetch_row($result2);
    
                $result3 = sql_query("SELECT title FROM stories_cat WHERE catid='$catid'");
                list($cat_title) = sql_fetch_row($result3);
    
                if ($this->radminsuper) {
                    $affiche = true;
                } else {
                    $topicadminX = explode(',', $topicadmin);
    
                    for ($iX = 0; $iX < count($topicadminX); $iX++) {
                        if (trim($topicadminX[$iX]) == $this->aid) {
                            $affiche = true;
                        }
                    }
                }
    
                $hometext = strip_tags($hometext, '<br><br />');
                $lg_max = 200;
    
                if (strlen($hometext) > $lg_max) {
                    $hometext = substr($hometext, 0, $lg_max) . ' ...';
                }

                $dashboard .= '
                <tr>
                    <td>' . $sid . '</td>
                    <td>';
    
                $title = aff_langue($title);
    
                if ($archive) {
                    $dashboard .=  $title . ' <i>(archive)</i>';
                } else {
                    if ($affiche) {
                        $dashboard .=  '<a data-bs-toggle="popover" data-bs-placement="left" data-bs-trigger="hover" href="article.php?sid=' . $sid . '" data-bs-content=\'   <div class="thumbnail"><img class="img-rounded" src="assets/images/topics/' . $topicimage . '" height="80" width="80" alt="topic_logo" /><div class="caption">' . htmlentities($hometext, ENT_QUOTES) . '</div></div>\' title="' . $sid . '" data-bs-html="true">' . ucfirst($title) . '</a>';
                        
                        if ($ihome == 1) {
                            $dashboard .=  '<br /><small><span class="badge bg-secondary" title="' . __d('npds', 'Catégorie') . '" data-bs-toggle="tooltip">' . aff_langue($cat_title) . '</span> <span class="text-danger">non publié en index</span></small>';
                        
                        } else {
                            if ($catid > 0) {
                                $dashboard .=  '<br /><small><span class="badge bg-secondary" title="' . __d('npds', 'Catégorie') . '" data-bs-toggle="tooltip"> ' . aff_langue($cat_title) . '</span> <span class="text-success"> publié en index</span></small>';
                            } else {
                                $dashboard .=  '<i>' . $title . '</i>';
                            }
                        }
                    }
                }
    
                if ($topictext == '') {
                    $dashboard .=  '</td>
                    <td>';
    
                } else {
                    $dashboard .=  '</td>
                    <td>' . $topictext . '<a href="index.php?op=newtopic&amp;topic=' . $topic . '" class="tooltip">' . aff_langue($topictext) . '</a>';
                }
    
                if ($affiche) {
                    $dashboard .=  '</td>
                    <td>
                    <a href="admin.php?op=EditStory&amp;sid=' . $sid . '" ><i class="fas fa-edit fa-lg me-2" title="' . __d('npds', 'Editer') . '" data-bs-toggle="tooltip"></i></a>
                    <a href="admin.php?op=RemoveStory&amp;sid=' . $sid . '" ><i class="fas fa-trash fa-lg text-danger" title="' . __d('npds', 'Effacer') . '" data-bs-toggle="tooltip"></i></a>';
                } else {
                    $dashboard .=  '</td>
                    <td>';
                }

                $dashboard .=  '</td>
                </tr>';
    
                $i++;
            }
    
            $dashboard .=  '
                </tbody>
            </table>
            <div class="d-flex my-2 justify-content-between flex-wrap">
            <ul class="pagination pagination-sm">
                <li class="page-item disabled"><a class="page-link" href="#">' . $nbre_articles . ' ' . __d('npds', 'Articles') . '</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">' . $nbPages . ' ' . __d('npds', 'Page(s)') . '</a></li>
            </ul>';
    
            $dashboard .=  paginate('admin.php?op=suite_articles&amp;deja_affiches=', '', $nbPages, $current, 1, $admart, $start);
            $dashboard .=  '
            </div>';
    
            $dashboard .=  '
            <form id="fad_articles" class="form-inline" action="admin.php" method="post">
                <label class="me-2 mt-sm-1">' . __d('npds', 'ID Article:') . '</label>
                <input class="form-control  me-2 mt-sm-3 mb-2" type="number" name="sid" />
                <select class="form-select me-2 mt-sm-3 mb-2" name="op">
                    <option value="EditStory" selected="selected">' . __d('npds', 'Editer un Article') . '</option>
                    <option value="RemoveStory">' . __d('npds', 'Effacer l\'Article') . '</option>
                </select>
                <button class="btn btn-primary ms-sm-2 mt-sm-3 mb-2" type="submit">' . __d('npds', 'Ok') . ' </button>
            </form>';
        }

        $this->title(__d('npds', 'Administration Dashboard'));

        $this->set('nbre_articles', $nbre_articles);

        $this->set('dashboard', $dashboard);
    }

}
