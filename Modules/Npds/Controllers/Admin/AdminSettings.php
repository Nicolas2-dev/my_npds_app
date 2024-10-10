<?php

namespace Modules\Npds\Controllers\Admin;

use Npds\Config\Config;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;


class AdminSettings extends AdminController
{
/**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = "";

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = '';


    /**
     * Call the parent construct
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
        $this->f_titre = __d('', '');

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
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    // public function __construct()
    // {
        // $f_meta_nom = 'Configure';
        // $f_titre = __d('npds', 'Préférences');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        

        // $hlpfile = "language/manuels/Config::get('npds.language')/config.html";

        // switch ($op) {
        //     case 'Configure':
        //         Configure();
        //         break;
        
        //     case 'ConfigSave':
        //         include("admin/settings_save.php");
        
        //         ConfigSave($xparse, $xsitename, $xnuke_url, $xsite_logo, $xslogan, $xstartdate, $xadminmail, $xtop, $xstoryhome, $xoldnum, $xultramode, $xanonpost, $xDefault_Theme, $xbanners, $xmyIP, $xfoot1, $xfoot2, $xfoot3, $xfoot4, $xbackend_title, $xbackend_language, $xbackend_image, $xbackend_width, $xbackend_height, $xlanguage, $xlocale, $xperpage, $xpopular, $xnewlinks, $xtoplinks, $xlinksresults, $xlinks_anonaddlinklock, $xnotify, $xnotify_email, $xnotify_subject, $xnotify_message, $xnotify_from, $xmoderate, $xanonymous, $xmaxOptions, $xsetCookies, $xtipath, $xuserimg, $xadminimg, $xadmingraphic, $xadmart, $xminpass, $xhttpref, $xhttprefmax, $xpollcomm, $xlinkmainlogo, $xstart_page, $xsmilies, $xOnCatNewLink, $xEmailFooter, $xshort_user, $xgzhandler, $xrss_host_verif, $xcache_verif, $xmember_list, $xdownload_cat, $xmod_admin_news, $xgmt, $xAutoRegUser, $xTitlesitename, $xfilemanager, $xshort_review, $xnot_admin_count, $xadmin_cook_duration, $xuser_cook_duration, $xtroll_limit, $xsubscribe, $xCloseRegUser, $xshort_menu_admin, $xmail_fonction, $xmemberpass, $xshow_user, $xdns_verif, $xmember_invisible, $xavatar_size, $xlever, $xcoucher, $xmulti_langue, $xadmf_ext, $xsavemysql_size, $xsavemysql_mode, $xtiny_mce, $xApp_twi, $xApp_fcb, $xDefault_Skin, $xsmtp_host, $xsmtp_auth, $xsmtp_username, $xsmtp_password, $xsmtp_secure, $xsmtp_crypt, $xsmtp_port, $xdkim_auto);
        //         break;
        // }
    // }

    function Configure()
    {
        echo '
        <hr />
        <form id="settingspref" action="admin.php" method="post">
        <fieldset>
            <legend><a class="tog" id="show_info_gene" title="' . __d('npds', 'Replier la liste') . '"><i id="i_info_gene" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;' . __d('npds', 'Informations générales du site') . '</legend>
            <div id="info_gene" class="adminsidefield card card-body mb-3" style="display:none;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                    <div class="mb-1" for="xparse">Parse algo</div>';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.parse') == 0) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xparse_fix" name="xparse" value="0" ' . $cky . ' />
                        <label class="form-check-label" for="xparse_fix">FixQuotes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xparse_str" name="xparse" value="1" ' . $ckn . ' />
                        <label class="form-check-label" for="xparse_str">StripSlashes</label>
                    </div>
                    </div>
                    <input type="hidden" name="xgzhandler" value="0" />
                    <div class="col-md-6 mb-3">
                    <div class="mb-1" for="xfilemanager">FileManager</div>';
    
        $cky = '';
        $ckn = '';
    
        if ($filemanager == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xfilemanager_y" name="xfilemanager" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xfilemanager_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xfilemanager_n" name="xfilemanager" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xfilemanager_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="row gy-0 gx-3">
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control js-dig10" id="xadmin_cook_duration" type="text" name="xadmin_cook_duration" value="' . Config::get('npds.admin_cook_duration') . '" min="1" maxlength="10" required="required" />
                        <label for="xadmin_cook_duration">' . __d('npds', 'Durée de vie en heure du cookie Admin') . '<span class="text-danger"> *</span></label>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control js-dig10" id="xuser_cook_duration" type="text" name="xuser_cook_duration" value="' . Config::get('npds.user_cook_duration') . '" min="1" maxlength="10" required="required" />
                        <label for="xuser_cook_duration">' . __d('npds', 'Durée de vie en heure du cookie User') . '<span class="text-danger"> *</span></label>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="xsitename" id="xsitename" value="' . Config::get('npds.sitename') . '" maxlength="100" />
                        <label for="xsitename">' . __d('npds', 'Nom du site') . '</label>
                        <span class="help-block text-end" id="countcar_xsitename"></span>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="xTitlesitename" id="xTitlesitename" value="' . Config::get('npds.Titlesitename') . '" maxlength="100" />
                        <label for="xTitlesitename">' . __d('npds', 'Nom du site pour la balise title') . '</label>
                        <span class="help-block text-end" id="countcar_xTitlesitename"></span>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="url" name="xnuke_url" id="xnuke_url" value="' . Config::get('npds.nuke_url') . '" data-fv-uri___allow-local="true" maxlength="200" />
                        <label for="xnuke_url">' . __d('npds', 'URL du site') . '</label>
                        <span class="help-block text-end" id="countcar_xnuke_url"></span>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="xsite_logo" id="xsite_logo" value="' . Config::get('npds.site_logo') . '" maxlength="255" />
                        <label for="xsite_logo">' . __d('npds', 'Logo du site pour les impressions') . '</label>
                        <span class="help-block text-end" id="countcar_xsite_logo"></span>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="xslogan" id="xslogan" value="' . Config::get('npds.slogan') . '" maxlength="100" />
                        <label for="xslogan">' . __d('npds', 'Slogan du site') . '</label> 
                        <span class="help-block text-end" id="countcar_xslogan"></span>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="xstartdate" id="xstartdate" value="' . Config::get('npds.startdate') . '" maxlength="30" />
                        <label for="xstartdate">' . __d('npds', 'Date de démarrage du site') . '</label> 
                        <span class="help-block text-end" id="countcar_xstartdate"></span>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control js-dig4" id="xtop" type="text" name="xtop" value="' . Config::get('npds.top'). '" min="1" maxlength="4" required="required" />
                        <label for="xtop">' . __d('npds', 'Nombre d\'éléments dans la page top') . '<span class="text-danger"> *</span></label> 
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control js-dig4" id="xstoryhome" type="text" name="xstoryhome" value="' . Config::get('npds.storyhome') . '" min="1" maxlength="4" required="required" />
                        <label for="xstoryhome">' . __d('npds', 'Nombre d\'articles en page principale') . '<span class="text-danger"> *</span></label>
                    </div>
                    </div>
                    <div class="col-12">
                    <div class="form-floating mb-3">
                        <input class="form-control js-dig4" id="xoldnum" type="text" name="xoldnum" value="' . Config::get('npds.oldnum') . '" min="1" maxlength="4" required="required" />
                        <label for="xoldnum">' . __d('npds', 'Nombre d\'articles dans le bloc des anciens articles') . '<span class="text-danger"> *</span></label>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="xanonymous" type="text" name="xanonymous" value="' . Config::get('npds.anonymous') . '" maxlength="25" />
                        <label for="xanonymous">' . __d('npds', 'Nom d\'utilisateur anonyme') . '</label>
                        <span class="help-block text-end" id="countcar_xanonymous"></span>
                    </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="mb-1" for="xmod_admin_news">' . __d('npds', 'Autoriser la création de news pour') . '</div>
                    <div class="form-check form-check-inline">';
    
        if (Config::get('npds.mod_admin_news') == 1) {
            echo '
                        <input type="radio" class="form-check-input" id="xmod_admin_news_a" name="xmod_admin_news" value="1" checked="checked" />
                        <label class="form-check-label" for="xmod_admin_news_a">' . __d('npds', 'Administrateurs') . ' / ' . __d('npds', 'Modérateurs') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmod_admin_news_m" name="xmod_admin_news" value="2" />
                        <label class="form-check-label" for="xmod_admin_news_m">' . __d('npds', 'Membres') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmod_admin_news_t" name="xmod_admin_news" value="0" />
                        <label class="form-check-label" for="xmod_admin_news_t">' . __d('npds', 'Tous') . '</label>';
        } elseif (Config::get('npds.mod_admin_news') == 2) {
            echo '
                        <input type="radio" class="form-check-input" id="xmod_admin_news_a" name="xmod_admin_news" value="1" />
                        <label class="form-check-label" for="xmod_admin_news_a">' . __d('npds', 'Administrateurs') . ' / ' . __d('npds', 'Modérateurs') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmod_admin_news_m" name="xmod_admin_news" value="2" checked="checked" />
                        <label class="form-check-label" for="xmod_admin_news_m">' . __d('npds', 'Membres') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmod_admin_news_t" name="xmod_admin_news" value="0" />
                        <label class="form-check-label" for="xmod_admin_news_t">' . __d('npds', 'Tous') . '</label>';
        } else {
            echo '
                        <input type="radio" class="form-check-input" id="xmod_admin_news_a" name="xmod_admin_news" value="1" />
                        <label class="form-check-label" for="xmod_admin_news_a">' . __d('npds', 'Administrateurs') . ' / ' . __d('npds', 'Modérateurs') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmod_admin_news_m" name="xmod_admin_news" value="2" />
                        <label class="form-check-label" for="xmod_admin_news_m">' . __d('npds', 'Membres') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmod_admin_news_t" name="xmod_admin_news" value="0" checked="checked" />
                        <label class="form-check-label" for="xmod_admin_news_t">' . __d('npds', 'Tous') . '</label>';
        }
    
        echo '
                    </div>
                </div>
                <div class="mb-3">
                    <div class="mb-1" for="xnot_admin_count">' . __d('npds', 'Ne pas enregistrer les \'hits\' des auteurs dans les statistiques') . '</div>';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.not_admin_count') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" id="xnot_admin_count_y" name="xnot_admin_count" value="1" ' . $cky . ' />
                    <label class="form-check-label" for="xnot_admin_count_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" id="xnot_admin_count_n" name="xnot_admin_count" value="0" ' . $ckn . ' />
                    <label class="form-check-label" for="xnot_admin_count_n">' . __d('npds', 'Non') . '</label>
                    </div>
                </div>
                <div class="row gy-0 gx-3">
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="xDefault_Theme" name="xDefault_Theme">';
    
        include("themes/list.php");
    
        $themelist = explode(" ", $themelist);
    
        for ($i = 0; $i < sizeof($themelist); $i++) {
            if ($themelist[$i] != '') {
                echo '<option value="' . $themelist[$i] . '" ';
    
                if ($themelist[$i] == Config::get('npds.Default_Theme')) 
                    echo 'selected="selected"';
    
                echo '>' . $themelist[$i] . '</option>';
            }
        }
    
        echo '
                        </select>
                        <label for="xDefault_Theme">' . __d('npds', 'Thème d\'affichage par défaut') . '</label>
                    </div>
                    </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3" id="skin_choice">
                    <select class="form-select" id="xDefault_Skin" name="xDefault_Skin">';
    
        // les skins disponibles
        $handle = opendir('assets/skins');
        while (false !== ($file = readdir($handle))) {
            if (($file[0] !== '_') and (!strstr($file, '.')) and (!strstr($file, 'assets')) and (!strstr($file, 'fonts'))) {
                $skins[] = array('name' => $file, 
                                 'description' => '', 
                                 'thumbnail' => $file . '/thumbnail', 
                                 'preview' => $file . '/', 
                                 'css' => $file . '/bootstrap.css', 
                                 'cssMin' => $file . '/bootstrap.min.css', 
                                 'cssxtra' => $file . '/extra.css', 
                                 'scss' => $file . '/_bootswatch.scss', 
                                 'scssVariables' => $file . '/_variables.scss');
            }
        }
        closedir($handle);
    
        if (!issetConfig::get('npds.Default_Skin'))
            Config::set('npds.Default_Skin', '');
    
        asort($skins);
    
        foreach ($skins as $k => $v) {
            echo '<option value="' . $skins[$k]['name'] . '" ';
    
            if ($skins[$k]['name'] == Config::get('npds.Default_Skin')) 
                echo 'selected="selected"';
    
            else if (Config::get('npds.Default_Skin') == '' and $skins[$k]['name'] == 'default') 
                echo 'selected="selected"';
    
            echo '>' . $skins[$k]['name'] . '</option>';
        }
    
        echo '
                    </select>
                    <label for="xDefault_Skin">' . __d('npds', 'Skin d\'affichage par défaut') . '</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="text" name="xstart_page" id="xstart_page" value="' . Config::get('npds.Start_Page') . '" maxlength="100" />
                    <label for="xstart_page">' . __d('npds', 'Page de démarrage') . '</label>
                    <span class="help-block text-end" id="countcar_xstart_page"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                    <select class="form-select" id="xlanguage" name="xlanguage">';
    
        $languageslist = Language::cache_list();
        $languageslist = explode(' ', $themeslist);
    
        for ($i = 0; $i < sizeof($languageslist); $i++) {
            if ($languageslist[$i] != '') {
                echo '<option value="' . $languageslist[$i] . '" ';
    
                if ($languageslist[$i] == Config::get('npds.language')) 
                    echo 'selected="selected"';
    
                echo '>' . $languageslist[$i] . '</option>';
            }
        }
    
        echo '
                    </select>
                    <label for="xlanguage">' . __d('npds', 'Sélectionner la langue du site') . '</label>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xmulti_langue">' . __d('npds', 'Activer le multi-langue') . '</label>
                    <div class="col-sm-8 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.multi_langue') == true) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmulti_langue_y" name="xmulti_langue" value="true" ' . $cky . ' />
                        <label class="form-check-label" for="xmulti_langue_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmulti_langue_n" name="xmulti_langue" value="false" ' . $ckn . ' />
                        <label class="form-check-label" for="xmulti_langue_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="text" name="xlocale" id="xlocale" value="' . Config::get('npds.locale') . '" maxlength="100" />
                    <label for="xlocale">' . __d('npds', 'Heure locale') . '</label>
                    <span class="help-block text-end" id="countcar_xlocale"></span>
                    </div>
                </div>';
    
        echo '
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                    <input class="form-control js-hhmm" type="text" name="xlever" id="xlever" value="' . Config::get('npds.lever', '08:00')
                    . '" maxlength="5" required="required" />
                    <label for="xlever">' . __d('npds', 'Le jour commence à') . '</label>
                    <span class="help-block">(HH:MM)<span class="float-end ms-1" id="countcar_xlever"></span></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                    <input class="form-control js-hhmm" type="text" name="xcoucher" id="xcoucher" value="' . Config::get('npds.coucher', '20:00') . '" maxlength="5" required="required" />
                    <label for="xcoucher">' . __d('npds', 'La nuit commence à') . '</label>
                    <span class="help-block">(HH:MM)<span class="float-end ms-1" id="countcar_xcoucher"></span></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                    <select class="form-select" id="xgmt" name="xgmt">
                        <option value="' . Config::get('npds.gmt') . '" selected="selected">' . Config::get('npds.gmt') . '</option>
                        <option value="-1">UTC-01:00</option>
                        <option value="-2">UTC-02:00</option>
                        <option value="-3">UTC-03:00</option>
                        <option value="-3.5">UTC-03:30</option>
                        <option value="-4">UTC-04:00</option>
                        <option value="-5">UTC-05:00</option>
                        <option value="-6">UTC-06:00</option>
                        <option value="-7">UTC-07:00</option>
                        <option value="-8">UTC-08:00</option>
                        <option value="-9">UTC-09:00</option>
                        <option value="-9.5">UTC-09:30</option>
                        <option value="-10">UTC-10:00</option>
                        <option value="-11">UTC-11:00</option>
                        <option value="-12">UTC-12:00</option>
                        <option value="0">UTC±00:00</option>
                        <option value="+1">UTC+01:00</option>
                        <option value="+2">UTC+02:00</option>
                        <option value="+3">UTC+03:00</option>
                        <option value="+3.5">UTC+03:30</option>
                        <option value="+4">UTC+04:00</option>
                        <option value="+4.5">UTC+04:30</option>
                        <option value="+5">UTC+05:00</option>
                        <option value="+5.5">UTC+05:30</option>
                        <option value="+5.75">UTC+05:45</option>
                        <option value="+6">UTC+06:00</option>
                        <option value="+6.5">UTC+06:30</option>
                        <option value="+7">UTC+07:00</option>
                        <option value="+8">UTC+08:00</option>
                        <option value="+8.75">UTC+08:45</option>
                        <option value="+9">UTC+09:00</option>
                        <option value="+9.5">UTC+09:30</option>
                        <option value="+10">UTC+10:00</option>
                        <option value="+10.5">UTC+10:30</option>
                        <option value="+11">UTC+11:00</option>
                        <option value="+12">UTC+12:00</option>
                        <option value="+12.75">UTC+12:45</option>
                        <option value="+13">UTC+13:00</option>
                        <option value="+14">UTC+14:00</option>
                    </select>
                    <label for="xgmt">UTC</label>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            //<![CDATA[
            tog(\'info_gene\',\'show_info_gene\',\'hide_info_gene\');
            $(function () {
                $("#xDefault_Theme").change(function () {
                    sk = $("#xDefault_Theme option:selected").text().substr(-3);
                    if(sk=="_sk") {
                    $("#skin_choice").removeClass("collapse");
                    } else {
                    $("#skin_choice").addClass("collapse");
                    }
                })
                .change();
            });
            //]]>
            </script>
        </fieldset>
        <fieldset>
        <legend><a class="tog" id="show_banner" title="' . __d('npds', 'Replier la liste') . '"><i id="i_banner" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;' . __d('npds', 'Options pour les Bannières') . '</legend>
            <div id="banner" class="adminsidefield card card-body mb-3" style="display:none;">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xbanners">' . __d('npds', 'Options pour les Bannières') . '</label>
                    <div class="col-sm-4">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.banners') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xbanners_y" name="xbanners" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xbanners_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xbanners_n" name="xbanners" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xbanners_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xbanners">' . __d('npds', 'Votre adresse IP (= ne pas comptabiliser les hits qui en proviennent)') . '</label>
                    <div class="col-sm-4">
                    <input class="form-control" type="text" name="xmyIP" id="xmyIP" value="' . Config::get('npds.myIP') . '" />
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            //<![CDATA[
            tog(\'banner\',\'show_banner\',\'hide_banner\');
            //]]>
            </script>
        </fieldset>
        <fieldset>
        <legend><a class="tog" id="show_mes_ppage" title="' . __d('npds', 'Replier la liste') . '"><i id="i_mes_ppage" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;' . __d('npds', 'Message de pied de page') . '</legend>
            <div id="mes_ppage" class="adminsidefield card card-body mb-3" style="display:none;">
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="xfoot1" name="xfoot1" style="height:100px;">' . htmlentities(stripslashes(Config::get('npds.foot1')), ENT_QUOTES | ENT_SUBSTITUTE, cur_charset) . '</textarea>
                    <label for="xfoot1">' . __d('npds', 'Ligne 1') . '</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="xfoot2" name="xfoot2" style="height:100px;">' . htmlentities(stripslashes(Config::get('npds.foot2')), ENT_QUOTES | ENT_SUBSTITUTE, cur_charset) . '</textarea>
                    <label for="xfoot2">' . __d('npds', 'Ligne 2') . '</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control col-sm-12" id="xfoot3" name="xfoot3" style="height:100px;">' . htmlentities(stripslashes(Config::get('npds.foot3')), ENT_QUOTES | ENT_SUBSTITUTE, cur_charset) . '</textarea>
                    <label for="xfoot3">' . __d('npds', 'Ligne 3') . '</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="xfoot4" name="xfoot4" style="height:100px;">' . htmlentities(stripslashes(Config::get('npds.foot4')), ENT_QUOTES | ENT_SUBSTITUTE, cur_charset) . '</textarea>
                    <label for="xfoot4">' . __d('npds', 'Ligne 4') . '</label>
                </div>
            </div>
            <script type="text/javascript">
            //<![CDATA[
            tog(\'mes_ppage\',\'show_mes_ppage\',\'hide_mes_ppage\');
            //]]>
            </script>
        </fieldset>
        <fieldset>
        <legend><a class="tog" id="show_bakend_rs" title="' . __d('npds', 'Replier la liste') . '"><i id="i_bakend_rs" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;' . __d('npds', 'Configuration des infos en Backend & Réseaux Sociaux') . '</legend>
            <div id="bakend_rs" class="adminsidefield card card-body mb-3" style="display:none;">
                <div class="row gy-0 gx-3">
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="xbackend_title" id="xbackend_title" value="' . Config::get('npds.backend_title') . '" maxlength="100" />
                        <label for="xbackend_title">' . __d('npds', 'Titre du backend') . '</label>
                        <span class="help-block text-end" id="countcar_xbackend_title"></span>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="xbackend_language" id="xbackend_language" value="' . Config::get('npds.backend_language') . '" maxlength="10" />
                        <label for="xbackend_language">' . __d('npds', 'Langue du backend') . '</label>
                        <span class="help-block text-end" id="countcar_xbackend_language"></span>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="url" name="xbackend_image" id="xbackend_image" value="' . Config::get('npds.backend_image')
                        . '" maxlength="200" />
                        <label for="xbackend_image">' . __d('npds', 'URL de l\'image du backend') . '</label>
                        <span class="help-block text-end" id="countcar_xbackend_image"></span>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="number" id="xbackend_width" name="xbackend_width" value="' . Config::get('npds.backend_width')
                        . '" min="0" max="9999" />
                        <label for="xbackend_width">' . __d('npds', 'Largeur de l\'image du backend') . '</label>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="number" id="xbackend_height" name="xbackend_height" value="' . Config::get('npds.backend_height')
                        . '" min="0" max="9999" />
                        <label for="xbackend_height">' . __d('npds', 'Hauteur de l\'image du backend') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xultramode">' . __d('npds', 'Activer export-news') . '</label>
                    <div class="col-sm-8 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.ultramode') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xultramode_y" name="xultramode" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xultramode_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xultramode_n" name="xultramode" value="0" ' . $ckn . '/>
                        <label class="form-check-label" for="xultramode_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xApp_twi">' . __d('npds', 'Activer Twitter') . '</label>
                    <div class="col-sm-8 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.npds_twi')
        == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xApp_twi_y" name="xApp_twi" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xApp_twi_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xApp_twi_n" name="xApp_twi" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xApp_twi_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xApp_fcb">' . __d('npds', 'Activer Facebook') . '</label>
                    <div class="col-sm-8 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.npds_fcb') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xApp_fcb_y" name="xApp_fcb" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xApp_fcb_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xApp_fcb_n" name="xApp_fcb" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xApp_fcb_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            //<![CDATA[
            tog(\'bakend_rs\',\'show_bakend_rs\',\'hide_bakend_rs\');
            //]]>
            </script>
        </fieldset>
        <fieldset>
        <legend><a class="tog" id="show_lien_web" title="' . __d('npds', 'Replier la liste') . '"><i id="i_lien_web" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;' . __d('npds', 'Configuration par défaut des Liens Web') . '</legend>
            <div id="lien_web" class="adminsidefield card card-body mb-3" style="display:none;">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xperpage">' . __d('npds', 'Nombre de liens par page') . '</label>
                    <div class="col-sm-4">
                    <select class="form-select" id="xperpage" name="xperpage">
                        <option  value="' . Config::get('npds.perpage') . '" selected="selected">' . Config::get('npds.perpage') . '</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xpopular">' . __d('npds', 'Nombre de clics sur un lien pour qu\'il soit populaire') . '</label>
                    <div class="col-sm-4">
                    <select class="form-select" id="xpopular" name="xpopular">
                        <option value="' . Config::get('npds.popular') . '" selected="selected">' . Config::get('npds.popular') . '</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="250">250</option>
                        <option value="500">500</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xnewlinks">' . __d('npds', 'Nombre de Liens \'Nouveaux\'') . '</label>
                    <div class="col-sm-4">
                    <select class="form-select" id="xnewlinks" name="xnewlinks">
                        <option value="' . Config::get('npds.newlinks') . '" selected="selected">' . Config::get('npds.newlinks') . '</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xtoplinks">' . __d('npds', 'Nombre de Liens \'Meilleur\'') . '</label>
                    <div class="col-sm-4">
                    <select class="form-select" id="xtoplinks" name="xtoplinks">
                        <option value="' . Config::get('npds.toplinks') . '" selected="selected">' . Config::get('npds.toplinks') . '</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xlinksresults">' . __d('npds', 'Nombre de liens dans les résultats des recherches') . '</label>
                    <div class="col-sm-4">
                    <select class="form-select" id="xlinksresults" name="xlinksresults">
                        <option value="' . Config::get('npds.linksresults') . '" selected="selected">' . Config::get('npds.linksresults') . '</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xlinks_anonaddlinklock">' . __d('npds', 'Laisser les utilisateurs anonymes poster de nouveaux liens') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.links_anonaddlinklock') == 0) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xlinks_anonaddlinklock_y" name="xlinks_anonaddlinklock" value="0" ' . $cky . ' />
                        <label class="form-check-label" for="xlinks_anonaddlinklock_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xlinks_anonaddlinklock_n" name="xlinks_anonaddlinklock" value="1" ' . $ckn . '/>
                        <label class="form-check-label" for="xlinks_anonaddlinklock_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xlinkmainlogo">' . __d('npds', 'Afficher le logo sur la page web links') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.linkmainlogo') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xlinkmainlogo_y" name="xlinkmainlogo" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xlinkmainlogo_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xlinkmainlogo_n" name="xlinkmainlogo" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xlinkmainlogo_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xOnCatNewLink">' . __d('npds', 'Activer l\'icône [N]ouveau pour les catégories') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.OnCatNewLink') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xOnCatNewLink_y" name="xOnCatNewLink" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xOnCatNewLink_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xOnCatNewLink_n" name="xOnCatNewLink" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xOnCatNewLink_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            //<![CDATA[
            tog(\'lien_web\',\'show_lien_web\',\'hide_lien_web\');
            //]]>
            </script>
        </fieldset>
        <fieldset>
        <legend><a class="tog" id="show_sys_mes" title="' . __d('npds', 'Replier la liste') . '"><i id="i_sys_mes" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;' . __d('npds', 'Système de Messagerie (Email)') . '</legend>
            <div id="sys_mes" class="adminsidefield card card-body mb-3" style="display:none;">
                <div class="form-floating mb-3">
                    <input class="form-control" type="email" name="xadminmail" id="xadminmail" value="' . Config::get('npds.adminmail') . '" maxlength="254" required="required" />
                    <label for="xadminmail">' . __d('npds', 'Adresse E-mail de l\'administrateur') . '</label> 
                    <span class="help-block text-end">' . __d('npds', 'Adresse E-mail valide, autorisée et associée au serveur d\'envoi.') . '<span id="countcar_xadminmail float-end"></span></span>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xmail_fonction">' . __d('npds', 'Utiliser SMTP(S)') . '</label>
                    <div class="col-sm-8 my-2">';
    
        $cky = '';
        $ckn = '';
    
        $mail_fonction = Config::get('npds.mail_fonction');

        if (!$mail_fonction) 
            $mail_fonction = 1;
    
        if ($mail_fonction == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmail_fonction1" name="xmail_fonction" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xmail_fonction1">' . __d('npds', 'Non') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmail_fonction2" name="xmail_fonction" value="2" ' . $ckn . ' />
                        <label class="form-check-label" for="xmail_fonction2">' . __d('npds', 'Oui') . '</label>
                    </div>
                    </div>
                </div>';
    
        include "config/phpmailer.php";
    
        echo '
                <div id="smtp" class="row">
                    <div class="form-label my-3">' . __d('npds', 'Configuration de PHPmailer SMTP(S)') . '</div>
                    <div class="mb-3 row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" name="xsmtp_host" id="xsmtp_host" value="' . $smtp_host . '" maxlength="100" required="required" />
                            <label for="xsmtp_host">' . __d('npds', 'Nom du serveur') . '</label>
                            <span class="help-block text-end" id="countcar_xsmtp_host"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" name="xsmtp_port" id="xsmtp_port" value="' . $smtp_port . '" maxlength="4" required="required" />
                            <label for="xsmtp_port">' . __d('npds', 'Port TCP') . '</label>
                            <span class="help-block text-end">' . __d('npds', 'Utiliser 587 si vous avez activé le chiffrement TLS') . '.<span class="float-end ms-1" id="countcar_xsmtp_port"></span></span>
                        </div>
                    </div>
                    </div>';
    
        $smtpaky = '';
        $smtpakn = '';
    
        if ($smtp_auth == 1) {
            $smtpaky = 'checked="checked"';
            $smtpakn = '';
        } else {
            $smtpaky = '';
            $smtpakn = 'checked="checked"';
        }
    
        echo '
                    <div class="mb-3 row">
                    <label class="col-form-label col-sm-6" for="xsmtp_auth">' . __d('npds', 'Activer l\'authentification SMTP(S)') . '</label>
                    <div class="col-sm-6 my-2">
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="xsmtp_auth_y" name="xsmtp_auth" value="1" ' . $smtpaky . ' />
                            <label class="form-check-label" for="xsmtp_auth_y">' . __d('npds', 'Oui') . '</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="xsmtp_auth_n" name="xsmtp_auth" value="0" ' . $smtpakn . ' />
                            <label class="form-check-label" for="xsmtp_auth_n">' . __d('npds', 'Non') . '</label>
                        </div>
                    </div>
                    </div>
                    <div id="auth" class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" name="xsmtp_username" id="xsmtp_username" value="' . $smtp_username . '" maxlength="100" required="required" />
                            <label for="xsmtp_username">' . __d('npds', 'Nom d\'utilisateur') . '</label>
                            <span class="help-block text-end" id="countcar_xsmtp_username"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input class="form-control" type="password" name="xsmtp_password" id="xsmtp_password" value="' . $smtp_password . '" maxlength="100" required="required" />
                            <label for="xsmtp_password">' . __d('npds', 'Mot de passe') . '</label>
                            <span class="help-block text-end" id="countcar_xsmtp_password"></span>
                        </div>
                    </div>
                    </div>';
    
        $smtpsky = '';
        $smtpskn = '';
    
        if ($smtp_secure == 1) {
            $smtpsky = 'checked="checked"';
            $smtpskn = '';
        } else {
            $smtpsky = '';
            $smtpskn = 'checked="checked"';
        }
    
        echo '
                    <div class="mb-3 row">
                    <div class="col-md-6 my-auto">
                        <label class="form-label me-4" for="xsmtp_secure">' . __d('npds', 'Activer le chiffrement') . '</label>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="xsmtp_secure_y" name="xsmtp_secure" value="1" ' . $smtpsky . ' />
                            <label class="form-check-label" for="xsmtp_secure_y">' . __d('npds', 'Oui') . '</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="xsmtp_secure_n" name="xsmtp_secure" value="0" ' . $smtpskn . ' />
                            <label class="form-check-label" for="xsmtp_secure_n">' . __d('npds', 'Non') . '</label>
                        </div>
                    </div>
                    <div class="col-md-6" id="chifr">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="xsmtp_crypt" name="xsmtp_crypt">
                                <option  value="' . $smtp_crypt . '" selected="selected">' . strtoupper($smtp_crypt) . '</option>
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                            </select>
                            <label for="xsmtp_crypt">' . __d('npds', 'Protocole de chiffrement') . '</label>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xdkim_auto">DKIM</label>
                    <div class="col-sm-8 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (!$dkim_auto) 
            $dkim_auto = 1;
    
        if ($dkim_auto == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="dkim1" name="xdkim_auto" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="dkim1">' . __d('npds', 'Du Dns') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="dkim2" name="xdkim_auto" value="2" ' . $ckn . ' />
                        <label class="form-check-label" for="dkim2">' . __d('npds', 'Automatique') . '</label>
                    </div>
                    <span class="help-block">' . __d('npds', 'Du DNS') . ' ==> ' . __d('npds', 'DKIM du DNS (si existant et valide).') . '<br />' . __d('npds', 'Automatique') . ' ==> ' . __d('npds', 'génération automatique du DKIM par le portail.') . '</span>
                    </div>
                </div>';
    
        // Footer of Email send by App
        settype($message, 'string');
    
        include "signat.php";
    
        echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="xEmailFooter">' . __d('npds', 'Pied') . ' ' . __d('npds', 'de') . ' Email</label> 
                    <div class="col-sm-12">
                    <textarea class="form-control" id="xEmailFooter" name="xEmailFooter" cols="45" rows="8">' . $message . '</textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xnotify">' . __d('npds', 'Notifier les nouvelles contributions par E-mail') . '</label>
                    <div class="col-sm-8 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.notify') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xnotify_y" name="xnotify" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xnotify_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xnotify_n" name="xnotify" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xnotify_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xnotify_email">' . __d('npds', 'Adresse E-mail où envoyer le message') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" type="email" name="xnotify_email" id="xnotify_email" value="' . Config::get('npds.notify_email') . '" maxlength="254" required="required" />
                    <span class="help-block text-end" id="countcar_xnotify_email"></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xnotify_subject">' . __d('npds', 'Sujet de l\'E-mail') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" type="text" name="xnotify_subject" id="xnotify_subject" value="' . Config::get('npds.notify_subject') . '" maxlength="100" required="required" />
                    <span class="help-block text-end" id="countcar_xnotify_subject"></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xnotify_message">' . __d('npds', 'Message de l\'E-mail') . '</label>
                    <div class="col-sm-8">
                    <textarea class="form-control" id="xnotify_message" name="xnotify_message" rows="8">' . Config::get('npds.notify_message') . '</textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xnotify_from">' . __d('npds', 'Compte E-mail (Provenance)') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" type="email" name="xnotify_from" id="xnotify_from" value="' . Config::get('npds.notify_from') . '" maxlength="100" required="required" />
                    <span class="help-block text-end">' . __d('npds', 'Adresse E-mail valide, autorisée et associée au serveur d\'envoi.') . ' <span id="countcar_xnotify_from"></span></span>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            //<![CDATA[
                tog(\'sys_mes\',\'show_sys_mes\',\'hide_sys_mes\');
            //]]>
            </script>
        </fieldset>
        <fieldset>
        <legend><a class="tog" id="show_opt_comment" title="' . __d('npds', 'Replier la liste') . '"><i id="i_opt_comment" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;' . __d('npds', 'Options pour les Commentaires') . '</legend>
            <div id="opt_comment" class="adminsidefield card card-body mb-3" style="display:none;">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-7" for="xmoderate">' . __d('npds', 'Type de modération') . '</label>
                    <div class="col-sm-5">
                    <select class="form-select" id="xmoderate" name="xmoderate">';
    
        if (Config::get('npds.moderate') == 1) {
            echo '
                    <option value="1" selected="selected">' . __d('npds', 'Modération par l\'Administrateur') . '</option>
                    <option value="2">' . __d('npds', 'Modération par les Utilisateurs') . '</option>
                    <option value="0">' . __d('npds', 'Pas de modération') . '</option>';
        } elseif (Config::get('npds.moderate') == 2) {
            echo '
                    <option value="1">' . __d('npds', 'Modération par l\'Administrateur') . '</option>
                    <option value="2" selected="selected">' . __d('npds', 'Modération par les Utilisateurs') . '</option>
                    <option value="0">' . __d('npds', 'Pas de modération') . "</option>";
        } elseif (Config::get('npds.moderate') == 0) {
            echo '
                    <option value="1">' . __d('npds', 'Modération par l\'Administrateur') . '</option>
                    <option value="2">' . __d('npds', 'Modération par les Utilisateurs') . '</option>
                    <option value="0" selected="selected">' . __d('npds', 'Pas de modération') . '</option>';
        }
    
        echo '
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-7" for="xanonpost">' . __d('npds', 'Autoriser les commentaires anonymes') . '</label>
                    <div class="col-sm-5 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.anonpost') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xanonpost_y" name="xanonpost" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xanonpost_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xanonpost_n" name="xanonpost" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xanonpost_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-7" for="xtroll_limit">' . __d('npds', 'Nombre maximum de commentaire par utilisateur en 24H') . '</label>
                    <div class="col-sm-5">';
    
        $troll_limit = Config::get('npds.troll_limit');

        if ($troll_limit == '') 
            $troll_limit = "6";
    
        echo '
                    <input class="form-control" id="xtroll_limit" type="text" name="xtroll_limit" value="' . $troll_limit . '" min="1" maxlength="3" required="required" />
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            //<![CDATA[
            tog(\'opt_comment\',\'show_opt_comment\',\'hide_opt_comment\');
            //]]>
            </script>
        </fieldset>
        <fieldset>
        <legend><a class="tog" id="show_opt_sond" title="' . __d('npds', 'Replier la liste') . '"><i id="i_opt_sond" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;' . __d('npds', 'Options des sondages') . '</legend>
            <div id="opt_sond" class="adminsidefield card card-body mb-3" style="display:none;">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xmaxOptions">' . __d('npds', 'Nombre maximum de choix pour les sondages') . '</label>
                    <div class="col-sm-4">
                    <select class="form-select" id="xmaxOptions" name="xmaxOptions">
                        <option value="' . Config::get('npds.maxOptions') . '">' . Config::get('npds.maxOptions') . '</option>
                        <option value="10">10</option>
                        <option value="12">12</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xsetCookies">' . __d('npds', 'Autoriser les utilisateurs à voter plusieurs fois') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = ''; //???? valeur inversé ???
    
        if (Config::get('npds.setCookies') == 0) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xsetCookies_y" name="xsetCookies" value="0" ' . $cky . ' />
                        <label class="form-check-label" for="xsetCookies_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xsetCookies_n" name="xsetCookies" value="1" ' . $ckn . '/>
                        <label class="form-check-label" for="xsetCookies_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xpollcomm">' . __d('npds', 'Activer les commentaires des sondages') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.pollcomm') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xpollcomm_y" name="xpollcomm" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xpollcomm_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xpollcomm_n" name="xpollcomm" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xpollcomm_n" >' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            //<![CDATA[
            tog(\'opt_sond\',\'show_opt_sond\',\'hide_opt_sond\');
            //]]>
            </script>
        </fieldset>
        <fieldset>
        <legend><a class="tog" id="show_para_illu" title="' . __d('npds', 'Replier la liste') . '"><i id="i_para_illu" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;' . __d('npds', 'Paramètres liés à l\'illustration') . '</legend>
            <div id="para_illu" class="adminsidefield card card-body mb-3" style="display:none;">
                <div class="row">
                    <div class="col-lg-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="xtipath" id="xtipath" value="' . Config::get('npds.tipath') . '" maxlength="100" />
                        <label for="xtipath">' . __d('npds', 'Chemin des images des sujets') . '</label>
                        <span class="help-block text-end" id="countcar_xtipath"></span>
                    </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="xuserimg" id="xuserimg" value="' . Config::get('npds.userimg') . '" maxlength="100" />
                        <label for="xuserimg">' . __d('npds', 'Chemin de certaines images (vote, ...)') . '</label>
                        <span class="help-block text-end" id="countcar_xuserimg"></span>
                    </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="xadminimg" id="xadminimg" value="' . Config::get('npds.adminimg') . '" maxlength="100" />
                        <label for="xadminimg">' . __d('npds', 'Chemin des images du menu administrateur') . '</label>
                        <span class="help-block text-end" id="countcar_xadminimg"></span>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xadmingraphic">' . __d('npds', 'Activer les images dans le menu administration') . '</label>
                    <div class="col-sm-8 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.admingraphic') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xadmingraphic_y" name="xadmingraphic" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xadmingraphic_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xadmingraphic_n" name="xadmingraphic" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xadmingraphic_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>';
    
        echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xadmf_ext">' . __d('npds', 'Extension des fichiers d\'image') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" type="text" name="xadmf_ext" id="xadmf_ext" value="' . Config::get('npds.admf_ext', "gif") . '" maxlength="3" />
                    <span class="help-block text-end" id="countcar_xadmf_ext"></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="xshort_menu_admin">' . __d('npds', 'Activer les menus courts pour l\'administration') . '</label>
                    <div class="col-sm-8 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.short_menu_admin') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xshort_menu_admin_y" name="xshort_menu_admin" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xshort_menu_admin_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xshort_menu_admin_n" name="xshort_menu_admin" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xshort_menu_admin_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            //<![CDATA[
            tog("para_illu","show_para_illu","hide_para_illu");
            //]]>
            </script>
        </fieldset>
        <fieldset>
        <legend><a class="tog" id="show_divers" title="' . __d('npds', 'Replier la liste') . '"><i id="i_divers" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;' . __d('npds', 'Divers') . '</legend>
            <div id="divers" class="adminsidefield card card-body mb-3" style="display:none;">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xadmart">' . __d('npds', 'Nombres d\'articles en mode administration') . '</label>
                    <div class="col-sm-4">
                    <select class="form-select" id="xadmart" name="xadmart">
                        <option value="' . Config::get('npds.admart') . '">' . Config::get('npds.admart') . '</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="30">30</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xminpass">' . __d('npds', 'Longueur minimum du mot de passe des utilisateurs') . '</label>
                    <div class="col-sm-4">
                    <select class="form-select" id="xminpass" name="xminpass">
                        <option value="' . Config::get('npds.minpass') . '">' . Config::get('npds.minpass') . '</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="8">8</option>
                        <option value="10">10</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xshow_user">' . __d('npds', 'Nombre d\'utilisateurs listés') . '</label>
                    <div class="col-sm-4">
                    <select class="form-select" id="xshow_user" name="xshow_user">
                        <option value="' . Config::get('npds.show_user') . '">' . Config::get('npds.show_user') . '</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xsmilies">' . __d('npds', 'Activer les avatars') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.smilies') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xsmilies_y" name="xsmilies" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xsmilies_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xsmilies_n" name="xsmilies" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xsmilies_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xavatar_size">' . __d('npds', 'Taille maximum des avatars personnels (largeur * hauteur / 60*80) en pixel') . '</label>
                    <div class="col-sm-4">';
    
        echo '
                    <input class="form-control" type="text" id="xavatar_size" name="xavatar_size" value="' . Config::get('npds.avatar_size', "60*80") . '" size="11" maxlength="10" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xshort_user">' . __d('npds', 'Activer la description simplifiée des utilisateurs') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.short_user') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xshort_user_y" name="xshort_user" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xshort_user_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xshort_user_n" name="xshort_user" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xshort_user_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xAutoRegUser">' . __d('npds', 'Autoriser la création automatique des membres') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        $AutoRegUser = Config::get('npds.AutoRegUser');

        if (($AutoRegUser == '') and ($AutoRegUser != 0)) 
            $AutoRegUser = 1;
    
        if ($AutoRegUser == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xAutoRegUser_y" name="xAutoRegUser" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xAutoRegUser_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xAutoRegUser_n" name="xAutoRegUser" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xAutoRegUser_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xmemberpass">' . __d('npds', 'Autoriser les utilisateurs à choisir leur mot de passe') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        $memberpass = Config::get('npds.memberpass');

        if (($memberpass == '') and ($memberpass != 0)) 
            $memberpass = 1;
    
        if ($memberpass == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmemberpass_y" name="xmemberpass" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xmemberpass_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmemberpass_n" name="xmemberpass" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xmemberpass_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xsubscribe">' . __d('npds', 'Autoriser les abonnements') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if ($subscribe == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xsubscribe_y" name="xsubscribe" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xsubscribe_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xsubscribe_n" name="xsubscribe" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xsubscribe_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xmember_invisible">' . __d('npds', 'Autoriser les membres invisibles') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.member_invisible') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmember_invisible_y" name="xmember_invisible" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xmember_invisible_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmember_invisible_n" name="xmember_invisible" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xmember_invisible_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xCloseRegUser">' . __d('npds', 'Fermer les nouvelles inscriptions') . '</label>
                    <div class="col-sm-4 my-2">';
    
        if ((Config::get('npds.CloseRegUser') == '') and (Config::get('npds.CloseRegUser') != 1)) 
            $AutoRegUser = 0; // ????????
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.CloseRegUser') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xCloseRegUser_y" name="xCloseRegUser" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xCloseRegUser_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xCloseRegUser_n" name="xCloseRegUser" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xCloseRegUser_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xhttpref">' . __d('npds', 'Activer les référants HTTP') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';

        $httpref = Config::get('npds.httpref');

        if ($httpref == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xhttpref_y" name="xhttpref" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xhttpref_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xhttpref_n" name="xhttpref" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xhttpref_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xhttprefmax">' . __d('npds', 'Combien de référants au maximum') . '</label>
                    <div class="col-sm-4">
                    <select class="form-select" id="xhttprefmax" name="xhttprefmax">
                        <option value="' . Config::get('npds.httprefmax') . '">' . Config::get('npds.httprefmax') . '</option>
                        <option value="100">100</option>
                        <option value="250">250</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                        <option value="2000">2000</option>
                        <option value="4000">4000</option>
                        <option value="8000">8000</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xmember_list">' . __d('npds', 'Liste des membres') . ' : ' . __d('npds', 'Privé') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.member_list') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmember_list_y" name="xmember_list" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xmember_list_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xmember_list_n" name="xmember_list" value="0" ' . $ckn . ' />
                        <label class="form-check-label" for="xmember_list_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xdownload_cat">' . __d('npds', 'Rubrique de téléchargement') . '</label>
                    <div class="col-sm-4">
                    <select class="form-select" id="xdownload_cat" name="xdownload_cat">
                        <option value="' . Config::get('npds.download_cat') . '">' . aff_langue(Config::get('npds.download_cat')) . '</option>';
    
        $result = sql_query("SELECT distinct dcategory FROM downloads");
    
        while (list($category) = sql_fetch_row($result)) {
            $category = stripslashes($category);
    
            echo '<option value="' . $category . '">' . aff_langue($category) . '</option>';
        }
    
        echo '
                        <option value="' . __d('npds', 'Tous') . '">- ' . __d('npds', 'Tous') . '</option>
                        <option value="' . __d('npds', 'Aucune catégorie') . '">- ' . __d('npds', 'Aucune catégorie') . '</option>
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xshort_review">' . __d('npds', 'Critiques') . ' : ' . __d('npds', 'courtes') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.short_review') == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xshort_review_y" name="xshort_review" value="1" ' . $cky . ' />
                        <label class="form-check-label" for="xshort_review_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xshort_review_n" name="xshort_review" value="0" ' . $ckn . '/>
                        <label class="form-check-label" for="xshort_review_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            //<![CDATA[
            tog("divers","show_divers","hide_divers");
            //]]>
            </script>
        </fieldset>
        <fieldset>
        <legend><a class="tog" id="show_divers_http" title="' . __d('npds', 'Replier la liste') . '"><i id="i_divers_http" class="fa fa-caret-down fa-lg text-primary" ></i>&nbsp;</a>' . __d('npds', 'Divers') . ' HTTP</legend>
            <div id="divers_http" class="adminsidefield card card-body mb-3" style="display:none;">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xrss_host_verif">' . __d('npds', 'Pour les grands titres de sites de news, activer la vérification de l\'existance d\'un web sur le Port 80') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.rss_host_verif') == true) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xrss_host_verif_y" name="xrss_host_verif" value="true" ' . $cky . ' />
                        <label class="form-check-label" for="xrss_host_verif_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xrss_host_verif_n" name="xrss_host_verif" value="false" ' . $ckn . ' />
                        <label class="form-check-label" for="xrss_host_verif_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xcache_verif">' . __d('npds', 'Pour les pages HTML générées, activer les tags avancés de gestion du cache') . '</label>
                    <div class="col-sm-4 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.cache_verif') == true) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xcache_verif_y" name="xcache_verif" value="true" ' . $cky . ' />
                        <label class="form-check-label" for="xcache_verif_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xcache_verif_n" name="xcache_verif" value="false" ' . $ckn . ' />
                        <label class="form-check-label" for="xcache_verif_n">' . __d('npds', 'Non') . '</label> <span class="small help-text">(Multimania)</span>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-8" for="xdns_verif">' . __d('npds', 'Activer la résolution DNS pour les posts des forums, IP-Ban, ...') . '</label>';
        
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.dns_verif') == true) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '<div class="col-sm-4 my-2">
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xdns_verif_y" name="xdns_verif" value="true" ' . $cky . ' />
                        <label class="form-check-label" for="xdns_verif_y">' . __d('npds', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="xdns_verif_n" name="xdns_verif" value="false" ' . $ckn . ' />
                        <label class="form-check-label" for="xdns_verif_n">' . __d('npds', 'Non') . '</label>
                    </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                //<![CDATA[ 
                tog(\'divers_http\',\'show_divers_http\',\'hide_divers_http\');
                //]]>
            </script>
        </fieldset>
        <fieldset>
            <legend><a class="tog" id="show_divers_syst" title="' . __d('npds', 'Replier la liste') . '"><i id="i_divers_syst" class="fa fa-caret-down fa-lg text-primary" ></i>&nbsp;</a>' . __d('npds', 'Divers') . ' SYSTEM</legend>
            <div id="divers_syst" class="adminsidefield card card-body mb-3" style="display:none;">';
    

        $savemysql_size = Config::get('npds.savemysql_size');

        if (!$savemysql_size)
            $savemysql_size = '256';
        else {
            if ($savemysql_size == '256') 
                $sel_size256 = 'selected="selected"';
            else 
                $sel_size256 = '';
    
            if ($savemysql_size == '512') 
                $sel_size512 = 'selected="selected"';
            else 
                $sel_size512 = '';
    
            if ($savemysql_size == '1024') 
                $sel_size1024 = 'selected="selected"';
            else 
                $sel_size1024 = '';
        }
    
        echo '
        <div class="form-floating mb-3">
            <select class="form-select" id="xsavemysql_size" name="xsavemysql_size">
                <option value="256" ' . $sel_size256 . '>256 Ko</option>
                <option value="512" ' . $sel_size512 . '>512 Ko</option>
                <option value="1024" ' . $sel_size1024 . '>1024 Ko</option>
            </select>
            <label class="text-primary" for="xsavemysql_size">' . __d('npds', 'Taille maximum des fichiers de sauvegarde SaveMysql') . '</label>
        </div>';
    
        $savemysql_mode = Config::get('npds.savemysql_mode');

        if (!$savemysql_mode)
            $savemysql_mode = '1';
        else {
            $type_save1 = $savemysql_mode == '1' ? 'selected="selected"' : '';
            $type_save2 = $savemysql_mode == '2' ? 'selected="selected"' : '';
            $type_save3 = $savemysql_mode == '3' ? 'selected="selected"' : '';
        }
    
        echo '
        <div class="form-floating mb-3">
            <select class="form-select" id="xsavemysql_mode" name="xsavemysql_mode">
                <option value="1" ' . $type_save1 . '>' . __d('npds', 'Toute tables. Fichier envoyé au navigateur. Pas de limite de taille') . '</option>
                <option value="2" ' . $type_save2 . '>' . __d('npds', 'Fichiers dans /slogs. table par table, tables non scindées : limite') . '&nbsp;' . $savemysql_size . ' Ko</option>
                <option value="3" ' . $type_save3 . '>' . __d('npds', 'Fichiers dans /slogs. table par table, lignes par lignes, tables scindées : limite') . '&nbsp;' . $savemysql_size . ' Ko</option>
            </select>
            <label class="text-primary" for="xsavemysql_mode">' . __d('npds', 'Type de sauvegarde SaveMysql') . '</label>
        </div>
        <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xtiny_mce">' . __d('npds', 'Activer l\'éditeur Tinymce') . '</label>';
    
        $cky = '';
        $ckn = '';
    
        if (Config::get('npds.tiny_mce')) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
            <div class="col-sm-8 my-2">
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" id="xtiny_mce_y" name="xtiny_mce" value="true" ' . $cky . ' />
                    <label class="form-check-label" for="xtiny_mce_y">' . __d('npds', 'Oui') . '</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" id="xtiny_mce_n" name="xtiny_mce" value="false" ' . $ckn . ' />
                    <label class="form-check-label" for="xtiny_mce_n">' . __d('npds', 'Non') . '</label>
                </div>
            </div>
        </div>
    
        </div>
        <script type="text/javascript">
            //<![CDATA[
            tog(\'divers_syst\',\'show_divers_syst\',\'hide_divers_syst\');
            //]]>
        </script>
        </fieldset>
        <input type="hidden" name="op" value="ConfigSave" />
        <div class="my-3">
            <button class="btn btn-primary" type="submit">' . __d('npds', 'Sauver les modifications') . '</button>
        </div>
        </form>';
    
        $fv_parametres = '
        xadmin_cook_duration: {
            validators: {
                regexp: {
                    regexp:/^\d{1,10}$/,
                    message: "0-9"
                },
                between: {
                    min: 1,
                    max: 9999999999,
                    message: "1 ... 9999999999"
                }
            }
        },
        xuser_cook_duration: {
            validators: {
                regexp: {
                    regexp:/^\d{1,10}$/,
                    message: "0-9"
                },
                between: {
                    min: 1,
                    max: 9999999999,
                    message: "1 ... 9999999999"
                }
            }
        },
        xtop: {
            validators: {
                regexp: {
                    regexp:/^[1-9]\d{0,4}$/,
                    message: "0-9"
                },
                between: {
                    min: 1,
                    max: 9999,
                    message: "1 ... 9999"
                }
            }
        },
        xstoryhome: {
            validators: {
                regexp: {
                    regexp:/^[1-9]\d{0,4}$/,
                    message: "0-9"
                },
                between: {
                    min: 1,
                    max: 9999,
                    message: "1 ... 9999"
                }
            }
        },
        xoldnum: {
            validators: {
                regexp: {
                    regexp:/^[1-9]\d{0,4}$/,
                    message: "0-9"
                },
                between: {
                    min: 1,
                    max: 9999,
                    message: "1 ... 9999"
                }
            }
        },
        xmyIP: {
            validators: {
                ip: {
                    message: "Please enter a valid IP address"
                }
            }
        },
        xlever: {
            validators: {
                regexp: {
                    regexp: /^(2[0-3]|[0-1][0-9]):([0-5][0-9])$/,
                    message: "00:00"
                }
            }
        },
        xcoucher: {
            validators: {
                regexp: {
                    regexp: /^(2[0-3]|[0-1][0-9]):([0-5][0-9])$/,
                    message: "00:00"
                }
            }
        },
        xtroll_limit: {
            validators: {
                regexp: {
                    regexp:/^[1-9](\d{0,2})$/,
                    message: "0-9"
                },
                between: {
                    min: 1,
                    max: 999,
                    message: "1 ... 999"
                }
            }
        },
        xadminmail: {
            validators: {
                emailAddress: {
                    message: "' . __d('npds', 'Merci de fournir une nouvelle adresse Email valide.') . '",
                }
            }
        },
        xsmtp_host: {
            validators: {
                notEmpty: {
                    enabled: true,
                },
            },
        },
        xsmtp_port: {
            validators: {
                notEmpty: {
                    enabled: true,
                },
            },
        },
        xsmtp_username: {
            validators: {
                notEmpty: {
                    enabled: true,
                },
            },
        },
        xsmtp_password: {
            validators: {
                notEmpty: {
                    enabled: true,
                },
            },
        },
        !###!
        xmail1.addEventListener("change", function (e) {
            if(e.target.checked) {
                fvitem.disableValidator("xsmtp_host");
                fvitem.disableValidator("xsmtp_port");
                fvitem.disableValidator("xsmtp_username");
                fvitem.disableValidator("xsmtp_password");
                smtp.style.display="none";
            }
        });
        xmail2.addEventListener("change", function (e) {
            if(e.target.checked) {
                fvitem.enableValidator("xsmtp_host");
                fvitem.enableValidator("xsmtp_port");
                smtp.style.display="flex";
            }
            fvitem.revalidateField("xsmtp_host");
            fvitem.revalidateField("xsmtp_port");
        });
        auth_y.addEventListener("change", function (e) {
            if(e.target.checked) {
                fvitem.enableValidator("xsmtp_username");
                fvitem.enableValidator("xsmtp_password");
                auth.style.display="flex";
            }
            fvitem.revalidateField("xsmtp_username");
            fvitem.revalidateField("xsmtp_password");
        });
        auth_n.addEventListener("change", function (e) {
            if(e.target.checked) {
                fvitem.disableValidator("xsmtp_username");
                fvitem.disableValidator("xsmtp_password");
                auth.style.display="none"
            }
        });
    
        secu_y.addEventListener("change", function (e) {
            e.target.checked ? chifr.style.display="block" : chifr.style.display="none" ;
        });
        secu_n.addEventListener("change", function (e) {
            e.target.checked ? chifr.style.display="none" : chifr.style.display="block" ;
        });
    
        if(xmail1.checked) {
            fvitem.disableValidator("xsmtp_host");
            fvitem.disableValidator("xsmtp_port");
            fvitem.disableValidator("xsmtp_username");
            fvitem.disableValidator("xsmtp_password");
            smtp.style.display="none";
        }
        if(auth_n.checked) {
            fvitem.disableValidator("xsmtp_username");
            fvitem.disableValidator("xsmtp_password");
            auth.style.display="none";
        }
        ';
    
        $arg1 = '
        const settingspref = document.getElementById("settingspref");
        const smtp = document.getElementById("smtp");
        const auth = document.getElementById("auth");
        const chifr = document.getElementById("chifr");
        const xmail1 = document.querySelector("#xmail_fonction1");
        const xmail2 = document.querySelector("#xmail_fonction2");
        const auth_n = document.querySelector("#xsmtp_auth_n");
        const auth_y = document.querySelector("#xsmtp_auth_y");
        const secu_n = document.querySelector("#xsmtp_secure_n");
        const secu_y = document.querySelector("#xsmtp_secure_y");
    
        xmail2.checked ? "" : smtp.style.display="none" ;// no need ?...
        secu_y.checked ? "" : chifr.style.display="none" ;
    
        var formulid = ["settingspref"];
        inpandfieldlen("xsitename",100);
        inpandfieldlen("xTitlesitename",100);
        inpandfieldlen("xnuke_url",200);
        inpandfieldlen("xsite_logo",255);
        inpandfieldlen("xslogan",100);
        inpandfieldlen("xstartdate",30);
        inpandfieldlen("xanonymous",25);
        inpandfieldlen("xstart_page",100);
        inpandfieldlen("xlocale",100);
        inpandfieldlen("xlever",5);
        inpandfieldlen("xcoucher",5);
        inpandfieldlen("xgmt",5);
        inpandfieldlen("xbackend_title",100);
        inpandfieldlen("xbackend_language",10);
        inpandfieldlen("xbackend_image",200);
        inpandfieldlen("xadminmail",254);
        inpandfieldlen("xnotify_email",254);
        inpandfieldlen("xnotify_from",254);
        inpandfieldlen("xnotify_subject",100);
        inpandfieldlen("xtipath",100);
        inpandfieldlen("xuserimg",100);
        inpandfieldlen("xadminimg",100);
        inpandfieldlen("xadmf_ext",3);
        ';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }

    function ConfigSave($xparse, $xsitename, $xnuke_url, $xsite_logo, $xslogan, $xstartdate, $xadminmail, $xtop, $xstoryhome, $xoldnum, $xultramode, $xanonpost, $xDefault_Theme, $xbanners, $xmyIP, $xfoot1, $xfoot2, $xfoot3, $xfoot4, $xbackend_title, $xbackend_language, $xbackend_image, $xbackend_width, $xbackend_height, $xlanguage, $xlocale, $xperpage, $xpopular, $xnewlinks, $xtoplinks, $xlinksresults, $xlinks_anonaddlinklock, $xnotify, $xnotify_email, $xnotify_subject, $xnotify_message, $xnotify_from, $xmoderate, $xanonymous, $xmaxOptions, $xsetCookies, $xtipath, $xuserimg, $xadminimg, $xadmingraphic, $xadmart, $xminpass, $xhttpref, $xhttprefmax, $xpollcomm, $xlinkmainlogo, $xstart_page, $xsmilies, $xOnCatNewLink, $xEmailFooter, $xshort_user, $xgzhandler, $xrss_host_verif, $xcache_verif, $xmember_list, $xdownload_cat, $xmod_admin_news, $xgmt, $xAutoRegUser, $xTitlesitename, $xfilemanager, $xshort_review, $xnot_admin_count, $xadmin_cook_duration, $xuser_cook_duration, $xtroll_limit, $xsubscribe, $xCloseRegUser, $xshort_menu_admin, $xmail_fonction, $xmemberpass, $xshow_user, $xdns_verif, $xmember_invisible, $xavatar_size, $xlever, $xcoucher, $xmulti_langue, $xadmf_ext, $xsavemysql_size, $xsavemysql_mode, $xtiny_mce, $xApp_twi, $xApp_fcb, $xDefault_Skin, $xsmtp_host, $xsmtp_auth, $xsmtp_username, $xsmtp_password, $xsmtp_secure, $xsmtp_crypt, $xsmtp_port, $xdkim_auto)
    {
        include("config/config.php");
    
        if ($xparse == 0) {
            $xsitename =  FixQuotes($xsitename);
            $xTitlesitename = FixQuotes($xTitlesitename);
        } else {
            $xsitename =  stripslashes($xsitename);
            $xTitlesitename = stripslashes($xTitlesitename);
        }
    
        $xnuke_url = FixQuotes($xnuke_url);
        $xsite_logo = FixQuotes($xsite_logo);
    
        if ($xparse == 0) {
            $xslogan = FixQuotes($xslogan);
            $xstartdate = FixQuotes($xstartdate);
        } else {
            $xslogan = stripslashes($xslogan);
            $xstartdate = stripslashes($xstartdate);
        }
    
        // Theme
        $xDefault_Theme = FixQuotes($xDefault_Theme);
        if ($xDefault_Theme != Config::get('npds.Default_Theme')) {
    
            include("config/cache.config.php");
    
            $dh = opendir($CACHE_CONFIG['data_dir']);
    
            while (false !== ($filename = readdir($dh))) {
                if ($filename === '.' or $filename === '..' or $filename === 'ultramode.txt' or $filename === 'net2zone.txt' or $filename === 'sql') 
                    continue;
    
                unlink($CACHE_CONFIG['data_dir'] . $filename);
            }
        }
    
        $xmyIP = FixQuotes($xmyIP);
    
        $xfoot1 = str_replace(chr(13) . chr(10), "\n", $xfoot1);
        $xfoot2 = str_replace(chr(13) . chr(10), "\n", $xfoot2);
        $xfoot3 = str_replace(chr(13) . chr(10), "\n", $xfoot3);
        $xfoot4 = str_replace(chr(13) . chr(10), "\n", $xfoot4);
    
        if ($xparse == 0)
            $xbackend_title = FixQuotes($xbackend_title);
        else
            $xbackend_title = stripslashes($xbackend_title);
    
        $xbackend_language = FixQuotes($xbackend_language);
        $xbackend_image = FixQuotes($xbackend_image);
        $xbackend_width = FixQuotes($xbackend_width);
        $xbackend_height = FixQuotes($xbackend_height);
        $xlanguage = FixQuotes($xlanguage);
        $xlocale = FixQuotes($xlocale);
        $xnotify_email = FixQuotes($xnotify_email);
    
        if ($xparse == 0) {
            $xnotify_subject = FixQuotes($xnotify_subject);
            $xdownload_cat = FixQuotes($xdownload_cat);
        } else {
            $xnotify_subject = stripslashes($xnotify_subject);
            $xdownload_cat = stripslashes($xdownload_cat);
        }
    
        $xnotify_message = str_replace(chr(13) . chr(10), "\n", $xnotify_message);
    
        $xnotify_from = FixQuotes($xnotify_from);
        $xanonymous = FixQuotes($xanonymous);
        $xtipath = FixQuotes($xtipath);
        $xuserimg = FixQuotes($xuserimg);
        $xadminimg = FixQuotes($xadminimg);
    
        $file = fopen("config/config.php", "w");
    
        $line = "######################################################################\n";
        $content = "<?php\n";
        $content .= "$line";
        $content .= "# DUNE by App : Net Portal Dynamic System\n";
        $content .= "# ===================================================\n";
        $content .= "#\n";
        $content .= "# This version name App Copyright (c) 2001-" . date("Y") . " by Philippe Brunier\n";
        $content .= "#\n";
        $content .= "# This module is to configure the main options for your site\n";
        $content .= "#\n";
        $content .= "# This program is free software. You can redistribute it and/or modify\n";
        $content .= "# it under the terms of the GNU General Public License as published by\n";
        $content .= "# the Free Software Foundation; either version 3 of the License.\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "$line";
        $content .= "# ========================\n";
        $content .= "# Database & System Config\n";
        $content .= "# ========================\n";
        $content .= "# dbhost:      MySQL Database Hostname\n";
        $content .= "# dbuname:     MySQL Username\n";
        $content .= "# dbpass:      MySQL Password\n";
        $content .= "# dbname:      MySQL Database Name\n";
        $content .= "# mysql_p:     Persistent connection to MySQL Server (1) or Not (0)\n";
        $content .= "# mysql_i:     Use MySQLi (1) instead of MySQL interface (0)\n";
        $content .= "# =======================\n";
        $content .= "# not_used1:  unused\n";
        $content .= "# not_used2:  unused\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$dbhost = \"$dbhost\";\n";
        $content .= "\$dbuname = \"$dbuname\";\n";
        $content .= "\$dbpass = \"$dbpass\";\n";
        $content .= "\$dbname = \"$dbname\";\n";
    
        $mysql_p = Config::get('npds.mysql_p');

        if (!isset($mysql_p)) 
            $mysql_p = 1;

        $mysql_i = Config::get('npds.mysql_i');

        $content .= "\$mysql_p = $mysql_p;\n";
    
        if (!isset($mysql_i)) 
            $mysql_i = 0;
    
        $content .= "\$mysql_i = $mysql_i;\n";
        $content .= "# =======================\n";
        $content .= "\$not_used1 = '';\n";
        $content .= "\$not_used2 = '';\n";
        $content .= "\n";
        $content .= "/*********************************************************************/\n";
        $content .= "/* You finished to configure the Database. Now you can change all    */\n";
        $content .= "/* you want in the Administration Section.   To enter just launch    */\n";
        $content .= "/* you web browser pointing to http://yourdomain.com/admin.php       */\n";
        $content .= "/*                                                                   */\n";
        $content .= "/* At the prompt use the following ID to login (case sensitive):     */\n";
        $content .= "/*                                                                   */\n";
        $content .= "/* AdminID: Root                                                     */\n";
        $content .= "/* Password: Password                                                */\n";
        $content .= "/*                                                                   */\n";
        $content .= "/* Be sure to change inmediately the Root login & password clicking  */\n";
        $content .= "/* on Edit Admin in the Admin menu. After that, click on Preferences */\n";
        $content .= "/* to configure your new site. In that menu you can change all you   */\n";
        $content .= "/* need to change.                                                   */\n";
        $content .= "/*                                                                   */\n";
        $content .= "/*********************************************************************/\n";
        $content .= "\n\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# General Site Configuration\n";
        $content .= "#\n";
        $content .= "# \$parse:          Select the parse function you want to use for preference\n";
        $content .= "# \$gzhandler:      PHP > 5.x : default 0 / PHP < 5.x sending compressed html with zlib : 1 - be careful\n";
        $content .= "# \$admin_cook_duration : Duration in hour for Admin cookie (default 24)\n";
        $content .= "# \$user_cook_duration: Duration in hour for Admin cookie (default 24)\n";
        $content .= "# \$sitename:       Your Site Name\n";
        $content .= "# \$Titlesitename:  Your Site Phrase for the Title (html Title Tag) off the HTML Page\n";
        $content .= "# \$nuke_url:       Complete URL for your site (Do not put / at end)\n";
        $content .= "# \$site_logo:      Logo for Printer Friendly Page (It's good to have a Black/White graphic)\n";
        $content .= "# \$slogan:         Your site's slogan\n";
        $content .= "# \$startdate:      Start Date to display in Statistic Page\n";
        $content .= "# \$moderate:       Moderation of comments\n";
        $content .= "# \$anonpost:       Allow Anonymous to Post Comments? (1=Yes 0=No)\n";
        $content .= "# \$troll_limit:    Maximum Number off Comments per user (24H)\n";
        $content .= "# \$mod_admin_news  Allow only Moderator and Admin to Post News? (1=Yes 0=No)\n";
        $content .= "# \$not_admin_count Don't record Admin's Hits in stats (1=Yes=>don't rec 0=No=>rec)\n";
        $content .= "# \$Default_Theme:  Default Theme for your site (See /themes directory for the complete list, case sensitive!)\n";
        $content .= "# \$Default_Skin:   Default Skin for Theme ... with skins (See /assets/skins directory for the complete list, case sensitive!)\n";
        $content .= "# \$Start_Page:     Default Page for your site (default : index.php but you can use : topics.php, links.php ...)\n";
        $content .= "# \$foot(x):        Messages for all footer pages (Can include HTML code)\n";
        $content .= "# \$anonymous:      Anonymous users Default Name\n";
        $content .= "# \$not_used3:      unused\n";
        $content .= "# \$minpass:        Minimum character for users passwords\n";
        $content .= "# \$show_user:      Number off user showed in memberslist page\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$parse = \"$xparse\";\n";
        $content .= "\$gzhandler = \"$xgzhandler\";\n";
        $content .= "\$admin_cook_duration = \"$xadmin_cook_duration\";\n";
        $content .= "\$user_cook_duration = \"$xuser_cook_duration\";\n";
        $content .= "\$sitename = \"$xsitename\";\n";
        $content .= "\$Titlesitename = \"$xTitlesitename\";\n";
        $content .= "\$nuke_url = \"$xnuke_url\";\n";
        $content .= "\$site_logo = \"$xsite_logo\";\n";
        $content .= "\$slogan = \"$xslogan\";\n";
        $content .= "\$startdate = \"$xstartdate\";\n";
        $content .= "\$anonpost = $xanonpost;\n";
    
