<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Date;
use Modules\Npds\Support\Facades\Language;


class NewsAutomatedStory extends AdminController
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
    protected $hlpfile = 'automated';

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
    protected $f_meta_nom = 'autoStory';


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
        $this->f_titre = __d('news', 'Articles programmés');

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
    public function autoStory()
    {
        echo '
        <hr />
        <h3>' . __d('news', 'Liste des articles') . '</h3>
        <table id="tab_adm" data-toggle="table" data-striped="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-6" data-sortable="true" data-halign="center">' . __d('news', 'Titre') . '</th>
                    <th class="n-t-col-xs-4 small" data-sortable="true" data-align="center" data-align="right">' . __d('news', 'Date prévue de publication') . '</th>
                    <th class="n-t-col-xs-2" data-align="center">' . __d('news', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        $result = sql_query("SELECT anid, title, date_debval, topic FROM autonews ORDER BY date_debval ASC");
    
        while (list($anid, $title, $time, $topic) = sql_fetch_row($result)) {
            if ($anid != '') {
                $affiche = false;
    
                $result2 = sql_query("SELECT topicadmin, topicname FROM topics WHERE topicid='$topic'");
                list($topicadmin, $topicname) = sql_fetch_row($result2);
    
                if ($radminsuper) {
                    $affiche = true;
                } else {
                    $topicadminX = explode(",", $topicadmin);
    
                    for ($i = 0; $i < count($topicadminX); $i++) {
                        if (trim($topicadminX[$i]) == $aid) {
                            $affiche = true;
                        }
                    }
                }
    
                if ($title == '') {
                    $title = __d('news', 'Aucun Sujet');
                }
    
                if ($affiche) {
                    echo '
                    <tr>
                        <td><a href="admin.php?op=autoEdit&amp;anid=' . $anid . '">' . Language::aff_langue($title) . '</a></td>
                        <td>' . Date::formatTimestamp("nogmt" . $time) . '</td>
                        <td><a href="admin.php?op=autoEdit&amp;anid=' . $anid . '"><i class="fa fa-edit fa-lg me-2" title="' . __d('news', 'Afficher l\'article') . '" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=autoDelete&amp;anid=' . $anid . '">&nbsp;<i class="fas fa-trash fa-lg text-danger" title="' . __d('news', 'Effacer l\'Article') . '" data-bs-toggle="tooltip" ></i></a></td>
                    </tr>';
                } else {
                    echo '
                    <tr>
                        <td><i>' . Language::aff_langue($title) . '</i></td>
                        <td>' . Date::formatTimestamp("nogmt" . $time) . '</td>
                        <td>&nbsp;</td>
                    </tr>';
                }
            }
        }
    
        echo '
            </tbody>
        </table>';
    
        Css::adminfoot('', '', '', '');
    }

}
