<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class AdminSubmissions extends AdminController
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
    protected $f_meta_nom = '';


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
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    // public function __construct()
    // {
        // $f_meta_nom = 'submissions';
        // $f_titre = __d('news', 'Article en attente de validation');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        
        // $hlpfile = "language/manuels/Config::get('npds.language')/submissions.html";

        // switch ($op) {

        //     default:
        //         submissions();
        //         break;
        // }
    // }

    function submissions()
    {
        $dummy = 0;

        $result = sql_query("SELECT qid, subject, timestamp, topic, uname FROM queue ORDER BY timestamp");
    
        if (sql_num_rows($result) == 0)
            echo '
            <hr />
            <h3>' . __d('news', 'Pas de nouveaux Articles postés') . '</h3>';
        else {
            echo '
            <hr />
            <h3>' . __d('news', 'Nouveaux Articles postés') . '<span class="badge bg-danger float-end">' . sql_num_rows($result) . '</span></h3>
            <table id="tad_subm" data-toggle="table" data-striped="true" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
                <thead>
                    <tr>
                        <th data-halign="center"><i class="fa fa-user fa-lg"></i></th>
                        <th data-sortable="true" data-sorter="htmlSorter" data-halign="center">' . __d('news', 'Sujet') . '</th>
                        <th data-sortable="true" data-sorter="htmlSorter" data-halign="center">' . __d('news', 'Titre') . '</th>
                        <th data-halign="center" data-align="right">' . __d('news', 'Date') . '</th>
                        <th class="n-t-col-xs-2" data-halign="center" data-align="center">' . __d('news', 'Fonctions') . '</th>
                    </tr>
                </thead>
                <tbody>';
    
            while (list($qid, $subject, $timestamp, $topic, $uname) = sql_fetch_row($result)) {
    
                if ($topic < 1) 
                    $topic = 1;
    
                $affiche = false;
                $result2 = sql_query("SELECT topicadmin, topictext, topicimage FROM topics WHERE topicid='$topic'");
                list($topicadmin, $topictext, $topicimage) = sql_fetch_row($result2);
    
                if ($radminsuper)
                    $affiche = true;
                else {
                    $topicadminX = explode(',', $topicadmin);
    
                    for ($i = 0; $i < count($topicadminX); $i++) {
                        if (trim($topicadminX[$i]) == $aid) 
                            $affiche = true;
                    }
                }
    
                echo '
                <tr>
                    <td>' . userpopover($uname, '40', 2) . ' ' . $uname . '</td>
                    <td>';
    
                if ($subject == '') 
                    $subject = __d('news', 'Aucun Sujet');
    
                $subject = aff_langue($subject);
    
                if ($affiche)
                    echo '<img class=" " src="assets/images/topics/' . $topicimage . '" height="30" width="30" alt="avatar" />&nbsp;<a href="admin.php?op=topicedit&amp;topicid=' . $topic . '" class="adm_tooltip">' . aff_langue($topictext) . '</a></td>
                    <td align="left"><a href="admin.php?op=DisplayStory&amp;qid=' . $qid . '">' . ucfirst($subject) . '</a></td>';
                else
                    echo aff_langue($topictext) . '</td>
                    <td><i>' . ucfirst($subject) . '</i></td>';
    
                echo '
                    <td class="small">' . formatTimestamp($timestamp) . '</td>';
    
                if ($affiche)
                    echo '
                        <td><a class="" href="admin.php?op=DisplayStory&amp;qid=' . $qid . '"><i class="fa fa-edit fa-lg" title="' . __d('news', 'Editer') . '" data-bs-toggle="tooltip" ></i></a><a class="text-danger" href="admin.php?op=DeleteStory&amp;qid=' . $qid . '"><i class="fas fa-trash fa-lg ms-3" title="' . __d('news', 'Effacer') . '" data-bs-toggle="tooltip" ></i></a></td>
                    </tr>';
                else
                    echo '
                        <td>&nbsp;</td>
                    </tr>';
    
                $dummy++;
            }
    
            if ($dummy < 1)
                echo '<h3>' . __d('news', 'Pas de nouveaux Articles postés') . '</h3>';
            else
                echo '
                    </tbody>
                </table>';
        }
    
        adminfoot('', '', '', '');
    }

}