        if (!$xtroll_limit) 
            $xtroll_limit = 6;
    
        $content .= "\$troll_limit = $xtroll_limit;\n";
        $content .= "\$moderate = $xmoderate;\n";
        $content .= "\$mod_admin_news = $xmod_admin_news;\n";
        $content .= "\$not_admin_count = $xnot_admin_count;\n";
        $content .= "\$Default_Theme = \"$xDefault_Theme\";\n";
    
        if (substr($xDefault_Theme, -3) != "_sk") 
            $xDefault_Skin = '';
    
        $content .= "\$Default_Skin = \"$xDefault_Skin\";\n";
        $content .= "\$Start_Page = \"$xstart_page\";\n";
        $content .= "\$foot1 = \"$xfoot1\";\n";
        $content .= "\$foot2 = \"$xfoot2\";\n";
        $content .= "\$foot3 = \"$xfoot3\";\n";
        $content .= "\$foot4 = \"$xfoot4\";\n";
        $content .= "\$anonymous = \"$xanonymous\";\n";
        $content .= "\$not_used3 = '';\n";
        $content .= "\$minpass = $xminpass;\n";
        $content .= "\$show_user = $xshow_user;\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# General Stories Options\n";
        $content .= "#\n";
        $content .= "# \$top:       How many items in Top Page?\n";
        $content .= "# \$storyhome: How many stories to display in Home Page?\n";
        $content .= "# \$oldnum:    How many stories in Old Articles Box?\n";
        $content .= "$line";
        $content .= "\n";
    
