<?php

use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Config\Config;
use Modules\Npds\Support\Sanitize;
use Modules\Npds\Support\Facades\Auth;
use Modules\Fmanager\Library\Navigator;
use Modules\Npds\Support\Facades\Crypt;
use Modules\Npds\Support\Facades\Cookie;
use Modules\Theme\Support\Facades\Theme;
use Modules\Module\Support\Facades\Module;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;
use Modules\Fmanager\Support\Facades\Fmanager;

$user = Auth::guard('user');

if (isset($user)) {
    
    $themelist  = Theme::list();
    $themelist  = explode(' ', $themelist);

    $pos        = array_search(Cookie::cookie_user(9), $themelist);

    if ($pos !== false) {
        Config::set('npds.Default_Theme', $themelist[$pos]);
    }
}

// Lancement sur un Répertoire en fonction d'un fichier de conf particulier
$FmaRep = Request::query('FmaRep') ?: Request::post('FmaRep');

if ($FmaRep) {
    if (Module::filtre_module($FmaRep)) {
        if (Config::has('fmanager.'. $FmaRep)) {

            if (Fmanager::fma_autorise('a', '')) {
                $theme_fma      = Config::get('fmanager.themeG_fma');
                $fic_minuscptr  = 0;
                $dir_minuscptr  = 0;
            } else {
                Access_Error();
            }
        } else {
            Access_Error();
        }
    } else {
        Access_Error();
    }
} else {
    Access_Error();
}

if ($browse != '') {
    $ibid = rawurldecode(Crypt::decrypt($browse));

    if (substr(@php_uname(), 0, 7) == "Windows") {
        $ibid = preg_replace('#[\*\?"<>|]#i', '', $ibid);
    } else {
        $ibid = preg_replace('#[\:\*\?"<>|]#i', '', $ibid);
    }

    $ibid = str_replace('..', '', $ibid);

    // contraint ‡ rester dans la zone de repertoire dÈfinie
    $ibid = $basedir_fma . substr($ibid, strlen($basedir_fma));
    $base = $ibid;
} else {
    $base = $basedir_fma;
}

// initialisation de la classe
$obj = new Navigator();

$obj->Extension = explode(' ', $extension_fma);

// Construction de la Classe
if ($obj->File_Navigator($base, $tri_fma['tri'], $tri_fma['sens'], $dirsize_fma)) {

    // Current PWD and Url_back / match by OS determination
    if (substr(@php_uname(), 0, 7) == "Windows") {
        $cur_nav = str_replace('\\', '/', $obj->Pwd());
        $cur_nav_back = dirname($base);
    } else {
        $cur_nav = $obj->Pwd();
        $cur_nav_back = str_replace('\\', '/', dirname($base));
    }

    $home = '/' . basename($basedir_fma);
    $cur_nav_href_back = "<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=" . rawurlencode(Crypt::encrypt($cur_nav_back)) . "\">" . str_replace(dirname($basedir_fma), "", $cur_nav_back) . "</a>/" . basename($cur_nav);
    
    if ($home_fma != '') {
        $cur_nav_href_back = str_replace($home, $home_fma, $cur_nav_href_back);
    }

    $cur_nav_encrypt = rawurlencode(Crypt::encrypt($cur_nav));
} else {
    // le répertoire ou sous répertoire est protégé (ex : chmod)
    Url::redirect("modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=" . rawurlencode(Crypt::encrypt(dirname($base))));
}

// gestion des types d'extension de fichiers
$handle = opendir($racine_fma .'/images/upload/file_types');

while (false !== ($file = readdir($handle))) {
    if ($file != '.' && $file != '..') {
        $prefix = strtoLower(substr($file, 0, strpos($file, '.')));

        $att_icons[$prefix] = '<img src="'. site_url('assets/images/upload/file_types/' . $file) . '" alt="" />';
    }
}
closedir($handle);

$att_icon_dir = '<i class="bi bi-folder fs-2"></i>';

// Répertoires
$subdirs = '';
$sizeofDir = 0;

