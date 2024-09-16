<?php

namespace App\Modules\Users\Controllers\Front;


use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Hack;
use App\Modules\Npds\Support\Facades\Crypt;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Npds\Support\Facades\Mailer;
use App\Modules\Npds\Support\Facades\Password;

/**
 * [UserLogin description]
 */
class UserPassword extends FrontController
{

    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();

        //     case 'forgetpassword':
        //         ForgetPassword();
        //         break;
        
        //     case "mailpasswd":
        //         if ($uname != '' and $code != '') {
        //             if (strlen($code) >= Config::get('npds.minpass'))
        //                 mail_password($uname, $code);
        //             else
        //                 message_error("<i class=\"fa fa-exclamation\"></i>&nbsp;" . translate("Mot de passe erroné, refaites un essai.") . "<br /><br />", "");
        //         } else
        //             main($user);
        //         break;
        
        //     case 'validpasswd':
        //         if ($code != '')
        //             valid_password($code);
        //         else
        //             main($user);
        //         break;
        
        //     case 'updatepasswd':
        //         if ($code != '' and $passwd != '')
        //             update_password($code, $passwd);
        //         else
        //             main($user);
        //         break;        
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
    public function ForgetPassword()
    {
        echo '
        <h2 class="mb-3">' . translate("Utilisateur") . '</h2>
        <div class="card card-body">
            <div class="alert alert-danger lead"><i class="fa fa-exclamation me-2"></i>' . translate("Vous avez perdu votre mot de passe ?") . '</div>
            <div class="alert alert-success"><i class="fa fa-exclamation me-2"></i>' . translate("Pas de problème. Saisissez votre identifiant et le nouveau mot de passe que vous souhaitez utiliser puis cliquez sur envoyer pour recevoir un Email de confirmation.") . '</div>
            <form id="forgetpassword" action="user.php" method="post">
                <div class="row g-2">
                    <div class="col-sm-6 ">
                    <div class="mb-3 form-floating">
                        <input type="text" class="form-control" name="uname" id="inputuser" placeholder="' . translate("Identifiant") . '" required="required" />
                        <label for="inputuser">' . translate("Identifiant") . '</label>
                    </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="mb-3 form-floating">
                        <input type="password" class="form-control" name="code" id="inputpassuser" placeholder="' . translate("Mot de passe") . '" required="required" />
                        <label for="inputpassuser">' . translate("Mot de passe") . '</label>
                    </div>
                    <div class="progress" style="height: 0.4rem;">
                        <div id="passwordMeter_cont" class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    </div>
                </div>
                <input type="hidden" name="op" value="mailpasswd" />
                <input class="btn btn-primary btn-lg my-3" type="submit" value ="' . translate("Envoyer") . '" />
            </form>
        </div>';
    
        $fv_parametres = '
            code: {
                validators: {
                    checkPassword: {
                    message: "Le mot de passe est trop simple."
                    },
                }
            },';
    
        $arg1 = '
            var formulid = ["forgetpassword"];';
    
        Css::adminfoot('fv', $fv_parametres, $arg1, 'foo');
    }
    
    /**
     * [mail_password description]
     *
     * @param   [type]  $uname  [$uname description]
     * @param   [type]  $code   [$code description]
     *
     * @return  [type]          [return description]
     */
    public function mail_password($uname, $code)
    {
        
    
        $uname = Hack::remove(stripslashes(htmlspecialchars(urldecode($uname), ENT_QUOTES, cur_charset)));
    
        $result = sql_query("SELECT uname, email, pass FROM users WHERE uname='$uname'");
        $tmp_result = sql_fetch_row($result);
    
        if (!$tmp_result) {
            User::message_error(translate("Désolé, aucune information correspondante pour cet utlilisateur n'a été trouvée") . "<br /><br />", '');
        } else {
            $host_name = Request::getip();
            list($uname, $email, $pass) = $tmp_result;
    
            // On envoie une URL avec dans le contenu : username, email, le MD5 du passwd retenu et le timestamp
            $url = "Config::get('npds.nuke_url')/user.php?op=validpasswd&code=" . urlencode(Crypt::encrypt($uname) . "#fpwd#" . Crypt::encryptK($email . "#fpwd#" . $code . "#fpwd#" . time(), $pass));
    
            $message = translate("Le compte utilisateur") . ' ' . $uname . ' ' . translate("at") . ' ' . Config::get('npds.sitename') . ' ' . translate("est associé à votre Email.") . "\n\n";
            $message .= translate("Un utilisateur web ayant l'adresse IP ") . " $host_name " . translate("vient de demander une confirmation pour changer de mot de passe.") . "\n\n" . translate("Votre url de confirmation est :") . " <a href=\"$url\">$url</a> \n\n" . translate("Si vous n'avez rien demandé, ne vous inquiétez pas. Effacez juste ce Email. ") . "\n\n";
            
            include("signat.php");
    
            $subject = translate("Confirmation du code pour") . ' ' . $uname;
    
            Mailer::send_email($email, $subject, $message, '', true, 'html', '');
            User::message_pass('<div class="alert alert-success lead text-center"><i class="fa fa-exclamation"></i>&nbsp;' . translate("Confirmation du code pour") . ' ' . $uname . ' ' . translate("envoyée par courrier.") . '</div>');
            
            Ecr_Log('security', 'Lost_password_request : ' . $uname, '');
        }
    }
    