        if (!$xtop) 
            $xtop = 10;
    
        $content .= "\$top = $xtop;\n";
    
        if (!$xstoryhome) 
            $xstoryhome = 10;
    
        $content .= "\$storyhome = $xstoryhome;\n";
    
        if (!$xoldnum) 
            $xoldnum = 10;
    
        $content .= "\$oldnum = $xoldnum;\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# Banners/Advertising Configuration\n";
        $content .= "#\n";
        $content .= "# \$banners: Activate Banners Ads for your site? (1=Yes 0=No)\n";
        $content .= "# \$myIP:    Write your IP number to not count impressions, be fair about this!\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$banners = $xbanners;\n";
        $content .= "\$myIP = \"$xmyIP\";\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# XML/RDF Backend Configuration & Social Networks\n";
        $content .= "#\n";
        $content .= "# \$backend_title:    Backend title, can be your site's name and slogan\n";
        $content .= "# \$backend_language: Language format of your site\n";
        $content .= "# \$backend_image:    Image logo for your site\n";
        $content .= "# \$backend_width:    Image logo width\n";
        $content .= "# \$backend_height:   Image logo height\n";
        $content .= "# \$ultramode:        Activate ultramode plain text and XML files backend syndication? (1=Yes 0=No). locate in /cache directory\n";
        $content .= "# \$npds_twi:         Activate the Twitter syndication? (1=Yes 0=No).\n";
        $content .= "# \$npds_fcb:         Activate the Facebook syndication? (1=Yes 0=No).\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$backend_title = \"$xbackend_title\";\n";
        $content .= "\$backend_language = \"$xbackend_language\";\n";
        $content .= "\$backend_image = \"$xbackend_image\";\n";
        $content .= "\$backend_width = \"$xbackend_width\";\n";
        $content .= "\$backend_height = \"$xbackend_height\";\n";
        $content .= "\$ultramode = $xultramode;\n";
    
