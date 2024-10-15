<?php

namespace Modules\Users\Controllers\Front;

use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Console\Console;
use Npds\Session\Session;
use Npds\Support\Facades\DB;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Auth;
use Modules\Npds\Support\Facades\Hack;
use Modules\Users\Sform\SformUserInfo;
use Modules\Users\Support\Facades\User;
use Modules\Npds\Support\Facades\Cookie;
use Modules\Users\Support\Facades\Avatar;
use Modules\Geoloc\Support\Facades\Geoloc;
use Modules\Npds\Support\Facades\Metalang;
use Modules\Users\Support\Facades\UserMenu;
use Modules\ReseauxSociaux\Support\Facades\Reseaux;

/**
 * [UserLogin description]
 */
class UserMain extends FrontController
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
     * [index description]
     *
     * @return  [type]  [return description]
     */
    public function index()
    {
        global $user;

        $this->title(__('user info'));

        if (!User::AutoReg()) {
            unset($user);
        }

        if (empty($user)) {
            Url::redirect('user/login');

        } elseif (isset($user)) {
           $cookie = Cookie::cookiedecode($user);

           $this->userinfo($cookie);
        }
    }

    /**
     * [userinfo description]
     *
     * @param   [type]  $uname  [$uname description]
     *
     * @return  [type]          [return description]
     */
    public function userinfo($cookie)
    {
        $user  = Auth::guard('user');
        $admin = Auth::guard('admin');

        if ((Config::get('npds.member_list') == 1) and ((!isset($user)) and (!isset($admin)))) {
            Url::redirect('index');
        }
        
        $uname = Hack::remove(Request::query('uname')) ?: $cookie[1];

        if ($uname != '') {

            $this->set('message', Session::message('message'));

            $user_temp = DB::table('users')
                            ->select('uid', 'name', 'femail', 'url', 'bio', 'user_avatar', 'user_from', 'user_occ', 'user_intrest', 'user_sig', 'user_journal', 'mns')
                            ->where('uname', $uname)
                            ->first();

            if (!$user_temp['uid']) {
                Url::redirect('index');
            }

            $posterdata         = User::get_userdata_from_id($user_temp['uid']);
            $posterdata_extend  = User::get_userdata_extend_from_id($user_temp['uid']);

            $this->set('user_avatar', Avatar::url($user_temp));
            $this->set('uname', $uname);

            // user outil
            if (isset($cookie[1])) {
                if ($uname !== $cookie[1]) {
                    $this->set('user_links',    true);                    
                    $this->set('user_uid',      $user_temp['uid']);                    
                    $this->set('posterdata',    $posterdata);
                }
            }

            $this->set('reseaux_sociaux', Reseaux::reseaux_list($posterdata_extend));

            if (!isset($cookie[1])) {
                if ($uname == $cookie[1]) {
                    $this->set('personnaliser', true);
                }
            }

            if (isset($cookie[1]) and ($uname == $cookie[1])) {
                $this->set('user_menu', UserMenu::member($posterdata));
            }

            // Geoloc carte
            if (array_key_exists($ch_lat = Config::get('geoloc.config.ch_lat'), $posterdata_extend) 
            and array_key_exists($ch_lon = Config::get('geoloc.config.ch_lon'), $posterdata_extend)) 
            {
                if ($posterdata_extend[$ch_lat] != '' and $posterdata_extend[$ch_lon] != '') {
                    $div_row = '<div class="col-md-6">';
                } else {
                    $div_row = '<div class="col-md-12">';
                }

                $this->set('div_row', $div_row);
            }

            // sform
            $this->set('sform_user_info', with(new SformUserInfo(array_merge($posterdata, $posterdata_extend)))->display());

            // geoloc carte
            $this->set('geoloc_carte', Geoloc::geoloc_carte($uname, $posterdata_extend));

            // Journal en ligne de user.
            if ($user_temp['uid'] != 1 and !empty($user_journal)) {
                $this->set('user_journal', Metalang::meta_lang(stripslashes(Hack::remove($user_journal))));
            }
         
            // module commentaire fichier helper provisoirement en attente de creation library
            $this->set('user_commentaires', user_commentaires($uname, $user_temp['uid']));

            // module news fichier helper provisoirement en attente de creation library
            $this->set('user_articles', user_articles($uname));

            // module news fichier helper provisoirement en attente de creation library
            $this->set('user_contributions', user_contributions($uname, $user_temp['uid']));

            if ($posterdata['attachsig'] == 1) {
                $this->set('user_sig', nl2br(Hack::remove($user_temp['user_sig'])));
            }
        } else {
            Url::redirect('user/login');
        }
    }

}
