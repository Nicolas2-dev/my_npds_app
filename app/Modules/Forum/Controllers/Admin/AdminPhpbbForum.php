<?php

namespace App\Modules\Forum\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;


class AdminPhpbbForum extends AdminController
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
        // $f_meta_nom = 'ForumAdmin';
        // $f_titre = __d('forum', 'Gestion des forums');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        
        // $hlpfile = "language/manuels/Config::get('npds.language')/forumcat.html";
        
        // include("auth.php");
    // }

    function ForumAdmin()
    {
        echo '
        <hr />
        <h3 class="mb-3">' . __d('forum', 'Catégories de Forum') . '</h3>
        <table data-toggle="table" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right">' . __d('forum', 'Index') . '&nbsp;</th>
                    <th class="n-t-col-xs-5" data-sortable="true" data-halign="center">' . __d('forum', 'Nom') . '&nbsp;</th>
                    <th class="n-t-col-xs-3" data-halign="center" data-align="right">' . __d('forum', 'Nombre de Forum(s)') . '&nbsp;</th>
                    <th class="n-t-col-xs-2" data-halign="center" data-align="center">' . __d('forum', 'Fonctions') . '&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
    
        $result = sql_query("SELECT cat_id, cat_title FROM catagories ORDER BY cat_id");
    
        while (list($cat_id, $cat_title) = sql_fetch_row($result)) {
    
            $gets = sql_query("SELECT COUNT(*) AS total FROM forums WHERE cat_id='$cat_id'");
            $numbers = sql_fetch_assoc($gets);
    
            echo '
                <tr>
                    <td>' . $cat_id . '</td>
                    <td>' . StripSlashes($cat_title) . '</td>
                    <td>' . $numbers['total'] . ' <a href="admin.php?op=ForumGo&amp;cat_id=' . $cat_id . '"><i class="fa fa-eye fa-lg align-middle" title="' . __d('forum', 'Voir les forums de cette catégorie') . ': ' . StripSlashes($cat_title) . '." data-bs-toggle="tooltip" data-bs-placement="right"></i></a></td>
                    <td><a href="admin.php?op=ForumCatEdit&amp;cat_id=' . $cat_id . '"><i class="fa fa-edit fa-lg" title="' . __d('forum', 'Editer') . '" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=ForumCatDel&amp;cat_id=' . $cat_id . '&amp;ok=0"><i class="fas fa-trash fa-lg text-danger ms-3" title="' . __d('forum', 'Effacer') . '" data-bs-toggle="tooltip" ></i></a></td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>
        <h3 class="my-3">' . __d('forum', 'Ajouter une catégorie') . '</h3>
        <form id="forumaddcat" action="admin.php" method="post">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="catagories">' . __d('forum', 'Nom') . '</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="catagories" id="catagories" rows="3" required="required"></textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto">
                    <input type="hidden" name="op" value="ForumCatAdd" />
                    <button class="btn btn-primary col-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('forum', 'Ajouter une catégorie') . '</button>
                </div>
            </div>
        </form>';
    
        $arg1 = '
        var formulid = ["forumaddcat"];';
    
        adminfoot('fv', '', $arg1, '');
    }
    
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
    
    function ForumGoEdit($forum_id, $ctg)
    {
        $result = sql_query("SELECT forum_id, forum_name, forum_desc, forum_access, forum_moderator, cat_id, forum_type, forum_pass, arbre, attachement, forum_index FROM forums WHERE forum_id='$forum_id'");
        list($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id_1, $forum_type, $forum_pass, $arbre, $attachement, $forum_index) = sql_fetch_row($result);

        settype($sel0, 'string');
        settype($sel1, 'string');
        settype($sel2, 'string');
        settype($sel5, 'string');
        settype($sel6, 'string');
        settype($sel7, 'string');
        settype($sel8, 'string');
        settype($sel9, 'string');
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('forum', 'Editer') . ' : <span class="text-muted">' . $forum_name . '</span></h3>
        <form id="fadeditforu" action="admin.php" method="post">
        <input type="hidden" name="forum_id" value="' . $forum_id . '" />
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_index">' . __d('forum', 'Index') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="forum_index" name="forum_index" value="' . $forum_index . '" required="required" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_name">' . __d('forum', 'Nom du forum') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="forum_name" name="forum_name" value="' . $forum_name . '" required="required" />
                    <span class="help-block">' . __d('forum', '(Redirection sur un forum externe : <.a href...)') . '<span class="float-end" id="countcar_forum_name"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_desc">' . __d('forum', 'Description') . '</label>
                <div class="col-sm-8">
                    <textarea class="form-control" id="forum_desc" name="forum_desc" rows="5">' . $forum_desc . '</textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_mod">' . __d('forum', 'Modérateur(s)') . '</label>';
    
        $moderator = str_replace(' ', ',', get_moderator($forum_mod));
    
        echo '
                <div class="col-sm-8">
                    <input id="forum_mod" class="form-control" type="text" id="forum_mod" name="forum_mod" value="' . $moderator . '," />
                </div>
            </div>';
    
        echo '
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_access">' . __d('forum', 'Niveau d\'accès') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="forum_access" name="forum_access">';
    
        if ($forum_access == 0)
            $sel0 = ' selected="selected"';
    
        if ($forum_access == 1)
            $sel1 = ' selected="selected"';
    
        if ($forum_access == 2)
            $sel2 = ' selected="selected"';
    
        if ($forum_access == 9)
            $sel9 = ' selected="selected"';
    
        echo '
                    <option value="0"' . $sel0 . '>' . __d('forum', 'Publication Anonyme autorisée') . '</option>
                    <option value="1"' . $sel1 . '>' . __d('forum', 'Utilisateur enregistré uniquement') . '</option>
                    <option value="2"' . $sel2 . '>' . __d('forum', 'Modérateurs uniquement') . '</option>
                    <option value="9"' . $sel9 . '>' . __d('forum', 'Fermé') . '</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="cat_id">' . __d('forum', 'Catégories') . ' </label>
                <div class="col-sm-8">
                    <select class="form-select" id="cat_id" name="cat_id">';
    
        $result = sql_query("SELECT cat_id, cat_title FROM catagories");
    
        while (list($cat_id, $cat_title) = sql_fetch_row($result)) {
            if ($cat_id == $cat_id_1)
                echo '<option value="' . $cat_id . '" selected="selected">' . StripSlashes($cat_title) . '</option>';
            else
                echo '<option value="' . $cat_id . '">' . StripSlashes($cat_title) . '</option>';
        }
    
        echo '
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="forum_type">' . __d('forum', 'Type') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="forum_type" name="forum_type">';
    
        if ($forum_type == 0)
            $sel0 = ' selected="selected"';
        else
            $sel0 = '';
    
        if ($forum_type == 1)
            $sel1 = ' selected="selected"';
        else
            $sel1 = '';
    
        if ($forum_type == 5)
            $sel5 = ' selected="selected"';
        else
            $sel5 = '';
    
        if ($forum_type == 6)
            $sel6 = ' selected="selected"';
        else
            $sel6 = '';
    
        if ($forum_type == 7)
            $sel7 = ' selected="selected"';
        else
            $sel7 = '';
    
        if ($forum_type == 8)
            $sel8 = ' selected="selected"';
        else
            $sel8 = '';
    
        if ($forum_type == 9)
            $sel9 = ' selected="selected"';
        else
            $sel9 = '';
    
        $lana = '';
        $dinp = 'd-none';
        $attinp = 'type="text" ';
        $helpinp = '';
    
        switch ($forum_type) {
            case '1':
                $dinp = 'd-flex';
                $lana = 'Mot de Passe';
                $attinp = ' type="password" maxlength="60"';
                $helpinp = '';
                break;
    
            case '5':
                $dinp = 'd-flex';
                $lana = 'Groupe ID';
                $attinp = ' type="text" maxlength="3"';
                $helpinp = '';
                break;
    
            case '7':
                $dinp = 'd-flex';
                $lana = 'Groupe ID';
                $attinp = ' type="text" maxlength="3"';
                $helpinp = '';
                break;
    
            case '8':
                $dinp = 'd-flex';
                $lana = 'Fichier de formulaire';
                $attinp = 'type="text" maxlength="60"';
                $helpinp = '=> library/sform/forum';
                break;
        }
    
        echo '
                    <option value="0"' . $sel0 . '>' . __d('forum', 'Public') . '</option>
                    <option value="1"' . $sel1 . '>' . __d('forum', 'Privé') . '</option>
                    <option value="5"' . $sel5 . '>PHP Script + ' . __d('forum', 'Groupe') . '</option>
                    <option value="6"' . $sel6 . '>PHP Script</option>
                    <option value="7"' . $sel7 . '>' . __d('forum', 'Groupe') . '</option>
                    <option value="8"' . $sel8 . '>' . __d('forum', 'Texte étendu') . '</option>
                    <option value="9"' . $sel9 . '>' . __d('forum', 'Caché') . '</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row ' . $dinp . '" id="the_multi_input">
                <label id="labmulti" class="col-form-label col-sm-4" for="forum_pass">' . __d('forum', $lana) . '</label>
                <div class="col-sm-8">
                    <input class="form-control" ' . $attinp . ' id="forum_pass" name="forum_pass" value="' . $forum_pass . '" />
                    <span id="help_forum_pass" class="help-block">' . $helpinp . '<span class="float-end" id="countcar_forum_pass"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="arbre">' . __d('forum', 'Mode') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="arbre" name="arbre">';
    
        if ($arbre)
            echo '
                   <option value="0">' . __d('forum', 'Standard') . '</option>
                   <option value="1" selected="selected">' . __d('forum', 'Arbre') . '</option>';
        else
            echo '
                   <option value="0" selected="selected">' . __d('forum', 'Standard') . '</option>
                   <option value="1">' . __d('forum', 'Arbre') . '</option>';
    
        echo '
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="attachement">' . __d('forum', 'Attachement') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="attachement" name="attachement">';
    
        if ($attachement)
            echo '
                   <option value="0">' . __d('forum', 'Non') . '</option>
                   <option value="1" selected="selected">' . __d('forum', 'Oui') . '</option>';
        else
            echo '
                   <option value="0" selected="selected">' . __d('forum', 'Non') . '</option>
                   <option value="1">' . __d('forum', 'Oui') . '</option>';
    
        echo '
                    </select>
                </div>
            </div>
            <input type="hidden" name="ctg" value="' . StripSlashes($ctg) . '" />
            <input type="hidden" name="op" value="ForumGoSave" />
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto">
                    <button class="btn btn-primary" type="submit">' . __d('forum', 'Sauver les modifications') . '</button>
                </div>
            </div>
        </form>';
    
        echo auto_complete_multi('modera', 'uname', 'users', 'forum_mod', 'WHERE uid<>1');
    
        $arg1 = '
        var formulid=["fadeditforu"];
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
                    enabled: false,
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
        const form  = document.getElementById("fadeditforu");
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
                    fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").enableValidator("forum_pass","stringLength");
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
                    fvitem.disableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
                break;
            }
        });
        impu.addEventListener("input", function(e) {fvitem.revalidateField("forum_pass");});';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
    function ForumCatEdit($cat_id)
    {
        $result = sql_query("SELECT cat_id, cat_title FROM catagories WHERE cat_id='$cat_id'");
        list($cat_id, $cat_title) = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('forum', 'Editer la catégorie') . '</h3>
        <form id="phpbbforumedcat" action="admin.php" method="post">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="cat_id">ID</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="cat_id" id="cat_id" value="' . $cat_id . '" required="required" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="cat_title">' . __d('forum', 'Catégorie') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="cat_title" name="cat_title" value="' . StripSlashes($cat_title) . '" required="required" />
                </div>
            </div>
            <div class="mb-3 row">
                <input type="hidden" name="old_cat_id" value="' . $cat_id . '" />
                <input type="hidden" name="op" value="ForumCatSave" />
                <div class="col-sm-8 ms-sm-auto">
                    <button class="btn btn-primary col-sm-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;' . __d('forum', 'Sauver les modifications') . '</button>
                </div>
            </div>
        </form>';
    
        $fv_parametres = '
        cat_id: {
            validators: {
                regexp: {
                    regexp:/^(-|[1-9])(\d{0,10})$/,
                    message: "0-9"
                },
                between: {
                    min: -2147483648,
                    max: 2147483647,
                    message: "-2147483648 ... 2147483647"
                }
            }
        },';
    
        $arg1 = '
        var formulid=["phpbbforumedcat"];';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
    function ForumCatSave($old_catid, $cat_id, $cat_title)
    {
        $return = sql_query("UPDATE catagories SET cat_id='$cat_id', cat_title='" . AddSlashes($cat_title) . "' WHERE cat_id='$old_catid'");
    
        if ($return)
            sql_query("UPDATE forums SET cat_id='$cat_id' WHERE cat_id='$old_catid'");
    
        Q_Clean();
    
        global $aid;
        Ecr_Log("security", "UpdateForumCat($old_catid, $cat_id, $cat_title) by AID : $aid", '');
    
        Header("Location: admin.php?op=ForumAdmin");
    }
    
    function ForumGoSave($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass, $arbre, $attachement, $forum_index, $ctg)
    {
        // il faut supprimer le dernier , à cause de l'auto-complete
        $forum_mod = rtrim(chop($forum_mod), ',');
        $moderator = explode(',', $forum_mod);
    
        $forum_mod = '';
        $error_mod = '';
    
        for ($i = 0; $i < count($moderator); $i++) {
            $result = sql_query("SELECT uid FROM users WHERE uname='" . trim($moderator[$i]) . "'");
            list($forum_moderator) = sql_fetch_row($result);
    
            if ($forum_moderator != '') {
                $forum_mod .= $forum_moderator . ' ';
    
                sql_query("UPDATE users_status SET level='2' WHERE uid='$forum_moderator'");
            } else
                $error_mod .= $moderator[$i] . ' ';
        }
    
        if ($error_mod != '') {
            echo "<div><p align=\"center\">" . __d('forum', 'Le Modérateur sélectionné n\'existe pas.') . " : $error_mod<br />";
            echo "[ <a href=\"javascript:history.go(-1)\" >" . __d('forum', 'Retour en arrière') . "</a> ]</p></div>";
        } else {
            $forum_mod = str_replace(' ', ',', chop($forum_mod));
    
            if ($arbre > 1)
                $arbre = 1;
    
            if ($forum_pass) {
                if (($forum_type == 7) and ($forum_access == 0)) {
                    $forum_access = 1;
                }
    
                sql_query("UPDATE forums SET forum_name='$forum_name', forum_desc='$forum_desc', forum_access='$forum_access', forum_moderator='$forum_mod', cat_id='$cat_id', forum_type='$forum_type', forum_pass='$forum_pass', arbre='$arbre', attachement='$attachement', forum_index='$forum_index' WHERE forum_id='$forum_id'");
            } else
                sql_query("UPDATE forums SET forum_name='$forum_name', forum_desc='$forum_desc', forum_access='$forum_access', forum_moderator='$forum_mod', cat_id='$cat_id', forum_type='$forum_type', forum_pass='', arbre='$arbre', attachement='$attachement', forum_index='$forum_index' WHERE forum_id='$forum_id'");
    
            Q_Clean();
            global $aid;
            Ecr_Log("security", "UpdateForum($forum_id, $forum_name) by AID : $aid", '');
    
            Header("Location: admin.php?op=ForumGo&cat_id=$cat_id");
        }
    }
    
    function ForumCatAdd($catagories)
    {
        sql_query("INSERT INTO catagories VALUES (NULL, '$catagories')");
    
        global $aid;
        Ecr_Log('security', "AddForumCat($catagories) by AID : $aid", '');
    
        Header("Location: admin.php?op=ForumAdmin");
    }
    
    function ForumGoAdd($forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass, $arbre, $attachement, $forum_index, $ctg)
    {
        // il faut supprimer le dernier , à cause de l'auto-complete
        $forum_mod = rtrim(chop($forum_mod), ',');
        $moderator = explode(",", $forum_mod);
    
        $forum_mod = '';
        $error_mod = '';
    
        for ($i = 0; $i < count($moderator); $i++) {
            $result = sql_query("SELECT uid FROM users WHERE uname='" . trim($moderator[$i]) . "'");
            list($forum_moderator) = sql_fetch_row($result);
    
            if ($forum_moderator != '') {
                $forum_mod .= $forum_moderator . ' ';
    
                sql_query("UPDATE users_status SET level='2' WHERE uid='$forum_moderator'");
            } else
                $error_mod .= $moderator[$i] . ' ';
        }
    
        if ($error_mod != '') {
            echo '
            <div class="alert alert-danger">
                <p>' . __d('forum', 'Le Modérateur sélectionné n\'existe pas.') . ' : ' . $error_mod . '</p>
                <a href="javascript:history.go(-1)" class="btn btn-secondary">' . __d('forum', 'Retour en arrière') . '</a>
            </div>';
        } else {
            if ($arbre > 1)
                $arbre = 1;
    
            $forum_mod = str_replace(' ', ',', chop($forum_mod));
            sql_query("INSERT INTO forums VALUES (NULL, '$forum_name', '$forum_desc', '$forum_access', '$forum_mod', '$cat_id', '$forum_type', '$forum_pass', '$arbre', '$attachement', '$forum_index')");
    
            Q_Clean();
    
            global $aid;
            Ecr_Log("security", "AddForum($forum_name) by AID : $aid", "");
    
            Header("Location: admin.php?op=ForumGo&cat_id=$cat_id");
        }
    }
    
    function ForumCatDel($cat_id, $ok = 0)
    {
        if ($ok == 1) {
            $result = sql_query("SELECT forum_id FROM forums WHERE cat_id='$cat_id'");
    
            while (list($forum_id) = sql_fetch_row($result)) {
                sql_query("DELETE FROM forumtopics WHERE forum_id='$forum_id'");
                sql_query("DELETE FROM forum_read WHERE forum_id='$forum_id'");
    
                control_efface_post("forum_App", "", "", $forum_id);
    
                sql_query("DELETE FROM posts WHERE forum_id='$forum_id'");
            }
    
            sql_query("DELETE FROM forums WHERE cat_id='$cat_id'");
            sql_query("DELETE FROM catagories WHERE cat_id='$cat_id'");
    
            Q_Clean();
    
            global $aid;
            Ecr_Log("security", "DeleteForumCat($cat_id) by AID : $aid", "");
    
            Header("Location: admin.php?op=ForumAdmin");
        } else {
            echo '
            <hr />
            <div class="alert alert-danger">
                <p>' . __d('forum', 'ATTENTION :  êtes-vous sûr de vouloir supprimer cette Catégorie, ses Forums et tous ses Sujets ?') . '</p>
                <a href="admin.php?op=ForumCatDel&amp;cat_id=' . $cat_id . '&amp;ok=1" class="btn btn-danger me-2">' . __d('forum', 'Oui') . '</a>
                <a href="admin.php?op=ForumAdmin" class="btn btn-secondary">' . __d('forum', 'Non') . '</a>
            </div>';
    
            adminfoot('', '', '', '');
        }
    }
    
    function ForumGoDel($forum_id, $ok = 0)
    {
        if ($ok == 1) {
            sql_query("DELETE FROM forumtopics WHERE forum_id='$forum_id'");
            sql_query("DELETE FROM forum_read WHERE forum_id='$forum_id'");
    
            control_efface_post('forum_App', '', '', $forum_id);
    
            sql_query("DELETE FROM forums WHERE forum_id='$forum_id'");
            sql_query("DELETE FROM posts WHERE forum_id='$forum_id'");
    
            Q_Clean();
    
            global $aid;
            Ecr_Log('security', "DeleteForum($forum_id) by AID : $aid", '');
    
            Header("Location: admin.php?op=ForumAdmin");
        } else {
            echo '
            <hr />
            <div class="alert alert-danger">
                <p>' . __d('forum', 'ATTENTION :  êtes-vous certain de vouloir effacer ce Forum et tous ses Sujets ?') . '</p>
                <a class="btn btn-danger me-2" href="admin.php?op=ForumGoDel&amp;forum_id=' . $forum_id . '&amp;ok=1">' . __d('forum', 'Oui') . '</a>
                <a class="btn btn-secondary" href="admin.php?op=ForumAdmin" >' . __d('forum', 'Non') . '</a>
            </div>';
    
            adminfoot('', '', '', '');
        }
    }

}