        if (!$xApp_twi) 
            $xApp_twi = 0;
    
        $content .= "\$npds_twi = $xApp_twi;\n";
    
        if (!$xApp_fcb) 
            $xApp_fcb = 0;
    
        $content .= "\$npds_fcb = $xApp_fcb;\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# Site Language Preferences\n";
        $content .= "#\n";
        $content .= "# \$language:     Language of your site (You need to have lang-xxxxxx.php file for your selected language in the /language directory of your site)\n";
        $content .= "# \$locale:       Locale configuration to correctly display date with your country format. (See /usr/share/locale)\n";
        $content .= "# \$gmt:          Locale configuration to correctly display date with your GMT offset.\n";
        $content .= "# \$lever:        HH:MM where Day become.\n";
        $content .= "# \$coucher:      HH:MM where Night become.\n";
        $content .= "# \$multi_langue: Activate Multi-langue App'capability.\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$language = \"$xlanguage\";\n";
        $content .= "\$multi_langue = $xmulti_langue;\n";
        $content .= "\$locale = \"$xlocale\";\n";
        $content .= "\$gmt = \"$xgmt\";\n";
        $content .= "\$lever = \"$xlever\";\n";
        $content .= "\$coucher = \"$xcoucher\";\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# Web Links Preferences\n";
        $content .= "#\n";
        $content .= "# \$perpage:                  How many links to show on each page?\n";
        $content .= "# \$popular:                  How many hits need a link to be listed as popular?\n";
        $content .= "# \$newlinks:                 How many links to display in the New Links Page?\n";
        $content .= "# \$toplinks:                 How many links to display in The Best Links Page? (Most Popular)\n";
        $content .= "# \$linksresults:             How many links to display on each search result page?\n";
        $content .= "# \$links_anonaddlinklock:    Is Anonymous autorise to post new links? (0=Yes 1=No)\n";
        $content .= "# \$linkmainlogo:             Activate Logo on Main web Links Page (1=Yes 0=No)\n";
        $content .= "# \$OnCatNewLink:             Activate Icon for New Categorie on Main web Links Page (1=Yes 0=No)\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$perpage = $xperpage;\n";
        $content .= "\$popular = $xpopular;\n";
        $content .= "\$newlinks = $xnewlinks;\n";
        $content .= "\$toplinks = $xtoplinks;\n";
        $content .= "\$linksresults = $xlinksresults;\n";
        $content .= "\$links_anonaddlinklock = $xlinks_anonaddlinklock;\n";
        $content .= "\$linkmainlogo = $xlinkmainlogo;\n";
        $content .= "\$OnCatNewLink = $xOnCatNewLink;\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# Function Mail and Notification of News Submissions\n";
        $content .= "#\n";
        $content .= "# \$adminmail:      Site Administrator's Email\n";
        $content .= "# \$mail_fonction:  What Mail function to be used (1=mail, 2=email)\n";
        $content .= "# \$notify:         Notify you each time your site receives a news submission? (1=Yes 0=No)\n";
        $content .= "# \$notify_email:   Email, address to send the notification\n";
        $content .= "# \$notify_subject: Email subject\n";
        $content .= "# \$notify_message: Email body, message\n";
        $content .= "# \$notify_from:    account name to appear in From field of the Email\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$adminmail = \"$xadminmail\";\n";
        $content .= "\$mail_fonction = \"$xmail_fonction\";\n";
        $content .= "\$notify = $xnotify;\n";
        $content .= "\$notify_email = \"$xnotify_email\";\n";
        $content .= "\$notify_subject = \"$xnotify_subject\";\n";
        $content .= "\$notify_message = \"$xnotify_message\";\n";
        $content .= "\$notify_from = \"$xnotify_from\";\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# Survey/Polls Config\n";
        $content .= "#\n";
        $content .= "# \$maxOptions: Number of maximum options for each poll\n";
        $content .= "# \$setCookies: Set cookies to prevent visitors vote twice in a period of 24 hours? (0=Yes 1=No)\n";
        $content .= "# \$pollcomm:   Activate comments in Polls? (1=Yes 0=No)\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$maxOptions = $xmaxOptions;\n";
        $content .= "\$setCookies = $xsetCookies;\n";
        $content .= "\$pollcomm = $xpollcomm;\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# Some Graphics Options\n";
        $content .= "#\n";
        $content .= "# \$tipath:       Topics images path (put / only at the end, not at the begining)\n";
        $content .= "# \$userimg:      User images path (put / only at the end, not at the begining)\n";
        $content .= "# \$adminimg:     Administration system images path (put / only at the end, not at the begining)\n";
        $content .= "# \$admingraphic: Activate graphic menu for Administration Menu? (1=Yes 0=No)\n";
        $content .= "# \$short_menu_admin: Activate short Administration Menu? (1=Yes 0=No)\n";
        $content .= "# \$admf_ext:     Image Files'extesion for admin menu (default: gif)\n";
        $content .= "# \$admart:       How many articles to show in the admin section?\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$tipath = \"$xtipath\";\n";
        $content .= "\$userimg = \"$xuserimg\";\n";
        $content .= "\$adminimg = \"$xadminimg\";\n";
        $content .= "\$short_menu_admin = $xshort_menu_admin;\n";
        $content .= "\$admingraphic = $xadmingraphic;\n";
    
