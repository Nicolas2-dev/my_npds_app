<?php

namespace App\Modules\Blocks\Library\Traits;

use Npds\Config\Config;
use App\Modules\Npds\Support\Facades\Metalang;

/**
 * Undocumented trait
 */
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
        $theme_dir = with(get_instance())->template_dir();

        if (file_exists(theme_path($theme_dir .'/'. $theme . "/Fragments/bloc-right.php")) and ($bloc_side == "RIGHT")) {
            $inclusion = theme_path($theme_dir .'/'. $theme . '/Fragments/bloc-right.php');
        }
    
        if (file_exists(theme_path($theme_dir .'/'. $theme . "/Fragments/bloc-left.php")) and ($bloc_side == "LEFT")) {
            $inclusion = theme_path($theme_dir .'/'. $theme . '/Fragments/bloc-left.php');
        }
    
        if (!$inclusion) {
            if (file_exists(theme_path($theme_dir .'/'. $theme . "/Fragments/bloc.php"))) {
                $inclusion = theme_path($theme_dir .'/'. $theme . '/Fragments/bloc.php');

            } elseif (file_exists(module_path("Themes/Views/Fragments/bloc.php"))) {
                $inclusion = module_path('Themes/Views/Fragments/bloc.php');
                
            } else {
                echo 'bloc.php manquant / not find !<br />';
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
            "'!B_content!'i" => $content,
            "'!site_url!'i" => Config::get('npds.nuke_url'),
        );
    
        echo $htvar; 
        echo Metalang::meta_lang(preg_replace(array_keys($App_METALANG_words), array_values($App_METALANG_words), $Xcontent));
        echo '</div>'; 
    }

}
