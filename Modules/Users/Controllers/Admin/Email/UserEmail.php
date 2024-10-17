<?php

namespace Modules\Users\Controllers\Admin\Email;

use Modules\Npds\Support\Facades\Js;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;
use Shared\Editeur\Support\Facades\Editeur;


class UserEmail extends AdminController
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
    protected $hlpfile = "email_user";

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
    protected $f_meta_nom = 'email_user';


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
        $this->f_titre = __d('users', 'Diffusion d\'un Message Interne');

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
    public function email_user()
    {
        echo '
            <hr />
            <form id="emailuseradm" action="admin.php" method="post" name="AdmMI">
                <fieldset>
                    <legend>' . __d('users', 'Message') . '</legend>
                    <input type="hidden" name="op" value="send_email_to_user" />
                    <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="expediteur">' . __d('users', 'Expédier en tant') . '</label>
                    <div id="expediteur" class="col-sm-8 my-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="adm" name="expediteur" value="1" checked="checked" />
                            <label class="form-check-label" for="adm">' . __d('users', 'qu\'administrateur') . '</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="mem" name="expediteur" value="0" />
                            <label class="form-check-label" for="mem">' . __d('users', 'que membre') . '</label>
                        </div>
                    </div>
                    </div>
                    <div id="div_username" class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="username">' . __d('users', 'Utilisateur') . '</label>
                    <div class="col-sm-8">
                        <input  class="form-control" type="text" id="username" name="username" value="" />
                    </div>
                    </div>
                    <div id="div_groupe" class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="groupe">' . __d('users', 'Groupe') . '</label>
                    <div class="col-sm-8">
                        <select id="groupe" class="form-select" name="groupe" >
                            <option value="0" selected="selected">' . __d('users', 'Choisir un groupe');
    
        $resultID = sql_query("SELECT groupe_id, groupe_name FROM groupes ORDER BY groupe_id ASC");
    
        while (list($groupe_id, $groupe_name) = sql_fetch_row($resultID)) {
            echo '<option value="' . $groupe_id . '">' . $groupe_id . ' - ' . Language::aff_langue($groupe_name);
        }
    
        echo '
                        </select>
                    </div>
                    </div>
                    <div id="div_all" class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="all">' . __d('users', 'Envoyer à tous les membres') . '</label>
                    <div class="col-sm-8 ">
                        <div class="form-check my-2">
                            <input class="form-check-input" id="all" type="checkbox" name="all" value="1" />
                            <label class="form-check-label" for="all"></label>
                        </div>
                    </div>
                    </div>
                    <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="subject">' . __d('users', 'Sujet') . '</label>
                    <div class="col-sm-8">
                        <input  class="form-control" type="text" maxlength="100" id="subject" name="subject" required="required" />
                        <span class="help-block text-end"><span id="countcar_subject"></span></span>
                    </div>
                    </div>
                    <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="message">' . __d('users', 'Corps de message') . '</label>
                    <div class="col-sm-12">
                        <textarea class="tin form-control" rows="25" id="message" name="message"></textarea>
                    </div>
                    </div>';
    
        echo Editeur::aff_editeur('AdmMI', '');
    
        echo '
                    <div class="mb-3 row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary">' . __d('users', 'Envoyer') . '</button>
                    </div>
                    </div>
                </fieldset>
            </form>
        <script type="text/javascript">
        //<![CDATA[
        $("#all").on("click", function(){
            check = $("#all").is(":checked");
            if(check) {
            $("#div_username").addClass("collapse");
            $("#div_groupe").addClass("collapse");
            } else {
                $("#div_username").removeClass("collapse in");
                $("#div_groupe").removeClass("collapse in");
            }
        }); 
        $("#groupe").on("change", function(){
            sel = $("#groupe").val();
            if(sel!=0) {
            $("#div_username").addClass("collapse");
            $("#div_all").addClass("collapse");
            } else {
                $("#div_username").removeClass("collapse in");
                $("#div_all").removeClass("collapse in");
            }
        });
        $("#username").bind("change paste keyup", function() {
            ibid = $(this).val();
            if(ibid!="") {
            $("#div_groupe").addClass("collapse");
            $("#div_all").addClass("collapse");
            } else {
                $("#div_groupe").removeClass("collapse in");
                $("#div_all").removeClass("collapse in");
            }
        });
        //]]>
        </script>';
    
        $arg1 = '
        var formulid = ["emailuseradm"];
        inpandfieldlen("subject",100);
        ';
    
        echo Js::auto_complete('membre', 'uname', 'users', 'username', '86400');
    
        Css::adminfoot('fv', '', $arg1, '');
    }
    
}
