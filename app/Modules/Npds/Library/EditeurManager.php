<?php

namespace App\Modules\Npds\Library;

use Npds\Config\Config;
use App\Modules\Npds\Contracts\EditeurInterface;



class EditeurManager implements EditeurInterface 
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
     * [aff_editeur description]
     *
     * @param   [type]  $Xzone   [$Xzone description]
     * @param   [type]  $Xactiv  [$Xactiv description]
     *
     * @return  [type]           [return description]
     */
    public function aff_editeur($Xzone, $Xactiv)
    {
        global $tmp_theme, $tiny_mce_theme, $tiny_mce_relurl;

        $tmp = '';

        if (Config::get('npds.tiny_mce')) {
            static $tmp_Xzone;

            if ($Xzone == 'tiny_mce') {
                if ($Xactiv == 'end') {

                    if (substr($tmp_Xzone ?: '', -1) == ',') {
                        $tmp_Xzone = substr_replace($tmp_Xzone, '', -1);
                    }

                    if ($tmp_Xzone) {
                        $tmp = "
                        <script type=\"text/javascript\">
                        //<![CDATA[
                            document.addEventListener(\"DOMContentLoaded\", function(e) {
                                tinymce.init({
                                selector: 'textarea.tin',
                                mobile: {menubar: true},
                                language : '" . language_iso(1, '', '') . "',";

                        include("assets/shared/editeur/tinymce/themes/advanced/App.conf.php");

                        $tmp .= '
                                });
                            });
                        //]]>
                        </script>';
                    }
                } else {
                    $tmp .= '<script type="text/javascript" src="assets/shared/editeur/tinymce/tinymce.min.js"></script>';
                }
            } else {
                $tmp_Xzone .= $Xzone != 'custom' ? $Xzone . ',' : $Xactiv . ',';
            }
        } else {
            $tmp = '';
        }

        return $tmp;
    }

}
