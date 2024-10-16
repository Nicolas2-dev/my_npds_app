<?php


// since App Rev 16 this ressources are required dont remove //
echo '
<link rel="stylesheet" href="' . site_url('assets/shared/font-awesome/css/all.min.css') . '" />'; // web font V5
echo '
<link id="bsth" rel="stylesheet" href="' . site_url('assets/shared/bootstrap/dist/css/bootstrap.min.css') . '" />'; // framework
echo '
<link id="bsthxtra" rel="stylesheet" href="' . site_url('assets/shared/bootstrap/dist/css/extra.css') . '" />'; // developpement
echo '
<link rel="stylesheet" href="' . site_url('assets/shared/formvalidation/dist/css/formValidation.min.css') . '" />'; // form control
echo '
<link rel="stylesheet" href="' . site_url('assets/shared/jquery-ui/jquery-ui.min.css') . '" />'; //interface
echo '
<link rel="stylesheet" href="' . site_url('assets/shared/bootstrap-table/dist/bootstrap-table.min.css') . '" />'; // table
echo '
<link rel="stylesheet" href="' . site_url('assets/shared/prismjs/prism.css') . '" />
<script type="text/javascript" src="' . site_url('assets/shared/jquery/jquery.min.js') . '"></script>
';

if (defined('CITRON')) {
    if (method_exists(\Modules\Npds\Library\LanguageManager::class, 'language_iso'))
        echo '
        <script type="text/javascript"> var tarteaucitronForceLanguage = "' . Language::language_iso(1, '', '') . '"; </script>
        <script type="text/javascript" src="' . site_url('assets/shared/tarteaucitron/tarteaucitron.js') . '"></script>
        <script type="text/javascript">
            //<![CDATA[
                tarteaucitron.init({
                    "privacyUrl": "", /* Privacy policy url */
                    "hashtag": "#tarteaucitron", /* Ouverture automatique du panel avec le hashtag */
                    "cookieName": "tarteaucitron", /* Cookie name */
                    "orientation": "top", /* le bandeau doit être en haut (top) ou en bas (bottom) ? */
                    "showAlertSmall": true, /* afficher le petit bandeau en bas à droite ? */
                    "cookieslist": true, /* Afficher la liste des cookies installés ? */
                    "showIcon": false, /* Show cookie icon to manage cookies */
                    "iconPosition": "BottomRight", /* BottomRight, BottomLeft, TopRight and TopLeft */
                    "adblocker": false, /* Afficher un message si un adblocker est détecté */
                    "AcceptAllCta" : true, /* Show the accept all button when highPrivacy on */
                    "highPrivacy": false, /* désactiver le consentement implicite (en naviguant) ? */
                    "handleBrowserDNTRequest": false, /* If Do Not Track == 1, disallow all */
                    "removeCredit": true, /* supprimer le lien vers la source ? */
                    "moreInfoLink": true, /* Show more info link */
                    "useExternalCss": false, /* If false, the tarteaucitron.css file will be loaded */
                    "cookieDomain": "", /* Nom de domaine sur lequel sera posé le cookie - pour les multisites / sous-domaines - Facultatif */
                    "readmoreLink": "static.php?op=politiqueconf.html&App=1&metalang=1", /* Change the default readmore link */
                    "mandatory": true, /* Show a message about mandatory cookies */
                });
            //]]
        </script>'; //RGPD tool
}
