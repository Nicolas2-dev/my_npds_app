<?php

namespace App\Modules\Users\Controllers\Front;

use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Session\Session;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Hack;
use App\Modules\Npds\Support\Facades\Crypt;
use App\Modules\Npds\Support\Facades\Mailer;
use App\Modules\Npds\Support\Facades\Password;
use App\Modules\Users\Validator\ValidatorUserForgetPassword;

/**
 * [UserLogin description]
 */
class UserPassword extends FrontController
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
     * [ForgetPassword description]
     *
     * @return  [type]  [return description]
     */
    public function forget_password()
    {
        $this->title(__('Vous avez perdu votre mot de passe'));
            
        $this->set('message', Session::message('message'));

        with(new ValidatorUserForgetPassword())->display();
    }
    
    /**
     * [mail_password description]
     *
     * @param   [type]  $uname  [$uname description]
     * @param   [type]  $code   [$code description]
     *
     * @return  [type]          [return description]
     */
    public function mail_password()
    {
        $this->title(__('Vous avez perdu votre mot de passe'));
            
        $input = Request::post();

        if ($input['uname'] != '' and $input['code'] != '') {
            if (strlen($input['code']) >= Config::get('npds.minpass')) {

                $__uname = Hack::remove(stripslashes(htmlspecialchars(urldecode($input['uname']), ENT_QUOTES, 'utf-8')));
            
                $user_temp = DB::table('users')->select('uname', 'email', 'pass')->where('uname', $__uname)->first();

                if (!$user_temp) {
                    $this->set('error_not_user', true);
                } else {
                    $host_name = Request::getip();

                    // On envoie une URL avec dans le contenu : username, email, le MD5 du passwd retenu et le timestamp
                    $url = site_url("user/validpasswd?code=" . urlencode(Crypt::encrypt($user_temp['uname']) . "#fpwd#" . Crypt::encryptK($user_temp['email'] . "#fpwd#" . $input['code'] . "#fpwd#" . time(), $user_temp['pass'])));
            
                    $message = __d('users', 'Le compte utilisateur') . ' ' . $user_temp['uname'] . ' ' . __d('users', 'at') . ' ' . Config::get('npds.sitename') . ' ' . __d('users', 'est associé à votre Email.') . "\n\n";
                    $message .= __d('users', 'Un utilisateur web ayant l\'adresse IP ') . " $host_name " . __d('users', 'vient de demander une confirmation pour changer de mot de passe.') . "\n\n" . __d('users', 'Votre url de confirmation est :') . " <a href=\"$url\">$url</a> \n\n" . __d('users', 'Si vous n\'avez rien demandé, ne vous inquiétez pas. Effacez juste ce Email. ') . "\n\n";
                    
                    $message .= Config::get('signat.message');
            
                    $subject = __d('users', 'Confirmation du code pour') . ' ' . $user_temp['uname'];
            
                    Mailer::send_email($user_temp['email'], $subject, $message, '', true, 'html', '');
                    
                    $this->set('message_pass', true);
                    $this->set('uname', $user_temp['uname']);

                    Ecr_Log('security', 'Lost_password_request : ' . $user_temp['uname'], '');
                }
            } else {
                $this->set('error_pass', true);
            }
        } else {
            Url::redirect('user/login'); 
        }
    }
    
    /**
     * [valid_password description]
     *
     * @param   [type]  $code  [$code description]
     *
     * @return  [type]         [return description]
     */
    public function valid_password()
    {
        $this->title(__('Vous avez perdu votre mot de passe'));

        $code = Request::query('code');

        if ($code != '') {

            $ibid = explode("#fpwd#", $code);
        
            $user_temp = DB::table('users')->select('email', 'pass')->where('uname', Crypt::decrypt($ibid[0]))->first();

            if ($user_temp['email'] != '') {
                $ibid = explode("#fpwd#", Crypt::decryptK($ibid[1], $user_temp['pass']));
        
                if ($user_temp['email'] == $ibid[0]) {    
                    $this->set('not_match', false);
                    $this->set('user_mail', $user_temp['email']);
                    $this->set('ibid', $ibid);
                    $this->set('code', $code);

                } else {
                    // not_match
                    $this->set('not_match', true);

                    Ecr_Log('security', 'Lost_password_valid NOK Mail not match : ' . $ibid[0], '');
                }
            } else {
                // dad_hash
                $this->set('not_match', true);

                Ecr_Log('security', 'Lost_password_valid NOK Bad hash : ' . $ibid[0], '');
            }
        } else {
            Url::redirect('user/login'); 
        }
    }
    
    /**
     * [update_password description]
     *
     * @param   [type]  $code    [$code description]
     * @param   [type]  $passwd  [$passwd description]
     *
     * @return  [type]           [return description]
     */
    public function update_password()
    {
        if (Request::post('code') != '' and Request::post('passwd') != '') {

            $ibid   = explode("#fpwd#", Request::post('code'));

            $user_temp = DB::table('users')->select('email', 'pass')->where('uname', $uname = urlencode(Crypt::decrypt($ibid[0])))->first();

            if ($user_temp['email'] != '') {
                $ibid = explode("#fpwd#", Crypt::decryptK($ibid[1], $user_temp['pass']));
        
                if ($user_temp['email'] == $ibid[0]) {

                    // Le lien doit avoir été généré dans les 24H00
                    if ((time() - $ibid[2]) < 86400) {

                        // le mot de passe est-il identique
                        if ($ibid[1] == Request::post('passwd')) {

                            DB::table('users')->where('uname', $uname)->update([
                                'pass'      => Password::crypt($ibid[1]),
                                'hashkey'   => 1
                            ]);

                            $this->set('password_update', true);
                            
                            Ecr_Log('security', 'Lost_password_update OK : ' . $uname, '');
                        } else {
                            $this->set('not_match', true);
                            
                            Ecr_Log('security', 'Lost_password_update Password not match : ' . $uname, '');
                        }
                    } else {
                        $this->set('nok_time', true);
                        
                        Ecr_Log('security', 'Lost_password_update NOK Time > 24H00 : ' . $uname, '');
                    }
                } else {
                    $this->set('mail_not_match', true);
                    
                    Ecr_Log('security', 'Lost_password_update NOK Mail not match : ' . $uname, '');
                }
            } else {
                $this->set('mail_bad_user', true);
                
                Ecr_Log('security', 'Lost_password_update NOK Empty Mail or bad user : ' . $uname, '');
            }
        } else {
            Url::redirect('user/login'); 
        }
    }

}
