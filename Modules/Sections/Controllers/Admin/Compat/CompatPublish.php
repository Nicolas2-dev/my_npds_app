<?php

namespace Modules\Sections\Controllers\Admin\Compat;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;


class CompatPublish extends AdminController
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
    protected $hlpfile = "";

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = '';


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
        $this->f_titre = __d('', '');

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
     * @param [type] $article
     * @return void
     */
    public function publishcompat($article)
    {
        $result2 = sql_query("SELECT title FROM seccont WHERE artid='$article'");
        list($titre) = sql_fetch_row($result2);

        $result = sql_query("SELECT rubid, rubname, enligne, ordre FROM rubriques ORDER BY ordre");
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('sections', 'Publications connexes') . ' : <span class="text-muted">' . Language::aff_langue($titre) . '</span></h3>
        <form action="admin.php" method="post">';
    
        $i = 0;
    
        while (list($rubid, $rubname, $enligne, $ordre) = sql_fetch_row($result)) {
            if ($enligne == 0) {
                $online = __d('sections', 'Hors Ligne');
                $cla = "danger";
            } elseif ($enligne == 1) {
                $online = __d('sections', 'En Ligne');
                $cla = "success";
            }
    
            echo '
            <div class="list-group-item bg-light">
                <a class="arrow-toggle text-primary" data-bs-toggle="collapse" data-bs-target="#lst_' . $rubid . '" ><i class="toggle-icon fa fa-caret-down fa-lg"></i></a>&nbsp;' . Language::aff_langue($rubname) . '<span class="badge bg-' . $cla . ' float-end">' . $online . '</span>
            </div>';
    
            if ($radminsuper == 1) {
                $result2 = sql_query("SELECT secid, secname FROM sections WHERE rubid='$rubid' ORDER BY ordre");
            } else {
                $result2 = sql_query("SELECT DISTINCT sections.secid, sections.secname, sections.ordre FROM sections, publisujet WHERE sections.rubid='$rubid' AND sections.secid=publisujet.secid2 AND publisujet.aid='$aid' AND publisujet.type='1' ORDER BY ordre");
            }

            if (sql_num_rows($result2) > 0) {
                echo '<ul id="lst_' . $rubid . '" class="list-group mb-1 collapse">';
    
                while (list($secid, $secname) = sql_fetch_row($result2)) {
                    echo '<li class="list-group-item"><strong class="ms-3" title="' . __d('sections', 'sous-rubrique') . '" data-bs-toggle="tooltip">' . Language::aff_langue($secname) . '</strong></li>';
                    
                    $result3 = sql_query("SELECT artid, title FROM seccont WHERE secid='$secid' ORDER BY ordre");
                    
                    if (sql_num_rows($result3) > 0) {
                        
                        while (list($artid, $title) = sql_fetch_row($result3)) {
                            $i++;
    
                            $result4 = sql_query("SELECT id2 FROM compatsujet WHERE id2='$artid' AND id1='$article'");
                            echo '<li class="list-group-item list-group-item-action"><div class="form-check ms-3">';
    
                            if (sql_num_rows($result4) > 0)  {
                                echo '<input class="form-check-input" type="checkbox"  id="admin_rub' . $i . '" name="admin_rub[' . $i . ']" value="' . $artid . '" checked="checked" />';
                            } else {
                                echo '<input class="form-check-input" type="checkbox" id="admin_rub' . $i . '" name="admin_rub[' . $i . ']" value="' . $artid . '" />';
                            }
                            
                            echo '<label class="form-check-label" for="admin_rub' . $i . '">' . Language::aff_langue($title) . '</label></div></li>';
                        }
                    }
                }
                echo '</ul>';
            }
        }
    
        echo '
            <input type="hidden" name="article" value="' . $article . '" />
            <input type="hidden" name="op" value="updatecompat" />
            <input type="hidden" name="idx" value="' . $i . '" />
            <div class="mb-3 mt-3">
                <button class="btn btn-primary" type="submit">' . __d('sections', 'Valider') . '</button>&nbsp;<input class="btn btn-secondary" type="button" onclick="javascript:history.back()" value="' . __d('sections', 'Retour en arrière') . '" />
            </div>
        </form>';
    
        Css::adminfoot('', '', '', '');
    }
    
}
