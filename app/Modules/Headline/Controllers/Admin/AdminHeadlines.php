<?php

namespace App\Modules\Headline\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;


class AdminHeadlines extends AdminController
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
        // $f_meta_nom = 'HeadlinesAdmin';
        // $f_titre = __d('headline', 'Grands Titres de sites de News');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        
        // $hlpfile = "language/manuels/Config::get('npds.language')/headlines.html";

        // switch ($op) {
        //     case 'HeadlinesDel':
        //         HeadlinesDel($hid, $ok);
        //         break;
        
        //     case 'HeadlinesAdd':
        //         HeadlinesAdd($xsitename, $url, $headlinesurl, $status);
        //         break;
        
        //     case 'HeadlinesSave':
        //         HeadlinesSave($hid, $xsitename, $url, $headlinesurl, $status);
        //         break;
        
        //     case 'HeadlinesAdmin':
        //         HeadlinesAdmin();
        //         break;
        
        //     case 'HeadlinesEdit':
        //         HeadlinesEdit($hid);
        //         break;
        // }        
    // }

    function HeadlinesAdmin()
    {
        echo '
        <hr />
        <h3 class="mb-3">' . __d('headline', 'Liste des Grands Titres de sites de News') . '</h3>
        <table id="tad_headline" data-toggle="table" data-classes="table table-striped table-borderless" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">' . __d('headline', 'ID') . '</th>
                <th data-sortable="true" data-halign="center" >' . __d('headline', 'Nom du site') . '</th>
                <th data-sortable="true" data-halign="center" >' . __d('headline', 'URL') . '</th>
                <th data-sortable="true" data-halign="center" data-align="right" >' . __d('headline', 'Etat') . '</th>
                <th class="n-t-col-xs-2" data-halign="center" data-align="center" >' . __d('headline', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        $result = sql_query("SELECT hid, sitename, url, headlinesurl, status FROM headlines ORDER BY hid");
    
        while (list($hid, $sitename, $url, $headlinesurl, $status) = sql_fetch_row($result)) {
            echo '
                <tr>
                    <td>' . $hid . '</td>
                    <td>' . $sitename . '</td>
                    <td>' . $url . '</td>';
    
            if ($status == 1) {
                $status = '<span class="text-success">' . __d('headline', 'Actif(s)') . '</span>';
            } else {
                $status = '<span class="text-danger">' . __d('headline', 'Inactif(s)') . '</span>';
            }
    
            echo '
                    <td>' . $status . '</td>
                    <td>
                    <a href="admin.php?op=HeadlinesEdit&amp;hid=' . $hid . '"><i class="fa fa-edit fa-lg" title="' . __d('headline', 'Editer') . '" data-bs-toggle="tooltip"></i></a>&nbsp;
                    <a href="' . $url . '" target="_blank"><i class="fas fa-external-link-alt fa-lg" title="' . __d('headline', 'Visiter') . '" data-bs-toggle="tooltip"></i></a>&nbsp;
                    <a href="admin.php?op=HeadlinesDel&amp;hid=' . $hid . '&amp;ok=0" class="text-danger"><i class="fas fa-trash fa-lg" title="' . __d('headline', 'Effacer') . '" data-bs-toggle="tooltip"></i></a>
                    </td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>
        <hr />
        <h3 class="mb-3">' . __d('headline', 'Nouveau Grand Titre') . '</h3>
        <form id="fad_newheadline" action="admin.php" method="post">
            <fieldset>
                <div class="form-floating mb-3">
                    <input id="xsitename" class="form-control" type="text" name="xsitename" placeholder="' . __d('headline', 'Nom du site') . '" maxlength="30" required="required" />
                    <label for="xsitename">' . __d('headline', 'Nom du site') . '</label>
                </div>
                <div class="form-floating mb-3">
                    <input id="url" class="form-control" type="url" name="url" placeholder="' . __d('headline', 'URL') . '" maxlength="320" required="required" />
                    <label for="url">' . __d('headline', 'URL') . '</label>
                    <span class="help-block text-end"><span id="countcar_url"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input id="headlinesurl" class="form-control" type="url" name="headlinesurl" placeholder="' . __d('headline', 'URL pour le fichier RDF/XML') . '" maxlength="320" required="required" />
                    <label for="headlinesurl">' . __d('headline', 'URL pour le fichier RDF/XML') . '</label>
                    <span class="help-block text-end"><span id="countcar_headlinesurl"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="status" name="status">
                    <option name="status" value="1">' . __d('headline', 'Actif(s)') . '</option>
                    <option name="status" value="0" selected="selected">' . __d('headline', 'Inactif(s)') . '</option>
                    </select>
                    <label class="col-form-label col-sm-4" for="status">' . __d('headline', 'Etat') . '</label>
                </div>
                <button class="btn btn-primary" type="submit"><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('headline', 'Ajouter') . '</button>
                <input type="hidden" name="op" value="HeadlinesAdd" />
            </fieldset>
        </form>';
    
        $arg1 = '
            var formulid = ["fad_newheadline"];
            inpandfieldlen("xsitename",30);
            inpandfieldlen("url",320);
            inpandfieldlen("headlinesurl",320);';
    
        adminfoot('fv', '', $arg1, '');
    }
    
    function HeadlinesEdit($hid)
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
    
    function HeadlinesSave($hid, $xsitename, $url, $headlinesurl, $status)
    {
        sql_query("UPDATE headlines SET sitename='$xsitename', url='$url', headlinesurl='$headlinesurl', status='$status' WHERE hid='$hid'");
        
        Header("Location: admin.php?op=HeadlinesAdmin");
    }
    
    function HeadlinesAdd($xsitename, $url, $headlinesurl, $status)
    {
        sql_query("INSERT INTO headlines VALUES (NULL, '$xsitename', '$url', '$headlinesurl', '$status')");
        
        Header("Location: admin.php?op=HeadlinesAdmin");
    }
    
    function HeadlinesDel($hid, $ok = 0)
    {
        if ($ok == 1) {
            sql_query("DELETE FROM headlines WHERE hid='$hid'");
    
            Header("Location: admin.php?op=HeadlinesAdmin");
        } else {
            echo '
            <hr />
            <p class="alert alert-danger">
                <strong class="d-block mb-1">' . __d('headline', 'Etes-vous sûr de vouloir supprimer cette boîte de Titres ?') . '</strong>
                <a class="btn btn-danger btn-sm" href="admin.php?op=HeadlinesDel&amp;hid=' . $hid . '&amp;ok=1" role="button">' . __d('headline', 'Oui') . '</a>&nbsp;<a class="btn btn-secondary btn-sm" href="admin.php?op=HeadlinesAdmin" role="button">' . __d('headline', 'Non') . '</a>
            </p>';
        }
    }

}
