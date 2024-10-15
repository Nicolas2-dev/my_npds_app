<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class NewsStoriesCategoryNoMove extends AdminController
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
    protected $hlpfile = 'newarticle';

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
    protected $f_meta_nom = 'adminStory';


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
        $this->f_titre = __d('news', 'Articles');

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
     * @param [type] $catid
     * @param [type] $newcat
     * @return void
     */
    public function NoMoveCategory($catid, $newcat)
    {
        $result = sql_query("SELECT title FROM stories_cat WHERE catid='$catid'");
        list($title) = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Affectation d\'Articles vers une nouvelle Catégorie') . '</h3>';
    
        if (!$newcat) {
            echo '<label>' . __d('news', 'Tous les Articles dans') . ' <strong>' . aff_langue($title) . '</strong> ' . __d('news', 'seront affectés à') . '</label>';
            
            $selcat = sql_query("SELECT catid, title FROM stories_cat");
    
            echo '
            <form action="admin.php" method="post">
                <div class="mb-3 row">
                    <label class="col-form-label visually-hidden" for="newcat">' . __d('news', 'Sélectionner la nouvelle Catégorie : ') . '</label>
                    <div class="col-sm-12">
                        <select class="form-select" id="newcat" name="newcat">
                        <option name="newcat" value="0">' . __d('news', 'Articles') . '</option>';
    
            while (list($newcat, $title) = sql_fetch_row($selcat)) {
                echo '<option name="newcat" value="' . $newcat . '">' . aff_langue($title) . '</option>';
            }
    
            echo '
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <input type="hidden" name="catid" value="' . $catid . '" />
                        <input type="hidden" name="op" value="NoMoveCategory" />
                        <input class="btn btn-primary" type="submit" value="' . __d('news', 'Affectation') . '" />
                    </div>
                </div>
            </form>';
    
        } else {
            $resultm = sql_query("SELECT sid FROM stories WHERE catid='$catid'");
    
            while (list($sid) = sql_fetch_row($resultm)) {
                sql_query("UPDATE stories SET catid='$newcat' WHERE sid='$sid'");
            }
    
            sql_query("DELETE FROM stories_cat WHERE catid='$catid'");
    
            global $aid;
            Ecr_Log("security", "NoMoveCategory($catid, $newcat) by AID : $aid", "");
            echo '<div class="alert alert-success"><strong>' . __d('news', 'La ré-affectation est terminée !') . '</strong></div>';
    
        }
    
        adminfoot('', '', '', '');
    }

}
