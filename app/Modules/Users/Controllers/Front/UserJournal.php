<?php

namespace App\Modules\Users\Controllers\Front;

use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Console\Console;
use Npds\Session\Session;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Sanitize;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Hack;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Npds\Support\Facades\Cookie;

/**
 * [UserLogin description]
 */
class UserJournal extends FrontController
{

    /**
     * Undocumented variable
     *
     * @var integer
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
        Console::log('Controller : '. __CLASS__);

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

        Console::logSpeed('START Controller : '. __CLASS__);

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * [editjournal description]
     *
     * @return  [type]  [return description]
     */
    public function edit_journal()
    {
        if (Auth::guard('user')) {

            $this->title(__('Editer votre page principale'));
            
            $this->set('message', Session::message('message'));

            $userinfo = User::getuserinfo(Auth::check('user'));
        
            $this->set('userinfo',  $userinfo);
            $this->set('user_menu', User::member_menu($userinfo));
        } else {
            Url::redirect('index');
        }
    }
    
    /**
     * [savejournal description]
     *
     * @param   [type]  $uid       [$uid description]
     * @param   [type]  $journal   [$journal description]
     * @param   [type]  $datetime  [$datetime description]
     *
     * @return  [type]             [return description]
     */
    public function save_journal()
    {
        $cookie = Cookie::cookiedecode(Auth::check('user'));
    
        $user_temp = DB::table('users')->select('uid')->where('uname', $cookie[1])->first();

        $input = Request::post();

        if ($input['uid'] == $user_temp['uid']) {
            $rep = !empty($DOCUMENTROOT = Config::get('upload.config.DOCUMENTROOT')) ? $DOCUMENTROOT : $_SERVER['DOCUMENT_ROOT'];
    
            $user_dir = $rep . Config::get('upload.config.racine') . 'app/Modules/Users/storage/users_private/' . $cookie[1];

            if (!is_dir($user_dir)) {
                mkdir($user_dir, 0777);
                $fp = fopen($user_dir . '/index.html', 'w');
                fclose($fp);
                chmod($user_dir . '/index.html', 0644);
            }
    
            $journal = dataimagetofileurl($input['journal'], 'app/Modules/Users/storage/users_private/' . $cookie[1] . '/journal');
            $journal = Hack::remove(stripslashes(Sanitize::FixQuotes($journal)));
    
            if ($input['datetime']) {
                $journalentry = $journal;
                $journalentry .= '<br /><br />';
    
                $journalentry .= date('d-m-Y H:i', time() + ((int) Config::get('npds.gmt') * 3600));

                DB::table('users')->where('uid', $input['uid'])->update([
                    'user_journal'  => $journalentry
                ]);
            } else {
                DB::table('users')->where('uid', $input['uid'])->update([
                    'user_journal'  => $journal
                ]);
            }

            Session::set('message', ['type' => 'success', 'text' => __d('users', 'Votre Journal a été mis à jour avec success.')]);

            Url::redirect('user?opdashboard#message');
        } else {
            Url::redirect('index');
        }
    }

}
