<?php

namespace Modules\Forum\Controllers\Admin\Forum;

use Modules\Npds\Core\AdminController;


class Forum extends AdminController
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
     * @return void
     */
    public function ForumAdmin()
    {
        echo '
        <hr />
        <h3 class="mb-3">' . __d('forum', 'Catégories de Forum') . '</h3>
        <table data-toggle="table" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right">' . __d('forum', 'Index') . '&nbsp;</th>
                    <th class="n-t-col-xs-5" data-sortable="true" data-halign="center">' . __d('forum', 'Nom') . '&nbsp;</th>
                    <th class="n-t-col-xs-3" data-halign="center" data-align="right">' . __d('forum', 'Nombre de Forum(s)') . '&nbsp;</th>
                    <th class="n-t-col-xs-2" data-halign="center" data-align="center">' . __d('forum', 'Fonctions') . '&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
    
        $result = sql_query("SELECT cat_id, cat_title FROM catagories ORDER BY cat_id");
    
        while (list($cat_id, $cat_title) = sql_fetch_row($result)) {
    
            $gets = sql_query("SELECT COUNT(*) AS total FROM forums WHERE cat_id='$cat_id'");
            $numbers = sql_fetch_assoc($gets);
    
            echo '
                <tr>
                    <td>' . $cat_id . '</td>
                    <td>' . StripSlashes($cat_title) . '</td>
                    <td>' . $numbers['total'] . ' <a href="admin.php?op=ForumGo&amp;cat_id=' . $cat_id . '"><i class="fa fa-eye fa-lg align-middle" title="' . __d('forum', 'Voir les forums de cette catégorie') . ': ' . StripSlashes($cat_title) . '." data-bs-toggle="tooltip" data-bs-placement="right"></i></a></td>
                    <td><a href="admin.php?op=ForumCatEdit&amp;cat_id=' . $cat_id . '"><i class="fa fa-edit fa-lg" title="' . __d('forum', 'Editer') . '" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=ForumCatDel&amp;cat_id=' . $cat_id . '&amp;ok=0"><i class="fas fa-trash fa-lg text-danger ms-3" title="' . __d('forum', 'Effacer') . '" data-bs-toggle="tooltip" ></i></a></td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>
        <h3 class="my-3">' . __d('forum', 'Ajouter une catégorie') . '</h3>
        <form id="forumaddcat" action="admin.php" method="post">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="catagories">' . __d('forum', 'Nom') . '</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="catagories" id="catagories" rows="3" required="required"></textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto">
                    <input type="hidden" name="op" value="ForumCatAdd" />
                    <button class="btn btn-primary col-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('forum', 'Ajouter une catégorie') . '</button>
                </div>
            </div>
        </form>';
    
        $arg1 = '
        var formulid = ["forumaddcat"];';
    
        adminfoot('fv', '', $arg1, '');
    }

}
