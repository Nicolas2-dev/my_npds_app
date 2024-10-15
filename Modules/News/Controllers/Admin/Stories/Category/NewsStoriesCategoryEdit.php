<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class NewsStoriesCategoryEdit extends AdminController
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
     * @return void
     */
    public function EditCategory($catid)
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Articles');

        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Edition des Catégories') . '</h3>';
    
        $result = sql_query("SELECT title FROM stories_cat WHERE catid='$catid'");
        list($title) = sql_fetch_row($result);
    
        if (!$catid) {
            $selcat = sql_query("SELECT catid, title FROM stories_cat");
    
            echo '
            <form action="admin.php" method="post">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="catid">' . __d('news', 'Sélectionner une Catégorie') . '</label>
                    <div class="col-sm-12">
                        <select class="form-select" id="catid" name="catid">';
    
            echo '<option name="catid" value="0">' . __d('news', 'Articles') . '</option>';
    
            while (list($catid, $title) = sql_fetch_row($selcat)) {
                echo '
                   <option name="catid" value="' . $catid . '">' . aff_langue($title) . '</option>';
            }
    
            echo '
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <input type="hidden" name="op" value="EditCategory" />
                        <input class="btn btn-primary" type="submit" value="' . __d('news', 'Editer') . '" />
                    </div>
                </div>
            </form>';
    
            adminfoot('', '', '', '');
        } else {
            echo '
            <form id="storieseditcat" action="admin.php" method="post">
                <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="title">' . __d('news', 'Nom') . '</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text" id="title" name="title" maxlength="255" value="' . $title . '" required="required"/>
                        <span class="help-block text-end" id="countcar_title"></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <input type="hidden" name="catid" value="' . $catid . '" />
                        <input type="hidden" name="op" value="SaveEditCategory" />
                        <input class="btn btn-primary" type="submit" value="' . __d('news', 'Sauver les modifications') . '" />
                    </div>
                </div>
            </form>';
    
            $arg1 = '
            var formulid = ["storieseditcat"];
            inpandfieldlen("title",255);';
    
            adminfoot('fv', '', $arg1, '');
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $catid
     * @param [type] $title
     * @return void
     */
    public function SaveEditCategory($catid, $title)
    {
        $title = preg_replace('#"#', '', $title);
    
        $check = sql_num_rows(sql_query("SELECT catid FROM stories_cat WHERE title='$title'"));
    
        if ($check) {
            $what1 = '<div class="alert alert-danger lead" role="alert">' . __d('news', 'Cette Catégorie existe déjà !') . '<br /><a href="javascript:history.go(-2)" class="btn btn-secondary  mt-2">' . __d('news', 'Retour en arrière, pour changer le Nom') . '</a></div>';
        } else {
            $what1 = '<div class="alert alert-success lead" role="alert">' . __d('news', 'Catégorie sauvegardée') . '</div>';
    
            $result = sql_query("UPDATE stories_cat SET title='$title' WHERE catid='$catid'");
    
            global $aid;
            Ecr_Log("security", "SaveEditCategory($catid, $title) by AID : $aid", "");
        }

        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Edition des Catégories') . '</h3>
        ' . $what1;
    
        adminfoot('', '', '', '');
    }


}
