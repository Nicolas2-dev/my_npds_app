<?php

namespace Modules\Banners\Controllers\Front;

use Npds\Http\Request;
use Npds\Config\Config;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Auth;
use Modules\Npds\Support\Facades\Language;

/**
 * Undocumented class
 */
class Banners extends FrontController
{
 
    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();              
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * [after description]
     *
     * @param   [type]  $result  [$result description]
     *
     * @return  [type]           [return description]
     */
    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function viewbanner()
    {
        $okprint        = false;
        $while_limit    = 3;
        $while_cpt      = 0;
    
        $bresult = sql_query("SELECT bid FROM banner WHERE userlevel!='9'");
        $numrows = sql_num_rows($bresult);
    
        while ((!$okprint) and ($while_cpt < $while_limit)) {

            // More efficient random stuff, thanks to Cristian Arroyo from http://www.planetalinux.com.ar
            if ($numrows > 0) {
                mt_srand((float) microtime() * 1000000);
                $bannum = mt_rand(0, $numrows);
            } else {
                break;
            }
    
            $bresult2 = sql_query("SELECT bid, userlevel FROM banner WHERE userlevel!='9' LIMIT $bannum,1");
            list($bid, $userlevel) = sql_fetch_row($bresult2);
    
            if ($userlevel == 0) {
                $okprint = true;
            } else {
                if ($userlevel == 1) {
                    if (Auth::secur_static("member")) {
                        $okprint = true;
                    }
                }
    
                if ($userlevel == 3) {
                    if (Auth::secur_static("admin")) {
                        $okprint = true;
                    }
                }
            }
    
            $while_cpt = $while_cpt + 1;
        }
    
        // Le risque est de sortir sans un BID valide
        if (!isset($bid)) {
            $rowQ1 = Q_Select("SELECT bid FROM banner WHERE userlevel='0' LIMIT 0,1", 86400);
    
            if ($rowQ1) {
                $myrow = $rowQ1[0]; // erreur à l'install quand on n'a pas de banner dans la base ....
                $bid = $myrow['bid'];
                $okprint = true;
            }
        }
    
        if ($okprint) {
    
            $myhost = Request::getip();
    
            if (Config::get('npds.myIP') != $myhost) {
                sql_query("UPDATE banner SET impmade=impmade+1 WHERE bid='$bid'");
            }
    
            if (($numrows > 0) and ($bid)) {

                $aborrar = sql_query("SELECT cid, imptotal, impmade, clicks, imageurl, clickurl, date FROM banner WHERE bid='$bid'");
                list($cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date) = sql_fetch_row($aborrar);
    
                if ($imptotal == $impmade) {
                    sql_query("INSERT INTO bannerfinish VALUES (NULL, '$cid', '$impmade', '$clicks', '$date', now())");
                    sql_query("DELETE FROM banner WHERE bid='$bid'");
                }
    
                if ($imageurl != '') {
                    echo '<a href="banners.php?op=click&amp;bid=' . $bid . '" target="_blank"><img class="img-fluid" src="' . Language::aff_langue($imageurl) . '" alt="" /></a>';
                } else {
                    if (stristr($clickurl, '.txt')) {
                        if (file_exists($clickurl)) {
                            include_once($clickurl);
                        }
                    } else {
                        echo $clickurl;
                    }
                }
            }
        }
    }

}
