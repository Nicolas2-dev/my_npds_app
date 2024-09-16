<?php

namespace App\Controllers\Front;

use Npds\Config\Config;
use App\Support\Facades\Messenger;
use Npds\Support\Facades\Redirect;
use App\Controllers\Core\FrontController;
use App\Library\Supercache\SuperCacheEmpty;
use App\Library\Supercache\SuperCacheManager;


class FrontStartPage extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {

    }

    /**
     * [select_start_page description]
     *
     * @param   [type]  $op  [$op description]
     *
     * @return  [type]       [return description]
     */
    function select_start_page($op  = null)
    {
        // global $index;
    
        // // if (!AutoReg()) {
        // //     global $user;
        // //     unset($user);
        // // }

        // if ((Config::get('npds.Start_Page') == '') 
        // or ($op == "index") 
        // or ($op == "edito") 
        // or ($op == "edito-nonews")) {

        //     $index = 1;

            //$this->theindex($op, '', '');

            return $this->createView()
            ->shares('title', 'Homepage')
            ->with('content', 'fffff');


        // } else {
        //     return Redirect::to(Config::get('npds.Start_Page'));
        // }
    }
   
    function theindex($op, $catid, $marqeur)
    {
        //include("header.php");
    
        //Messenger::Mess_Check_Mail('user');


        // // Include cache manager
        // global $SuperCache;
    
        // if ($SuperCache) {
        //     $cache_obj = new SuperCacheManager();
        //     $cache_obj->startCachingPage();
        // } else {
        //     $cache_obj = new SuperCacheEmpty();
        // }
    
        // if (($cache_obj->get_Genereting_Output() == 1) 
        //     or ($cache_obj->get_Genereting_Output() == -1) 
        //     or (!$SuperCache)) 
        // {
            
        //     // Appel de la publication de News et la purge automatique
        //     automatednews();
    
        //     global $theme;
    
        //     if (($op == 'newcategory') or 
        //        ($op == 'newtopic') or 
        //        ($op == 'newindex') or 
        //        ($op == 'edito-newindex')) 
        //     {
        //         aff_news($op, $catid, $marqeur);

        //     } else {
        //         if (file_exists(APPPATH .'themes' .DS .$theme .DS .'central.php')) {
        //             include(APPPATH .'themes' .DS .$theme .DS .'central.php');

        //         } else {
        //             if (($op == 'edito') 
        //                or ($op == 'edito-nonews')) 
        //             {
        //                 aff_edito();
        //             }
    
        //             if ($op != 'edito-nonews') {
        //                 aff_news($op, $catid, $marqeur);
        //             }
        //         }
        //     }
        // }
    
        // if ($SuperCache) {
        //     $cache_obj->endCachingPage();
        // }
    


        //include("footer.php");
    }

}