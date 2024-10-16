<?php

namespace Modules\Npds\Core;

use Modules\Npds\Core\AccessError;


class FrontController extends AccessError
{

    /**
     * Undocumented variable
     *
     * @var string
     */
    protected $layout = 'frontend';

    /**
     * Undocumented variable
     *
     * @var string
     */
    protected $template = 'Npdsboost_sk';

    /**
     * Undocumented variable
     *
     * @var string
     */
    protected $template_dir = 'Frontend';


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
