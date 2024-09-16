<?php

namespace App\Modules\Users\Controllers\Front;


use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Spam;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Npds\Support\Facades\Mailer;
use App\Modules\Npds\Support\Facades\Language;
use App\Modules\Npds\Support\Facades\Metalang;
use App\Modules\Npds\Support\Facades\Password;

/**
 * [UserLogin description]
 */
class UserNews extends FrontController
{

    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();

        //     case 'only_newuser':
        
        //         if (Config::get('npds.CloseRegUser') == 0)
        //             Only_NewUser();
        //         else {
        //             include("header.php");
        
        //             if (file_exists("storage/static/closed.txt"))
        //                 include("storage/static/closed.txt");
        
        //             include("footer.php");
        //         }

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
    public function Only_NewUser()
    {
        global $user;

        if (Config::get('npds.CloseRegUser') == 0) {

            if (!$user) {
                global $uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $vpass, $C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1;
                        
                User::showimage();
        
                echo '
                <div>
                <h2 class="mb-3">' . translate("Utilisateur") . '</h2>
                <div class="card card-body mb-3">
                    <h3>' . translate("Notes") . '</h3>
                    <p>
                    ' . translate("Les préférences de compte fonctionnent sur la base des cookies.") . ' ' . translate("Nous ne vendons ni ne communiquons vos informations personnelles à autrui.") . ' ' . translate("En tant qu'utilisateur enregistré vous pouvez") . ' : 
                        <ul>
                            <li>' . translate("Poster des commentaires signés") . '</li>
                            <li>' . translate("Proposer des articles en votre nom") . '</li>
                            <li>' . translate("Disposer d'un bloc que vous seul verrez dans le menu (pour spécialistes, nécessite du code html)") . '</li>
                            <li>' . translate("Télécharger un avatar personnel") . '</li>
                            <li>' . translate("Sélectionner le nombre de news que vous souhaitez voir apparaître sur la page principale.") . '</li>
                            <li>' . translate("Personnaliser les commentaires") . '</li>
                            <li>' . translate("Choisir un look différent pour le site (si plusieurs proposés)") . '</li>
                            <li>' . translate("Gérer d'autres options et applications") . '</li>
                        </ul>
                    </p>';
        
                if (!Config::get('npds.memberpass')) {
                    echo '<div class="alert alert-success lead"><i class="fa fa-exclamation me-2"></i>' . translate("Le mot de passe vous sera envoyé à l'adresse Email indiquée.") . '</div>';
                }

                echo '
                </div>
                <div class="card card-body mb-3">';
        
                include("library/sform/extend-user/extend-user.php");
        
                echo '</div>';
        
                adminfoot('fv', $fv_parametres, $arg1, 'foo');
            } else {
                header("location: user.php");
            }
        } else {
            if (file_exists("storage/static/closed.txt"))
                include("storage/static/closed.txt");
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
    public function confirmNewUser($uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $vpass, $user_lnl, $C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1)
    {
        $uname = strip_tags($uname);
    
        if ($user_viewemail != 1) {
            $user_viewemail = '0';
        }
    
        $stop = User::userCheck($uname, $email);
    
        if (Config::get('npds.memberpass')) {
            if ((isset($pass)) and ($pass != $vpass)) {
                $stop = '<i class="fa fa-exclamation me-2"></i>' . translate("Les mots de passe sont différents. Ils doivent être identiques.");
            } elseif (strlen($pass) < Config::get('npds.minpass')) {
                $stop = '<i class="fa fa-exclamation me-2"></i>' . translate("Désolé, votre mot de passe doit faire au moins") . ' <strong>' . Config::get('npds.minpass') . '</strong> ' . translate("caractères");
            }
        }
    
        if (!$stop) {
            echo '
            <h2>' . translate("Utilisateur") . '</h2>
            <hr />
            <h3 class="mb-3"><i class="fa fa-user me-2"></i>' . translate("Votre fiche d'inscription") . '</h3>
            <div class="card">
                <div class="card-body">';
    
            include("library/sform/extend-user/aff_extend-user.php");
    
            echo '
                </div>
            </div>';
    
            User::hidden_form();
    
            global $charte;
            if (!$charte) {
                echo '
                    <div class="alert alert-danger lead mt-3">
                        <i class="fa fa-exclamation me-2"></i>' . translate("Vous devez accepter la charte d'utilisation du site") . '
                    </div>
                    <input type="hidden" name="op" value="only_newuser" />
                    <input class="btn btn-secondary mt-1" type="submit" value="' . translate("Retour en arrière") . '" />
                    </form>';
            } else {
                echo '
                    <input type="hidden" name="op" value="finish" /><br />
                    <input class="btn btn-primary mt-2" type="submit" value="' . translate("Terminer") . '" />
                    </form>';
                }
        } else {
            User::message_error($stop, "new user");
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
    public function finishNewUser($uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $user_lnl, $C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1)
    {
        global $makepass;
    
        if (!isset($_SERVER['HTTP_REFERER'])) {
            Ecr_Log('security', 'Ghost form in user.php registration. => NO REFERER', '');
            Spam::L_spambot('', "false");
    
            include('admin/die.php');
            die();
    
        } else if ($_SERVER['HTTP_REFERER'] . Config::get('npds.Npds_Key') !== Config::get('npds.nuke_url') . '/user.php' . Config::get('npds.Npds_Key')) {
            Ecr_Log('security', 'Ghost form in user.php registration. => ' . $_SERVER["HTTP_REFERER"], '');
            Spam::L_spambot('', "false");
    
            include('admin/die.php');
            die();
        }
    
        $user_regdate = time() + ((int) Config::get('npds.gmt') * 3600);
        $stop = User::userCheck($uname, $email);
    
        if (!$stop) {
    
            if (!Config::get('npds.memberpass')) {
                $makepass = User::makepass();
            } else {
                $makepass = $pass;
            }
    
            $AlgoCrypt = PASSWORD_BCRYPT;
            $min_ms = 100;
            $options = ['cost' => Password::getOptimalBcryptCostParameter($makepass, $AlgoCrypt, $min_ms)];
            $hashpass = password_hash($makepass, $AlgoCrypt, $options);
            $cryptpass = crypt($makepass, $hashpass);
            $hashkey = 1;
    
            $result = sql_query("INSERT INTO users VALUES (NULL,'$name','$uname','$email','','','$user_avatar','$user_regdate','$user_occ','$user_from','$user_intrest','$user_sig','$user_viewemail','','','$cryptpass', '1', '10','','0','0','0','','0','','Config::get('npds.Default_Theme')+Config::get('npds.Default_Skin')','10','0','0','1','0','','','$user_lnl')");
    
            list($usr_id) = sql_fetch_row(sql_query("SELECT uid FROM users WHERE uname='$uname'"));
            $result = sql_query("INSERT INTO users_extend VALUES ('$usr_id','$C1','$C2','$C3','$C4','$C5','$C6','$C7','$C8','$M1','$M2','$T1','$T2', '$B1')");
            
            $attach = $user_sig ? 1 : 0;
            
            $AutoRegUser = Config::get('npds.AutoRegUser');

            if (($AutoRegUser == 1) or (!isset($AutoRegUser))) {
                $result = sql_query("INSERT INTO users_status VALUES ('$usr_id','0','$attach','0','1','1','')");
            } else {
                $result = sql_query("INSERT INTO users_status VALUES ('$usr_id','0','$attach','0','1','0','')");
            }

            if ($result) {
                if (Config::get('npds.memberpass')) {
                    echo '
                    <h2>' . translate("Utilisateur") . '</h2>
                    <hr />
                    <h2><i class="fa fa-user me-2"></i>' . translate("Inscription") . '</h2>
                    <p class="lead">' . translate("Votre mot de passe est : ") . '<strong>' . $makepass . '</strong></p>
                    <p class="lead">' . translate("Vous pourrez le modifier après vous être connecté sur") . ' : <br /><a href="user.php?op=login&amp;uname=' . $uname . '&amp;pass=' . urlencode($makepass) . '"><i class="fas fa-sign-in-alt fa-lg me-2"></i><strong>' . Config::get('npds.sitename') . '</strong></a></p>';
    
                    $message = translate("Bienvenue sur") . Config::get('npds.sitename') ."!\n\n" . translate("Vous, ou quelqu'un d'autre, a utilisé votre Email identifiant votre compte") . " ($email) " . translate("pour enregistrer un compte sur") . Config::get('npds.sitename')."\n\n" . translate("Informations sur l'utilisateur :") . " : \n\n";
                    $message .=
                        translate("ID utilisateur (pseudo)") . ' : ' . $uname . "\n" .
                        translate("Véritable adresse Email") . ' : ' . $email . "\n";
    
                    if ($name != '') {
                        $message .= translate("Votre véritable identité") . ' : ' . $name . "\n";
                    }

                    if ($user_from != '') {
                        $message .= translate("Votre situation géographique") . ' : ' . $user_from . "\n";
                    }

                    if ($user_occ != '') {
                        $message .= translate("Votre activité") . ' : ' . $user_occ . "\n";
                    }

                    if ($user_intrest != '') {
                        $message .= translate("Vos centres d'intérêt") . ' : ' . $user_intrest . "\n";
                    }

                    if ($user_sig != '') {
                        $message .= translate("Signature") . ' : ' . $user_sig . "\n";
                    }

                    if (isset($C1) and $C1 != '') {
                        $message .= Language::aff_langue('[french]Activit&#x00E9; professionnelle[/french][english]Professional activity[/english][spanish]Actividad profesional[/spanish][german]Berufliche T&#xE4;tigkeit[/german]') . ' : ' . $C1 . "\n";
                    }

                    if (isset($C2) and $C2 != '') {
                        $message .= Language::aff_langue('[french]Code postal[/french][english]Postal code[/english][spanish]C&#xF3;digo postal[/spanish][german]Postleitzahl[/german]') . ' : ' . $C2 . "\n";
                    }

                    if (isset($T1) and $T1 != '') {
                        $message .= Language::aff_langue('[french]Date de naissance[/french][english]Birth date[/english][spanish]Fecha de nacimiento[/spanish][german]Geburtsdatum[/german]') . ' : ' . $T1 . "\n";
                    }

                    $message .= "\n\n\n" . Language::aff_langue("[french]Conform&eacute;ment aux articles 38 et suivants de la loi fran&ccedil;aise n&deg; 78-17 du 6 janvier 1978 relative &agrave; l'informatique, aux fichiers et aux libert&eacute;s, tout membre dispose d&rsquo; un droit d&rsquo;acc&egrave;s, peut obtenir communication, rectification et/ou suppression des informations le concernant.[/french][english]In accordance with Articles 38 et seq. Of the French law n &deg; 78-17 of January 6, 1978 relating to data processing, files and freedoms, any member has a right of access, can obtain communication, rectification and / or deletion of information about him.[/english][chinese]&#26681;&#25454;1978&#24180;1&#26376;6&#26085;&#20851;&#20110;&#25968;&#25454;&#22788;&#29702;&#65292;&#26723;&#26696;&#21644;&#33258;&#30001;&#30340;&#27861;&#22269;78-17&#21495;&#27861;&#24459;&#65292;&#20219;&#20309;&#25104;&#21592;&#37117;&#26377;&#26435;&#36827;&#20837;&#65292;&#21487;&#20197;&#33719;&#24471;&#36890;&#20449;&#65292;&#32416;&#27491;&#21644;/&#25110; &#21024;&#38500;&#26377;&#20851;&#20182;&#30340;&#20449;&#24687;&#12290;[/chinese][spanish]De conformidad con los art&iacute;culos 38 y siguientes de la ley francesa n &deg; 78-17 del 6 de enero de 1978, relativa al procesamiento de datos, archivos y libertades, cualquier miembro tiene derecho de acceso, puede obtener comunicaci&oacute;n, rectificaci&oacute;n y / o supresi&oacute;n de informaci&oacute;n sobre &eacute;l.[/spanish][german]Gem&auml;&szlig; den Artikeln 38 ff. Des franz&ouml;sischen Gesetzes Nr. 78-17 vom 6. Januar 1978 in Bezug auf Datenverarbeitung, Akten und Freiheiten hat jedes Mitglied ein Recht auf Zugang, kann Kommunikation, Berichtigung und / oder L&ouml;schung von Informationen &uuml;ber ihn.[/german]");
                    $message .= "\n\n\n" . Language::aff_langue("[french]Ce message et les pi&egrave;ces jointes sont confidentiels et &eacute;tablis &agrave; l'attention exclusive de leur destinataire (aux adresses sp&eacute;cifiques auxquelles il a &eacute;t&eacute; adress&eacute;). Si vous n'&ecirc;tes pas le destinataire de ce message, vous devez imm&eacute;diatement en avertir l'exp&eacute;diteur et supprimer ce message et les pi&egrave;ces jointes de votre syst&egrave;me.[/french][english]This message and any attachments are confidential and intended to be received only by the addressee. If you are not the intended recipient, please notify immediately the sender by reply and delete the message and any attachments from your system.[/english][chinese]&#27492;&#28040;&#24687;&#21644;&#20219;&#20309;&#38468;&#20214;&#37117;&#26159;&#20445;&#23494;&#30340;&#65292;&#24182;&#19988;&#25171;&#31639;&#30001;&#25910;&#20214;&#20154;&#25509;&#25910;&#12290; &#22914;&#26524;&#24744;&#19981;&#26159;&#39044;&#26399;&#25910;&#20214;&#20154;&#65292;&#35831;&#31435;&#21363;&#36890;&#30693;&#21457;&#20214;&#20154;&#24182;&#22238;&#22797;&#37038;&#20214;&#21644;&#31995;&#32479;&#20013;&#30340;&#25152;&#26377;&#38468;&#20214;&#12290;[/chinese][spanish]Este mensaje y cualquier adjunto son confidenciales y est&aacute;n destinados a ser recibidos por el destinatario. Si no es el destinatario deseado, notif&iacute;quelo al remitente de inmediato y responda al mensaje y cualquier archivo adjunto de su sistema.[/spanish][german]Diese Nachricht und alle Anh&auml;nge sind vertraulich und sollen vom Empf&auml;nger empfangen werden. Wenn Sie nicht der beabsichtigte Empf&auml;nger sind, benachrichtigen Sie bitte sofort den Absender und antworten Sie auf die Nachricht und alle Anlagen von Ihrem System.[/german]") . "\n\n\n";
                    
                    include("signat.php");
                    
                    $subject = html_entity_decode(translate("Inscription"), ENT_COMPAT | ENT_HTML401, cur_charset) . ' ' . $uname;
                    
                    Mailer::send_email($email, $subject, $message, '', true, 'html', '');
                } else {
                    $message = translate("Bienvenue sur") . Config::get('npds.sitename') ." !\n\n" . translate("Vous, ou quelqu'un d'autre, a utilisé votre Email identifiant votre compte") . " ($email) " . translate("pour enregistrer un compte sur") . Config::get('npds.sitename') .".\n\n" . translate("Informations sur l'utilisateur :") . "\n" . translate("-Identifiant : ") . " $uname\n" . translate("-Mot de passe : ") . " $makepass\n\n";
                    
                    include("signat.php");
                    
                    $subject = html_entity_decode(translate("Mot de passe utilisateur pour"), ENT_COMPAT | ENT_HTML401, cur_charset) . ' ' . $uname;
                    
                    Mailer::send_email($email, $subject, $message, '', true, 'html', '');
    
                    echo '
                    <h2>' . translate("Utilisateur") . '</h2>
                    <h2><i class="fa fa-user me-2"></i>Inscription</h2>
                    <div class="alert alert-success lead"><i class="fa fa-exclamation me-2"></i>' . translate("Vous êtes maintenant enregistré. Vous allez recevoir un code de confirmation dans votre boîte à lettres électronique.") . '</div>';
                }
    
                //------------------------------------------------
                if (file_exists("themes/default/include/new_user.inc")) {
                    include("themes/default/include/new_user.inc");
    
                    $time = date(translate("dateinternal"), time() + ((int) Config::get('npds.gmt') * 3600));
    
                    $message = Metalang::meta_lang(AddSlashes(str_replace("\n", "<br />", $message)));
    
                    $sql = "INSERT INTO priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) ";
                    $sql .= "VALUES ('', '$sujet', '$emetteur_id', '$usr_id', '$time', '$message')";
                    sql_query($sql);
                }
    
                //------------------------------------------------
                $subject = html_entity_decode(translate("Inscription"), ENT_COMPAT | ENT_HTML401, cur_charset) . ' : ' . Config::get('npds.sitename');
                
                Mailer::send_email(Config::get('npds.adminmail'), $subject, "Infos :
                    Nom : $name
                    ID : $uname
                    Email : $email", '', false, "text", '');
            }
    
        } else {
            User::message_error($stop, 'finish');
        }
    }

}