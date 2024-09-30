<?php

namespace App\Modules\Users\Controllers\Front;

use Npds\view\View;
use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Session\Session;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Secure;
use App\Modules\Users\Sform\SformUserNew;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Hack;
use App\Modules\Npds\Support\Facades\Spam;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Npds\Support\Facades\Mailer;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Npds\Support\Facades\Language;
use App\Modules\Npds\Support\Facades\Metalang;
use App\Modules\Npds\Support\Facades\Password;
use App\Modules\Users\Sform\SformUserNewFinish;
use App\Modules\Users\Sform\SformUserNewFinishHidden;

/**
 * [UserLogin description]
 */
class UserNews extends FrontController
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
     * [Only_NewUser description]
     *
     * @return  [type]  [return description]
     */
    public function only_new_user()
    {
        if (Config::get('npds.CloseRegUser') == 0) {

            if (!Auth::guard('user')) {
                $this->title(__('Editer votre page principale'));
            
                $this->set('message', Session::message('message'));

                Theme::showimage();

                $this->set('user_new_sform', with(new SformUserNew())->display());
            } else {
                Url::redirect('user');
            }
        } else {
            // if (file_exists("storage/static/closed.txt"))
            //     include("storage/static/closed.txt");
            Url::redirect('site/closed'); // Note a finaliser !!!!
        }
    }

    /**
     * [confirmNewUser description]
     *
     * @param   [type]  $uname           [$uname description]
     * @param   [type]  $name            [$name description]
     * @param   [type]  $email           [$email description]
     * @param   [type]  $user_avatar     [$user_avatar description]
     * @param   [type]  $user_occ        [$user_occ description]
     * @param   [type]  $user_from       [$user_from description]
     * @param   [type]  $user_intrest    [$user_intrest description]
     * @param   [type]  $user_sig        [$user_sig description]
     * @param   [type]  $user_viewemail  [$user_viewemail description]
     * @param   [type]  $pass            [$pass description]
     * @param   [type]  $vpass           [$vpass description]
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
     *
     * @return  [type]                   [return description]
     */
    public function confirm_new_user()
    {
        $this->title(__('Editer votre page principale'));

        $input = Request::post();

        $uname  = strip_tags($input['uname']);
        $stop   = User::userCheck($uname, $input['email']);
    
        if (Config::get('npds.memberpass')) {
            if ((isset($input['pass'])) and ($input['pass'] != $input['vpass'])) {
                $this->set('pass_does_not_match', true);

                $stop = __d('users', 'Les mots de passe sont différents. Ils doivent être identiques.');
            
            } elseif (strlen($input['pass']) < Config::get('npds.minpass')) {
                $this->set('error_minpass', true);

                $stop = __d('users', 'Désolé, votre mot de passe doit faire au moins <strong>{0}</strong> caractères', Config::get('npds.minpass'));
            }
        }
        
        if (!$stop) {
            $this->set('user_new_confirme_sform', with(new SformUserNewFinish())->display());

            $this->set('user_new_hidden_sform', with(new SformUserNewFinishHidden())->display());
        } else {
            $this->set('user_new_confirme_hidden_sform', with(new SformUserNewFinishHidden())->display());

            $this->set('stop_message', $stop);
        }
    }
    
    /**
     * [finishNewUser description]
     *
     * @param   [type]  $uname           [$uname description]
     * @param   [type]  $name            [$name description]
     * @param   [type]  $email           [$email description]
     * @param   [type]  $user_avatar     [$user_avatar description]
     * @param   [type]  $user_occ        [$user_occ description]
     * @param   [type]  $user_from       [$user_from description]
     * @param   [type]  $user_intrest    [$user_intrest description]
     * @param   [type]  $user_sig        [$user_sig description]
     * @param   [type]  $user_viewemail  [$user_viewemail description]
     * @param   [type]  $pass            [$pass description]
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
     *
     * @return  [type]                   [return description]
     */
    public function finish_new_user()
    {
        //
        Secure::ghost_form();

        $this->title(__('Editer votre page principale'));

        $input = Request::post();

        $stop = User::userCheck($input['uname'], $input['email']);

        if (!$stop) {

            $makepass = (!Config::get('npds.memberpass') ? User::makepass() : $input['pass']);

            $user_id = DB::table('users')->insert([
                'name'              => stripslashes(Hack::remove($input['name'])),
                'uname'             => stripslashes(Hack::remove($input['uname'])),
                'email'             => stripslashes(Hack::remove($input['email'])),
                'femail'            => '',
                'url'               => '',
                'user_avatar'       => stripslashes(Hack::remove($input['user_avatar'])),
                'user_regdate'      => time() + ((int) Config::get('npds.gmt') * 3600),
                'user_occ'          => stripslashes(Hack::remove($input['user_occ'])),
                'user_from'         => stripslashes(Hack::remove($input['user_from'])),
                'user_intrest'      => stripslashes(Hack::remove($input['user_intrest'])),
                'user_sig'          => stripslashes(Hack::remove($input['user_sig'])),
                'user_viewemail'    => stripslashes(Hack::remove($input['user_viewemail'])),
                'user_theme'        => '',
                'user_journal'      => '',
                'pass'              => Password::crypt($makepass),
                'hashkey'           => 1,
                'storynum'          => 10,
                'umode'             => '',
                'uorder'            => 0,
                'thold'             => 0,
                'noscore'           => 0,
                'bio'               => '',
                'ublockon'          => 0,
                'ublock'            => '',
                'theme'             => Config::get('npds.Default_Theme'). '+' .Config::get('npds.Default_Skin'),
                'commentmax'        => 10,
                'counter'           => 0,
                'send_email'        => 0,
                'is_visible'        => 1,
                'mns'               => 0,
                'user_langue'       => '',
                'user_lastvisit'    => '',
                'user_lnl'          => $input['user_lnl'],             
            ]);

            DB::table('users_extend')->insert([
                'uid'    => $user_id,
                'C1'     => Request::post('C1', null),
                'C2'     => Request::post('C2', null),
                'C3'     => Request::post('C3', null),
                'C4'     => Request::post('C4', null),
                'C5'     => Request::post('C5', null),
                'C6'     => Request::post('C6', null),
                'C7'     => Request::post('C7', null),
                'C8'     => Request::post('C8', null),
                'M1'     => Request::post('M1', null),
                'M2'     => Request::post('M2', null),
                'T1'     => Request::post('T1', null),
                'T2'     => Request::post('T2', null),
                'B1'     => Request::post('B1', null),
            ]);

            $AutoRegUser = Config::get('npds.AutoRegUser');

            if (($AutoRegUser == 1) or (!isset($AutoRegUser))) {
                DB::table('users_status')->insert([
                    'uid'       => $user_id,
                    'posts'     => 0,
                    'attachsig' => ($input['user_sig'] ? 1 : 0),
                    'rang'      => 0,
                    'level'     => 1,
                    'open'      => 1,
                    'groupe'    => '',
                ]);
            } else {
                DB::table('users_status')->insert([
                    'uid'       => $user_id,
                    'posts'     => 0,
                    'attachsig' => ($input['user_sig'] ? 1 : 0),
                    'rang'      => 0,
                    'level'     => 1,
                    'open'      => 0,
                    'groupe'    => '',
                ]);                
            }

            if ($user_id) {
                if (Config::get('npds.memberpass')) {

                    $this->set('utilisateur_info', true);
                    $this->set('uname', $input['uname']);
                    $this->set('makepass', $makepass);
                    $this->set('makepass_url_encode', urlencode($makepass));

                    $message = __d('users', 'Bienvenue sur') . Config::get('npds.sitename') ."!\n\n" 
                             . __d('users', 'Vous, ou quelqu\'un d\'autre, a utilisé votre Email identifiant votre compte') . " 
                             (". $input['email'] .") " . __d('users', 'pour enregistrer un compte sur') . Config::get('npds.sitename')."\n\n" 

                             . __d('users', 'Informations sur l\'utilisateur :') . " : \n\n";
                             
                    $message .=
                        __d('users', 'ID utilisateur (pseudo)') . ' : ' . $input['uname'] . "\n" .
                        __d('users', 'Véritable adresse Email') . ' : ' . $input['email'] . "\n";
    
                    if ($input['name'] != '') {
                        $message .= __d('users', 'Votre véritable identité') . ' : ' . $input['name'] . "\n";
                    }

                    if ($input['user_from'] != '') {
                        $message .= __d('users', 'Votre situation géographique') . ' : ' . $input['user_from'] . "\n";
                    }

                    if ($input['user_occ'] != '') {
                        $message .= __d('users', 'Votre activité') . ' : ' . $input['user_occ'] . "\n";
                    }

                    if ($input['user_intrest'] != '') {
                        $message .= __d('users', 'Vos centres d\'intérêt') . ' : ' . $input['user_intrest'] . "\n";
                    }

                    if ($input['user_sig'] != '') {
                        $message .= __d('users', 'Signature') . ' : ' . $input['user_sig'] . "\n";
                    }

                    if (isset($input['C1']) and $input['C1'] != '') {
                        $message .= Language::aff_langue('[french]Activit&#x00E9; professionnelle[/french][english]Professional activity[/english][spanish]Actividad profesional[/spanish][german]Berufliche T&#xE4;tigkeit[/german]') . ' : ' . $input['C1'] . "\n";
                    }

                    if (isset($input['C2']) and $input['C2'] != '') {
                        $message .= Language::aff_langue('[french]Code postal[/french][english]Postal code[/english][spanish]C&#xF3;digo postal[/spanish][german]Postleitzahl[/german]') . ' : ' . $input['C2'] . "\n";
                    }

                    if (isset($input['T1']) and $input['T1'] != '') {
                        $message .= Language::aff_langue('[french]Date de naissance[/french][english]Birth date[/english][spanish]Fecha de nacimiento[/spanish][german]Geburtsdatum[/german]') . ' : ' . $input['T1'] . "\n";
                    }

                    $message .= "\n\n\n" . Language::aff_langue("[french]Conform&eacute;ment aux articles 38 et suivants de la loi fran&ccedil;aise n&deg; 78-17 du 6 janvier 1978 relative &agrave; l'informatique, aux fichiers et aux libert&eacute;s, tout membre dispose d&rsquo; un droit d&rsquo;acc&egrave;s, peut obtenir communication, rectification et/ou suppression des informations le concernant.[/french][english]In accordance with Articles 38 et seq. Of the French law n &deg; 78-17 of January 6, 1978 relating to data processing, files and freedoms, any member has a right of access, can obtain communication, rectification and / or deletion of information about him.[/english][chinese]&#26681;&#25454;1978&#24180;1&#26376;6&#26085;&#20851;&#20110;&#25968;&#25454;&#22788;&#29702;&#65292;&#26723;&#26696;&#21644;&#33258;&#30001;&#30340;&#27861;&#22269;78-17&#21495;&#27861;&#24459;&#65292;&#20219;&#20309;&#25104;&#21592;&#37117;&#26377;&#26435;&#36827;&#20837;&#65292;&#21487;&#20197;&#33719;&#24471;&#36890;&#20449;&#65292;&#32416;&#27491;&#21644;/&#25110; &#21024;&#38500;&#26377;&#20851;&#20182;&#30340;&#20449;&#24687;&#12290;[/chinese][spanish]De conformidad con los art&iacute;culos 38 y siguientes de la ley francesa n &deg; 78-17 del 6 de enero de 1978, relativa al procesamiento de datos, archivos y libertades, cualquier miembro tiene derecho de acceso, puede obtener comunicaci&oacute;n, rectificaci&oacute;n y / o supresi&oacute;n de informaci&oacute;n sobre &eacute;l.[/spanish][german]Gem&auml;&szlig; den Artikeln 38 ff. Des franz&ouml;sischen Gesetzes Nr. 78-17 vom 6. Januar 1978 in Bezug auf Datenverarbeitung, Akten und Freiheiten hat jedes Mitglied ein Recht auf Zugang, kann Kommunikation, Berichtigung und / oder L&ouml;schung von Informationen &uuml;ber ihn.[/german]");
                    $message .= "\n\n\n" . Language::aff_langue("[french]Ce message et les pi&egrave;ces jointes sont confidentiels et &eacute;tablis &agrave; l'attention exclusive de leur destinataire (aux adresses sp&eacute;cifiques auxquelles il a &eacute;t&eacute; adress&eacute;). Si vous n'&ecirc;tes pas le destinataire de ce message, vous devez imm&eacute;diatement en avertir l'exp&eacute;diteur et supprimer ce message et les pi&egrave;ces jointes de votre syst&egrave;me.[/french][english]This message and any attachments are confidential and intended to be received only by the addressee. If you are not the intended recipient, please notify immediately the sender by reply and delete the message and any attachments from your system.[/english][chinese]&#27492;&#28040;&#24687;&#21644;&#20219;&#20309;&#38468;&#20214;&#37117;&#26159;&#20445;&#23494;&#30340;&#65292;&#24182;&#19988;&#25171;&#31639;&#30001;&#25910;&#20214;&#20154;&#25509;&#25910;&#12290; &#22914;&#26524;&#24744;&#19981;&#26159;&#39044;&#26399;&#25910;&#20214;&#20154;&#65292;&#35831;&#31435;&#21363;&#36890;&#30693;&#21457;&#20214;&#20154;&#24182;&#22238;&#22797;&#37038;&#20214;&#21644;&#31995;&#32479;&#20013;&#30340;&#25152;&#26377;&#38468;&#20214;&#12290;[/chinese][spanish]Este mensaje y cualquier adjunto son confidenciales y est&aacute;n destinados a ser recibidos por el destinatario. Si no es el destinatario deseado, notif&iacute;quelo al remitente de inmediato y responda al mensaje y cualquier archivo adjunto de su sistema.[/spanish][german]Diese Nachricht und alle Anh&auml;nge sind vertraulich und sollen vom Empf&auml;nger empfangen werden. Wenn Sie nicht der beabsichtigte Empf&auml;nger sind, benachrichtigen Sie bitte sofort den Absender und antworten Sie auf die Nachricht und alle Anlagen von Ihrem System.[/german]") . "\n\n\n";
                    
                    $message .= Config::get('signat.message');
                    
                    $subject = html_entity_decode(__d('users', 'Inscription'), ENT_COMPAT | ENT_HTML401, 'utf-8') . ' ' . $input['uname'];
                    
                    Mailer::send_email($input['email'], $subject, $message, '', true, 'html', '');
                } else {

                    $this->set('inscription_mail', true);

                    $message = __d('users', 'Bienvenue sur') . Config::get('npds.sitename') ." !\n\n" . __d('users', 'Vous, ou quelqu\'un d\'autre, a utilisé votre Email identifiant votre compte') . " (". $input['email'] .") " . __d('users', 'pour enregistrer un compte sur') . Config::get('npds.sitename') .".\n\n" . __d('users', 'Informations sur l\'utilisateur :') . "\n" . __d('users', '-Identifiant : ') . " ". $input['uname'] ."\n" . __d('users', '-Mot de passe : ') . " $makepass\n\n";
                    
                    $message .= Config::get('signat.message');
                    
                    $subject = html_entity_decode(__d('users', 'Mot de passe utilisateur pour'), ENT_COMPAT | ENT_HTML401, 'utf-8') . ' ' . $input['uname'];
                    
                    Mailer::send_email($input['email'], $subject, $message, '', true, 'html', '');
                }
    
                if (Config::has('users.new_user')) {
                    DB::table('priv_msgs')->insert([
                        'msg_image'     => '',
                        'subject'       => Config::get('users.new_user.subject'),
                        'from_userid'   => Config::get('users.new_user.emetteur_id'),
                        'to_userid'     => $user_id,
                        'msg_time'      => date(__d('users', 'd-m-Y H:i'), time() + ((int) Config::get('npds.gmt') * 3600)),
                        'msg_text'      => Metalang::meta_lang(AddSlashes(str_replace("\n", "<br />", Config::get('users.new_user.message')))),
                    ]);
                }
    
                $subject = html_entity_decode(__d('users', 'Inscription'), ENT_COMPAT | ENT_HTML401, 'utf-8') . ' : ' . Config::get('npds.sitename');
                
                Mailer::send_email(Config::get('npds.adminmail'), $subject, 'Infos :
                    Nom     : '. $input['name'] .'
                    ID      : '. $input['uname'] .'
                    Email   : '. $input['email'], 
                    '', 
                    false, 
                    "text", 
                    ''
                );
            }
    
        } else {
            $this->set('stop_message', $stop);
        }
    }

}
