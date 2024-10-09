<?php

namespace App\Modules\Banners\Controllers\Front;

use App\Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class BannerClientChange extends FrontController
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
     * @param [type] $login
     * @param [type] $pass
     * @param [type] $cid
     * @param [type] $bid
     * @param [type] $url
     * @return void
     */
    public function change_banner_url_by_client($login, $pass, $cid, $bid, $url)
    {
        header_page();
    
        $result = sql_query("SELECT passwd FROM bannerclient WHERE cid='$cid'");
        list($passwd) = sql_fetch_row($result);
    
        if (!empty($pass) and $pass == $passwd) {
            sql_query("UPDATE banner SET clickurl='$url' WHERE bid='$bid'");
            echo '<div class="alert alert-success">' . __d('banners', 'Vous avez changé l\'url de la bannière') . '<br /><a href="javascript:history.go(-1)" class="alert-link">' . __d('banners', 'Retour en arrière') . '</a></div>';
        } else
            echo '<div class="alert alert-danger">' . __d('banners', 'Identifiant incorrect !') . '<br />' . __d('banners', 'Merci de') . ' <a href="banners.php?op=login" class="alert-link">' . __d('banners', 'vous reconnecter.') . '</a></div>';
    
        footer_page();
    }

}