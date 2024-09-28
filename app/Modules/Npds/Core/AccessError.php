<?php

namespace App\Modules\Npds\Core;

use Npds\Routing\Url;
use App\Controllers\Core\BaseController;


class AccessError extends BaseController
{

    /**
     * Call the parent construct
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
    public function access_error()
    {
        return Url::redirect('error');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function access_denied()
    {
        return Url::redirect('denied');
    }

}