while ($obj->NextDir()) {
    if (Fmanager::fma_autorise('d', $obj->FieldName)) {
        $subdirs .= '<tr>';

        $clik_url = '<a href="'. site_url('modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;FmaRep=$FmaRep&amp;browse=' . rawurlencode(Crypt::encrypt($base. '/'. $obj->FieldName))) . '">';
        
        if ($dirpres_fma[0]) {
            $subdirs .= '<td width="3%">' . $clik_url . $att_icon_dir . '</a></td>';
        }

        if ($dirpres_fma[1]) {
            $subdirs .= '<td nowrap="nowrap" width="50%">'. $clik_url . Fmanager::extend_ascii($obj->FieldName) . '</a></td>';
        }

        if ($dirpres_fma[2]) {
            $subdirs .= '<td><small>' . $obj->FieldDate . '</small></td>';
        }

        if ($dirpres_fma[3]) {
            $sizeofD    = $obj->FieldSize;
            $sizeofDir  = $sizeofDir + $sizeofD;
            $subdirs    .= '<td><small>' . $obj->ConvertSize($sizeofDir) . '</small></td>';

        } else {
            $subdirs .= '<td><small>#NA</small></td>';
        }

        $subdirs .= '</tr>';
    }
}

// Fichiers
$fp = @file("pic-manager.txt");

// La première ligne du tableau est un commentaire
// settype($fp[1], 'integer');

$Max_thumb = $fp[1];

// settype($fp[2], 'integer');

$refresh = $fp[2];

if ($refresh == 0) {
    $refresh = 3600;
}

$rep_cache          = $racine_fma . '/cache/';
$rep_cache_encrypt  = rawurlencode(Crypt::encrypt($rep_cache));
$cache_prefix       = $cookie[1] . md5(str_replace('/', '.', str_replace($racine_fma . '/', '', $cur_nav)));


