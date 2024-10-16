<?php

namespace Modules\Upload\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;


class Upload extends AdminController
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
    protected $hlpfile = 'upload';

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
    protected $f_meta_nom = 'upConfigure';


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
        $this->f_titre = __d('upload', 'Configuration Upload');

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
     * @param [type] $f_meta_nom
     * @param [type] $f_titre
     * @return void
     */
    function upConfigure($f_meta_nom, $f_titre)
    {
        echo '
        <hr />
        <div class="alert alert-danger lead"><strong>!!! EN TRAVAUX NE PAS UTILISER !!!</strong></div>
        <form id="settingsupload" action="admin.php" method="post">
        <fieldset>
            <legend>' . __d('upload', 'Parametres') . '</legend>
            <div id="info_gene" class="adminsidefield card card-body mb-3">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xmax_size">' . __d('upload', 'Taille maxi des fichiers') . '</label>
                    <div class="col-sm-8">
                    <div class="input-group mb-2">
                        <div id="humread_size" class="input-group-text">' . $max_size . '</div>
                        <input onkeyup="convertoct(\'xmax_size\',\'humread_size\')" class="form-control " id="xmax_size" type="number" name="xmax_size" value="' . $max_size . '" min="1" maxlength="8" required="required" />
                    </div>
                    <span class="help-block">Taille maxi des fichiers en octets<span class="float-end ms-1" id="countcar_xmax_size"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xdocumentroot">' . __d('upload', 'Chemin physique') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control " id="xdocumentroot" type="text" name="xdocumentroot" value="' . $DOCUMENTROOT . '" />
                    <span class="help-block">Si votre variable $DOCUMENT_ROOT n\'est pas bonne (notamment en cas de redirection) vous pouvez en spécifier une ici (c\'est le chemin physique d\'accès à la racine de votre site en partant de / ou C:\) par exemple /data/web/mon_site OU c:\web\mon_site SINON LAISSER cette variable VIDE<span class="float-end ms-1" id="countcar_xdocumentroot"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xautorise_upload_p">Activer upload</label>
                    <div class="col-sm-8 my-2">';

        $cky = '';
        $ckn = '';

        if ($autorise_upload_p == "true") {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }

        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xautorise_upload_p_y" name="xautorise_upload_p" value="true" ' . $cky . ' />
                        <label class="form-check-label" for="xautorise_upload_p_y">' . __d('upload', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xautorise_upload_p_n" name="xautorise_upload_p" value="false" ' . $ckn . ' />
                        <label class="form-check-label" for="xautorise_upload_p_n">' . __d('upload', 'Non') . '</label>
                    </div>
                    <span class="help-block">Autorise l\'upload DANS le répertoire personnel du membre (true ou false)</span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xracine">' . __d('upload', 'Racine du site') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control " id="xracine" type="text" name="xracine" value="' . $racine . '" />
                    <span class="help-block">Sous répertoire : n\'utiliser QUE SI votre App n\'est pas directement dans la racine de votre site par exemple si : www.mon_site/App/.... ALORS /App (avec le / DEVANT) sinon RIEN;<span class="float-end ms-1" id="countcar_xracine"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xrep_upload">' . __d('upload', 'Répertoire de téléchargement') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control " id="xrep_upload" type="text" name="xrep_upload" value="' . $rep_upload . '" />
                    <span class="help-block">Répertoire de téléchargement (avec le / terminal)<span class="float-end ms-1" id="countcar_xrep_upload"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xrep_cache">' . __d('upload', 'Répertoire de cache') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control " id="xrep_cache" type="text" name="xrep_cache" value="' . $rep_cache . '" />
                    <span class="help-block">Répertoire de stockage des fichiers temporaires (avec le / terminal)<span class="float-end ms-1" id="countcar_xrep_cache"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xrep_log">' . __d('upload', 'Répertoire des log') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control " id="xrep_log" type="text" name="xrep_log" value="' . $rep_log . '" />
                    <span class="help-block">Répertoire/fichier de stockage de la log de téléchargement (par défaut /slogs/security.log)<span class="float-end ms-1" id="countcar_xrep_log"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xurl_upload">' . __d('upload', 'Url site') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control " id="xurl_upload" type="text" name="xurl_upload" value="' . $url_upload . '" />
                    <span class="help-block">URL HTTP(S) de votre site (exemple : http(s)://www.monsite.org)<span class="float-end ms-1" id="countcar_xurl_upload"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xurl_upload_css">' . __d('upload', 'Url css') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" id="xurl_upload_css" type="text" name="xurl_upload_css" value="' . $url_upload_css . '" />
                    </div>
                </div>';

        include('modules/upload/include/mimetypes.php');

        $sel = '';
        $opt = '';
        $tab_ext = explode(' ', $extension_autorise);

        foreach ($mimetypes as $ext_name => $ext_def) {
            if (in_array($ext_name, $tab_ext))
                $sel = ' selected="selected"';
            else 
                $sel = '';

            $opt .= '<option ' . $sel . 'value="' . $ext_name . '">.' . $ext_name . '</option>';
        };

        echo '
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="xextension_autorise">Extensions</label>
                <div class="col-sm-8">
                <select multiple="multiple" class="form-select" id="xextension_autorise" name="xextension_autorise[]" size="8">'
            . $opt . '
                </select>
                <span class="help-block">Extensions des fichiers autorisés</span>
                </div>
            </div>';

        $opt = '';
        $v = '';

        $hrchoice = array('0' => 'afficher les images de !divers', '1' => 'afficher les images de !mime', '2' => 'afficher les images de la racine du répertoire', '3' => 'afficher les documents');
        
        foreach ($hrchoice as $k => $af) {
            if ($ed_profil[$k] == "1") 
                $sel = 'selected="selected"';
            else 
                $sel = '';

            $opt .= '<option ' . $sel . ' value="' . $k . '">' . $af . '</option>';
        }

        echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xed_profil">Affichage</label>
                    <div class="col-sm-8">
                    <select multiple="multiple" class="form-select" id="xed_profil" name="xed_profil[]">'
                . $opt . '
                    </select>
                    <span class="help-block">Gére l\'affichage de la Banque Images et Documents</span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xed_nb_images">' . __d('upload', 'Nombre d\'images') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control " id="xed_nb_images" type="text" name="xed_nb_images" min="1" maxlength="3" value="' . $ed_nb_images . '" />
                    <span class="help-block">Nombre d\'image par ligne dans l\'afficheur d\'image de l\'editeur HTML</span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xwidth_max">' . __d('upload', 'Largeur maxi') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control " id="xwidth_max" type="text" name="xwidth_max" value="' . $width_max . '" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xheight_max">' . __d('upload', 'Hauteur maxi') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control " id="xheight_max" type="text" name="xheight_max" value="' . $height_max . '" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xquota">' . __d('upload', 'Espace disque') . '</label>
                    <div class="col-sm-8">
                    <div class="input-group mb-2">
                        <div id="humread_quota" class="input-group-text">' . $quota . '</div>
                        <input  onkeyup="convertoct(\'xquota\',\'humread_quota\')" class="form-control " id="xquota" type="text" name="xquota" min="1" maxlength="8" value="' . $quota . '" />
                    </div>
                    <span class="help-block">Limite de l\'espace disque alloué pour l\'upload (en octects)<span class="float-end ms-1" id="countcar_xquota"></span></span>
                    </div>
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="op" value="Extend-Admin-SubModule" />
        <input type="hidden" name="subop" value="uploadSave" />
        <input type="hidden" name="adm_img_mod" value="1" />
        <div class="mb-3">
            <button class="btn btn-primary" type="submit">' . __d('upload', 'Sauver les modifications') . '</button>
        </div>
        </form>';

        echo '
        <script type="text/javascript">
            //<![CDATA[
                $("#humread_quota").text(fileSize(Number($("#xquota").val())));
                $("#humread_size").text(fileSize(Number($("#xmax_size").val())));
            function fileSize(b) {
                var u = 0, s=1024;
                while (b >= s || -b >= s) {
                    b /= s;
                    u++;
                }
                return (u ? b.toFixed(1) + " " : b) + " KMGTPEZY"[u] + "o";
            }
            function convertoct(e,f) {
                $("#"+f).text(fileSize(Number($("#"+e).val())));
            }
            //]]
        </script>';

        $fv_parametres = '
        xmax_size: {
            validators: {
                regexp: {
                    regexp:/^\d{1,8}$/,
                    message: "0 ... 9"
                },
                between: {
                    min: 1,
                    max: 99999999,
                    message: "1 ... 99999999"
                }
            }
        },
        xquota: {
            validators: {
                regexp: {
                    regexp:/^\d{1,8}$/,
                    message: "0 ... 9"
                },
                between: {
                    min: 1,
                    max: 99999999,
                    message: "1 ... 99999999"
                }
            }
        },
        xheight_max: {
            validators: {
                regexp: {
                    regexp:/^[1-9](\d{1,4})$/,
                    message: "0 ... 9"
                },
                between: {
                    min: 1,
                    max: 9999,
                    message: "1 ... 9999"
                }
            }
        },
        xwidth_max: {
            validators: {
                regexp: {
                    regexp:/^[1-9](\d{1,4})$/,
                    message: "0 ... 9"
                },
                between: {
                    min: 1,
                    max: 9999,
                    message: "1 ... 9999"
                }
            }
        },
        xed_nb_images: {
            validators: {
                regexp: {
                    regexp:/^[1-9](\d{0,2})$/,
                    message: "0 ... 9"
                },
                between: {
                    min: 1,
                    max: 120,
                    message: "1 ... 120"
                }
            }
        },
        ';

        $arg1 = '
        var formulid = ["settingsupload"];
        inpandfieldlen("xmax_size",8);
        inpandfieldlen("xdocumentroot",200);
        inpandfieldlen("xracine",40);
        inpandfieldlen("xrep_upload",200);
        inpandfieldlen("xrep_cache",200);
        inpandfieldlen("xrep_log",200);
        inpandfieldlen("xurl_upload",200);
        inpandfieldlen("xed_profil",100);
        inpandfieldlen("xed_nb_images",3);
        inpandfieldlen("xwidth_max",3);
        inpandfieldlen("xheight_max",3);
        inpandfieldlen("xquota",8);
        ';

        Css::adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
