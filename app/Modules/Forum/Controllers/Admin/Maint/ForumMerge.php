<?php

namespace App\Modules\Forum\Controllers\Admin\Maint;

use App\Modules\Npds\Core\AdminController;


class ForumMerge extends AdminController
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
    public function MergeForum()
    {
        echo '
        <hr/>
        <h3 class="mb-3">' . __d('forum', 'Fusionner des forums') . '</h3>
        <form id="fad_mergeforum" action="admin.php" method="post">
            <fieldset>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="oriforum">' . __d('forum', 'Forum d\'origine') . '</label>
                    <div class="col-sm-8">
                    <select class="form-select" id="oriforum" name="oriforum">';
    
        $sql = "SELECT forum_id, forum_name FROM forums ORDER BY forum_index,forum_id";
    
        if ($result = sql_query($sql)) {
            if ($myrow = sql_fetch_assoc($result)) {
                do {
                    echo '<option value="' . $myrow['forum_id'] . '">' . $myrow['forum_name'] . '</option>';
                } while ($myrow = sql_fetch_assoc($result));
            } else
                echo '<option value="-1">' . __d('forum', 'No More Forums') . '</option>';
        } else
            echo '<option value="-1">Database Error</option>';
    
        echo '
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="destforum">' . __d('forum', 'Forum de destination') . '</label>
                    <div class="col-sm-8">
                    <select class="form-select" id="destforum" name="destforum">';
    
        if ($result = sql_query($sql)) {
            if ($myrow = sql_fetch_assoc($result)) {
                do {
                    echo '<option value="' . $myrow['forum_id'] . '">' . $myrow['forum_name'] . '</option>';
                } while ($myrow = sql_fetch_assoc($result));
            } else
                echo '<option value="-1">' . __d('forum', 'No More Forums') . '</option>';
        } else
            echo '<option value="-1">Database Error</option>';
    
        echo '
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-8 ms-sm-auto">
                    <input type="hidden" name="op" value="MergeForumAction" />
                    <button class="btn btn-primary col-12" type="submit" name="Merge_Forum_Action">' . __d('forum', 'Fusionner') . '</button>
                    </div>
                </div>
            </fieldset>
        </form>';
    
        sql_free_result($result);
    
        adminfoot('', '', '', '');
    }

}
