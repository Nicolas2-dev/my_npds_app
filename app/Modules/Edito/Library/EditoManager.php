<?php

namespace App\Modules\Edito\Library;

use App\Modules\Edito\Contracts\EditoInterface;



class EditoManager implements EditoInterface 
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
     * [aff_edito description]
     *
     * @return  [type]  [return description]
     */
    public function aff_edito()
    {
        list($affich, $Xcontents) = fab_edito();
    
        if (($affich) and ($Xcontents != '')) {
            $notitle = false;
    
            if (strstr($Xcontents, '!edito-notitle!')) {
                $notitle = 'notitle';
                $Xcontents = str_replace('!edito-notitle!', '', $Xcontents);
            }
    
            $ret = false;
    
            if (function_exists("themedito")) {
                $ret = themedito($Xcontents);
            } else {
                if (function_exists("theme_centre_box")) {
                    $title = (!$notitle) ? __d('edito', 'EDITO') : '';
    
                    theme_centre_box($title, $Xcontents);
    
                    $ret = true;
                }
            }
    
            if ($ret == false) {
                if (!$notitle) {
                    echo '<span class="edito">' . __d('edito', 'EDITO') . '</span>';
                }
    
                echo $Xcontents;
                echo '<br />';
            }
        }
    }

    /**
     * [fab_edito description]
     *
     * @return  [type]  [return description]
     */
    public function fab_edito()
    {
        global $cookie;

        $path = STORAGE_PATH .'static' .DS;

        if (isset($cookie[3])) {
            if (file_exists($path .'edito_membres.php')) {
                $fp = fopen($path .'edito_membres.php', "r");
                if (filesize($path .'edito_membres.php') > 0) {
                    $Xcontents = fread($fp, filesize($path .'edito_membres.php'));
                }

                fclose($fp);
            } else {
                if (file_exists($path .'edito.php')) {
                    $fp = fopen($path .'edito.php', "r");
                    if (filesize($path .'edito.php') > 0) {
                        $Xcontents = fread($fp, filesize($path .'edito.php'));
                    }

                    fclose($fp);
                }
            }
        } else {
            if (file_exists($path .'edito.php')) {
                $fp = fopen($path .'edito.php', "r");
                if (filesize($path .'edito.php') > 0) {
                    $Xcontents = fread($fp, filesize($path .'edito.php'));
                }

                fclose($fp);
            }
        }

        $affich = false;
        $Xibid = strstr($Xcontents, 'aff_jours');

        if ($Xibid) {
            parse_str($Xibid, $Xibidout);

            if (($Xibidout['aff_date'] + ($Xibidout['aff_jours'] * 86400)) - time() > 0) {
                $affichJ = false;
                $affichN = false;

                if ((NightDay() == 'Jour') and ($Xibidout['aff_jour'] == 'checked')) {
                    $affichJ = true;
                }

                if ((NightDay() == 'Nuit') and ($Xibidout['aff_nuit'] == 'checked')) {
                    $affichN = true;
                }
            }

            $XcontentsT = substr($Xcontents, 0, strpos($Xcontents, 'aff_jours'));
            $contentJ = substr($XcontentsT, strpos($XcontentsT, "[jour]") + 6, strpos($XcontentsT, "[/jour]") - 6);
            $contentN = substr($XcontentsT, strpos($XcontentsT, "[nuit]") + 6, strpos($XcontentsT, "[/nuit]") - 19 - strlen($contentJ));
            $Xcontents = '';

            if (isset($affichJ) and $affichJ === true) {
                $Xcontents = $contentJ;
            }

            if (isset($affichN) and $affichN === true) {
                $Xcontents = $contentN != '' ? $contentN : $contentJ;
            }

            if ($Xcontents != '') {
                $affich = true;
            }
        } else {
            $affich = true;
        }

        $Xcontents = meta_lang(aff_langue($Xcontents));

        return array($affich, $Xcontents);
    }

}
