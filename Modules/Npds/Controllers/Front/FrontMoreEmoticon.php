<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class FrontMoreEmoticon extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {

    }

    public function index()
    {
        if (isset($user)) {
            if ($cookie[9] == '') 
                $cookie[9] = Config::get('npds.Default_Theme');
        
            if (isset($theme)) 
                $cookie[9] = $theme;
        
            $tmp_theme = $cookie[9];
        
            if (!$file = @opendir("themes/$cookie[9]")) {
                $tmp_theme = Config::get('npds.Default_Theme');
            }
        } else {
            $tmp_theme = Config::get('npds.Default_Theme');
        }
        
        include('meta/meta.php');
        
        echo '<link rel="stylesheet" href="assets/skins/default/bootstrap.min.css">';
        
        echo import_css($tmp_theme, '', '', '');
        
        include('library/javascript/formhelp.java.php');
        
        echo '
                </head>
                <body class="p-2">
                ' . putitems_more() . '
                </body>
            </html>';
        
        
    }

}