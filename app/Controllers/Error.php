<?php


namespace App\Controllers;


use Npds\View\View;
use Npds\Http\Response;
use Modules\Npds\Core\FrontController;

/**
 * Error class to generate 404 pages.
 */
class Error extends FrontController
{
    protected $useLayout = true;


    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Load a 404 page with the error message.
     *
     * @param mixed $error
     */
    public function error404($error = null)
    {
        Response::addHeader("HTTP/1.0 404 Not Found");

        //
        View::share('title', __('Error 404'));

        return View::make('error404')->withError($error);
    }
    
}
