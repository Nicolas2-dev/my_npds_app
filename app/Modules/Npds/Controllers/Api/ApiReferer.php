<?php

namespace App\Modules\Npds\Controllers\Api;

use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Controllers\Core\BaseController;
use App\Modules\Npds\Support\Facades\Hack;


class ApiReferer extends BaseController
{

    /**
     * Undocumented variable
     *
     * @var boolean
     */
    protected $autoRender = false;


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
    public static function update()
    {
        if (Config::get('npds.httpref') == 1) {
            $referer = htmlentities(strip_tags(Hack::remove(getenv("HTTP_REFERER"))), ENT_QUOTES, 'utf-8');
            
            if ($referer != '' and !strstr($referer, "unknown") and !stristr($referer, $_SERVER['SERVER_NAME'])) {
                DB::table('referer')->insert([
                    'url' => $referer
                ]);
            }
        }
        
    }

}
