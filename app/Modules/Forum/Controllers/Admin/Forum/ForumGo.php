<?php

namespace App\Modules\Forum\Controllers\Admin\Forum;

use App\Modules\Npds\Core\AdminController;


class ForumGo extends AdminController
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
    protected $hlpfile = 'forumcat';

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
    protected $f_meta_nom = 'ForumAdmin';


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
        $this->f_titre = __d('forum', 'Gestion des forums');

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
     * @param [type] $cat_id
     * @return void
     */
    function ForumGo($cat_id)
    {
        $result = sql_query("SELECT cat_title FROM catagories WHERE cat_id='$cat_id'");
        list($cat_title) = sql_fetch_row($result);
    
        $ctg = StripSlashes($cat_title);
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('forum', 'Forum classé en') . ' ' . $ctg . '</h3>
        <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-show-columns="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">' . __d('forum', 'Index') . '&nbsp;</th>
                    <th data-sortable="true" data-halign="center">' . __d('forum', 'Nom') . '&nbsp;</th>
                    <th data-sortable="true" data-halign="center">' . __d('forum', 'Modérateur(s)') . '&nbsp;</th>
                    <th data-sortable="true" data-halign="center">' . __d('forum', 'Accès') . '&nbsp;</th>
                    <th data-sortable="true" data-halign="center">' . __d('forum', 'Type') . '&nbsp;</th>
                    <th data-sortable="true" data-halign="center">' . __d('forum', 'Mode') . '&nbsp;</th>
                    <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="center"><img class="n-smil" src="assets/images/forum/subject/07.png" alt="icon_pieces jointes" title="' . __d('forum', 'Attachement') . '" data-bs-toggle="tooltip"></th>
                    <th data-sortable="true" data-halign="center" data-align="center">' . __d('forum', 'Fonctions') . '&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
    
        $result = sql_query("SELECT forum_id, forum_name, forum_access, forum_moderator, forum_type, arbre, attachement, forum_index FROM forums WHERE cat_id='$cat_id' ORDER BY forum_index,forum_id");
    
        while (list($forum_id, $forum_name, $forum_access, $forum_moderator, $forum_type, $arbre, $attachement, $forum_index) = sql_fetch_row($result)) {
            $moderator = str_replace(' ', ', ', get_moderator($forum_moderator));
    
            echo '
                <tr>
                    <td>' . $forum_index . '</td>
                    <td>' . $forum_name . '</td>
                    <td><i class="fa fa-balance-scale fa-lg fa-fw me-1"></i>' . $moderator . '</td>';
    
            switch ($forum_access) {
                case (0):
                    echo '
                <td>' . __d('forum', 'Publication Anonyme autorisée') . '</td>';
                    break;
    
                case (1):
                    echo '
                <td>' . __d('forum', 'Utilisateur enregistré') . '</td>';
                    break;
    
                case (2):
                    echo '
                <td>' . __d('forum', 'Modérateurs') . '</td>';
                    break;
    
                case (9):
                    echo '
                <td>Forum ' . __d('forum', 'Fermé') . '</td>';
                    break;
            }
    
            if ($forum_type == 0)
                echo '
                <td>' . __d('forum', 'Public') . '</td>';
            elseif ($forum_type == 1)
                echo '
                <td>' . __d('forum', 'Privé') . '</td>';
            elseif ($forum_type == 5)
                echo '
                <td>PHP + ' . __d('forum', 'Groupe') . '</td>';
            elseif ($forum_type == 6)
                echo '
                <td>PHP</td>';
            elseif ($forum_type == 7)
                echo '
                <td>' . __d('forum', 'Groupe') . '</td>';
            elseif ($forum_type == 8)
                echo '
                <td>' . __d('forum', 'Texte étendu') . '</td>';
            else
                echo '
                <td>' . __d('forum', 'Caché') . '</td>';
    
            if ($arbre)
                echo '
                <td>' . __d('forum', 'Arbre') . '</td>';
            else
                echo '
                <td>' . __d('forum', 'Standard') . '</td>';
    
            if ($attachement)
                echo '
                <td class="text-danger">' . __d('forum', 'Oui') . '</td>';
            else
                echo '
                <td>' . __d('forum', 'Non') . '</td>';
            echo '
                <td><a href="admin.php?op=ForumGoEdit&amp;forum_id=' . $forum_id . '&amp;ctg=' . urlencode($ctg) . '"><i class="fa fa-edit fa-lg" title="' . __d('forum', 'Editer') . '" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=ForumGoDel&amp;forum_id=' . $forum_id . '&amp;ok=0"><i class="fas fa-trash fa-lg text-danger ms-3" title="' . __d('forum', 'Effacer') . '" data-bs-toggle="tooltip" ></i></a></td>
            </tr>';
        }
    
        echo '
            </tbody>
        </table>
        <h3 class="my-3">' . __d('forum', 'Ajouter plus de Forum pour') . ' : <span class="text-muted">' . $ctg . '</span></h3>
        <form id="fadaddforu" action="admin.php" method="post">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_index">' . __d('forum', 'Index') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="forum_index" name="forum_index" max="9999" />
                    <span class="help-block text-end" id="countcar_forum_index"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_name">' . __d('forum', 'Nom du forum') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="forum_name" name="forum_name" maxlength="150" required="required" />
                    <span class="help-block">' . __d('forum', '(Redirection sur un forum externe : <.a href...)') . '<span class="float-end" id="countcar_forum_name"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_desc">' . __d('forum', 'Description') . '</label>
                <div class="col-sm-8">
                    <textarea class="form-control" id="forum_desc" name="forum_desc" rows="5"></textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_mod">' . __d('forum', 'Modérateur(s)') . '</label>
                <div class="col-sm-8">
                    <input id="l_forum_mod" class="form-control" type="text" id="forum_mod" name="forum_mod" required="required" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_access">' . __d('forum', 'Niveau d\'accès') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="forum_access" name="forum_access">
                    <option value="0">' . __d('forum', 'Publication Anonyme autorisée') . '</option>
                    <option value="1">' . __d('forum', 'Utilisateur enregistré uniquement') . '</option>
                    <option value="2">' . __d('forum', 'Modérateurs uniquement') . '</option>
                    <option value="9">' . __d('forum', 'Fermé') . '</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_type">' . __d('forum', 'Type') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="forum_type" name="forum_type" >
                    <option value="0">' . __d('forum', 'Public') . '</option>
                    <option value="1">' . __d('forum', 'Privé') . '</option>
                    <option value="5">PHP Script + ' . __d('forum', 'Groupe') . '</option>
                    <option value="6">PHP Script</option>
                    <option value="7">' . __d('forum', 'Groupe') . '</option>
                    <option value="8">' . __d('forum', 'Texte étendu') . '</option>
                    <option value="9">' . __d('forum', 'Caché') . '</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row d-none" id="the_multi_input">
                <label id="labmulti" class="col-form-label col-sm-4" for="forum_pass"></label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="forum_pass" name="forum_pass" />
                    <span id="help_forum_pass" class="help-block"><span class="float-end" id="countcar_forum_pass"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="arbre">' . __d('forum', 'Mode') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="arbre" name="arbre">
                    <option value="0">' . __d('forum', 'Standard') . '</option>
                    <option value="1">' . __d('forum', 'Arbre') . '</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="attachement">' . __d('forum', 'Attachement') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="attachement" name="attachement">
                    <option value="0">' . __d('forum', 'Non') . '</option>
                    <option value="1">' . __d('forum', 'Oui') . '</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto">
                    <input type="hidden" name="ctg" value="' . $ctg . '" />
                    <input type="hidden" name="cat_id" value="' . $cat_id . '" />
                    <input type="hidden" name="op" value="ForumGoAdd" />
                    <button class="btn btn-primary col-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('forum', 'Ajouter') . ' </button>
                </div>
            </div>
            </form>';
    
        echo auto_complete_multi('modera', 'uname', 'users', 'l_forum_mod', 'WHERE uid<>1');
    
        $arg1 = '
        var formulid=["fadaddforu"];
        inpandfieldlen("forum_index",4);
        inpandfieldlen("forum_name",150);';
    
        $fv_parametres = '
        forum_pass:{
            validators: {
                regexp: {
                    enabled: false,
                    regexp: /^([2-9]|[1-9][0-9]|[1][0-2][0-6])$/,
                    message: "2...126",
                },
                stringLength: {
                    min: 8,
                    max:60,
                    message: "> 8 < 60"
                },
                notEmpty: {
                    enabled: true,
                    message: "Required",
                }
            },
        },
        forum_index: {
            validators: {
                regexp: {
                    regexp:/^([0-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-9][0-9][0-9][0-9])$/,
                    message: "0-9999"
                }
            }
        },
        !###!
        var 
        inpOri = $("#the_multi_input"),
        helptext = $("#help_forum_pass"),
        oo = $("#forum_type").val(),
        labelo = $("#labmulti");
        const form  = document.getElementById("fadaddforu");
        const impu = document.getElementById("forum_pass");
        switch (oo){
            case "1":
                fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").enableValidator("forum_pass","stringLength")
            break;
            case "5": case "7":
                fvitem.enableValidator("forum_pass","notEmpty").enableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
            break;
            case "8":
                fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
            break;
            default:
                fvitem.disableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
            break;
        }
        
    
        form.querySelector(\'[name="forum_type"]\').addEventListener("change", function(e) {
            switch (e.target.value) {
                case "1":
                    inpOri.removeClass("d-none").addClass("d-flex");
                    $("#forum_pass").val("").attr({type:"password", maxlength:"60", required:"required"});
                    helptext.html("<span class=\"float-end\" id=\"countcar_forum_pass\"></span>")
                    labelo.html("' . __d('forum', 'Mot de Passe') . '");
                    fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").enableValidator("forum_pass","stringLength")
                break;
                case "5": case "7":
                    inpOri.removeClass("d-none").addClass("d-flex");
                    $("#forum_pass").val("").attr({type:"text", maxlength:"3", required:"required"});
                    helptext.html("2...126<span class=\"float-end\" id=\"countcar_forum_pass\"></span>");
                    labelo.html("' . __d('forum', 'Groupe ID') . '");
                    fvitem.enableValidator("forum_pass","notEmpty").enableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
                break;
                case "8":
                    inpOri.removeClass("d-none").addClass("d-flex");
                    $("#forum_pass").val("").attr({type:"text", maxlength:"60", required:"required"});
                    helptext.html("=> library/sform/forum<span class=\"float-end\" id=\"countcar_forum_pass\"></span>")
                    labelo.html("' . __d('forum', 'Fichier de formulaire') . '");
                    fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
                break;
                default:
                    inpOri.removeClass("d-flex").addClass("d-none");
                    $("#forum_pass").val("");
                    fvitem.disableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength")
                break;
            }
        });
        impu.addEventListener("input", function(e) {fvitem.revalidateField("forum_pass");});';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
