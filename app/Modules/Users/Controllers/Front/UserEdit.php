<?php

namespace App\Modules\Users\Controllers\Front;

use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Support\Csrf;
use Npds\Config\Config;
use Npds\Session\Session;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Sanitize;
use App\Modules\Upload\Library\Upload;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Hack;
use App\Modules\Users\Sform\SformUserEdite;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Npds\Support\Facades\Password;
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
            
            $userinfo = getusrinfo(Auth::check('user'));
        
            Theme::showimage();

            $this->set('user_edite_sform', with(new SformUserEdite($userinfo))->display());

            $this->set('user_menu', User::member_menu($userinfo));

        } else {
            Url::redirect('index');
        }
    }
    
    /**
     * [saveuser description]
     *
     * @param   [type]  $uid             [$uid description]
     * @param   [type]  $name            [$name description]
     * @param   [type]  $uname           [$uname description]
     * @param   [type]  $email           [$email description]
     * @param   [type]  $femail          [$femail description]
     * @param   [type]  $url             [$url description]
     * @param   [type]  $pass            [$pass description]
     * @param   [type]  $vpass           [$vpass description]
     * @param   [type]  $bio             [$bio description]
     * @param   [type]  $user_avatar     [$user_avatar description]
     * @param   [type]  $user_occ        [$user_occ description]
     * @param   [type]  $user_from       [$user_from description]
     * @param   [type]  $user_intrest    [$user_intrest description]
     * @param   [type]  $user_sig        [$user_sig description]
     * @param   [type]  $user_viewemail  [$user_viewemail description]
     * @param   [type]  $attach          [$attach description]
     * @param   [type]  $usend_email     [$usend_email description]
     * @param   [type]  $uis_visible     [$uis_visible description]
     * @param   [type]  $user_lnl        [$user_lnl description]
     * @param   [type]  $C1              [$C1 description]
     * @param   [type]  $C2              [$C2 description]
     * @param   [type]  $C3              [$C3 description]
     * @param   [type]  $C4              [$C4 description]
     * @param   [type]  $C5              [$C5 description]
     * @param   [type]  $C6              [$C6 description]
     * @param   [type]  $C7              [$C7 description]
     * @param   [type]  $C8              [$C8 description]
     * @param   [type]  $M1              [$M1 description]
     * @param   [type]  $M2              [$M2 description]
     * @param   [type]  $T1              [$T1 description]
     * @param   [type]  $T2              [$T2 description]
     * @param   [type]  $B1              [$B1 description]
     * @param   [type]  $MAX_FILE_SIZE   [$MAX_FILE_SIZE description]
     * @param   [type]  $raz_avatar      [$raz_avatar description]
     *
     * @return  [type]                   [return description]
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
                            $user_avatar = $this->user_avatar_update($input);
            
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
                                    'name'            => $input['name'], 
                                    'email'           => $input['email'], 
                                    'femail'          => Hack::remove($input['femail']), 
                                    'url'             => Hack::remove($input['url']), 
                                    'pass'            => $checkpass = Password::crypt($input), 
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
                                    'name'            => $input['name'], 
                                    'email'           => $input['email'], 
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

                                Url::redirect('user/edituser');
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

            Url::redirect('user'); 
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

    /**
     * Undocumented function
     *
     * @param [type] $input
     * @return void
     */
    private function user_avatar_update($input)
    {
        $user_avatar    = Request::post('user_avatar', false);
        $raz_avatar     = Request::post('raz_avatar', false);
        
        if (!Request::post('B1', false)) {

            $rep = !empty($DOCUMENTROOT = Config::get('upload.config.DOCUMENTROOT')) ? $DOCUMENTROOT : $_SERVER['DOCUMENT_ROOT'];

            $upload = new Upload();

            $upload->maxupload_size = $input['MAX_FILE_SIZE'];
            $field1_filename = trim($upload->getFileName("B1"));

            $suffix = strtoLower(substr(strrchr($field1_filename, '.'), 1));

            $racine = Config::get('upload.config.racine');

            $user_dir = $racine . 'app/Modules/Users/storage/users_private/' . $input['uname'] . '/';

            if (($suffix == 'gif') or ($suffix == 'jpg') or ($suffix == 'png') or ($suffix == 'jpeg')) {

                $field1_filename = Hack::remove(preg_replace('#[/\\\:\*\?"<>|]#i', '', rawurldecode($field1_filename)));
                $field1_filename = preg_replace('#\.{2}|config.php|/etc#i', '', $field1_filename);

                if ($field1_filename) {
                    if (Config::get('upload.config.autorise_upload_p')) {

                        if (!is_dir($rep . $user_dir)) {
                            @umask("0000");

                            if (@mkdir($rep . $user_dir, 0777)) {
                                $fp = fopen($rep . $user_dir . 'index.html', 'w');
                                fclose($fp);
                            } else {
                                $user_dir = $racine . 'app/Modules/Users/storage/users_private/';
                            }
                        }
                    } else {
                        $user_dir = $racine . 'app/Modules/Users/storage/users_private/';
                    }

                    if ($upload->saveAs($input['uname'] . '.' . $suffix, $rep . $user_dir, 'B1', true)) {

                        $user_avatar_url    = 'modules/users/storage/users_private/' . $input['uname'] . '/';
                        $user_avatar        = site_url($user_avatar_url . $input['uname'] . '.' . $suffix);
                        $img_size           = @getimagesize($rep . $user_dir . $input['uname'] . '.' . $suffix);

                        $avatar_limit       = explode("*", Config::get('npds.avatar_size', '80*100'));

                        if (($img_size[0] > $avatar_limit[0]) or ($img_size[1] > $avatar_limit[1])) {
                            $raz_avatar = true;
                        }
                    }
                }
            }
        }

        if ($raz_avatar) {

            if (strstr($user_avatar, '/users_private')) {
                if(!empty($user_avatar)) {
                    @unlink($rep. $user_dir. $input['uname'] . '.' . strtoLower(substr(strrchr($user_avatar, '.'), 1)));
                }
            }

            $user_avatar = 'blank.gif';
        }
        
        return $user_avatar;
    }

}
