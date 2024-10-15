<?php

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Npds\Core\AdminController;


/**
 * Undocumented class
 */
class Newsletter extends AdminController
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
    protected $hlpfile = 'lnl';

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
    protected $f_meta_nom = 'lnl';


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
        $this->f_titre = __d('newsletter', 'Petite Lettre D\'information');

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



    // controller header

    /**
     * case "Shw_Header":  => Detail_Header_Footer($Headerid, "HED");
     * case "Shw_Footer":  => Detail_Header_Footer($Footerid, "FOT");
     * 
     * Undocumented function
     *
     * @param [type] $ibid
     * @param [type] $type
     * @return void
     */
    public function Detail_Header_Footer($ibid, $type)
    {
        // $type = HED or FOT
        $result = sql_query("SELECT text, html FROM lnl_head_foot WHERE type='$type' AND ref='$ibid'");
        $tmp = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3 class="mb-2">';
    
        if ($type == "HED")
            echo __d('newsletter', 'Message d\'entête');
        else
            echo __d('newsletter', 'Message de pied de page');
    
        echo ' - ' . __d('newsletter', 'Prévisualiser');
    
        if ($tmp[1] == 1)
            echo '<code> HTML</code></h3>
            <div class="card card-body">' . $tmp[0] . '</div>';
        else
            echo '<code>' . __d('newsletter', 'TEXTE') . '</code></h3>
            <div class="card card-body">' . nl2br($tmp[0]) . '</div>';
    
        echo '
        <hr />
        <form action="admin.php" method="post" name="adminForm">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="xtext">' . __d('newsletter', 'Texte') . '</label>
                <div class="col-sm-12">
                    <textarea class="tin form-control" cols="70" rows="20" name="xtext" >' . htmlspecialchars($tmp[0], ENT_COMPAT | ENT_HTML401, cur_charset) . '</textarea>
                </div>
            </div>';
    
        if ($tmp[1] == 1) {
            global $tiny_mce_relurl;
            $tiny_mce_relurl = 'false';
            echo aff_editeur('xtext', '');
        }
    
        if ($type == 'HED')
            echo '
            <input type="hidden" name="op" value="lnl_Add_Header_Mod" />';
        else
            echo '
            <input type="hidden" name="op" value="lnl_Add_Footer_Mod" />';
    
        echo '
            <input type="hidden" name="ref" value="' . $ibid . '" />
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <button class="btn btn-primary me-1" type="submit">' . __d('newsletter', 'Valider') . '</button>
                    <a class="btn btn-secondary" href="admin.php?op=lnl" >' . __d('newsletter', 'Retour en arrière') . '</a>
                </div>
            </div>
        </form>';
    
        adminfoot('', '', '', '');
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function Sup_HeaderOK()
    {
        sql_query("DELETE FROM lnl_head_foot WHERE ref='$Headerid'");

        header("location: admin.php?op=lnl");
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function Add_Header_Mod()
    {
        sql_query("UPDATE lnl_head_foot SET text='$xtext' WHERE ref='$ref'");

        header("location: admin.php?op=lnl_Shw_Header&Headerid=$ref");
    } 


    // controller footer

    /**
     * case "Add_Footer": => Add_Header_Footer("FOT");
     * case "Add_Header": => Add_Header_Footer("HED");
     * 
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function Add_Header_Footer($ibid)
    {
        $t = '';
        $v = '';
    
        if ($ibid == 'HED') {
            $ti = "message d'entête";
            $va = 'lnl_Add_Header_Submit';
        } else {
            $ti = "Message de pied de page";
            $va = 'lnl_Add_Footer_Submit';
        }
    
        echo '
            <hr />
            <h3 class="mb-2">' . ucfirst(__d('newsletter', $ti)) . '</h3>
            <form id="lnlheadfooter" action="admin.php" method="post" name="adminForm">
            <fieldset>
                <div class="mb-3">
                    <label class="col-form-label" for="html">' . __d('newsletter', 'Format de données') . '</label>
                    <div>
                        <input class="form-control" id="html" type="number" min="0" max="1" value="1" name="html" required="required" />
                        <span class="help-block"> <code>html</code> ==&#x3E; [1] / <code>text</code> ==&#x3E; [0]</span>
                    </div>
                    </div>
                <div class="mb-3">
                    <label class="col-form-label" for="xtext">' . __d('newsletter', 'Texte') . '</label>
                    <div>
                    <textarea class="form-control" id="xtext" rows="20" name="xtext" ></textarea>
                    </div>
                </div>
                <div class="mb-3">';
    
        global $tiny_mce_relurl;
    
        $tiny_mce_relurl = 'false';
        echo aff_editeur('xtext', 'false');
    
        echo '
                    <input type="hidden" name="op" value="' . $va . '" />
                    <button class="btn btn-primary col-sm-12 col-md-6" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('newsletter', 'Ajouter') . ' ' . __d('newsletter', $ti) . '</button>
                </div>
            </fieldset>
        </form>';
    
        $fv_parametres = '
            html: {
            validators: {
                regexp: {
                    regexp:/[0-1]$/,
                    message: "0 | 1"
                }
            }
        },
        ';
    
        $arg1 = '
        var formulid = ["lnlheadfooter"];
        ';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
    /**
     * case "Add_Footer_Submit": => Add_Header_Footer_Submit("FOT", $xtext, $html);
     * case "Add_Header_Submit": => Add_Header_Footer_Submit("HED", $xtext, $html);
     * 
     * Undocumented function
     *
     * @param [type] $ibid
     * @param [type] $xtext
     * @param [type] $xhtml
     * @return void
     */
    public function Add_Header_Footer_Submit($ibid, $xtext, $xhtml)
    {
        if ($ibid == "HED")
            sql_query("INSERT INTO lnl_head_foot VALUES ('0', 'HED','$xhtml', '$xtext', 'OK')");
        else
            sql_query("INSERT INTO lnl_head_foot VALUES ('0', 'FOT', '$xhtml', '$xtext', 'OK')");

        header("location: admin.php?op=lnl");
    }

    /**
     * case "Add_Footer_Mod":
     * 
     * Undocumented function
     *
     * @return void
     */
    public function Add_Footer_Mod()
    {
        sql_query("UPDATE lnl_head_foot SET text='$xtext' WHERE ref='$ref'");

        header("location: admin.php?op=lnl_Shw_Footer&Footerid=$ref");
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function Sup_FooterOK()
    {
        sql_query("DELETE FROM lnl_head_foot WHERE ref='$Footerid'");
        
        header("location: admin.php?op=lnl");
    }



    // controller body

    /**
     * case "Shw_Body":  => Detail_Body($Bodyid);
     * 
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function Detail_Body($ibid)
    {
        echo '
        <hr />
        <h3 class="mb-2">' . __d('newsletter', 'Corps de message') . ' - ';
    
        $result = sql_query("SELECT text, html FROM lnl_body WHERE ref='$ibid'");
        $tmp = sql_fetch_row($result);
    
        if ($tmp[1] == 1)
            echo __d('newsletter', 'Prévisualiser') . ' <code>HTML</code></h3>
            <div class="card card-body">' . $tmp[0] . '</div>';
        else
            echo __d('newsletter', 'Prévisualiser') . ' <code>' . __d('newsletter', 'TEXTE') . '</code></h3>
            <div class="card card-body">' . nl2br($tmp[0]) . '</div>';
    
        echo '
        <form action="admin.php" method="post" name="adminForm">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="xtext">' . __d('newsletter', 'Corps de message') . '</label>
                <div class="col-sm-12">
                    <textarea class="tin form-control" rows="30" name="xtext" >' . htmlspecialchars($tmp[0], ENT_COMPAT | ENT_HTML401, cur_charset) . '</textarea>
                </div>
            </div>';
    
        if ($tmp[1] == 1) {
            global $tiny_mce_relurl;
            $tiny_mce_relurl = "false";
    
            echo aff_editeur("xtext", "false");
        }
    
        echo '
            <input type="hidden" name="op" value="lnl_Add_Body_Mod" />
            <input type="hidden" name="ref" value="' . $ibid . '" />
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <button class="btn btn-primary" type="submit">' . __d('newsletter', 'Valider') . '</button>&nbsp;
                    <button href="javascript:history.go(-1)" class="btn btn-secondary">' . __d('newsletter', 'Retour en arrière') . '</button>
                </div>
            </div>
        </form>';
    
        adminfoot('', '', '', '');
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function Add_Body()
    {
        echo '
        <hr />
        <h3 class="mb-2">' . __d('newsletter', 'Corps de message') . '</h3>
        <form id="lnlbody" action="admin.php" method="post" name="adminForm">
            <fieldset>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="html">' . __d('newsletter', 'Format de données') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" id="html" type="number" min="0" max="1" step="1" value="1" name="html" required="required" />
                    <span class="help-block"> <code>html</code> ==&#x3E; [1] / <code>text</code> ==&#x3E; [0]</span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="xtext">' . __d('newsletter', 'Texte') . '</label>
                    <div class="col-sm-12">
                    <textarea class="tin form-control" id="xtext" rows="30" name="xtext" ></textarea>
                    </div>
                </div>';
    
        global $tiny_mce_relurl;
    
        $tiny_mce_relurl = "false";
        echo aff_editeur("xtext", "false");
    
        echo '
                <div class="mb-3 row">
                    <input type="hidden" name="op" value="lnl_Add_Body_Submit" />
                    <button class="btn btn-primary col-sm-12 col-md-6" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('newsletter', 'Ajouter') . ' ' . __d('newsletter', 'corps de message') . '</button>
                    <a href="admin.php?op=lnl" class="btn btn-secondary col-sm-12 col-md-6">' . __d('newsletter', 'Retour en arrière') . '</a>
                </div>
            </fieldset>
        </form>';
    
        $fv_parametres = '
            html: {
            validators: {
                regexp: {
                    regexp:/[0-1]$/,
                    message: "0 | 1"
                }
            }
        },
        ';
    
        $arg1 = '
        var formulid = ["lnlbody"];
        ';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $Ytext
     * @param [type] $Yhtml
     * @return void
     */
    public function Add_Body_Submit($Ytext, $Yhtml)
    {
        sql_query("INSERT INTO lnl_body VALUES ('0', '$Yhtml', '$Ytext', 'OK')");

        header("location: admin.php?op=lnl");
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function Add_Body_Mod()
    {
        sql_query("UPDATE lnl_body SET text='$xtext' WHERE ref='$ref'");

        header("location: admin.php?op=lnl_Shw_Body&Bodyid=$ref");
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function Sup_BodyOK()
    {
        sql_query("DELETE FROM lnl_body WHERE ref='$Bodyid'");

        header("location: admin.php?op=lnl");
    } 
    
    

    // controller 

    /**
     * Undocumented function
     *
     * @return void
     */
    public function main()
    {
        echo '
        <hr />
        <h3 class="mb-2">' . __d('newsletter', 'Petite Lettre D\'information') . '</h3>
        <ul class="nav flex-md-row flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="admin.php?op=lnl_List">' . __d('newsletter', 'Liste des LNL envoyées') . '</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="admin.php?op=lnl_User_List">' . __d('newsletter', 'Afficher la liste des prospects') . '</a>
            </li>
        </ul>
        <h4 class="my-3"><a href="admin.php?op=lnl_Add_Header" ><i class="fa fa-plus-square me-2"></i></a>' . __d('newsletter', 'Message d\'entête') . '</h4>';
        
        ShowHeader();
    
        echo '
        <h4 class="my-3"><a href="admin.php?op=lnl_Add_Body" ><i class="fa fa-plus-square me-2"></i></a>' . __d('newsletter', 'Corps de message') . '</h4>';
        
        ShowBody();
    
        echo '
        <h4 class="my-3"><a href="admin.php?op=lnl_Add_Footer"><i class="fa fa-plus-square me-2"></i></a>' . __d('newsletter', 'Message de pied de page') . '</h4>';
        
        ShowFooter();
    
        echo '
        <hr />
        <h4>' . __d('newsletter', 'Assembler une lettre et la tester') . '</h4>
        <form id="ltesto" action="admin.php" method="post">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xheader" id="testXheader"min="0" />
                    <label for="testXheader">' . __d('newsletter', 'Entête') . '</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xbody" id="testXbody" maxlength="11" />
                    <label for="testXbody">' . __d('newsletter', 'Corps') . '</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xfooter" id="testXfooter" min="0" />
                    <label for="testXfooter">' . __d('newsletter', 'Pied') . '</label>
                    </div>
                </div>
                <div class="mb-3 col-sm-12">
                    <input type="hidden" name="op" value="lnl_Test" />
                    <button class="btn btn-primary" type="submit">' . __d('newsletter', 'Valider') . '</button>
                </div>
            </div>
        </form>
        <hr />
        <h4>' . __d('newsletter', 'Envoyer La Lettre') . '</h4>
        <form id="lsendo" action="admin.php" method="post">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xheader" id="Xheader" />
                    <label for="Xheader">' . __d('newsletter', 'Entête') . '</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xbody" id="Xbody" min="0" />
                    <label for="Xbody">' . __d('newsletter', 'Corps') . '</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xfooter" id="Xfooter" />
                    <label for="Xfooter">' . __d('newsletter', 'Pied') . '</label>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="text" maxlength="255" id="Xsubject" name="Xsubject" />
                    <label for="Xsubject">' . __d('newsletter', 'Sujet') . '</label>
                    <span class="help-block text-end"><span id="countcar_Xsubject"></span></span>
                    </div>
                </div>
                <hr />
                <div class="mb-3 col-sm-12">
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" value="All" checked="checked" id="tous" name="Xtype" />
                    <label class="form-check-label" for="tous">' . __d('newsletter', 'Tous les Utilisateurs') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" value="Mbr" id="mem" name="Xtype" />
                    <label class="form-check-label" for="mem">' . __d('newsletter', 'Seulement aux membres') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" value="Out" id="prosp" name="Xtype" />
                    <label class="form-check-label" for="prosp">' . __d('newsletter', 'Seulement aux prospects') . '</label>
                    </div>
                </div>';
    
        $mX = liste_group();
        $tmp_groupe = '';
    
        foreach ($mX as $groupe_id => $groupe_name) {
            if ($groupe_id == '0') 
                $groupe_id = '';
    
            $tmp_groupe .= '<option value="' . $groupe_id . '">' . $groupe_name . '</option>';
        }
    
        echo '
                <div class="mb-3 col-sm-12">
                    <select class="form-select" name="Xgroupe">' . $tmp_groupe . '</select>
                </div>
                <input type="hidden" name="op" value="lnl_Send" />
                <div class="mb-3 col-sm-12">
                    <button class="btn btn-primary" type="submit">' . __d('newsletter', 'Valider') . '</button>
                </div>
            </div>
            </form>';
    
        $fv_parametres = '
                Xbody: {
                validators: {
                    regexp: {
                    regexp:/^\d{1,11}$/,
                    message: "0 | 1"
                    }
                }
            },
            ';
    
        $arg1 = '
            var formulid = ["ltesto","lsendo"];
            inpandfieldlen("Xsubject",255);
            ';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }

    /**
     * case "Sup_Header": => Del_Question("lnl_Sup_HeaderOK", "Headerid=$Headerid");
     * case "Sup_Body":   => Del_Question("lnl_Sup_BodyOK", "Bodyid=$Bodyid");
     * case "Sup_Footer": => Del_Question("lnl_Sup_FooterOK", "Footerid=$Footerid");
     * 
     * Undocumented function
     *
     * @param [type] $retour
     * @param [type] $param
     * @return void
     */
    public function Del_Question($retour, $param)
    {
        echo '
        <hr />
        <div class="alert alert-danger">' . __d('newsletter', 'Etes-vous sûr de vouloir effacer cet Article ?') . '</div>
        <a href="admin.php?op=' . $retour . '&amp;' . $param . '" class="btn btn-danger btn-sm">' . __d('newsletter', 'Oui') . '</a>
        <a href="javascript:history.go(-1)" class="btn btn-secondary btn-sm">' . __d('newsletter', 'Non') . '</a>';
    
        adminfoot('', '', '', '');
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $Yheader
     * @param [type] $Ybody
     * @param [type] $Yfooter
     * @return void
     */
    public function Test($Yheader, $Ybody, $Yfooter)
    {
        $result = sql_query("SELECT text, html FROM lnl_head_foot WHERE type='HED' AND ref='$Yheader'");
        $Xheader = sql_fetch_row($result);
    
        $result = sql_query("SELECT text, html FROM lnl_body WHERE html='$Xheader[1]' AND ref='$Ybody'");
        $Xbody = sql_fetch_row($result);
    
        $result = sql_query("SELECT text, html FROM lnl_head_foot WHERE type='FOT' AND html='$Xheader[1]' AND ref='$Yfooter'");
        $Xfooter = sql_fetch_row($result);
    
        // For Meta-Lang
        //   global $cookie; // a quoi ca sert
        //   $uid=$cookie[0]; // a quoi ca sert
    
        if ($Xheader[1] == 1) {
            echo '
            <hr />
            <h3 class="mb-3">' . __d('newsletter', 'Prévisualiser') . ' HTML</h3>';
    
            $Xmime = 'html-nobr';
            $message = meta_lang($Xheader[0] . $Xbody[0] . $Xfooter[0]);
        } else {
            echo '
            <hr />
            <h3 class="mb-3">' . __d('newsletter', 'Prévisualiser') . ' ' . __d('newsletter', 'TEXTE') . '</h3>';
    
            $Xmime = 'text';
            $message = meta_lang(nl2br($Xheader[0]) . nl2br($Xbody[0]) . nl2br($Xfooter[0]));
        }
    
        echo '
        <div class="card card-body">
        ' . $message . '
        </div>
        <a class="btn btn-secondary my-3" href="javascript:history.go(-1)" >' . __d('newsletter', 'Retour en arrière') . '</a>';
    
        send_email(Config::get('npds.adminmail'), 'LNL TEST', $message, Config::get('npds.adminmail'), true, $Xmime, '');
    
        adminfoot('', '', '', '');
    }

    /**
     * case "List":
     * 
     * Undocumented function
     *
     * @return void
     */
    public function lnl_list()
    {
        $result = sql_query("SELECT ref, header , body, footer, number_send, type_send, date, status FROM lnl_send ORDER BY date");
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('newsletter', 'Liste des LNL envoyées') . '</h3>
        <table data-toggle="table" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-1" data-halign="center" data-align="right">ID</th>
                    <th class="n-t-col-xs-1" data-halign="center" data-align="right">' . __d('newsletter', 'Entête') . '</th>
                    <th class="n-t-col-xs-1" data-halign="center" data-align="right">' . __d('newsletter', 'Corps') . '</th>
                    <th class="n-t-col-xs-1" data-halign="center" data-align="right">' . __d('newsletter', 'Pied') . '</th>
                    <th data-halign="center" data-align="right">' . __d('newsletter', 'Nbre d\'envois effectués') . '</th>
                    <th data-halign="center" data-align="center">' . __d('newsletter', 'Type') . '</th>
                    <th data-halign="center" data-align="right">' . __d('newsletter', 'Date') . '</th>
                    <th data-halign="center" data-align="center">' . __d('newsletter', 'Etat') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        while (list($ref, $header, $body, $footer, $number_send, $type_send, $date, $status) = sql_fetch_row($result)) {
            echo '
                <tr>
                    <td>' . $ref . '</td>
                    <td>' . $header . '</td>
                    <td>' . $body . '</td>
                    <td>' . $footer . '</td>
                    <td>' . $number_send . '</td>
                    <td>' . $type_send . '</td>
                    <td>' . $date . '</td>';
    
            if ($status == "NOK") {
                echo '
                <td class="text-danger">' . $status . '</td>';
            } else {
                echo '
                <td>' . $status . '</td>';
            }
    
            echo '
                </tr>';
        }
    
        echo '
            </tbody>
        </table>';
    
        adminfoot('', '', '', '');
    }
    
    /**
     * case "User_List":
     * 
     * Undocumented function
     *
     * @return void
     */
    public function lnl_user_list()
    {
        $result = sql_query("SELECT email, date, status FROM lnl_outside_users ORDER BY date");
    
        echo '
        <hr />
        <h3 class="mb-2">' . __d('newsletter', 'Liste des prospects') . '</h3>
        <table id="tad_prospect" data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-5" data-halign="center" data-sortable="true">' . __d('newsletter', 'E-mail') . '</th>
                    <th class="n-t-col-xs-3" data-halign="center" data-align="right" data-sortable="true">' . __d('newsletter', 'Date') . '</th>
                    <th class="n-t-col-xs-2" data-halign="center" data-align="center" data-sortable="true">' . __d('newsletter', 'Etat') . '</th>
                    <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">' . __d('newsletter', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        while (list($email, $date, $status) = sql_fetch_row($result)) {
            echo '
                <tr>
                    <td>' . $email . '</td>
                    <td>' . $date . '</td>';
    
            if ($status == "NOK")
                echo '
                <td class="text-danger">' . $status . '</td>';
            else
                echo '
                <td class="text-success">' . $status . '</td>';
    
            echo '
                    <td><a href="admin.php?op=lnl_Sup_User&amp;lnl_user_email=' . $email . '" class="text-danger"><i class="fas fa-trash fa-lg text-danger" data-bs-toggle="tooltip" title="' . __d('newsletter', 'Effacer') . '"></i></a></td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>
        <br /><a href="javascript:history.go(-1)" class="btn btn-secondary">' . __d('newsletter', 'Retour en arrière') . '</a>';
    
        adminfoot('', '', '', '');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function Sup_User()
    {
        sql_query("DELETE FROM lnl_outside_users WHERE email='$lnl_user_email'");

        header("location: admin.php?op=lnl_User_List");
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function send()
    {
        $deb = 0;
        $limit = 50; // nombre de messages envoyé par boucle.
        if (!isset($debut)) 
            $debut = 0;

        if (!isset($number_send)) 
            $number_send = 0;

        $result = sql_query("SELECT text, html FROM lnl_head_foot WHERE type='HED' AND ref='$Xheader'");
        $Yheader = sql_fetch_row($result);

        $result = sql_query("SELECT text, html FROM lnl_body WHERE html='$Yheader[1]' AND ref='$Xbody'");
        $Ybody = sql_fetch_row($result);

        $result = sql_query("SELECT text, html FROM lnl_head_foot WHERE type='FOT' AND html='$Yheader[1]' AND ref='$Xfooter'");
        $Yfooter = sql_fetch_row($result);

        $subject = stripslashes($Xsubject);
        $message = $Yheader[0] . $Ybody[0] . $Yfooter[0];


        $Xmime = $Yheader[1] == 1 ? 'html-nobr' : 'text';

        if ($Xtype == "All") {
            $Xtype = "Out";
            $OXtype = "All";
        }

        // Outside Users
        if ($Xtype == "Out") {
            $mysql_result = sql_query("SELECT email FROM lnl_outside_users WHERE status='OK'");
            $nrows = sql_num_rows($mysql_result);

            $result = sql_query("SELECT email FROM lnl_outside_users WHERE status='OK' ORDER BY email limit $debut,$limit");

            while (list($email) = sql_fetch_row($result)) {
                if (($email != "Anonyme") or ($email != "Anonymous")) {
                    if ($email != '') {
                        if (($message != '') and ($subject != '')) {

                            if ($Xmime == "html-nobr") {
                                $Xmessage = $message . "<br /><br /><hr noshade>";
                                $Xmessage .= __d('newsletter', 'Pour supprimer votre abonnement à notre Lettre, suivez ce lien') . " : <a href=\"Config::get('npds.nuke_url')/lnl.php?op=unsubscribe&email=$email\">" . __d('newsletter', 'Modifier') . "</a>";
                            } else {
                                $Xmessage = $message . "\n\n------------------------------------------------------------------\n";
                                $Xmessage .= __d('newsletter', 'Pour supprimer votre abonnement à notre Lettre, suivez ce lien') . " : Config::get('npds.nuke_url')/lnl.php?op=unsubscribe&email=$email";
                            }

                            send_email($email, $subject, meta_lang($Xmessage), "", true, $Xmime, '');

                            $number_send++;
                        }
                    }
                }
            }
        }

        // App Users
        if ($Xtype == 'Mbr') {
            if ($Xgroupe != '') {
                $result = '';

                $mysql_result = sql_query("SELECT u.uid FROM users u, users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND (s.groupe LIKE '%$Xgroupe,%' OR s.groupe LIKE '%,$Xgroupe' OR s.groupe='$Xgroupe') AND u.user_lnl='1'");
                $nrows = sql_num_rows($mysql_result);
                
                $resultGP = sql_query("SELECT u.email, u.uid, s.groupe FROM users u, users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND (s.groupe LIKE '%$Xgroupe,%' OR s.groupe LIKE '%,$Xgroupe' OR s.groupe='$Xgroupe') AND u.user_lnl='1' ORDER BY u.email LIMIT $debut,$limit");
                
                while (list($email, $uid, $groupe) = sql_fetch_row($resultGP)) {
                    $tab_groupe = explode(',', $groupe);
                    
                    if ($tab_groupe)
                        foreach ($tab_groupe as $groupevalue) {
                            if ($groupevalue == $Xgroupe)
                                $result[] = $email;
                        }
                }

                $fonction = "each"; ///???gloups

                if (is_array($result)) 
                    $boucle = true;
                else 
                    $boucle = false;
            } else {
                $mysql_result = sql_query("SELECT u.uid FROM users u, users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND u.user_lnl='1'");
                $nrows = sql_num_rows($mysql_result);
                $result = sql_query("SELECT u.uid, u.email FROM users u, users_status s WHERE s.open='1' AND u.uid=s.uid AND u.user_lnl='1' ORDER BY email LIMIT $debut,$limit");
                $fonction = "sql_fetch_row";
                $boucle = true;
            }

            if ($boucle) {
                while (list($bidon, $email) = $fonction($result)) { ///???gloups réinterprété comme each .. ???
                    if (($email != "Anonyme") or ($email != "Anonymous")) {
                        if ($email != '') {
                            if (($message != '') and ($subject != '')) {
                                send_email($email, $subject, meta_lang($message), "", true, $Xmime, '');
                                $number_send++;
                            }
                        }
                    }
                }
            }
        }

        $deb = $debut + $limit;
        $chartmp = '';

        settype($OXtype, 'string');

        if ($deb >= $nrows) {
            if ((($OXtype == "All") and ($Xtype == "Mbr")) or ($OXtype == "")) {
                if (($message != '') and ($subject != '')) {
                    $timeX = date("Y-m-d H:m:s", time());

                    if ($OXtype == "All") {
                        $Xtype = "All";
                    }

                    if (($Xtype == "Mbr") and ($Xgroupe != "")) {
                        $Xtype = $Xgroupe;
                    }

                    sql_query("INSERT INTO lnl_send VALUES ('0', '$Xheader', '$Xbody', '$Xfooter', '$number_send', '$Xtype', '$timeX', 'OK')");
                }

                header("location: admin.php?op=lnl");
            } else {
                if ($OXtype == "All") {
                    $chartmp = "$Xtype : $nrows / $nrows";
                    $deb = 0;
                    $Xtype = "Mbr";
                    $mysql_result = sql_query("SELECT u.uid FROM users u, users_status s WHERE s.open='1' and u.uid=s.uid and u.email!='' and u.user_lnl='1'");
                    $nrows = sql_num_rows($mysql_result);
                }
            }
        }

        if ($chartmp == '') 
            $chartmp = "$Xtype : $deb / $nrows";

        include("storage/meta/meta.php");

        echo "<script type=\"text/javascript\">
                //<![CDATA[
                function redirect() {
                    window.location=\"admin.php?op=lnl_Send&debut=" . $deb . "&OXtype=$OXtype&Xtype=$Xtype&Xgroupe=$Xgroupe&Xheader=" . $Xheader . "&Xbody=" . $Xbody . "&Xfooter=" . $Xfooter . "&number_send=" . $number_send . "&Xsubject=" . $Xsubject . "\";
                }
                setTimeout(\"redirect()\",10000);
                //]]>
                </script>";

        echo '
            <link href="' . Config::get('npds.nuke_url') . '/themes/App-boost_sk/style/style.css" title="default" rel="stylesheet" type="text/css" media="all">
            <link id="bsth" rel="stylesheet" href="' . Config::get('npds.nuke_url') . '/assets/skins/default/bootstrap.min.css">
            </head>
                <body>
                <div class="d-flex justify-content-center mt-4">
                    <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <div class="text-center mt-4">
                    ' . __d('newsletter', 'Transmission LNL en cours') . ' => ' . $chartmp . '<br /><br />App - Portal System
                    </div>
                </div>
                </body>
            </html>';
    }

}
