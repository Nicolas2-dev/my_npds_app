<?php

namespace Modules\News\Controllers\Admin;


use Npds\Config\Config;
use Modules\Npds\Core\AdminController;


class NewsTopicsRelatedEdit extends AdminController
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
    protected $hlpfile = 'topics';

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
    protected $f_meta_nom = 'topicsmanager';


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
        $this->f_titre = __d('news', 'Gestion des sujets');

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
     * @param [type] $topicid
     * @param integer $ok
     * @return void
     */
    public function relatededit($tid, $rid)
    {
        $result = sql_query("SELECT name, url FROM related WHERE rid='$rid'");
        list($name, $url) = sql_fetch_row($result);
    
        $result2 = sql_query("SELECT topictext, topicimage FROM topics WHERE topicid='$tid'");
        list($topictext, $topicimage) = sql_fetch_row($result2);

        echo '
        <hr />
        <h3>' . __d('news', 'Sujet : ') . ' ' . $topictext . '</h3>
        <h4>' . __d('news', 'Editer les Liens Relatifs') . '</h4>';
    
        if ($topicimage != "")
            echo '
            <div class="thumbnail">
                <img class="img-fluid " src="' . Config::get('npds.tipath') . $topicimage . '" alt="' . $topictext . '" />
            </div>';
    
        echo '
        <form class="form-horizontal" action="admin.php" method="post" id="editrelatedlink">
            <fieldset>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="name">' . __d('news', 'Nom du site') . '</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="name" id="name" value="' . $name . '" maxlength="30" required="required" />
                    <span class="help-block text-end"><span id="countcar_name"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="url">' . __d('news', 'URL') . '</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-text">
                            <a href="' . $url . '" target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a>
                        </span>
                        <input type="url" class="form-control" name="url" id="url" value="' . $url . '" maxlength="320" />
                    </div>
                    <span class="help-block text-end"><span id="countcar_url"></span></span>
                    </div>
                    <input type="hidden" name="op" value="relatedsave" />
                    <input type="hidden" name="tid" value="' . $tid . '" />
                    <input type="hidden" name="rid" value="' . $rid . '" />
                </fieldset>
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto">
                    <button class="btn btn-primary col-12" type="submit">' . __d('news', 'Sauver les modifications') . '</button>
                </div>
            </div>
        </form>';
    
        $arg1 = '
            var formulid = ["editrelatedlink"];
            inpandfieldlen("name",30);
            inpandfieldlen("url",320);
        ';
    
        adminfoot('fv', '', $arg1, '');
    }

    /**
     * Undocumented function
     *
     * @param [type] $topicid
     * @param integer $ok
     * @return void
     */
    public function relatedsave($tid, $rid, $name, $url)
    {
        sql_query("UPDATE related SET name='$name', url='$url' WHERE rid='$rid'");
    
        Header("Location: admin.php?op=topicedit&topicid=$tid");
    }

}
