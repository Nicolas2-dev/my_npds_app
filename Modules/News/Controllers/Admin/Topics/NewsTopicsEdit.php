<?php

namespace Modules\News\Controllers\Admin;


use Npds\Config\Config;
use Modules\Npds\Support\Facades\Js;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;


class NewsTopicsEdit extends AdminController
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
    public function topicedit($topicid)
    {
        $result = sql_query("SELECT topicid, topicname, topicimage, topictext, topicadmin FROM topics WHERE topicid='$topicid'");
        list($topicid, $topicname, $topicimage, $topictext, $topicadmin) = sql_fetch_row($result);

        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Editer le Sujet :') . ' <span class="text-muted">' . Language::aff_langue($topicname) . '</span></h3>';
    
        if ($topicimage != '') {
            echo '<div class="card card-body my-4 py-3"><img class="img-fluid mx-auto d-block" src="' . Config::get('npds.tipath') . $topicimage . '" alt="image-sujet" /></div>';
        }
    
        echo '
        <form action="admin.php" method="post" id="topicchange">
            <fieldset>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="topicname">' . __d('news', 'Intitulé') . '</label>
                    <div class="col-sm-8">
                    <input id="topicname" class="form-control" type="text" name="topicname" maxlength="20" value="' . $topicname . '" placeholder="' . __d('news', 'cesiteestgénial') . '" required="required" />
                    <span class="help-block">' . __d('news', '(un simple nom sans espaces)') . ' - ' . __d('news', 'max caractères') . ' : <span id="countcar_topicname"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="topictext">' . __d('news', 'Texte') . '</label>
                    <div class="col-sm-8">
                    <textarea id="topictext" class="form-control" rows="3" name="topictext" maxlength="250" placeholder="' . __d('news', 'ce site est génial') . '" required="required">' . $topictext . '</textarea>
                    <span class="help-block">' . __d('news', '(description ou nom complet du sujet)') . ' - ' . __d('news', 'max caractères') . ' : <span id="countcar_topictext"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="topicimage">' . __d('news', 'Image') . '</label>
                    <div class="col-sm-8">
                    <input id="topicimage" class="form-control" type="text" name="topicimage" maxlength="20" value="' . $topicimage . '" placeholder="genial.png" />
                    <span class="help-block">' . __d('news', '(nom de l\'image + extension)') . ' (' . Config::get('npds.tipath') . '). - ' . __d('news', 'max caractères') . ' : <span id="countcar_topicimage"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="topicadmin">' . __d('news', 'Administrateur(s) du sujet') . '</label>
                    <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-cog fa-lg"></i></span>
                        <input class="form-control" type="text" id="topicadmin" name="topicadmin" maxlength="255" value="' . $topicadmin . '" />
                    </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
            <hr />
            <h4 class="my-3">' . __d('news', 'Ajouter des Liens relatifs au Sujet') . '</h4>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="name">' . __d('news', 'Nom du site') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="name" id="name" maxlength="30" />
                    <span class="help-block">' . __d('news', 'max caractères') . ' : <span id="countcar_name"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="url">' . __d('news', 'URL') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="url" name="url" id="url" maxlength="320" placeholder="http://www.valideurl.org" />
                    <span class="help-block">' . __d('news', 'max caractères') . ' : <span id="countcar_url"></span></span>
                </div>
            </div>
            </fieldset>
            <div class="mb-3 row">
                <input type="hidden" name="topicid" value="' . $topicid . '" />
                <input type="hidden" name="op" value="topicchange" />
                <div class="col-sm-8 ms-sm-auto">
                    <button class="btn btn-primary" type="submit">' . __d('news', 'Sauver les modifications') . '</button>
                    <button class="btn btn-secondary" onclick="javascript:document.location.href=\'admin.php?op=topicsmanager\'">' . __d('news', 'Retour en arrière') . '</button>
                </div>
            </div>
        </form>';
    
        /*
        <form id="fad_deltop" action="admin.php" method="post">
            <input type="hidden" name="topicid" value="'.$topicid.'" />
            <input type="hidden" name="op" value="topicdelete" />
        </form>
        <button class="btn btn-danger"><i class="fas fa-trash fa-lg"></i>&nbsp;&nbsp;'.__d('news', 'Effacer le Sujet !').'</button>
        */
    
        echo '
        <hr />
        <h3 class="my-2">' . __d('news', 'Gérer les Liens Relatifs : ') . ' <span class="text-muted">' . Language::aff_langue($topicname) . '</span></h3>';
    
        $res = sql_query("SELECT rid, name, url FROM related WHERE tid='$topicid'");
    
        echo '
        <table id="tad_linkrel" data-toggle="table" data-striped="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <th data-sortable="true" data-halign="center">' . __d('news', 'Nom') . '</th>
                <th data-sortable="true" data-halign="center">' . __d('news', 'Url') . '</th>
                <th class="n-t-col-xs-2" data-halign="center" data-align="right">' . __d('news', 'Fonctions') . '</th>
            </thead>
            <tbody>';
    
        while (list($rid, $name, $url) = sql_fetch_row($res)) {
            echo '
                <tr>
                    <td>' . $name . '</td>
                    <td><a href="' . $url . '" target="_blank">' . $url . '</a></td>
                    <td>
                       <a href="admin.php?op=relatededit&amp;tid=' . $topicid . '&amp;rid=' . $rid . '" ><i class="fas fa-edit fa-lg" data-bs-toggle="tooltip" title="' . __d('news', 'Editer') . '"></i></a>&nbsp;
                       <a href="' . $url . '" target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a>&nbsp;
                       <a href="admin.php?op=relateddelete&amp;tid=' . $topicid . '&amp;rid=' . $rid . '" ><i class="fas fa-trash fa-lg text-danger" data-bs-toggle="tooltip" title="' . __d('news', 'Effacer') . '"></i></a>
                    </td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>';
    
        $fv_parametres = '
        topicadmin: {
            validators: {
                callback: {
                    message: "Please choose an administrator from the provided list.",
                    callback: function(value, validator, $field) {
                    diff="";
                    var value = $field.val();
                    if (value === "") {return true;}
                    function split( n ) {
                        return n.split( /,\s*/ );
                    }
                    diff = $(split(value)).not(admin).get();
                    console.log(diff);
                    if (diff!="") {return false;}
                    return true;
                    }
                }
            }
        },
        topicimage: {
            validators: {
                regexp: {
                    regexp: /^[\w]+\\.(jpg|jpeg|png|gif)$/,
                    message: "This must be a valid file name with one of this extension jpg, jpeg, png, gif."
                }
            }
        },
        topicname: {
            validators: {
                regexp: {
                    regexp: /^\w+$/i,
                    message: "This must be a simple word without space."
                }
            }
        },';
    
        $arg1 = '
        var formulid = ["topicchange"];
        inpandfieldlen("topicname",20);
        inpandfieldlen("topictext",250);
        inpandfieldlen("topicimage",20);
        inpandfieldlen("name",30);
        inpandfieldlen("url",320);
        ';
    
        echo Js::auto_complete_multi('admin', 'aid', 'authors', 'topicadmin', '');
    
        Css::adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
