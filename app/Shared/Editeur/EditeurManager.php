<?php

namespace Shared\Editeur;

use Npds\Config\Config;
use Shared\Editeur\Contracts\EditeurInterface;

/**
 * Undocumented class
 */
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
        global $tmp_theme, $tiny_mce_theme, $tiny_mce_relurl, $tiny_mce_theme, $tiny_mce_relurl;

        $tmp = '';

        $tmp_theme = with(get_instance())->template();

        $tiny_mce_init=true;
        $tiny_mce=true;
        $tiny_mce_theme="full"; //  "short"
        $tiny_mce_relurl="";

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

                        include(shared_path('Editeur/assets/tinymce/themes/advanced/npds.conf.php'));

                        $tmp .= '
                                });
                            });
                        //]]>
                        </script>';
                    }
                } else {
                    $tmp .= '<script type="text/javascript" src="'. site_url('shared/editeur/assets/tinymce/tinymce.min.js') .'"></script>';
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
