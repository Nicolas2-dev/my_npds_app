<?php

namespace App\Modules\Npds\Controllers\Error;

use Npds\view\View;
use App\Controllers\Core\BaseController;


class AccessError extends BaseController
{

    /**
     * Undocumented variable
     *
     * @var boolean
     */
    protected $autoRender = false;

    /**
     * Undocumented variable
     *
     * @var boolean
     */
    protected $useLayout  = false;


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
        $Titlesitename = 'Npds : '. __('Access error !');

        $this->access_denied($Titlesitename);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function access_denied($Titlesitename = '')
    {
        if(empty($Titlesitename)) {
            $Titlesitename = 'Npds : '. __('Access denied !');
        } 

        if (file_exists(module_path("Npds/storage/meta/meta.php"))) {
            include(module_path("Npds/storage/meta/meta.php"));
        }

        View::make('Modules/Npds/Views/AccessError/access_denied')->display();
    }

}
