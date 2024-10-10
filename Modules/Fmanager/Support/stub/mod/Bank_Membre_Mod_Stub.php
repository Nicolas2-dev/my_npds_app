<?php

namespace Modules\Fmanager\Support\Mod;

use Npds\Config\Config;

/**
 * Undocumented class
 */
class Bank_Membre_Mod_Stub
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
        $replace = str_replace(' ', '%20', 'users_private' . str_replace(dirname(Config::get('fmanager.membre.basedir_fma')), '', $data['cur_nav_back']) . '/' . basename($data['cur_nav']) . '/' . static::$obj->FieldName);

        if ((static::$obj->FieldView == "jpg") 
        or (static::$obj->FieldView == "gif") 
        or (static::$obj->FieldView == "png") 
        or (static::$obj->FieldView == "jpeg")){

            $url_modifier = Config::get('npds.tiny_mce') 
                ? '"#" onclick="javascript:parent.tinymce.activeEditor.selection.setContent(\'<img class="img-fluid" src="' . $replace . '" />\'); top.tinymce.activeEditor.windowManager.close();"' 
                : '"#"';
        } else {

            $url_modifier = Config::get('npds.tiny_mce') 
                ? '"#" onclick="javascript:parent.tinymce.activeEditor.selection.setContent(\'<a href="' . site_url($replace) . '" target="_blank">' . static::$obj->FieldName . '</a>\'); top.tinymce.activeEditor.windowManager.close();"' 
                : '"' . site_url($replace) . '"';
        }

        return $url_modifier;
    }

}