if ($Max_thumb > 0) {
    $files = '<div id="photo">';

    while ($obj->NextFile()) {
        if (Fmanager::fma_autorise('f', $obj->FieldName)) {

            $suf = strtolower($obj->FieldView);

            if (($suf == 'gif') or ($suf == 'jpg') or ($suf == 'png') or ($suf == 'swf') or ($suf == 'mp3')) {
                if ($ficpres_fma[1]) {

                    $ibid = rawurlencode(Crypt::encrypt(rawurldecode($cur_nav_encrypt) . '#fma#' . Crypt::encrypt($obj->FieldName)));
                    $imagette = '';

                    if (($suf == 'gif') or ($suf == 'jpg')) {
                        if ((function_exists('gd_info')) or extension_loaded('gd')) {

                            //cached or not ?
                            if (file_exists($rep_cache . $cache_prefix . '.' . $obj->FieldName)) {
                                if (filemtime($rep_cache . $cache_prefix . '.' . $obj->FieldName) > time() - $refresh) {

                                    if (filesize($rep_cache . $cache_prefix . '.' . $obj->FieldName) > 0) {
                                        $cache      = true;
                                        $image      = Fmanager::imagesize($obj->FieldName, $Max_thumb);
                                        $imagette   = rawurlencode(Crypt::encrypt(rawurldecode($rep_cache_encrypt) . '#fma#' . Crypt::encrypt($cache_prefix . '.' . $obj->FieldName)));
                                    } else {
                                        $cache = false;
                                    }
                                } else {
                                    $cache = false;
                                }
                            } else {
                                $cache = false;
                            }

                            if (!$cache) {
                                $image = Fmanager::CreateThumb($obj->FieldName, $cur_nav, $rep_cache . $cache_prefix . '.', $Max_thumb, $suf);

                                if (array_key_exists('gene-img', $image)) {
                                    if ($image['gene-img'][0] == true) {
                                        $imagette = rawurlencode(Crypt::encrypt(rawurldecode($rep_cache_encrypt) . '#fma#' . Crypt::encrypt($cache_prefix . '.' . $obj->FieldName)));
                                    }
                                }
                            }
                        } else {
                            $image = Fmanager::imagesize($curn_nav . $obj->FieldName, $Max_thumb);
                        }
                    } else if (($suf == 'png') or ($suf == 'swf')) {
                        $image = Fmanager::imagesize($curn_nav . $obj->FieldName, $Max_thumb);
                    }

                    $h_i = $image['hauteur'][0];
                    $h_pi = $image['hauteur'][1];
                    $w_i = $image['largeur'][0];
                    $w_pi = $image['largeur'][1];;

                    switch ($suf) {
                        case 'gif':
                        case 'jpg':
                        case 'png':
                            $PopUp = "'getfile.php?att_id=$ibid&amp;apli=f-manager','PicManager','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=" . ($h_pi + 40) . ",width=" . ($w_pi + 40) . ",toolbar=no,scrollbars=yes,resizable=yes'";
                            $files .= '
                            <div class="imagethumb">
                                <a href="javascript:void(0);" onclick="popup=window.open(' . $PopUp . '); popup.focus();"><img src="getfile.php?att_id=';
                            
                            if ($imagette) {
                                $files .= $imagette;
                            } else {
                                $files .= $ibid;
                            }

                            $files .= "&amp;apli=f-manager\" border=\"0\" width=\"$w_i\" height=\"$h_i\" alt=\"$obj->FieldName\" loading=\"lazy\"/ ></a>\n";
                            $files .= '
                            </div>';
                            break;

                        case "swf":
                            $img_size = "width=\"$w_i\" height=\"$h_i\"";
                            $PopUp = "getfile.php?att_id=$ibid&amp;apli=f-manager";
                            $files .= "<div class=\"imagethumb\">";
                            $files .= "<a href=\"$PopUp\" target=\"_blank\">";
                            $files .= "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4\,0\,2\,0\" $img_size><param name=\"quality\" value=\"high\"><param name=\"src\" value=\"$PopUp\"><embed src=\"$PopUp\" quality=\"high\" pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" $img_size></embed></object>";
                            $files .= "</a>\n</div>";
                            break;

                        case "mp3":
                            $PopUp = "getfile.php?att_id=$ibid&amp;apli=f-manager";
                            $files .= "<div class=\"imagethumb\">";
                            $uniqid = uniqid("mp3");
                            $files .= "<a href=\"javascript:void(0);\" onclick=\"document.$uniqid.SetVariable('player:jsUrl', '$PopUp'); document.$uniqid.SetVariable('player:jsPlay', '');\">";
                            $files .= "<div class=\"mp3\"><p align=\"center\">" . __d('fmanager', 'Cliquer ici pour charger le fichier dans le player') . "</p><br />" . Sanitize::split_string_without_space($obj->FieldName, 24) . "</div>";
                            $files .= "<div style=\"margin-top: 10px;\">";
                            $files .= "<object id=\"" . $uniqid . "\" type=\"application/x-shockwave-flash\" data=\"modules/$ModPath/plugins/player_mp3_maxi.swf\" width=\"$Max_thumb\" height=\"15\">
                     <param name=\"movie\" value=\"modules/$ModPath/plugins/player_mp3_maxi.swf\" />
                     <param name=\"FlashVars\" value=\"showstop=1&amp;showinfo=1&amp;showvolume=1\" />
                     </object></div>";
                            $files .= "</a>\n</div>";
                            break;
                    }
                }
            }
        }
    }

    $files .= '</div>';
}

chdir("$racine_fma/");

// Génération de l'interface
$inclusion = false;

if (file_exists("themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/pic-manager.html")) {
    $inclusion = "themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/pic-manager.html";
} elseif (file_exists("themes/default/html/modules/f-manager/pic-manager.html")) {
    $inclusion = "themes/default/html/modules/f-manager/pic-manager.html";
} else {
    echo "html/modules/f-manager/pic-manager.html manquant / not find !";
}

