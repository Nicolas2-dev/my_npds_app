<?php

namespace Modules\Forum\Controllers\Admin\Forum;

use Modules\Npds\Core\AdminController;


class ForumGoEdit extends AdminController
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
     * @param [type] $forum_id
     * @param [type] $ctg
     * @return void
     */
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

}
