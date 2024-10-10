<?php

namespace Modules\Authors\Library;

use Npds\Support\Facades\DB;
use Modules\Authors\Contracts\AuthorsInterface;


class AuthorsManager implements AuthorsInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * [formatAidHeader description]
     *
     * @param   [type]  $aid  [$aid description]
     *
     * @return  [type]        [return description]
     */
    public function formatAidHeader($aid)
    {
        if ($author = DB::table('authors')->select('url', 'email')->where('aid', $aid)->first()) {
            
            if (isset($author['url'])) {
                echo '<a href="' . $author['url'] . '" >' . $aid . '</a>';

            } elseif (isset($author['email'])) {
                echo '<a href="mailto:' . $author['email'] . '" >' . $aid . '</a>';

            } else {
                echo $aid;
            }
        }
    }

    /**
     * [modulo_droit description]
     *
     * @return  [type]  [return description]
     */
    public function modulo_droit()
    {
        $listdroits          = '';
        $listdroitsmodulo    = '';

        // sélection des fonctions sauf les fonctions de type alerte 
        $R = sql_query("SELECT fid, fnom, fnom_affich, fcategorie FROM fonctions f WHERE f.finterface =1 AND fcategorie < 7 ORDER BY f.fcategorie");
        
        while (list($fid, $fnom, $fnom_affich, $fcategorie) = sql_fetch_row($R)) {
            if ($fcategorie == 6) {
                $listdroitsmodulo .= '
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check">
                        <input class="ckbm form-check-input" id="ad_d_m_' . $fnom . '" type="checkbox" name="ad_d_m_' . $fnom . '" value="' . $fid . '" />
                        <label class="form-check-label" for="ad_d_m_' . $fnom . '">' . $fnom_affich . '</label>
                        </div>
                    </div>';
            } else {
                if ($fid != 12)
                    $listdroits .= '
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check">
                        <input class="ckbf form-check-input" id="ad_d_' . $fid . '" type="checkbox" name="ad_d_' . $fid . '" value="' . $fid . '" />
                        <label class="form-check-label" for="ad_d_' . $fid . '">' . __d('authors', $fnom_affich) . '</label>
                        </div>
                    </div>';
            }
        }        
    }

    /**
     * [uri_check description]
     *
     * @return  [type]  [return description]
     */
    public function uri_check()
    {
        $scri_check = '
            <script type="text/javascript">
            //<![CDATA[
            $(function () {
                check = $("#cb_radminsuper").is(":checked");
                if(check) {
                    $("#adm_droi_f, #adm_droi_m").addClass("collapse");
                }
            });
            $("#cb_radminsuper").on("click", function(){
                check = $("#cb_radminsuper").is(":checked");
                if(check) {
                    $("#adm_droi_f, #adm_droi_m").toggleClass("collapse","collapse show");
                    $(".ckbf, .ckbm, #ckball_f, #ckball_m").prop("checked", false);
                } else {
                    $("#adm_droi_f, #adm_droi_m").toggleClass("collapse","collapse show");
                }
            }); 
            $(document).ready(function(){ 
                $("#ckball_f").change(function(){
                    check_a_f = $("#ckball_f").is(":checked");
                    if(check_a_f) {
                        $("#ckb_status_f").text("' . html_entity_decode(__d('authors', 'Tout décocher'), ENT_COMPAT | ENT_HTML401, cur_charset) . '");
                    } else {
                        $("#ckb_status_f").text("' . __d('authors', 'Tout cocher') . '");
                    }
                    $(".ckbf").prop("checked", $(this).prop("checked"));
                });
                
                $("#ckball_m").change(function(){
                    check_a_m = $("#ckball_m").is(":checked");
                    if(check_a_m) {
                        $("#ckb_status_m").text("' . html_entity_decode(__d('authors', 'Tout décocher'), ENT_COMPAT | ENT_HTML401, cur_charset) . '");
                    } else {
                        $("#ckb_status_m").text("' . __d('authors', 'Tout cocher') . '");
                    }
                    $(".ckbm").prop("checked", $(this).prop("checked"));
                });
            });
            //]]>
            </script>';
    }

    /**
     * [error_handler description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function error_handler($ibid)
    {
        echo '
        <div class="alert alert-danger mb-3">
        ' . __d('authors', 'Merci d\'entrer l\'information en fonction des spécifications') . '<br />' . $ibid . '
        </div>
        <a class="btn btn-outline-secondary" href="'. site_url('admin.php?op=mod_authors') .'" >' . __d('authors', 'Retour en arrière') . '</a>';
    }

}
