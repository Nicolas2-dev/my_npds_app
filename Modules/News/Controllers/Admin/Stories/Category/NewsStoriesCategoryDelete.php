<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class NewsStoriesCategoryDelete extends AdminController
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
     * @param [type] $cat
     * @return void
     */
    public function DelCategory($cat)
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Articles');

        $result = sql_query("SELECT title FROM stories_cat WHERE catid='$cat'");
        list($title) = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3 class="mb-3 text-danger">' . __d('news', 'Supprimer une Catégorie') . '</h3>';
    
        if (!$cat) {
            $selcat = sql_query("SELECT catid, title FROM stories_cat");
    
            echo '
            <form action="admin.php" method="post">
                <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="cat">' . __d('news', 'Sélectionner une Catégorie à supprimer') . '</label>
                    <div class="col-sm-12">
                        <select class="form-select" id="cat" name="cat">';
    
            while (list($catid, $title) = sql_fetch_row($selcat)) {
                echo '<option name="cat" value="' . $catid . '">' . aff_langue($title) . '</option>';
            }
    
            echo '
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <input type="hidden" name="op" value="DelCategory" />
                        <button class="btn btn-danger" type="submit">' . __d('news', 'Effacer') . '</button>
                    </div>
                </div>
            </form>';
    
        } else {
            $result2 = sql_query("SELECT * FROM stories WHERE catid='$cat'");
            $numrows = sql_num_rows($result2);
    
            if ($numrows == 0) {
                sql_query("DELETE FROM stories_cat WHERE catid='$cat'");
    
                global $aid;
                Ecr_Log('security', "DelCategory($cat) by AID : $aid", '');
    
                echo '<div class="alert alert-success" role="alert">' . __d('news', 'Suppression effectuée') . '</div>';
            } else {
                echo '
                <div class="alert alert-danger lead" role="alert">
                    <p class="noir"><strong>' . __d('news', 'Attention : ') . '</strong> ' . __d('news', 'la Catégorie') . ' <strong>' . $title . '</strong> ' . __d('news', 'a') . ' <strong>' . $numrows . '</strong> ' . __d('news', 'Articles !') . '<br />';
                
                echo __d('news', 'Vous pouvez supprimer la Catégorie, les Articles et Commentaires') . ' ';
                
                echo __d('news', 'ou les affecter à une autre Catégorie.') . '<br /></p>
                    <p align="text-center"><strong>' . __d('news', 'Que voulez-vous faire ?') . '</strong></p>
                </div>
                <a href="admin.php?op=YesDelCategory&amp;catid=' . $cat . '" class="btn btn-outline-danger">' . __d('news', 'Tout supprimer') . '</a>
                <a href="admin.php?op=NoMoveCategory&amp;catid=' . $cat . '" class="btn btn-outline-primary">' . __d('news', 'Affecter à une autre Catégorie') . '</a></p>';
            }
        }
    
        adminfoot('', '', '', '');
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $catid
     * @return void
     */
    public function YesDelCategory($catid)
    {
        sql_query("DELETE FROM stories_cat WHERE catid='$catid'");
        $result = sql_query("SELECT sid FROM stories WHERE catid='$catid'");
        
        while (list($sid) = sql_fetch_row($result)) {
            sql_query("DELETE FROM stories WHERE catid='$catid'");
    
            // commentaires
            if (file_exists("modules/comments/article.conf.php")) {
                include("modules/comments/article.conf.php");
    
                sql_query("DELETE FROM posts WHERE forum_id='$forum' AND topic_id='$topic'");
            }
        }
    
        global $aid;
        Ecr_Log('security', "YesDelCategory($catid) by AID : $aid", '');
    
        Header("Location: admin.php");
    }

}
