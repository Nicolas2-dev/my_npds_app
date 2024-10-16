<?php

use Npds\Config\Config;

/**
 * 
 */
return [

    /**
     * Ne pas modifier cette ligne
     */
    'racine_fma' => dirname($_SERVER['SCRIPT_FILENAME']),

    // GENERAL

    /**
     * permet de limiter l'utilisation de F-manager
     * 
     * Tous le monde    => access_fma => '';
     * anonyme          => access_fma => 'anonyme';
     * membre           => access_fma => 'membre';
     *  groupe '2,5'    => access_fma => '2,5';
     *                  => s'il existe un fichier de configuration portant le nom du groupe ALORS tous les membres du groupe partagent le même fichier
     *                  => Attention - cela s'arrête au premier groupe qui rempli la condition
     *  admin           => access_fma => 'admin'
     */
    'access_fma' => 'membre',

    /**
     * permet de choisir le tri utilisé et son sens
     * 
     * D : Date
     * S : Size
     * N : Name (defaut)
     * ASC  : Sens ascendant
     * DESC : Sens descendant (defaut)
     * 
     * tri_fma => [
     *     'tri'  => 'S',
     *     'sens' => 'DESC',
     * ]
     */
    'tri_fma' => array(
        'tri'  => 'N',
        'sens' => 'ASC',
    ),

    // REPERTOIRES

    /**
     * Vous pouvez limiter la navigation à un sous-répertoire se trouvant sous la racine de votre site
     * 
     * basedir_fma => fmanager.config.default.racine_fma.'/static'
     */
    'basedir_fma' => function ($cookie) {
        return Config::get('fmanager.default.racine_fma') .'/storage/users_private/'. $cookie[1];
    },

    /**
     * permet de contrôler la navigation dans des sous-répertoires
     * 
     * CETTE LIMITE s'etend à tout le système de fichier !!
     * 
     * anonyme  => ce répertoire n'est visible que par les anonymes
     * membre   => ce répertoire n'est visible que par les membre
     * '2,5'    => ce répertoire n'est visible que par les membre du(des) groupes x,y,...
     * '-2,-5'  => ce répertoire sera visible par Tous les membres sauf ceux du(des) groupes x,y,...
     * admin    => ce répertoire n'est visible que par les administrateurs
     * 
     * dirlimit_fma =>[
     *     'ftp' => 'anonyme',
     *     'static' => 'membre',
     *     'documentations de développements' => '2,5',
     *     'admin' => 'admin'
     * ] 
     */
    'dirlimit_fma' => [
         'mns'            => 999,
    ],
 
    /**
     * permet d'afficher la taille des répertoires
     * 
     * ATTENTION cette fonction peut-être consommatrice de CPU si vos répertoires contiennent de nombreux fichiers
     */
    'dirsize_fma' => true,

    /**
     * permet de contrôler les informations affichées relatives aux répertoires (0 non affiché / 1 affiché)
     * 
     * position 1 = icone
     * position 2 = nom et lien sur le répertoire
     * position 3 = Date
     * position 4 = Taille
     * position 5 = Permissions
     * position 6 = Pic-Manager
     * 
     * dirpres_fma => 111011
     */
    'dirpres_fma' => 111111,

    /**
     * permet de contrôler les actions autorisées relatives aux répertoires (0 non-autorisé / 1 autorisé)
     * 
     * position 1 = create
     * position 2 = rename
     * position 3 = delete
     * position 4 = chmod
     * position 5 = not used
     * 
     * dircmd_fma => 10000
     */
    'dircmd_fma' => 00000,

    /**
     * permet de définir la liste des extensions valide
     * 
     * extension_fma => 'doc xls pps ppt sxw xls sxi sxd sxg stw rtf txt pdf zip rar tar tgz gif jpg jpeg png swf mp3'
     * 
     * extension_fma => '*'; : tous les types de fichiers sont autorisés
     */
    'extension_fma' => 'doc xls pps ppt sxw xls sxi sxd sxg stw rtf txt pdf zip rar tar tgz gif jpg jpeg png swf mp3',

    /**
     * permet de définir la liste des extensions qui seront éditables
     * 
     * extension_Edit_fma => 'txt php js html htm'
     */
    'extension_Edit_fma' => '',

    /**
     * permet de définir la liste des extensions Editables qui supporteront un editeur Wysiwyg (TinyMce par exemple)
     * 
     * extension_Wysiwyg_fma => 'html htm'
     */
    'extension_Wysiwyg_fma' => '',

    /**
     * permet de contrôler l'affichage de certains fichiers (.htaccess, config.php ...)
     * 
     * CETTE LIMITE s'étend à tous le système de fichier !!
     * 
     * anonyme  => ce fichier n'est visible que par les anonymes
     * membre   => ce fichier n'est visible que par les membres
     * '2,5'    => ce fichier n'est visible que par les membres du(des) groupes x,y,...
     * '-2,-5'  => ce fichier sera visible par Tous les membres sauf ceux du(des) groupes x,y,...
     * admin    => ce fichier n'est visible que par les administrateurs
     * 
     * ficlimit_fma => [
     *     'license.txt' => 'anonyme',
     *     'developpement-modules-V1.2.pdf' => 'membre',
     *     'edito.txt' => '2,5',
     *     'config.php' => 'admin'
     * ]
     */
    'ficlimit_fma' => [
        '.htaccess'           => 999,
        'config.php'          => 999,
        'pic-manager.txt'     => 999,
        'index.html'          => 999,
        'upload.conf.php'     => 999,
    ],

    /**
     * permet d'inclure automatiquement un fichier particulier (par exemple une bannière ...) s'il se trouve dans le répertoire courant
     */
    'infos_fma' => 'infos.txt',
    
    /**
     * permet de ne pas afficher le fichier dans la liste des fichiers ... car il est affecté à un groupe qui n'existe pas !
     */
    'ficlimit_fma[\'infos.txt\']' => 999, 

    /**
     * permet de contrôler les informations affichées relatives aux fichiers (0 non affiché / 1 affiché)
     * 
     * position 1 = icone
     * position 2 = nom et lien sur le fichier
     * position 3 = Date
     * position 4 = Taille
     * position 5 = Permissions
     * 
     * ficpres_fma => 11101
     */
    'ficpres_fma' => 11111,

    /**
     * permet de contrôler les actions autorisées relatives aux fichiers (0 non-autorisé / 1 autorisé)
     * 
     * position 1 = create / upload
     * position 2 = rename
     * position 3 = delete
     * position 4 = chmod
     * position 5 = edit
     * position 6 = move
     * 
     * ficcmd_fma => 100011
     */
    'ficcmd_fma' => 101010,
 
    /**
     * permet d'adjoindre un fichier de type xxxxx.mod.php associé à celui-ci et contenant une variable ($url_modifier)
     * qui permet de modifier le comportement du lien se trouvant sur les fichiers affichés par FMA
     * 
     * voir le comportement du fichier download.conf.php ET download.mod.php
     */
    'url_fma_modifier' => false,

    // THEME 
 
    /**
     * Vous pouvez spécifier les fichiers de thème utilisés par ce fichier de configuration fichier du thème général
     */
    'themeG_fma' => 'f-manager',
    
    /**
     * fichier utilisé lors des actions (delete, edit, ...)
     */
    'themeC_fma' => 'f-manager-cmd',

    /**
     * Vous pouvez spécifier la représentation de la racine
     * 
     * home_fma => '';          => représentation standard
     * home_fma => 'Home';      => Un texte
     * home_fma => '<img ...>'; => Une image
     */
    'home_fma' => '',

    /**
     * permet d'inclure le files-manager dans le thème de npds
     */
    'npds_fma' => false,

    /**
     * d'inclure la css d'un thème 
     * 
     * Cette option n'a de sens que si npds_fma => false
     */
    'css_fma' => function() {
        // NOTE: gestion du theme a revoir, non finalise

        if ((Config::get('fmanager.default.npds_fma') === false) 
        and (file_exists(theme_path(Config::get('npds.Default_Theme') .'/assets/css/f-manager.css')))) 
        {
            $css = site_url('themes/'. Config::get('npds.Default_Theme') .'/assets/css/f-manager.css');
        } else { 
            $css = site_url('themes/'. Config::get('npds.Default_Theme') .'/assets/css/style.css');
        }
         
        return $css;
    },

    /**
     * permet de spécifier si une seule fenêtre fille est utilisée (0 : Non / 1 : Oui) lors d'une demande d'affichage
     * 
     * Attention cette option peut être incompatible avec certaines utilisation du File-Manager
     * 
     * wopenH_fma permet de spécifier la hauteur de la fenêtre fille (par défaut 500)
     * wopenW_fma permet de spécifier la largeur de la fenêtre fille (par défaut 400)
     * 
     * wopenH_fma => 500
     * wopenW_fma => 400
     * 
     * wopenH_fma et $wopenW_fma ne servent que si $wopen_fma=true ...
     */
    'wopen_fma' => true,

    /**
     * permet de passer de F-manager à Pic-manager (vis et versa) dans une seule fenêtre
     */
    'uniq_fma' => false,

    /**
     * permet de passer une variable complémentaire définie localement dans le fichier de configuration
     * 
     * urlext_fma => '&amp;groupe=$groupe'
     */
    'urlext_fma' => '',

];
