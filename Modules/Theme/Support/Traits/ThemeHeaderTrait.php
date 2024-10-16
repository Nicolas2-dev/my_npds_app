<?php

namespace Modules\Theme\Support\Traits;

use Npds\Config\Config;
use Npds\Events\Manager as Events;
use Modules\Blocks\Support\Facades\Block;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;


trait ThemeHeaderTrait
{

    /**
    * [theme_header description]
    *
    * @return  [type]  [return description]
    */
    public function theme_header($pdst) 
    {
        global $user;

        // ContainerGlobal permet de transmettre à Theme-Dynamic un élément de personnalisation avant
        // le chargement de header.html / Si vide alors la class body est chargée par défaut par TD
        $ContainerGlobal = '<div id="container">';

        $rep = false;

        settype($ContainerGlobal, 'string');

        // $theme = with(get_instance())->template();
        $theme_dir = with(get_instance())->template_dir();

        if (file_exists(theme_path($theme_dir .'/'. $this->theme . "/Fragments/header.php"))) {
            $rep = theme_path($theme_dir .'/'. $this->theme);
        } elseif (file_exists(module_path("Theme/Views/Fragments/header.php"))) {
            $rep = module_path('Theme/Views');
        } else {
            echo 'Fragments/header.php manquant / not find !<br />';
            die();
        }

        if ($rep) {
            if (file_exists(module_path("Theme/Support/Include/body_onload.php")) 
            or file_exists(theme_path($theme_dir .'/'. $this->theme."/Support/Include/body_onload.php"))) {
                $onload_init = ' onload="init();"';
            } else {
                $onload_init = '';
            }

            if (!$ContainerGlobal) {
                echo '<body' . $onload_init . ' class="body">';
            } else {
                echo '<body' . $onload_init . '>';
                echo $ContainerGlobal;
            }

            ob_start();
                // landing page
                if (stristr($_SERVER['REQUEST_URI'], Config::get('npds.Start_Page')) 
                and file_exists(theme_path($rep . "/Fragments/header_landing.php"))) {
                    include($rep . "/Fragments/header_landing.php");
                } else {
                    include($rep . "/Fragments/header.php");
                }

                $Xcontent = ob_get_contents();
            ob_end_clean();

            //echo meta_lang(aff_langue($Xcontent));
            echo Metalang::meta_lang(Language::aff_langue($Xcontent));
        }

        // referer
		Events::sendEvent('referer');

        // counter
		Events::sendEvent('counter');

        if (file_exists(module_path("Themes/Support/Include/header_after.php"))) {
            include(module_path("Themes/Support/Include/header_after.php"));
        }

        $moreclass = 'col';
        
        echo '
        <div id="corps" class="container-fluid n-hyphenate">
            <div class="row g-3">';

        switch ($pdst) {
            case '-1':
                echo '<div id="col_princ" class="col-12">';
                break;
        
            case '1':
                $this->colsyst('#col_LB');
                echo '
                    <div id="col_LB" class="collapse show col-lg-3">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-1">';
                Block::leftblocks($moreclass);
                echo '
                    </div>
                    </div>
                    <div id="col_princ" class="col-lg-6">';
                break;
        
            case '2':
            case '6':
                echo '<div id="col_princ" class="col-lg-9">';
                break;
        
            case '3':
                $this->colsyst('#col_LB');
                echo '
                <div id="col_LB" class="collapse show col-lg-3">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-1">';
                Block::leftblocks($moreclass);
                echo '
                    </div>
                </div>';
                $this->colsyst('#col_RB');
                echo ' 
                <div id="col_RB" class="collapse show col-lg-3">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-1">';
                Block::rightblocks($moreclass);
                echo '
                    </div>
                </div>
                <div id="col_princ" class="col-lg-6">';
                break;
        
            case '4':
                echo '<div id="col_princ" class="col-lg-6">';
                break;
        
            case '5':
                $this->colsyst('#col_RB');
                echo '
                <div id="col_RB" class="collapse show col-lg-3">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-1">';
                Block::rightblocks($moreclass);
                echo '
                    </div>
                </div>
                <div id="col_princ" class="col-lg-9">';
                break;
                
            default:
                $this->colsyst('#col_LB');
                echo '
                    <div id="col_LB" class="collapse show col-lg-3">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-1">';
                Block::leftblocks($moreclass);
                echo '
                        </div>
                    </div>
                    <div id="col_princ" class="col-lg-9">';
                break;
        }
    }

}
