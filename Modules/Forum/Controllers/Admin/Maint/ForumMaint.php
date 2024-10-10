<?php

namespace Modules\Forum\Controllers\Admin\Maint;

use Modules\Npds\Core\AdminController;


class ForumMain extends AdminController
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
    protected $hlpfile = 'forummaint';

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
    protected $f_meta_nom = 'MaintForumAdmin';


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
        $this->f_titre = __d('forum', 'Maintenance des Forums');

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
    public function ForumMaintAdmin()
    {
        echo '
        <hr />
        <h3 class="mb-3">' . __d('forum', 'Maintenance des Forums') . '</h3>';
    
        // Mark Topics, Synchro Forum_read table, Merge Forums
        echo '
        <div class="row">
            <div class="col-12">
                <form id="fad_forumaction" action="admin.php" method="post">
                    <input type="hidden" name="op" value="MaintForumMarkTopics" />
                    <button class="btn btn-primary btn-block mt-1" type="submit" name="Topics_Mark"><i class="far fa-check-square fa-lg"></i>&nbsp;' . __d('forum', 'Marquer tous les Topics comme lus') . '</button>
                </form>
            </div>
            <div class="col-12">
                <form action="admin.php" method="post">
                    <input type="hidden" name="op" value="SynchroForum" />
                    <button class="btn btn-primary btn-block mt-1 " type="submit" name="Synchro_Forum"><i class="fas fa-sync fa-lg"></i>&nbsp;' . __d('forum', 'Synchroniser les forums') . '</button>
                </form>
            </div>
            <div class="col-12">
                <form action="admin.php" method="post">
                    <input type="hidden" name="op" value="MergeForum" />
                    <button class="btn btn-primary btn-block mt-1" type="submit" name="Merge_Forum"><i class="fa fa-compress fa-lg"></i>&nbsp;' . __d('forum', 'Fusionner des forums') . '</button>
                </form>
            </div>
        </div>
        <h3 class="my-3">' . __d('forum', 'Supprimer massivement les Topics') . '</h3>
        <form id="faddeletetop" action="admin.php" method="post" autocomplete="nope" >
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="titreforum">' . __d('forum', 'Nom du forum') . '</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="forum_name" id="titreforum" maxlength="150" autocomplete="nope" placeholder="   " />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="before">' . __d('forum', 'Date') . '</label>
                <div class="col-sm-8">
                    <div class="input-group">
                    <span id="datePicker" class="input-group-text bg-light date"><i class="far fa-calendar-check fa-lg"></i></span>
                    <input type="text" class="form-control" name="before" id="before" />
                    </div>
                    <span class="help-block text-end">Avant cette date !</span>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto">
                    <input type="hidden" name="op" value="MaintForumTopics" />
                    <button class="btn btn-primary" type="submit" name="Topics_Mark">' . __d('forum', 'Envoyer') . '</button>
                </div>
            </div>
        </form>
        <script type="text/javascript" src="assets/shared/flatpickr/dist/flatpickr.min.js"></script>
        <script type="text/javascript" src="assets/shared/flatpickr/dist/l10n/' . language_iso(1, '', '') . '.js"></script>
        <script type="text/javascript">
        //<![CDATA[
            $(document).ready(function() {
                $("<link>").appendTo("head").attr({type: "text/css", rel: "stylesheet",href: "assets/shared/flatpickr/dist/themes/App.css"});
            })
        //]]>
        </script>';
    
        $fv_parametres = '
            before:{},
            !###!
            flatpickr("#before", {
                altInput: true,
                altFormat: "l j F Y",
                dateFormat:"Y-m-d",
                "locale": "' . language_iso(1, '', '') . '",
                onChange: function() {
                    fvitem.revalidateField(\'before\');
                }
            });
        ';
    
        $arg1 = '
        var formulid = ["faddeletetop"];';
    
        echo auto_complete("forname", "forum_name", "forums", "titreforum", "86400");
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
