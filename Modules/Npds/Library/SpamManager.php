<?php

namespace Modules\Npds\Library;

use Npds\Http\Request;
use Modules\Npds\Contracts\SpamInterface;


class SpamManager implements SpamInterface 
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
     * [preg_anti_spam description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function preg_anti_spam($ibid)
    {
        // Adaptation - David MARTINET alias Boris (2011)
        return ("<a href=\"mailto:" . $this->anti_spam($ibid, 1) . "\" target=\"_blank\">" . $this->anti_spam($ibid, 0) . "</a>");
    }

    /**
     * [anti_spam description]
     *
     * @param   [type]  $str       [$str description]
     * @param   [type]  $highcode  [$highcode description]
     *
     * @return  [type]             [return description]
     */
    public function anti_spam($str, $highcode = 0)
    {
        // Idée originale : Pomme (2004). Nouvelle version : David MARTINET alias Boris (2011)
        $str_encoded = "";
        mt_srand((float)microtime() * 1000000);

        for ($i = 0; $i < strlen($str); $i++) {
            if ($highcode == 1) {
                $alea = mt_rand(1, 400);
                $modulo = 4;
            } else {
                $alea = mt_rand(1, 300);
                $modulo = 3;
            }

            switch (($alea % $modulo)) {
                case 0:
                    $str_encoded .= $str[$i];
                    break;

                case 1:
                    $str_encoded .= "&#" . ord($str[$i]) . ";";
                    break;

                case 2:
                    $str_encoded .= "&#x" . bin2hex($str[$i]) . ";";
                    break;

                case 3:
                    $str_encoded .= "%" . bin2hex($str[$i]) . "";
                    break;

                default:
                    $str_encoded = "Error";
                    break;
            }
        }

        return $str_encoded;
    }

    /**
     * [Q_spambot description]
     *
     * @return  [type]  [return description]
     */
    public function Q_spambot()
    {
        // Idée originale, développement et intégration - Gérald MARINO alias neo-machine
        // Rajout brouillage anti_spam() : David MARTINET, alias Boris (2011)
        // Other stuff : Dev 2012
        global $user;

        $asb_question = array(
            '4 - (3 / 1)'       => 1,
            '7 - 5 - 0'         => 2,
            '2 + (1 / 1)'       => 3,
            '2 + (1 + 1)'       => 4,
            '3 + (0) + 2'       => 5,
            '3 + (9 / 3)'       => 6,
            '4 + 3 - 0'         => 7,
            '6 + (0) + 2'       => 8,
            '8 + (5 - 4)'       => 9,
            '0 + (6 + 4)'       => 10,
            '(5 * 2) + 1'       => 11,
            '6 + (3 + 3)'       => 12,
            '1 + (6 * 2)'       => 13,
            '(8 / 1) + 6 '      => 14,
            '6 + (5 + 4)'       => 15,
            '8 + (4 * 2)'       => 16,
            '1 + (8 * 2)'       => 17,
            '9 + (3 + 6)'       => 18,
            '(7 * 2) + 5'       => 19,
            '(8 * 3) - 4'       => 20,
            '7 + (2 * 7)'       => 21,
            '9 + 5 + 8'         => 22,
            '(5 * 4) + 3'       => 23,
            '0 + (8 * 3)'       => 24,
            '1 + (4 * 6)'       => 25,
            '(6 * 5) - 4'       => 26,
            '3 * (9 + 0)'       => 27,
            '4 + (3 * 8)'       => 28,
            '(6 * 4) + 5'       => 29,
            '0 + (6 * 5)'       => 30
        );

        // START ALEA
        mt_srand((int) microtime() * 1000000);

        // choix de la question
        $asb_index = mt_rand(0, count($asb_question) - 1);
        $ibid = array_keys($asb_question);
        $aff = $ibid[$asb_index];

        // translate
        $tab = explode(' ', str_replace(')', '', str_replace('(', '', $aff)));
        $al1 = mt_rand(0, count($tab) - 1);

        if (function_exists("imagepng")) {
            $aff = str_replace($tab[$al1], html_entity_decode($tab[$al1], ENT_QUOTES | ENT_HTML401, 'UTF-8'), $aff);
        } else {
            $aff = str_replace($tab[$al1], $tab[$al1], $aff);
        }

        // mis en majuscule
        if ($asb_index % 2) {
            $aff = ucfirst($aff);
        }

        // END ALEA

        //Captcha - si GD
        if (function_exists("imagepng")) {
            $aff = "<img src=\"getfile.php?att_id=" . rawurlencode(encrypt($aff . " = ")) . "&amp;apli=captcha\" style=\"vertical-align: middle;\" />";
        } else {
            $aff = "" . $this->anti_spam($aff . " = ", 0) . "";
        }

        $tmp = '';

        if ($user == '') {
            $tmp = '
            <div class="mb-3 row">
                <div class="col-sm-9 text-end">
                    <label class="form-label text-danger" for="asb_reponse">' . __d('npds', 'Anti-Spam / Merci de répondre à la question suivante : ') . '&nbsp;' . $aff . '</label>
                </div>
                <div class="col-sm-3 text-end">
                    <input class="form-control" type="text" id="asb_reponse" name="asb_reponse" maxlength="2" onclick="this.value" />
                    <input type="hidden" name="asb_question" value="' . encrypt($ibid[$asb_index] . ',' . time()) . '" />
                </div>
            </div>';
        } else {
            $tmp = '
            <input type="hidden" name="asb_question" value="" />
            <input type="hidden" name="asb_reponse" value="" />';
        }

        return $tmp;
    }

    /**
     * [L_spambot description]
     *
     * @param   [type]  $ip      [$ip description]
     * @param   [type]  $status  [$status description]
     *
     * @return  [type]           [return description]
     */
    public function L_spambot($ip, $status)
    {
        $cpt_sup = 0;
        $maj_fic = false;

        if ($ip == '') {
            $ip = Request::getip();
        }

        $ip = urldecode($ip);

        if (file_exists($filelog = storage_path('logs/spam.log'))) {
            $tab_spam = str_replace("\r\n", '', file($filelog));

            if (in_array($ip . '|1', $tab_spam)) {
                $cpt_sup = 2;
            }

            if (in_array($ip . '|2', $tab_spam)) {
                $cpt_sup = 3;
            }

            if (in_array($ip . '|3', $tab_spam)) {
                $cpt_sup = 4;
            }

            if (in_array($ip . '|4', $tab_spam)) {
                $cpt_sup = 5;
            }
        }

        if ($cpt_sup) {
            if ($status == "false") {
                $tab_spam[array_search($ip . '|' . ($cpt_sup - 1), $tab_spam)] = $ip . '|' . $cpt_sup;
            } else if ($status == "ban") {
                $tab_spam[array_search($ip . '|' . ($cpt_sup - 1), $tab_spam)] = $ip . '|5';
            } else {
                $tab_spam[array_search($ip . '|' . ($cpt_sup - 1), $tab_spam)] = '';
            }

            $maj_fic = true;
        } else {
            if ($status == "false") {
                $tab_spam[] = $ip . '|1';
                $maj_fic = true;
            } else if ($status == 'ban') {
                
                if (!in_array($ip . '|5', $tab_spam)) {
                    $tab_spam[] = $ip . '|5';
                    $maj_fic = true;
                }
            }
        }

        if ($maj_fic) {
            $file = fopen($filelog, 'w');
            
            foreach ($tab_spam as $key => $val) {
                if ($val) {
                    fwrite($file, $val . "\r\n");
                }
            }

            fclose($file);
        }
    }

    /**
     * [R_spambot description]
     *
     * @param   [type]  $asb_question  [$asb_question description]
     * @param   [type]  $asb_reponse   [$asb_reponse description]
     * @param   [type]  $message       [$message description]
     *
     * @return  [type]                 [return description]
     */
    public function R_spambot($asb_question, $asb_reponse, $message = '')
    {
        global $user, $REQUEST_METHOD;

        if ($REQUEST_METHOD == "POST") {
            if ($user == '') {
                if (($asb_reponse != '') and (is_numeric($asb_reponse)) and (strlen($asb_reponse) <= 2)) {
                    $ibid = decrypt($asb_question);
                    $ibid = explode(',', $ibid);
                    $result = "\$arg=($ibid[0]);";

                    // submit intervient en moins de 5 secondes (trop vite) ou plus de 30 minutes (trop long)
                    $temp = time() - $ibid[1];
                    if (($temp < 1800) and ($temp > 5)) {
                        eval($result);
                    } else {
                        $arg = uniqid(mt_rand());
                    }
                } else {
                    $arg = uniqid(mt_rand());
                }

                if ($arg == $asb_reponse) {
                    // plus de 2 http:// dans le texte
                    preg_match_all('#http://#', $message, $regs);

                    if (count($regs[0]) > 2) {
                        $this->L_spambot('', "false");
                        return false;
                    } else {
                        $this->L_spambot('', "true");
                        return true;
                    }
                } else {
                    $this->L_spambot('', "false");
                    return false;
                }
            } else {
                $this->L_spambot('', "true");
                return true;
            }
        } else {
            $this->L_spambot('', "false");
            return false;
        }
    }

    /**
     * [spam_boot description]
     *
     * @return  [type]  [return description]
     */
    public function spam_boot() 
    {
        // First of all : Spam from IP / |5 indicate that the same IP has passed 6 times with status KO in the anti_spambot function
        if (file_exists(storage_path('/logs/spam.log'))) {
            $tab_spam = str_replace("\r\n", "", file(storage_path('/logs/spam.log')));
        }

        if (is_array($tab_spam)) {
            $ipadr = urldecode(Request::getip());
            $ipv = strstr($ipadr, ':') ? '6' : '4';

            if (in_array($ipadr . "|5", $tab_spam)) {
                access_denied();
            }

            //=> nous pouvons bannir une plage d'adresse ip en V4 (dans l'admin IPban sous forme x.x.%|5 ou x.x.x.%|5)
            if ($ipv == '4') {
                $ip4detail = explode('.', $ipadr);

                if (in_array($ip4detail[0] . '.' . $ip4detail[1] . '.%|5', $tab_spam)) {
                    access_denied();
                }

                if (in_array($ip4detail[0] . '.' . $ip4detail[1] . '.' . $ip4detail[2] . '.%|5', $tab_spam)) {
                    access_denied();
                }
            }

            //=> nous pouvons bannir une plage d'adresse ip en V6 (dans l'admin IPban sous forme x:x:%|5 ou x:x:x:%|5)
            if ($ipv == '6') {
                $ip6detail = explode(':', $ipadr);

                if (in_array($ip6detail[0] . ':' . $ip6detail[1] . ':%|5', $tab_spam)) {
                    access_denied();
                }

                if (in_array($ip6detail[0] . ':' . $ip6detail[1] . ':' . $ip6detail[2] . ':%|5', $tab_spam)) {
                    access_denied();
                }
            }
        }    
    }

}
