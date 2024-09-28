<?php


use Npds\config\Config;

return array(

    /**
     * Taille maxi des fichiers en octets
     */
    'max_size' => 15680000,

    /**
     * Si votre variable $DOCUMENT_ROOT n'est pas bonne (notamment en cas de redirection)
     * vous pouvez en spécifier une ici (c'est le chemin physique d'accès à la racine de votre site en partant de / ou C:\)
     * par exemple /data/web/mon_site OU c:\web\mon_site SINON LAISSER cette variable VIDE
     */
    'DOCUMENTROOT' => 'F:/Dev_Diver/Npds/Npds_Mini/revolution_16_mini/',

    //'DOCUMENTROOT2' => null,

    /**
     * Autorise l'upload DANS le répertoire personnel du membre (true ou false)
     */
    'autorise_upload_p' => true,
 
    /**
     * Sous répertoire : n'utiliser que si votre App n'est pas directement dans la racine de votre site
     * par exemple si : www.mon_site/App/.... alors $racine="/App" (avec le / DEVANT) sinon $racine="";
     */
    'racine' => '',

    /**
     * Répertoire de téléchargement (avec le / terminal)
     */
    'rep_upload' => module_path('Upload/storage/'),

    /**
     * Répertoire de stockage des fichiers temporaires (avec le / terminal)
     */
    'rep_cache' => module_path('Upload/storage/tmp/'),

    /**
     * Répertoire/fichier de stockage de la log de téléchargement (par défaut /slogs/security.log)
     */
    'rep_log' => storage_path('logs/security.log'),

    /**
     * URL HTTP de votre site (exemple : http://www.monsite.org)  !
     */
    'url_upload' => 'http://127.0.0.1/',

    /**
     * URL de la feuille de style à utiliser pour la présentation de la fenetre d'upload (ou "")
     * pour une css dans le theme courant utiliser :
     */
    'url_upload_css' => function($theme) {

        $instance = get_instance();

        if(is_null($theme)) {
            $theme = $instance->template();
        }

        $theme_dir  = $instance->template_dir();

        $css_name = Config::get(strtolower($theme).'.config.url_upload_css_name', 'upload_theme');

        if (file_exists(theme_path($theme_dir .'/'. $theme .'/assets/css/'. $css_name .'.css'))) {
            $css = site_url('themes/'. $theme_dir .'/'. $theme .'/assets/css/'. $css_name .'.css');
        } else {
            $css = site_url('assets/shared/bootstrap/dist/css/bootstrap.min.css');
        }

        return $css;
    },

    // Divers

    /**
     * Gére l'affichage de la Banque Images et Documents : "0000" => rien / "1111" => tous
     * 1 (true) ou 0 (False)
     * 
     * 1er position   : afficher les images de !divers
     * 2ième position : afficher les images de !mime
     * 3ième position : afficher les images de la racine du répertoire (celles qui seront téléchargées)
     * 4ième position : afficher les documents
     */
    'ed_profil' => '1111',
 
    /**
     * Nombre d'image par ligne dans l'afficheur d'image de l'editeur HTML
     */
    'ed_nb_images' => 10,
 
    /**
     * suffix des fichiers autorisés (séparé par un espace)
     */
    'extension_autorise' => 'doc xls pps ppt sxw xls sxi sxd sxg stw rtf txt pdf zip rar tar tgz gif jpg jpeg png swf',

    /**
     * Taille maxi en affichage des images dans les banques de l'Editeur HTML
     */
    'width_max'  => 50,
    'height_max' => 50,

    /**
     * Limite de l'espace disque alloué pour l'upload (en octects)
     */
    'quota' => 733999999,

);
