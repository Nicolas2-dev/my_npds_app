<?php

namespace Modules\Theme\Library\Traits;

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Css;
use Shared\Editeur\Support\Facades\Editeur;


trait ThemeHeadTrait
{

    /**
     * [head description]
     *
     * @return  [type]  [return description]
     */
    public function head()
    //function head($tiny_mce_init, $css_pages_ref, $css, $tmp_theme, $skin, $js, $m_description, $m_keywords)
    {
        global $topic, $hlpfile, $user, $hr, $long_chain, $tmp_theme, $tiny_mce_init, $skin, $css_pages_ref, $css;
    
        // $header = 1;

        if (file_exists(module_path("Themes/Support/Include/header_before.php"))) {
            include(module_path("Themes/Support/Include/header_before.php"));
        }

        // support metalang 
        Config::set('npds.theme', $this->theme); 

        $theme_dir = with(get_instance())->template_dir();

        settype($m_keywords, 'string');
        settype($m_description, 'string');
    
        if (Config::get('npds.gzhandler') == 1) {
            ob_start("ob_gzhandler");
        }
        
        // Meta
        if (file_exists(module_path('Npds/storage/meta/meta.php'))) {
            $meta_op = '';
            include(module_path('Npds/storage/meta/meta.php'));
        }
    
        // Favicon
        if (file_exists(theme_path($theme_dir .'/'. $this->theme."/assets/images/favicon.ico"))) {
            $favico = theme_path($theme_dir .'/'. $this->theme."/assets/images/favicon.ico");
        } else {
            $favico = 'assets/images/favicon.ico';
        }
    
        echo '<link rel="shortcut icon" href="' . $favico . '" type="image/x-icon" />';
    
        // Syndication RSS & autres
    
        // Canonical
        $scheme = strtolower($_SERVER['REQUEST_SCHEME'] ?? 'http');
        $host   = $_SERVER['HTTP_HOST'];
        $uri    = $_SERVER['REQUEST_URI'];
    
        echo '<link rel="canonical" href="' . ($scheme . '://' . $host . $uri) . '" />';
    
        // humans.txt
        if (file_exists("humans.txt")) {
            echo '<link type="text/plain" rel="author" href="' . Config::get('npds.nuke_url') . '/humans.txt" />';
        }
    
        echo '
        <link href="backend.php?op=RSS0.91" title="' . Config::get('npds.sitename') . ' - RSS 0.91" rel="alternate" type="text/xml" />
        <link href="backend.php?op=RSS1.0" title="' . Config::get('npds.sitename') . ' - RSS 1.0" rel="alternate" type="text/xml" />
        <link href="backend.php?op=RSS2.0" title="' . Config::get('npds.sitename') . ' - RSS 2.0" rel="alternate" type="text/xml" />
        <link href="backend.php?op=ATOM" title="' . Config::get('npds.sitename') . ' - ATOM" rel="alternate" type="application/atom+xml" />
        ';
    
        $tiny_mce_init=true;
        $tiny_mce=true;
        $tiny_mce_theme="full"; //  "short"
        $tiny_mce_relurl="";

        // Tiny_mce
        if ($tiny_mce_init) {
            echo Editeur::aff_editeur("tiny_mce", "begin");
        }
    
        // include externe JAVASCRIPT for functions, codes in the <body onload="..." event...
        //
        $this->Body_Onload_defaul();

        //
        $this->Body_Onload_theme();


        // include externe file from themes/default/include or themes/.../include for functions, codes ... - skin motor
        if (file_exists(module_path("Theme/Support/Include/header_head.php"))) {
            ob_start();
                include module_path("Theme/Support/Include/header_head.php");
                $hH = ob_get_contents();
            ob_end_clean();
    
            if ($skin != '' and substr($this->theme, -3) == "_sk") {
                $hH = str_replace('assets/shared/bootstrap/dist/css/bootstrap.min.css', 'assets/skins/' . $skin . '/bootstrap.min.css', $hH);
                $hH = str_replace('assets/shared/bootstrap/dist/css/extra.css', 'assets/skins/' . $skin . '/extra.css', $hH);
            }
            echo $hH;
        }
    
        if (file_exists(theme_path($theme_dir .'/'. $this->theme."/Support/Include/header_head.php"))) {
            include(theme_path($theme_dir .'/'. $this->theme."/Support/Include/header_head.php"));
        }
    
        echo Css::import_css($this->theme, '', $css_pages_ref, $css);
    
        // // Mod by Jireck - Chargeur de JS via PAGES.PHP
        // if ($js) {
        //     if (is_array($js)) {
        //         foreach ($js as $k => $tab_js) {
        //             if (stristr($tab_js, 'http://') || stristr($tab_js, 'https://'))
        //                 echo '<script type="text/javascript" src="' . $tab_js . '"></script>';
        //             else {
        //                 if (file_exists("app/Themes/$tmp_theme/assets/js/$tab_js") and ($tab_js != ''))
        //                     echo '<script type="text/javascript" src="app/Themes/' . $tmp_theme . '/assets/js/' . $tab_js . '"></script>';
        //                 elseif (file_exists("$tab_js") and ($tab_js != ""))
        //                     echo '<script type="text/javascript" src="' . $tab_js . '"></script>';
        //             }
        //         }
        //     } else {
        //         if (file_exists("app/Themes/$tmp_theme/assets/js/$js"))
        //             echo '<script type="text/javascript" src="app/Themes/' . $tmp_theme . '/assets/js/' . $js . '"></script>';
        //         elseif (file_exists("$js"))
        //             echo '<script type="text/javascript" src="' . $js . '"></script>';
        //     }
        // }
    
        echo '</head>';
    }

}