    /**
     * [valid_password description]
     *
     * @param   [type]  $code  [$code description]
     *
     * @return  [type]         [return description]
     */
    public function valid_password($code)
    {
        
    
        $ibid = explode("#fpwd#", $code);
    
        $result = sql_query("SELECT email,pass FROM users WHERE uname='" . Crypt::decrypt($ibid[0]) . "'");
        list($email, $pass) = sql_fetch_row($result);
    
        if ($email != '') {
            $ibid = explode("#fpwd#", Crypt::decryptK($ibid[1], $pass));
    
            if ($email == $ibid[0]) {
                echo '
                <p class="lead">' . translate("Vous avez perdu votre mot de passe ?") . '</p>
                <div class="card border rounded p-3">
                    <div class="row">
                        <div class="col-sm-7">
                        <div class="blockquote">' . translate("Pour valider votre nouveau mot de passe, merci de le re-saisir.") . '<br />' . translate("Votre mot de passe est : ") . ' <strong>' . $ibid[1] . '</strong></div>
                        </div>
                        <div class="col-sm-5">
                        <form id="lostpassword" action="user.php" method="post">
                            <div class="mb-3 row">
                                <label class="col-form-label col-sm-12" for="passwd">' . translate("Mot de passe") . '</label>
                                <div class="col-sm-12">
                                    <input type="password" class="form-control" name="passwd" placeholder="' . $ibid[1] . '" required="required" />
                                </div>
                            </div>
                            <input type="hidden" name="op" value="updatepasswd" />
                            <input type="hidden" name="code" value="' . $code . '" />
                            <div class="mb-3 row">
                                <div class="col-sm-12">
                                    <input class="btn btn-primary" type="submit" value="' . translate("Valider") . '" />
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>';
            } else {
                User::message_pass('<div class="alert alert-danger lead text-center">' . translate("Erreur") . '</div>');
                Ecr_Log('security', 'Lost_password_valid NOK Mail not match : ' . $ibid[0], '');
            }
        } else {
            User::message_pass('<div class="alert alert-danger lead text-center">' . translate("Erreur") . '</div>');
            Ecr_Log('security', 'Lost_password_valid NOK Bad hash : ' . $ibid[0], '');
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
    public function update_password($code, $passwd)
    {
        
    
        $ibid = explode("#fpwd#", $code);
        $uname = urlencode(Crypt::decrypt($ibid[0]));
    
        $result = sql_query("SELECT email,pass FROM users WHERE uname='$uname'");
        list($email, $pass) = sql_fetch_row($result);
    
        if ($email != '') {
            $ibid = explode("#fpwd#", Crypt::decryptK($ibid[1], $pass));
    
            if ($email == $ibid[0]) {
                // Le lien doit avoir été généré dans les 24H00
                if ((time() - $ibid[2]) < 86400) {
                    // le mot de passe est-il identique
                    if ($ibid[1] == $passwd) {
                        $AlgoCrypt = PASSWORD_BCRYPT;
                        $min_ms = 250;
                        $options = ['cost' => Password::getOptimalBcryptCostParameter($ibid[1], $AlgoCrypt, $min_ms),];
                        $hashpass = password_hash($ibid[1], $AlgoCrypt, $options);
                        $cryptpass = crypt($ibid[1], $hashpass);
    
                        sql_query("UPDATE users SET pass='$cryptpass', hashkey='1' WHERE uname='$uname'");
    
                        User::message_pass('<div class="alert alert-success lead text-center"><a class="alert-link" href="user.php"><i class="fa fa-exclamation me-2"></i>' . translate("Mot de passe mis à jour. Merci de vous re-connecter") . '<i class="fas fa-sign-in-alt fa-lg ms-2"></i></a></div>');
                        Ecr_Log('security', 'Lost_password_update OK : ' . $uname, '');
                    } else {
                        User::message_pass('<div class="alert alert-danger lead text-center">' . translate("Erreur") . ' : ' . translate("Les mots de passe sont différents. Ils doivent être identiques.") . '</div>');
                        Ecr_Log('security', 'Lost_password_update Password not match : ' . $uname, '');
                    }
                } else {
                    User::message_pass('<div class="alert alert-danger lead text-center">' . translate("Erreur") . ' : ' . translate("Votre url de confirmation est expirée") . ' > 24 h</div>');
                    Ecr_Log('security', 'Lost_password_update NOK Time > 24H00 : ' . $uname, '');
                }
            } else {
                User::message_pass('<div class="alert alert-danger lead text-center">' . translate("Erreur : Email invalide") . '</div>');
                Ecr_Log('security', 'Lost_password_update NOK Mail not match : ' . $uname, '');
            }
        } else {
            User::message_pass('<div class="alert alert-danger lead text-center">' . translate("Erreur") . '</div>');
            Ecr_Log('security', 'Lost_password_update NOK Empty Mail or bad user : ' . $uname, '');
        }
    }

}