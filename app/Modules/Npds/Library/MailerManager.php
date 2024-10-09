<?php

namespace App\Modules\Npds\Library;

use Npds\Config\Config;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use PHPMailer\PHPMailer\PHPMailer;
use App\Modules\Npds\Contracts\MailerInterface;


class MailerManager implements MailerInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * [send_email description]
     *
     * @param   [type] $email     [$email description]
     * @param   [type] $subject   [$subject description]
     * @param   [type] $message   [$message description]
     * @param   [type] $from      [$from description]
     * @param   [type] $priority  [$priority description]
     * @param   false  $mime      [$mime description]
     * @param   text   $file      [$file description]
     *
     * @return  [type]            [return description]
     */
    public function send_email($email, $subject, $message, $from = "", $priority = false, $mime = "text", $file = null)
    {
        $From_email = $from != '' ? $from : Config::get('npds.adminmail');

        if (preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $From_email)) {
            
            if (Config::get('mailer.dkim_auto') == 2) {
                //Private key filename for this selector 
                $privatekeyfile = module_path('Npds/storage/phpmailer/key/' . Config::get('npds.Npds_Key') . '_dkim_private.pem');
                //Public key filename for this selector 
                $publickeyfile = module_path('Npds/storage/phpmailer/key/' . Config::get('npds.Npds_Key') . '_dkim_public.pem');

                if (!file_exists($privatekeyfile)) {
                    //Create a 2048-bit RSA key with an SHA256 digest 
                    $pk = openssl_pkey_new(
                        [
                            'digest_alg' => 'sha256',
                            'private_key_bits' => 2048,
                            'private_key_type' => OPENSSL_KEYTYPE_RSA,
                        ]
                    );

                    //Save private key 
                    openssl_pkey_export_to_file($pk, $privatekeyfile);

                    //Save public key 
                    $pubKey = openssl_pkey_get_details($pk);
                    $publickey = $pubKey['key'];
                    file_put_contents($publickeyfile, $publickey);
                }
            }

            $debug = false;
            $mail = new PHPMailer($debug);

            try {
                //Server settings config smtp 
                if (Config::get('npds.mail_fonction') == 2) {
                    $mail->isSMTP();
                    $mail->Host       = Config::get('mailer.smtp_host');
                    $mail->SMTPAuth   = Config::get('mailer.smtp_auth');
                    $mail->Username   = Config::get('mailer.smtp_username');
                    $mail->Password   = Config::get('mailer.smtp_password');

                    if (Config::get('mailer.smtp_secure')) {
                        if (Config::get('mailer.smtp_crypt') === 'tls') {
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        } elseif (Config::get('mailer.smtp_crypt') === 'ssl') {
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        }
                    }

                    $mail->Port = Config::get('mailer.smtp_port');
                }

                $mail->CharSet = cur_charset;
                $mail->Encoding = 'base64';

                if ($priority) {
                    $mail->Priority = 2;
                }

                //Recipients 
                $mail->setFrom(Config::get('npds.adminmail'), Config::get('npds.sitename'));
                $mail->addAddress($email, $email);

                //Content 
                if ($mime == 'mixed') {
                    $mail->isHTML(true);

                    // pièce(s) jointe(s)) 
                    if (!is_null($file)) {
                        if (is_array($file)) {
                            $mail->addAttachment($file['file'], $file['name']);
                        } else {
                            $mail->addAttachment($file);
                        }
                    }
                }

                if (($mime == 'html') or ($mime == 'html-nobr')) {
                    $mail->isHTML(true);

                    if ($mime != 'html-nobr') {
                        $message = nl2br($message);
                    }
                }

                $mail->Subject = $subject;
                $stub_mail = "<html>\n<head>\n<style type='text/css'>\nbody {\nbackground: #FFFFFF;\nfont-family: Tahoma, Calibri, Arial;\nfont-size: 1 rem;\ncolor: #000000;\n}\na, a:visited, a:link, a:hover {\ntext-decoration: underline;\n}\n</style>\n</head>\n<body>\n %s \n</body>\n</html>";
                
                if ($mime == 'text') {
                    $mail->isHTML(false);
                    $mail->Body = $message;
                } else {
                    $mail->Body = sprintf($stub_mail, $message);
                }

                if (Config::get('mailer.dkim_auto') == 2) {
                    $mail->DKIM_domain = str_replace(['http://', 'https://'], ['', ''], Config::get('npds.nuke_url'));
                    $mail->DKIM_private = $privatekeyfile;;
                    $mail->DKIM_selector = Config::get('npds.Npds_Key');
                    $mail->DKIM_identity = $mail->From;
                }

                if (Config::get('npds.mail_fonction') == 2) {
                    if ($debug) {
                        // on génère un journal détaillé après l'envoi du mail 
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    }
                }

                $mail->send();

                if ($debug) {
                    // stop l'exécution du script pour affichage du journal sur la page 
                    die();
                }

                $result = true;
            } catch (Exception $e) {
                Ecr_Log('smtpmail', "send Smtp mail by $email", "Message could not be sent. Mailer Error: $mail->ErrorInfo");
                $result = false;
            }
        }

        return $result ? true : false;
    }

    /**
     * [copy_to_email description]
     *
     * @param   [type]  $to_userid  [$to_userid description]
     * @param   [type]  $sujet      [$sujet description]
     * @param   [type]  $message    [$message description]
     *
     * @return  [type]              [return description]
     */
    public function copy_to_email($to_userid, $sujet, $message)
    {
        

        $result = sql_query("SELECT email, send_email 
                            FROM users 
                            WHERE uid='$to_userid'");

        list($mail, $avertir_mail) = sql_fetch_row($result);

        if (($mail) and ($avertir_mail == 1)) {
            $this->send_email($mail, $sujet, $message, '', true, 'html', '');
        }
    }    

    /**
     * [fakedmail description]
     *
     * @param   [type]  $r  [$r description]
     *
     * @return  [type]      [return description]
     */
    public function fakedmail($r)
    {
        return preg_anti_spam($r[1]);
    }
    
    /**
     * [checkdnsmail description]
     *
     * @param   [type]  $email  [$email description]
     *
     * @return  [type]          [return description]
     */
    public function checkdnsmail($email)
    {
        $ibid = explode('@', $email);
    
        if (!checkdnsrr($ibid[1], 'MX'))
            return false;
        else
            return true;
    }
    
    /**
     * [isbadmailuser description]
     *
     * @param   [type]  $utilisateur  [$utilisateur description]
     *
     * @return  [type]                [return description]
     */
    public function isbadmailuser($utilisateur)
    {
        $contents = '';
        $filename = module_path("Users/storage/users_private/usersbadmail.txt");
        $handle = fopen($filename, "r");
    
        if (filesize($filename) > 0)
            $contents = fread($handle, filesize($filename));
        fclose($handle);
    
        if (strstr($contents, '#' . $utilisateur . '|'))
            return true;
        else
            return false;
    }

}