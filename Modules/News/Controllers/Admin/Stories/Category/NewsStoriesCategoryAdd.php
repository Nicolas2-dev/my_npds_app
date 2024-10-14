<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class NewsStoriesCategoryAdd extends AdminController
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
     * @return void
     */
    public function AddCategory()
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Articles');

        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Ajouter une nouvelle Catégorie') . '</h3>
        <form id="storiesaddcat" action="admin.php" method="post">
            <div class="mb-3 row">
                <label class="col-sm-12 col-form-label" for="title">' . __d('news', 'Nom') . '</label>
                <div class="col-sm-12">
                    <input class="form-control" type="text" id="title" name="title" maxlength="255" required="required" />
                    <span class="help-block text-end" id="countcar_title"></span>
                </div>
            </div>
            <input type="hidden" name="op" value="SaveCategory" />
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <input class="btn btn-primary" type="submit" value="' . __d('news', 'Sauver les modifications') . '" />
                </div>
            </div>
        </form>';
    
        $arg1 = '
        var formulid = ["storiesaddcat"];
        inpandfieldlen("title",255);';
    
        adminfoot('fv', '', $arg1, '');
    }

    /**
     * Undocumented function
     *
     * @param [type] $title
     * @return void
     */
    public function SaveCategory($title)
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Articles');

        $title = preg_replace('#"#', '', $title);
        $check = sql_num_rows(sql_query("SELECT catid FROM stories_cat WHERE title='$title'"));
        
        if ($check)
            $what1 = '<div class="alert alert-danger lead" role="alert">' . __d('news', 'Cette Catégorie existe déjà !') . '<br /><a href="javascript:history.go(-1)" class="btn btn-secondary  mt-2">' . __d('news', 'Retour en arrière, pour changer le Nom') . '</a></div>';
        else {
            $what1 = '<div class="alert alert-success lead" role="alert">' . __d('news', 'Nouvelle Catégorie ajoutée') . '</div>';
            $result = sql_query("INSERT INTO stories_cat VALUES (NULL, '$title', '0')");
        }

        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Ajouter une nouvelle Catégorie') . '</h3>
        ' . $what1;
    
        adminfoot('', '', '', '');
    }

}
