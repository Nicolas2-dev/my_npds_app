<?php

namespace App\Modules\Authors\Controllers\Front;

use Npds\Routing\Url;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Cookie;

/**
 * [UserLogin description]
 */
class AuthorLogout extends FrontController
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
     * [logout description]
     *
     * @return  [type]  [return description]
     */
    public function logout()
    {
        global $admin;

        Cookie::set("admin");
        Cookie::set("adm_exp");
        
        unset($admin);
                    
        Url::redirect('index');
    }    

}
