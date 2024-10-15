<?php

namespace Modules\Npds\Controllers\Api;

use App\Controllers\Core\BaseController;
use Modules\Npds\Support\Facades\Session;


class ApiSession extends BaseController
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
    public static function manage()
    {
        Session::session_manage();
    }

}
