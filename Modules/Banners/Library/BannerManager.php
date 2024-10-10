<?php

namespace Modules\Banners\Library;

use Npds\Config\Config;
use Modules\Banners\Contracts\BannerInterface;

/**
 * Undocumented class
 */
class BannerManager implements BannerInterface 
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
     * Undocumented function
     *
     * @return void
     */
    public function header_page()
    {
        include_once("modules/upload/upload.conf.php");

        include("storage/meta/meta.php");
    
        if ($url_upload_css) {
            $url_upload_cssX = str_replace('style.css', Config::get('npds.language') . '-style.css', $url_upload_css);
    
            if (is_readable($url_upload . $url_upload_cssX)) {
                $url_upload_css = $url_upload_cssX;
            }
    
            print("<link href=\"" . $url_upload . $url_upload_css . "\" title=\"default\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n");
        }

        if (file_exists('themes/default/include/header_head.inc')) {
            include('themes/default/include/header_head.inc');
        }
    
        if (file_exists('themes/' . Config::get('npds.Default_Theme') . '/include/header_head.inc')) {
            include('themes/' . Config::get('npds.Default_Theme') . '/include/header_head.inc');
        }
    
        if (file_exists('themes/' . Config::get('npds.Default_Theme') . '/style/style.css')) {
            echo '<link href="themes/' . Config::get('npds.Default_Theme') . '/style/style.css" rel="stylesheet" type=\"text/css\" media="all" />';
        }
    
        echo '
        </head>
        <body style="margin-top:64px;">
            <div class="container-fluid">
            <nav class="navbar navbar-dark navbar-expand-lg fixed-top bg-primary">
                <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><i class="fa fa-home fa-lg me-2"></i></a>
                <span class="navbar-text">' . __d('banners', 'Bannières - Publicité') . '</span>
                </div>
            </nav>
            <h2 class="mt-4">' . __d('banners', 'Bannières - Publicité') . ' @ ' . Config::get('npds.Titlesitename') . '</h2>
            <p align="center">';
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function footer_page()
    {
        include('themes/default/include/footer_after.inc');
    
        echo '</p>
            </div>
        </body>
        </html>';
    }

}