        if (!$xadmf_ext) {
            $xadmf_ext = "gif";
        }
    
        $content .= "\$admf_ext = \"$xadmf_ext\";\n";
        $content .= "\$admart = $xadmart;\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# HTTP Referers Options\n";
        $content .= "#\n";
        $content .= "# \$httpref:    Activate HTTP referer logs to know who is linking to our site? (1=Yes 0=No)";
        $content .= "# \$httprefmax: Maximum number of HTTP referers to store in the Database (Try to not set this to a high number, 500 ~ 1000 is Ok)\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$httpref = $xhttpref;\n";
        $content .= "\$httprefmax = $xhttprefmax;\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# Miscelaneous Options\n";
        $content .= "#\n";
        $content .= "# \$smilies:          Activate Avatar? (1=Yes 0=No)\n";
        $content .= "# \$avatar_size:      Maximum size for uploaded avatars in pixel (width*height) \n";
        $content .= "# \$short_user:       Activate Short User registration (without ICQ, MSN, ...)? (1=Yes 0=No)\n";
        $content .= "# \$member_list:      Make the members List Private (only for members) or Public (Private=Yes Public=No)\n";
        $content .= "# \$download_cat:     Witch category do you want to show first in download section?\n";
        $content .= "# \$AutoRegUser:      Allow automated new-user creation (sending email and allowed connection)\n";
        $content .= "# \$short_review:     For transform reviews like \"gold book\" (1=Yes, 0=no)\n";
        $content .= "# \$subscribe:        Allow your members to subscribe to topics, ... (1=Yes, 0=no)\n";
        $content .= "# \$member_invisible: Allow members to hide from other members, ... (1=Yes, 0=no)\n";
        $content .= "# \$CloseRegUser:     Allow you to close New Member Registration (from Gawax Idea), ... (1=Yes, 0=no)\n";
        $content .= "# \$memberpass:       Allow user to choose alone the password (1=Yes, 0=no)\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$smilies = $xsmilies;\n";
        $content .= "\$avatar_size = \"$xavatar_size\";\n";
        $content .= "\$short_user = $xshort_user;\n";
        $content .= "\$member_list = $xmember_list;\n";
        $content .= "\$download_cat = \"$xdownload_cat\";\n";
        $content .= "\$AutoRegUser = $xAutoRegUser;\n";
        $content .= "\$short_review = $xshort_review;\n";
        $content .= "\$subscribe = $xsubscribe;\n";
        $content .= "\$member_invisible = $xmember_invisible;\n";
        $content .= "\$CloseRegUser = $xCloseRegUser;\n";
        $content .= "\$memberpass = $xmemberpass;\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# HTTP Miscelaneous Options\n";
        $content .= "#\n";
        $content .= "# \$rss_host_verif: Activate the validation of the existance of a web on Port 80 for Headlines (true=Yes false=No)\n";
        $content .= "# \$cache_verif:    Activate the Advance Caching Meta Tag (pragma ...) (true=Yes false=No)\n";
        $content .= "# \$dns_verif:      Activate the DNS resolution for posts (forum ...), IP-Ban, ... (true=Yes false=No)\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$rss_host_verif = $xrss_host_verif;\n";
        $content .= "\$cache_verif = $xcache_verif;\n";
        $content .= "\$dns_verif = $xdns_verif;\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# SYSTEM Miscelaneous Options\n";
        $content .= "#\n";
        $content .= "# \$savemysql_size:  Determine the maximum size for one file in the SaveMysql process\n";
        $content .= "# \$savemysql_mode:  Type of Myql process (1, 2 or 3)\n";
        $content .= "# \$tiny_mce:        true=Yes or false=No to use tiny_mce Editor or NO Editor\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$savemysql_size = $xsavemysql_size;\n";
        $content .= "\$savemysql_mode = $xsavemysql_mode;\n";
        $content .= "\$tiny_mce = $xtiny_mce;\n";
        $content .= "\n";
        $content .= "$line";
        $content .= "# Do not touch the following options !\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$Npds_Prefix = \"$Npds_Prefix\";\n";
    
