<?php


namespace App\Controllers;

use Npds\View\View;
use Npds\Config\Config;
use Npds\Session\Session;
use App\Modules\Users\Models\User;
use App\Modules\Users\Models\UserStatus;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Theme\Support\Facades\Theme;
use App\Exceptions\Handler as ExceptionHandler;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Welcome extends FrontController
{

    private $basePath;


    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function before()
    {
        $this->basePath = str_replace(BASEPATH, '', $this->viewsPath());

//fdgr();

        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * Define Welcome page title and load template files
     */
    public function welcome()
    {
        $viewName = $this->method();

        $filePath = $this->basePath .$viewName .'.php';


// $uss = User::where('id', 2)->first();


// vd($uss, $uss->user_status, $uss->user_extend);



// $ussg = UserStatus::where('id', 2)->first();


// vd($ussg, $ussg->user, $ussg->user_extend);

// vd(Theme::lists());

// vd(Config::all(), Theme::config('config.long_chain', 15), Theme::config('config.theme'));

$this->set('message', Session::message('message'));
$this->set('logout', Session::message('logout'));



        $message = __('Hello, welcome from the welcome controller! <br/>
This content can be changed in <code>{0}</code>', $filePath);

        // Setup the View variables.
        $this->title(__('Welcome'));

        $this->set('welcome_message', $message);
    }

    /**
     * Define Subpage page title and load template files
     */
    public function subPage()
    {
        $viewName = $this->method();

        $filePath = $this->basePath .$viewName .'.php';

        $this->set('message', Session::message('message'));
        $this->set('logout', Session::message('logout'));

        $message = __('Hello, welcome from the welcome controller and subpage method! <br/>
This content can be changed in <code>{0}</code>', $filePath);

        // Setup the View variables.
        $this->title(__('Subpage'));

        $this->set('welcome_message', $message);
    }
}
