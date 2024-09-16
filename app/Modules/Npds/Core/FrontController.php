<?php

namespace App\Modules\Npds\Core;

use App\Controllers\Core\BaseController;


class FrontController extends BaseController
{

    protected $layout = 'frontend';


    protected $template = 'Npdsboost_sk';

    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    protected function before()
    {
        // Leave to parent's method the Flight decisions.
        return parent::before();
    }


    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }



}
