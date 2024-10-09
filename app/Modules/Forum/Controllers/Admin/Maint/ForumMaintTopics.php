<?php

namespace App\Modules\Forum\Controllers\Admin\Maint;

use App\Modules\Npds\Core\AdminController;


class ForumMaintTopics extends AdminController
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
     * @param [type] $before
     * @param [type] $forum_name
     * @return void
     */
    public function ForumMaintTopics($before, $forum_name)
    {
        echo '
        <hr />
        <h3 class="text-danger">' . __d('forum', 'Supprimer massivement les Topics') . '</h3>';
    
        if ($before != '') {
            echo '&nbsp;<span class="text-danger">< ' . $before . '</span>';
            $add_sql = "AND topic_time<'$before'";
            $topic_check = ' checked="checked"';
        } else {
            $add_sql = '';
            $topic_check = '';
        }
    
        $add_sql2 = $forum_name != '' ? "WHERE forum_name='$forum_name'" : '';
    
        echo '<form action="admin.php" method="post">';
    
        $resultF = sql_query("SELECT forum_id, forum_name FROM forums $add_sql2 ORDER BY forum_id ASC");
    
        while (list($forum_id, $forum_name) = sql_fetch_row($resultF)) {
            echo '
            <h4>' . $forum_name . '</h4>
            <div class="mb-3 border p-4">';
    
            $resultT = sql_query("SELECT topic_id, topic_title FROM forumtopics WHERE forum_id='$forum_id' $add_sql ORDER BY topic_id ASC");
            
            while (list($topic_id, $topic_title) = sql_fetch_row($resultT)) {
                $tt = Config::get('npds.parse') == 0 ? FixQuotes($topic_title) : stripslashes($topic_title);
                $oo = urlencode($tt); /////
    
                echo '
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" name="topics[' . $topic_id . ']" id="topics' . $topic_id . '" ' . $topic_check . '/>
                    <label class="form-check-label" for="topics' . $topic_id . '"><a href="admin.php?op=MaintForumTopicDetail&amp;topic=' . $topic_id . '&amp;topic_title=' . $tt . '" data-bs-toggle="tooltip" title="' . $tt . '" >' . $topic_id . '</a></label>
                </div>';
            }
    
            sql_free_result($resultT);
    
            echo '</div>';
        }
    
        sql_free_result($resultF);
    
        echo '
            <div class="mb-3>"
                <input type="hidden" name="op" value="ForumMaintTopicMassiveSup" />
                <input class="btn btn-danger" type="submit" name="Topics_Del" value="' . __d('forum', 'Supprimer massivement les Topics') . '" />
            </div>
        </form>';
    
        adminfoot('', '', '', '');
    }

}
