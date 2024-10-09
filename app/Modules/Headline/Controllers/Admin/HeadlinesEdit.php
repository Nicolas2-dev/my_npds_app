<?php

namespace App\Modules\Headline\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;


class HeadlinesEdit extends AdminController
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
    protected $hlpfile = 'headlines';

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
    protected $f_meta_nom = 'HeadlinesAdmin';


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
        $this->f_titre = __d('headline', 'Grands Titres de sites de News');

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
     * @param [type] $hid
     * @return void
     */
    public function HeadlinesEdit($hid)
    {
        $result = sql_query("SELECT sitename, url, headlinesurl, status FROM headlines WHERE hid='$hid'");
        list($xsitename, $url, $headlinesurl, $status) = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('headline', 'Editer paramètres Grand Titre') . '</h3>
        <form id="fed_headline" action="admin.php" method="post">
            <fieldset>
                <input type="hidden" name="hid" value="' . $hid . '" />
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xsitename">' . __d('headline', 'Nom du site') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" type="text" name="xsitename" id="xsitename"  maxlength="30" value="' . $xsitename . '" required="required" />
                    <span class="help-block text-end"><span id="countcar_xsitename"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="url">' . __d('headline', 'URL') . '&nbsp;<a href="' . $url . '" target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a></label>
                    <div class="col-sm-8">
                    <input class="form-control" type="url" id="url" name="url" maxlength="320" value="' . $url . '" required="required" />
                    <span class="help-block text-end"><span id="countcar_url"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="headlinesurl">' . __d('headline', 'URL pour le fichier RDF/XML') . '&nbsp;<a href="' . $headlinesurl . '" target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a></label>
                    <div class="col-sm-8">
                    <input class="form-control" type="url" name="headlinesurl" id="headlinesurl" maxlength="320" value="' . $headlinesurl . '" required="required" />
                    <span class="help-block text-end"><span id="countcar_headlinesurl"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="status">' . __d('headline', 'Etat') . '</label>
                    <div class="col-sm-8">
                    <select class="form-select" name="status">';
    
        $sel_a = $status == 1 ? 'selected="selected"' : '';
        $sel_i = $status == 0 ? 'selected="selected"' : '';
    
        echo '
    
                        <option name="status" value="1" ' . $sel_a . '>' . __d('headline', 'Actif(s)') . '</option>
                        <option name="status" value="0" ' . $sel_i . '>' . __d('headline', 'Inactif(s)') . '</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <input type="hidden" name="op" value="HeadlinesSave" />
                    <div class="col-sm-8 ms-sm-auto">
                    <button class="btn btn-primary col-12" type="submit"><i class="fa fa-edit fa-lg"></i>&nbsp;' . __d('headline', 'Sauver les modifications') . '</button>
                    </div>
                </div>
            </fieldset>
        </form>';
    
        $arg1 = '
            var formulid = ["fed_headline"];
            inpandfieldlen("xsitename",30);
            inpandfieldlen("url",320);
            inpandfieldlen("headlinesurl",320);';
    
        adminfoot('fv', '', $arg1, '');
    }

}
