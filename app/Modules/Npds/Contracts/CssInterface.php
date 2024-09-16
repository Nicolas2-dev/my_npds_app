<?php

namespace App\Modules\Npds\Contracts;


interface CssInterface {

    /**
     * [import_css_javascript description]
     *
     * @param   [type]  $tmp_theme      [$tmp_theme description]
     * @param   [type]  $language       [$language description]
     * @param   [type]  $fw_css         [$fw_css description]
     * @param   [type]  $css_pages_ref  [$css_pages_ref description]
     * @param   [type]  $css            [$css description]
     *
     * @return  [type]                  [return description]
     */
    public function import_css_javascript($tmp_theme, $fw_css, $css_pages_ref = '', $css = '');

    /**
     * [import_css description]
     *
     * @param   [type]  $tmp_theme      [$tmp_theme description]
     * @param   [type]  $language       [$language description]
     * @param   [type]  $fw_css         [$fw_css description]
     * @param   [type]  $css_pages_ref  [$css_pages_ref description]
     * @param   [type]  $css            [$css description]
     *
     * @return  [type]                  [return description]
     */
    public function import_css($tmp_theme, $fw_css, $css_pages_ref, $css);
    
    /**
     * [adminfoot description]
     *
     * @param   [type]  $fv             [$fv description]
     * @param   [type]  $fv_parametres  [$fv_parametres description]
     * @param   [type]  $arg1           [$arg1 description]
     * @param   [type]  $foo            [$foo description]
     *
     * @return  [type]                  [return description]
     */
    public function adminfoot($fv, $fv_parametres, $arg1, $foo);    

}