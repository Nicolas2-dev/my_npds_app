<?php

namespace App\Modules\Users\Controllers\Front;

use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Support\Csrf;
use Npds\Config\Config;
use Npds\Session\Session;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Sanitize;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Hack;
use App\Modules\Users\Sform\SformUserEdite;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Users\Support\Facades\Avatar;
use App\Modules\Npds\Support\Facades\Password;
use App\Modules\Users\Support\Facades\UserMenu;
use App\Modules\Users\Library\Traits\UserLogoutTrait;

/**
 * [UserLogin description]
 */
class UserEdit extends FrontController
{
    /**
     * 
     */
    use UserLogoutTrait;
    
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
    public function edit_user()
    {
        if (Auth::guard('user')) {

            $this->title(__('Editer votre page principale'));
            
            $this->set('message', Session::message('message'));

            $userinfo = getuserinfo(Auth::check('user'));
        
            Theme::showimage();

            $this->set('user_edite_sform', with(new SformUserEdite($userinfo))->display());

            $this->set('user_menu', UserMenu::member($userinfo));

        } else {
            Url::redirect('index');
        }
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function save_user()
    {
        if (Csrf::isTokenValid() 
        and (Request::post('op')    == 'saveuser')
        and (Request::isPost())) {

            $this->title(__('Editer votre page principale'));

            $user = Auth::check('user');

            DB::table('session')->where('time', '<', (time() - 300))->delete();

            $cookie = Cookie::cookiedecode($user);

            if (DB::table('session')->select('time')->where('username', $cookie[1])->count() == 1) {

                $input = Request::post();

                $user_uid = DB::table('users')->select('uid')->where('uname', $cookie[1])->first();

                if (($cookie[1] == $input['uname']) and ($input['uid'] == $user_uid['uid'])) {

                    if ((isset($input['pass'])) && ($input['pass'] != $input['vpass'])) {
                        
                        $this->set('error_password_identique', true);

                    } elseif (($input['pass'] != '') && (strlen($input['pass']) < Config::get('npds.minpass'))) {

                        $this->set('error_password_minpass', true);

                    } else {
                        $stop = User::userCheck('edituser', $input['email']);
            
                        if (!$stop) {

                            //
                            $this->white_users_bad_mail($input);
            
                            //
                            $user_avatar = Avatar::user_avatar_update($input);
            
                            $a = (isset($input['user_viewemail']) ? 1 : 0);
                            $u = (isset($input['usend_email']) ? 1 : 0);
                            $v = (isset($input['uis_visible']) ? 0 : 1);
                            $w = (isset($input['user_lnl']) ? 1 : 0);

                            if ($input['bio']) {
                                $input['bio'] = Sanitize::FixQuotes(strip_tags($input['bio']));
                            }

                            if (Request::post('pass', false)) {

                                Cookie::cookiedecode($user);
                                
                                DB::table('users')->where('uid', $input['uid'])->update([
                                    'name'            => Hack::remove($input['name']), 
                                    'email'           => $input['email'], 
                                    'femail'          => Hack::remove($input['femail']), 
                                    'url'             => Hack::remove($input['url']), 
                                    'pass'            => $checkpass = Password::crypt($input['pass']), 
                                    'hashkey'         => 1, 
                                    'bio'             => Hack::remove($input['bio']), 
                                    'user_avatar'     => $user_avatar, 
                                    'user_occ'        => Hack::remove($input['user_occ']), 
                                    'user_from'       => Hack::remove($input['user_from']), 
                                    'user_intrest'    => Hack::remove($input['user_intrest']), 
                                    'user_sig'        => Hack::remove($input['user_sig']), 
                                    'user_viewemail'  => $a, 
                                    'send_email'      => $u, 
                                    'is_visible'      => $v, 
                                    'user_lnl'        => $w 
                                ]);
                                
                                if ($userinfo = DB::table('users')
                                                ->select('uid', 'uname', 'pass', 'storynum', 'umode', 'uorder', 'thold', 'noscore', 'ublockon', 'theme', 'commentmax')
                                                ->where('uname', $input['uname'])
                                                ->where('pass', $checkpass)->first()) {
                                    
                                    Cookie::docookie(
                                        $userinfo['uid'], 
                                        $userinfo['uname'], 
                                        $userinfo['pass'], 
                                        $userinfo['storynum'], 
                                        $userinfo['umode'], 
                                        $userinfo['uorder'], 
                                        $userinfo['thold'], 
                                        $userinfo['noscore'], 
                                        $userinfo['ublockon'], 
                                        $userinfo['theme'], 
                                        $userinfo['commentmax'], 
                                        "");
                                }
                            } else { 
                                DB::table('users')->where('uid', $input['uid'])->update([
                                    'name'            => Hack::remove($input['name']), 
                                    'email'           => Hack::remove($input['email']), 
                                    'femail'          => Hack::remove($input['femail']), 
                                    'url'             => Hack::remove($input['url']), 
                                    'bio'             => Hack::remove($input['bio']), 
                                    'user_avatar'     => $user_avatar, 
                                    'user_occ'        => Hack::remove($input['user_occ']), 
                                    'user_from'       => Hack::remove($input['user_from']), 
                                    'user_intrest'    => Hack::remove($input['user_intrest']), 
                                    'user_sig'        => Hack::remove($input['user_sig']), 
                                    'user_viewemail'  => $a, 
                                    'send_email'      => $u, 
                                    'is_visible'      => $v, 
                                    'user_lnl'        => $w 
                                ]);  
                            }

                            DB::table('users_status')->where('uid', $input['uid'])->update([
                                'attachsig' => (isset($input['attach']) ? 1 : 0)
                            ]);

                            $data = [
                                'C1' => Hack::remove($input['C1']), 
                                'C2' => Hack::remove($input['C2']), 
                                'C3' => Hack::remove($input['C3']), 
                                'C4' => Hack::remove($input['C4']), 
                                'C5' => Hack::remove($input['C5']), 
                                'C6' => Hack::remove($input['C6']), 
                                'C7' => Hack::remove($input['C7']), 
                                'C8' => Hack::remove($input['C8']), 
                                'M1' => Hack::remove($input['M1']), 
                                'M2' => Hack::remove($input['M2']), 
                                'T1' => Hack::remove($input['T1']), 
                                'T2' => Hack::remove($input['T2']), 
                                'B1' => Request::post('B1', null) 
                            ];

                            if (DB::table('users_extend')->select('uid')->where('uid', $input['uid'])->count() == 1) {
                                DB::table('users_extend')->where('uid', $input['uid'])->update($data);
                            } else {
                                DB::table('users_extend')->insert($data);
                            }

                            if (Request::post('pass', false)) {
                                Session::set('message', ['type' => 'info', 'text' => __d('users', 'Votre compte a été mis à jour avec success. Veuillez vous reconecter')]);
                                
                                // mess_logout desactivé.
                                $this->logout(false);
                            } else {
                                Session::set('message', ['type' => 'success', 'text' => __d('users', 'Votre compte a été mis à jour avec success.')]);

                                Url::redirect('user?op=dashboard#message');
                            }
                        } else {
                            $this->set('error_check',   true);
                            $this->set('stop_message',  $stop);
                        }
                    }
                } else {
                    Url::redirect('index');
                }

            } else {
                Url::redirect('index');
            }
        } else {
            Session::set('message', ['type' => 'danger', 'text' => __d('users', 'Non autorié a modifier votre profile.')]);

            Url::redirect('user?op=dashboard#message'); 
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $input
     * @return void
     */
    private function white_users_bad_mail($input)
    {
        $path = module_path("Users/storage/users_private/usersbadmail.txt");

        $contents   = '';
        $filename   = $path;
        $handle     = fopen($filename, "r");

        if (filesize($filename) > 0) {
            $contents = fread($handle, filesize($filename));
        }

        fclose($handle);

        $re     = '/#' . $input['uid'] . '\|(\d+)/m';
        $maj    = preg_replace($re, '', $contents);
        $file   = fopen($path, 'w');

        fwrite($file, $maj);
        fclose($file);
    }

}
