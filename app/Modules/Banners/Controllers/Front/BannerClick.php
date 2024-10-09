<?php

namespace App\Modules\Banners\Controllers\Front;

use Npds\Config\Config;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Language;

/**
 * Undocumented class
 */
class BannerClick extends FrontController
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
     * @param [type] $bid
     * @return void
     */
    public function clickbanner($bid)
    {
        $bresult = sql_query("SELECT clickurl FROM banner WHERE bid='$bid'");
        list($clickurl) = sql_fetch_row($bresult);
    
        sql_query("UPDATE banner SET clicks=clicks+1 WHERE bid='$bid'");
        sql_free_result($bresult);
    
        if ($clickurl == '') {
            $clickurl = Config::get('npds.nuke_url');
        }
    
        Header("Location: " . Language::aff_langue($clickurl));
    }

}