        $Npds_Key = Config::get('npds.Npds_Key');

        if ($Npds_Key == '') 
            $Npds_Key = uniqid("");
    
        $content .= "\$Npds_Key = \"$Npds_Key\";\n";
        $content .= "\$Version_Num = \"v.16.8\";\n";
        $content .= "\$Version_Id = \"App\";\n";
        $content .= "\$Version_Sub = \"REvolution\";\n";
        $content .= "\n";
        $content .= "?>";
    
        fwrite($file, $content);
        fclose($file);
    
        $file = fopen("config/filemanager.php", "w");
    
        $content = "<?php\n";
        $content .= "# ========================================\n";
        $content .= "# DUNE by App : Net Portal Dynamic System\n";
        $content .= "# ========================================\n";
        $content .= "\$filemanager= $xfilemanager;\n";
        $content .= "?>";
    
        fwrite($file, $content);
        fclose($file);
    
        $xEmailFooter = str_replace(chr(13) . chr(10), "\n", $xEmailFooter);
    
        $file = fopen("config/signat.php", "w");
    
        $content = "<?php\n";
        $content .= "$line";
        $content .= "# DUNE by App : Net Portal Dynamic System\n";
        $content .= "# ===================================================\n";
        $content .= "#\n";
        $content .= "# This version name App Copyright (c) 2001-" . date("Y") . " by Philippe Brunier\n";
        $content .= "#\n";
        $content .= "# This module is to configure Footer of Email send By App\n";
        $content .= "#\n";
        $content .= "# This program is free software. You can redistribute it and/or modify\n";
        $content .= "# it under the terms of the GNU General Public License as published by\n";
        $content .= "# the Free Software Foundation; either version 3 of the License.\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "\$message .= \"$xEmailFooter\";\n";
    
