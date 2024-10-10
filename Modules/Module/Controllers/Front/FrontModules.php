<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class FrontModules extends FrontController
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
        //load_module($ModPath, $ModStart);

        if (filtre_module($ModPath) and filtre_module($ModStart)) {
            if (file_exists("app/Modules/$ModPath/$ModStart.php")) {
                include("app/Modules/$ModPath/$ModStart.php");
                
                die();
            } else {
                Access_Error();
            }
        } else {
            Access_Error();
        } 
        
    }

}