<?php

namespace App\Modules\Forum\Controllers\Admin\Maint;

use App\Modules\Npds\Core\AdminController;


class ForumMaintMarkTopics extends AdminController
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
    public function ForumMaintMarkTopics()
    {
        echo '
        <h3>' . __d('forum', 'Marquer tous les Topics comme lus') . '</h3>
        <table data-toggle="table" data-striped="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Topics ID</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';
    
        if (!$r = sql_query("DELETE FROM forum_read"))
            forumerror('0001');
        else {
            $resultF = sql_query("SELECT forum_id FROM forums ORDER BY forum_id ASC");
            $time_actu = time() + ((int) Config::get('npds.gmt') * 3600);
    
            while (list($forum_id) = sql_fetch_row($resultF)) {
                echo '
                <tr>
                    <td align="center">' . $forum_id . '</td>
                    <td align="left">';
    
                $resultT = sql_query("SELECT topic_id FROM forumtopics WHERE forum_id='$forum_id' ORDER BY topic_id ASC");
    
                while (list($topic_id) = sql_fetch_row($resultT)) {
                    $resultU = sql_query("SELECT uid FROM users ORDER BY uid DESC");
    
                    while (list($uid) = sql_fetch_row($resultU)) {
                        if ($uid > 1)
                            $r = sql_query("INSERT INTO forum_read (forum_id, topicid, uid, last_read, status) VALUES ('$forum_id', '$topic_id', '$uid', '$time_actu', '1')");
                    }
    
                    sql_free_result($resultU);
    
                    echo $topic_id . ' ';
                }
    
                sql_free_result($resultT);
    
                echo '
                    </td>
                    <td align="center">' . __d('forum', 'Ok') . '</td>
                </tr>';
            }
    
            sql_free_result($resultF);
        }
    
        echo '
        </tbody>
        </table>';
    
        adminfoot('', '', '', '');
    }

}
