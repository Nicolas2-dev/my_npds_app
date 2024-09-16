<?php

namespace App\Modules\Blocks\Library\Traits;

use App\Modules\Npds\Support\Facades\Metalang;


trait ThemeSideboxTrait
{

    /**
     * [themesidebox description]
     *
     * @param   [type]  $title    [$title description]
     * @param   [type]  $content  [$content description]
     *
     * @return  [type]            [return description]
     */
    public function themesidebox($title, $content)
    {
        global $B_class_title, $B_class_content, $bloc_side, $htvar;
    
        $inclusion = false;
    
        $theme = with(get_instance())->template();

        if (file_exists(app_path("Themes/" . $theme . "/Fragments/bloc-right.html")) and ($bloc_side == "RIGHT")) {
            $inclusion = app_path('Themes/' . $theme . '/Fragments/bloc-right.html');
        }
    
        if (file_exists(app_path("Themes/" . $theme . "/Fragments/bloc-left.html")) and ($bloc_side == "LEFT")) {
            $inclusion = app_path('Themes/' . $theme . '/Fragments/bloc-left.html');
        }
    
        if (!$inclusion) {
            if (file_exists(app_path("Themes/" . $theme . "/Fragments/bloc.html"))) {
                $inclusion = app_path('Themes/' . $theme . '/Fragments/bloc.html');

            } elseif (file_exists(app_path("Themes/default/Fragments/bloc.html"))) {
                $inclusion = app_path('Themes/default/Fragments/bloc.html');
                
            } else {
                echo 'bloc.html manquant / not find !<br />';
                die();
            }
        }
    
        ob_start();
            include($inclusion);
            $Xcontent = ob_get_contents();
        ob_end_clean();
    
        if ($title == 'no-title') {
            $Xcontent = str_replace('<div class="LB_title">!B_title!</div>', '', $Xcontent);
            $title = '';
        }
    
        $App_METALANG_words = array(
            "'!B_title!'i" => $title,
            "'!B_class_title!'i" => $B_class_title,
            "'!B_class_content!'i" => $B_class_content,
            "'!B_content!'i" => $content
        );
    
        echo $htvar; 
        echo Metalang::meta_lang(preg_replace(array_keys($App_METALANG_words), array_values($App_METALANG_words), $Xcontent));
        echo '</div>'; 
    }

}
