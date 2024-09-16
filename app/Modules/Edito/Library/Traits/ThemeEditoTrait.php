<?php

namespace App\Modules\Edito\Library\Traits;

use App\Modules\Npds\Support\Facades\Language;
use App\Modules\Npds\Support\Facades\Metalang;


trait ThemeEditoTrait
{

    /**
     * [themedito description]
     *
     * @param   [type]  $content  [$content description]
     *
     * @return  [type]            [return description]
     */
    public function themedito($content)
    {
        $theme = with(get_instance())->template();
    
        $inclusion = false;
    
        if (file_exists(theme_path($theme . "/views/editorial.html"))) {
            $inclusion = theme_path($theme . "/views/editorial.html");

        } elseif (file_exists(theme_path("default/views/editorial.html"))) {
            $inclusion = theme_path("default/views/editorial.html");

        } else {
            echo 'editorial.html manquant / not find !<br />';
            die();
        }
    
        if ($inclusion) {
            ob_start();
                include($inclusion);
                $Xcontent = ob_get_contents();
            ob_end_clean();
    
            $App_METALANG_words = array(
                "'!editorial_content!'i" => $content
            );
    
            return Metalang::meta_lang(
                            Language::aff_langue(
                                preg_replace(
                                    array_keys($App_METALANG_words), 
                                    array_values($App_METALANG_words), 
                                    $Xcontent
                                )
                            )
                        );
        }
    }

}
