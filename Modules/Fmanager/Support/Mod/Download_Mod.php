<?php

namespace Modules\Fmanager\Support\Mod;

use Npds\Config\Config;
use Modules\Fmanager\Support\Facades\Fmanager;

/**
 * Undocumented class
 */
class Download_Mod
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
        $repw = str_replace(Config::get('fmanager.download.basedir_fma'), '', $data['cur_nav']);

        if ($repw != "") {
            if (substr($repw, 0, 1) == "/") {
                $repw = substr($repw, 1) . "/" . static::$obj->FieldName;
            }
        } else {
            $repw = static::$obj->FieldName;
        }

        $url_modifier = '"#" onclick="javascript:window.opener.document.adminForm.durl.value="' . $repw . '"; window.opener.document.adminForm.dfilename.value="' . Fmanager::extend_ascii(static::$obj->FieldName) . '";"';

        return $url_modifier;
    }

}
