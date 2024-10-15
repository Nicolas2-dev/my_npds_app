<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class NewsStoriesRemove extends AdminController
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
     * @param [type] $sid
     * @param integer $ok
     * @return void
     */
    public function removeStory($sid, $ok = 0)
    {
        if (($sid == '') or ($sid == '0'))
            header("location: admin.php");
    
        $result = sql_query("SELECT topic FROM stories WHERE sid='$sid'");
        list($topic) = sql_fetch_row($result);
    
        $affiche = false;
    
        $result2 = sql_query("SELECT topicadmin, topicname FROM topics WHERE topicid='$topic'");
        list($topicadmin, $topicname) = sql_fetch_row($result2);
    
        if ($radminsuper)
            $affiche = true;
        else {
            $topicadminX = explode(',', $topicadmin);
    
            for ($i = 0; $i < count($topicadminX); $i++) {
                if (trim($topicadminX[$i]) == $aid) 
                    $affiche = true;
            }
        }
    
        if (!$affiche) 
            header("location: admin.php");
    
        if ($ok) {
            $res = sql_query("SELECT hometext, bodytext, notes FROM stories WHERE sid='$sid'");
            list($hometext, $bodytext, $notes) = sql_fetch_row($res);
    
            $artcomplet = $hometext . $bodytext . $notes;
            $rechuploadimage = '#modules/upload/upload/a[i|c|]\d+_\d+_\d+.[a-z]{3,4}#m';
    
            preg_match_all($rechuploadimage, $artcomplet, $uploadimages);
    
            foreach ($uploadimages[0] as $imagetodelete) {
                unlink($imagetodelete);
            }
    
            sql_query("DELETE FROM stories WHERE sid='$sid'");
    
            // commentaires
            if (file_exists("modules/comments/article.conf.php")) {
                include("modules/comments/article.conf.php");
    
                sql_query("DELETE FROM posts WHERE forum_id='$forum' AND topic_id='$topic'");
            }
    
            global $aid;
            Ecr_Log('security', "removeStory ($sid, $ok) by AID : $aid", '');
    
            if (Config::get('npds.ultramode'))
                ultramode();
    
            Header("Location: admin.php");
        } else {

            $hlpfile = "language/manuels/Config::get('npds.language')/newarticle.html";

            echo '
            <div class="alert alert-danger">' . __d('news', 'Etes-vous sûr de vouloir effacer l\'Article N°') . ' ' . $sid . ' ' . __d('news', 'et tous ses Commentaires ?') . '</div>
            <p class=""><a href="admin.php?op=RemoveStory&amp;sid=' . $sid . '&amp;ok=1" class="btn btn-danger" >' . __d('news', 'Oui') . '</a>&nbsp;<a href="admin.php" class="btn btn-secondary" >' . __d('news', 'Non') . '</a></p>';
        }
    }

}
