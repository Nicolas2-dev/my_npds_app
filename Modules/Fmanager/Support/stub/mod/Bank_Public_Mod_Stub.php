<?php

namespace Modules\Fmanager\Support\Mod;

use Npds\Config\Config;

/**
 * Undocumented class
 */
class Bank_Public_Mod_Stub
{
  
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private static $obj;


    /**
     * Undocumented function
     *
     * @param [type] $obj
     * @return void
     */
    public function __construc($obj)
    {
        static::$obj = $obj;
    }

    /**
     * cette variable fonctionne si url_fma_modifier => true;
     * url_modifier permet de modifier le comportement du lien (a href ....) se trouvant sur les fichiers affichés par FMA
     *
     * @return void
     */
    public static function display($data)
    {
        if ((static::$obj->FieldView == "jpg") 
        or (static::$obj->FieldView == "gif") 
        or (static::$obj->FieldView == "png") 
        or (static::$obj->FieldView == "jpeg")) {

            $url_modifier = Config::get('npds.tiny_mce') 
                ? '"#" onclick="javascript:parent.tinymce.activeEditor.selection.setContent(\'<img class="img-fluid" src="'. site_url('getfile?att_id='. $data['ibid'] .'&amp;apli=f-manager') .'" />\'); top.tinymce.activeEditor.windowManager.close();"' 
                : '"#"';
        } else {
            
            $url_modifier = Config::get('npds.tiny_mce') 
                ? '"#" onclick="javascript:parent.tinymce.activeEditor.selection.setContent(\'<a href="'. site_url('getfile?att_id='. $data['ibid'] .'&amp;apli=f-manager') .'" target="_blank">' . static::$obj->FieldName . '</a>\'); top.tinymce.activeEditor.windowManager.close();"' 
                : '"'. site_url('getfile?att_id='. $data['ibid'] .'&amp;apli=f-manager') .'"';
        }

        return $url_modifier;
    }

}