if ($inclusion) {
    $Xcontent = join('', file($inclusion));
    $Xcontent = str_replace('_back', Fmanager::extend_ascii($cur_nav_href_back), $Xcontent);
    $Xcontent = str_replace('_refresh', '<a class="nav-link" href="modules.php?ModPath=' . $ModPath . '&amp;ModStart=' . $ModStart . '&amp;FmaRep=' . $FmaRep . '&amp;browse=' . rawurlencode($browse) . '"><i class="bi bi-arrow-clockwise fs-1 d-sm-none" title="' . __d('fmanager', 'Rafraîchir') . '" data-bs-toggle="tooltip"></i><span class="d-none d-sm-block mt-2">' . __d('fmanager', 'Rafraîchir') . '</span></a>', $Xcontent);
    $Xcontent = str_replace('_nb_subdir', ($obj->Count('d') - $dir_minuscptr), $Xcontent);

    if (($obj->Count('d') - $dir_minuscptr) == 0) {
        $Xcontent = str_replace('_classempty', 'collapse', $Xcontent);
    }

    $Xcontent = str_replace('_subdirs', $subdirs, $Xcontent);

    if ($uniq_fma) {
        $Xcontent = str_replace('_fileM', '<a class="nav-link" href="modules.php?ModPath=' . $ModPath . '&amp;ModStart=f-manager&amp;FmaRep=' . $FmaRep . '&amp;browse=' . rawurlencode($browse) . '"><i class="bi bi-folder fs-1 d-sm-none" data-bs-toggle="tooltip" title="' . __d('fmanager', 'Gestionnaire de fichiers') . '"></i><span class="d-none d-sm-block mt-2">' . __d('fmanager', 'Gestionnaire de fichiers') . '</span></a>', $Xcontent);
    } else {
        $Xcontent = str_replace('_fileM', '', $Xcontent);
    }

    if (isset($files)) {
        $Xcontent = str_replace('_files', $files, $Xcontent);
    } else {
        $Xcontent = str_replace('_files', '', $Xcontent);
    }

    if (!$App_fma) {

        // utilisation de pages.php
        // settype($PAGES, 'array');

        // require_once("themes/pages.php");

        $Titlesitename = Language::aff_langue($PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['title']);

        // global $user;

        // if (isset($user) and $user != '') {
        //     global $cookie;
        //     if ($cookie[9] != '') {
        //         $ibix = explode('+', urldecode($cookie[9]));

        //         if (array_key_exists(0, $ibix)) {
        //             $theme = $ibix[0];
        //         } else {
        //             $theme = Config::get('npds.Default_Theme');
        //         }

        //         if (array_key_exists(1, $ibix)) {
        //             $skin = $ibix[1];
        //         } else {
        //             $skin = Config::get('npds.Default_Skin'); //$skin=''; 
        //             }

        //         $tmp_theme = $theme;

        //         if (!$file = @opendir("themes/$theme")) {
        //             $tmp_theme = Config::get('npds.Default_Theme');
        //         }
        //     } else {
        //         $tmp_theme = Config::get('npds.Default_Theme');
        //     }
        // } else {
        //     $theme = Config::get('npds.Default_Theme');
        //     $skin = Config::get('npds.Default_Skin');
        //     $tmp_theme = $theme;
        // }

        include("storage/meta/meta.php");

        echo '
        <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="assets/shared/font-awesome/css/all.min.css" />
        <link rel="stylesheet" href="assets/shared/bootstrap/dist/css/bootstrap-icons.css" />
        <link rel="stylesheet" id="fw_css" href="assets/skins/' . $skin . '/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/shared/bootstrap-table/dist/bootstrap-table.min.css" />
        <link rel="stylesheet" id="fw_css_extra" href="assets/skins/' . $skin . '/extra.css" />
        <link href="' . $css_fma . '" title="default" rel="stylesheet" type="text/css" media="all" />
        <script type="text/javascript" src="assets/shared/jquey/jquery.min.js"></script>
        </head>
        <body class="p-3">';
    } 
    // else {
    //     include("header.php");
    // }

    // Head banner de présentation F-Manager
    if (file_exists("themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/head.html")) {
        echo "\n";
        include("themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/head.html");
        echo "\n";
    } else if (file_exists("themes/default/html/modules/f-manager/head.html")) {
        echo "\n";
        include("themes/default/html/modules/f-manager/head.html");
        echo "\n";
    }

    echo Metalang::meta_lang(Language::aff_langue($Xcontent));

    // Foot banner de présentation F-Manager
    if (file_exists("themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/foot.html")) {
        echo "\n";
        include("themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/foot.html");
        echo "\n";
    } else if (file_exists("themes/default/html/modules/f-manager/foot.html")) {
        echo "\n";
        include("themes/default/html/modules/f-manager/foot.html");
        echo "\n";
    }

    if (!$App_fma){
        echo '
            </body>
        </html>';
    } 
    // else {
    //     include("footer.php");
    // }

}
