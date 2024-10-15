<?php

namespace Modules\Npds\Library;

use Npds\Config\Config;
use Modules\Npds\Contracts\LanguageInterface;


class LanguageManager implements LanguageInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $tab_langue;

    /**
     * Undocumented function
     */
    public function __construct()
    {
        // init tab langues
        $this->make_tab_langue();
    }

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
     * [aff_langue description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function aff_langue($ibid)
    {
        // copie du tableau + rajout de transl pour gestion de l'appel à __(...); - Theme Dynamic
        $tab_llangue        = $this->tab_langue;
        $tab_llangue[]      = 'transl';

        reset($tab_llangue);

        $ok_language        = false;
        $trouve_language    = false;

        foreach ($tab_llangue as $key => $lang) {

            $pasfin         = true;
            $pos_deb        = false;
            $abs_pos_deb    = false;
            $pos_fin        = false;

            while ($pasfin) {
                // tags [langue] et [/langue]
                $pos_deb = strpos($ibid ?: '', "[$lang]", 0);
                $pos_fin = strpos($ibid ?: '', "[/$lang]", 0);

                if ($pos_deb === false) {
                    $pos_deb = -1;
                }

                if ($pos_fin === false) {
                    $pos_fin = -1;
                }

                // tags [!langue]
                $abs_pos_deb = strpos($ibid ?: '', "[!$lang]", 0);

                if ($abs_pos_deb !== false) {

                    $ibid = str_replace("[!$lang]", "[$lang]", $ibid);
                    $pos_deb = $abs_pos_deb;

                    if ($lang != Config::get('npds.language')) {
                        $trouve_language = true;
                    }
                }

                $decal = strlen($lang) + 2;

                if (($pos_deb >= 0) and ($pos_fin >= 0)) {
                    $fragment = substr($ibid, $pos_deb + $decal, ($pos_fin - $pos_deb - $decal));

                    if ($trouve_language == false) {
                        if ($lang != 'transl') {
                            $ibid = str_replace("[$lang]" . $fragment . "[/$lang]", $fragment, $ibid);
                        } else {
                            $ibid = str_replace("[$lang]" . $fragment . "[/$lang]", __($fragment), $ibid);
                        }

                        $ok_language = true;
                    } else {
                        if ($lang != 'transl') {
                            $ibid = str_replace("[$lang]" . $fragment . "[/$lang]", "", $ibid);
                        } else {
                            $ibid = str_replace("[$lang]" . $fragment . "[/$lang]", __($fragment), $ibid);
                        }
                    }
                } else {
                    $pasfin = false;
                }
            }

            if ($ok_language){
                $trouve_language = true;
            }
        }

        return $ibid;
    }

    /**
     * [make_tab_langue description]
     *
     * @return  [type]  [return description]
     */
    public function make_tab_langue()
    {
        $languageslist = $this->cache_list();

        $languageslocal = Config::get('npds.language') . ' ' . str_replace(Config::get('npds.language'), '', $languageslist);
        $languageslocal = trim(str_replace('  ', ' ', $languageslocal));

        $this->tab_langue = explode(' ', $languageslocal);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function cache_list()
    {
        if (file_exists(module_path('Npds/storage/language/tmp_language.php'))) {
            $languageslist = include(module_path('Npds/storage/language/tmp_language.php'));
        } else {
            $languageslist = $this->list();
        } 
        
        return $languageslist;
    }

    /**
     * [aff_localzone_langue description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function aff_localzone_langue($ibid)
    {
        global $tab_langue;

        $flag = array('french' => '🇫🇷', 'spanish' => '🇪🇸', 'german' => '🇩🇪', 'english' => '🇺🇸', 'chinese' => '🇨🇳');

        $M_langue = '
        <div class="mb-3">
            <select name="' . $ibid . '" class="form-select" onchange="this.form.submit()">
                <option value="">' . __d('npds', 'Choisir une langue') . '</option>';

        foreach ($tab_langue as $bidon => $langue) {
            $M_langue .= '<option value="' . $langue . '">' . $flag[$langue] . ' ' . $langue . '</option>';
        }

        $M_langue .= '
                    <option value="">- ' . __d('npds', 'Aucune langue') . '</option>
                </select>
            </div>
            <noscript>
                <input class="btn btn-primary" type="submit" name="local_sub" value="' . __d('npds', 'Valider') . '" />
            </noscript>';

        return $M_langue;
    }

    /**
     * [aff_local_langue description]
     *
     * @param   [type]  $ibid_index  [$ibid_index description]
     * @param   [type]  $ibid        [$ibid description]
     * @param   [type]  $mess        [$mess description]
     *
     * @return  [type]               [return description]
     */
    public function aff_local_langue($ibid_index, $ibid, $mess = '')
    {
        if ($ibid_index == '') {
            global $REQUEST_URI;

            $ibid_index = $REQUEST_URI;
        }

        $M_langue = '<form action="' . $ibid_index . '" name="local_user_language" method="post">';
        $M_langue .= $mess . $this->aff_localzone_langue($ibid);
        $M_langue .= '</form>';

        return $M_langue;
    }

    /**
     * [preview_local_langue description]
     *
     * @param   [type]  $local_user_language  [$local_user_language description]
     * @param   [type]  $ibid                 [$ibid description]
     *
     * @return  [type]                        [return description]
     */
    public function preview_local_langue($local_user_language, $ibid)
    {
        if ($local_user_language) {
            global $tab_langue;

            $old_langue = Config::get('npds.language');
            
            Config::set('npds.language', $local_user_language);

            $tab_langue = $this->tab_langue;
            $ibid = $this->aff_langue($ibid);

            Config::set('npds.language', $old_langue);
        }

        return $ibid;
    }

    /**
     * [language_iso description]
     *
     * @param   [type]  $l  [$l description]
     * @param   [type]  $s  [$s description]
     * @param   [type]  $c  [$c description]
     *
     * @return  [type]      [return description]
     */
    public function language_iso($l, $s, $c)
    {
        global $user_language;

        $iso_lang = '';
        $iso_country = '';
        $ietf = '';
        $select_lang = '';

        $select_lang = !empty($user_language) ? $user_language : Config::get('npds.language');

        switch ($select_lang) {
            case "french":
                $iso_lang = 'fr';
                $iso_country = 'FR';
                break;

            case "english":
                $iso_lang = 'en';
                $iso_country = 'US';
                break;

            case "spanish":
                $iso_lang = 'es';
                $iso_country = 'ES';
                break;

            case "german":
                $iso_lang = 'de';
                $iso_country = 'DE';
                break;

            case "chinese":
                $iso_lang = 'zh';
                $iso_country = 'CN';
                break;

            default:
                break;
        }

        if ($c !== 1) {
            $ietf = $iso_lang;
        }

        if (($l == 1) and ($c == 1)) {
            $ietf = $iso_lang . $s . $iso_country;
        }

        if (($l !== 1) and ($c == 1)) {
            $ietf = $iso_country;
        }

        if (($l !== 1) and ($c !== 1)) {
            $ietf = '';
        }

        if (($l == 1) and ($c !== 1)) {
            $ietf = $iso_lang;
        }

        return $ietf;
    }

}
