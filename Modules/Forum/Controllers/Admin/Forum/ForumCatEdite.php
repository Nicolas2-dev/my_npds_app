<?php

namespace Modules\Forum\Controllers\Admin\Forum;

use Modules\Npds\Core\AdminController;


class ForumCatEdit extends AdminController
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
    protected $hlpfile = 'forumcat';

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
    protected $f_meta_nom = 'ForumAdmin';


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
        $this->f_titre = __d('forum', 'Gestion des forums');

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
     * @param [type] $cat_id
     * @return void
     */
    function ForumCatEdit($cat_id)
    {
        $result = sql_query("SELECT cat_id, cat_title FROM catagories WHERE cat_id='$cat_id'");
        list($cat_id, $cat_title) = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('forum', 'Editer la catégorie') . '</h3>
        <form id="phpbbforumedcat" action="admin.php" method="post">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="cat_id">ID</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="cat_id" id="cat_id" value="' . $cat_id . '" required="required" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="cat_title">' . __d('forum', 'Catégorie') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="cat_title" name="cat_title" value="' . StripSlashes($cat_title) . '" required="required" />
                </div>
            </div>
            <div class="mb-3 row">
                <input type="hidden" name="old_cat_id" value="' . $cat_id . '" />
                <input type="hidden" name="op" value="ForumCatSave" />
                <div class="col-sm-8 ms-sm-auto">
                    <button class="btn btn-primary col-sm-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;' . __d('forum', 'Sauver les modifications') . '</button>
                </div>
            </div>
        </form>';
    
        $fv_parametres = '
        cat_id: {
            validators: {
                regexp: {
                    regexp:/^(-|[1-9])(\d{0,10})$/,
                    message: "0-9"
                },
                between: {
                    min: -2147483648,
                    max: 2147483647,
                    message: "-2147483648 ... 2147483647"
                }
            }
        },';
    
        $arg1 = '
        var formulid=["phpbbforumedcat"];';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