        $content .= "?>";
        fwrite($file, $content);
        fclose($file);
    
        $file = fopen("config/phpmailer.php", "w");
    
        $content = "<?php\n";
        $content .= "$line";
        $content .= "# DUNE by App : Net Portal Dynamic System\n";
        $content .= "# ===================================================\n";
        $content .= "#\n";
        $content .= "# This version name App Copyright (c) 2001-" . date("Y") . " by Philippe Brunier\n";
        $content .= "#\n";
        $content .= "# This file is to configure PHPMailer to send email from App portal\n";
        $content .= "#\n";
        $content .= "# This program is free software. You can redistribute it and/or modify\n";
        $content .= "# it under the terms of the GNU General Public License as published by\n";
        $content .= "# the Free Software Foundation; either version 3 of the License.\n";
        $content .= "$line";
        $content .= "\n";
        $content .= "# Configurer le serveur SMTP\n";
        $content .= "\$smtp_host = \"$xsmtp_host\";\n";
        $content .= "# Port TCP, utilisez 587 si vous avez activé le chiffrement TLS\n";
        $content .= "\$smtp_port = \"$xsmtp_port\";\n";
        $content .= "# Activer l'authentification SMTP\n";
        $content .= "\$smtp_auth = $xsmtp_auth;\n";
        $content .= "# Nom d'utilisateur SMTP\n";
        $content .= "\$smtp_username = \"$xsmtp_username\";\n";
        $content .= "# Mot de passe SMTP\n";
        $content .= "\$smtp_password = \"$xsmtp_password\";\n";
        $content .= "# Activer le chiffrement TLS\n";
        $content .= "\$smtp_secure = $xsmtp_secure;\n";
        $content .= "# Type du chiffrement TLS\n";
        $content .= "\$smtp_crypt = \"$xsmtp_crypt\";\n";
        $content .= "# DKIM 1 pour celui du dns 2 pour une génération automatique\n";
        $content .= "\$dkim_auto = $xdkim_auto;\n";
        $content .= "?>";
        
        fwrite($file, $content);
        fclose($file);
    
        global $aid;
        Ecr_Log("security", "ConfigSave() by AID : $aid", "");
    
        SC_Clean();
    
        Header("Location: admin.php?op=AdminMain");
    }

}
