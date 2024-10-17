<?php

use Npds\Config\Config;

/**
 * 
 */
return [

    /**
     * Répertoire serveur de la racine du site (avec le / terminal)
     */
    // 'DOCUMENTROOT' => function ($DOCUMENTROOT)
    // {
    //     if ($DOCUMENTROOT == '') {
    //         global $DOCUMENT_ROOT;
    //         if ($DOCUMENT_ROOT) {
    //             $DOCUMENTROOT = $DOCUMENT_ROOT;
    //         } else {
    //             $DOCUMENTROOT = $_SERVER['DOCUMENT_ROOT'];
    //         }
    //     }

    //     return $DOCUMENTROOT;
    // },

    /**
     * Répertoire de téléchargement (avec le / terminal)
     */
    'rep_upload_forum' => config('upload.rep_upload') . 'upload_forum/',

    // Max size
    /**
     * 
     */
    'max_file_size_total' => config('upload.quota'),
    
    /**
     * 
     */
    'max_file_size' => config('upload.max_size'), 


    // Divers / Don't modify !

    /**
     * 
     */
    'insert_base' => true,
    
    /**
     * 
     */
    'visible_forum' => 1,

    /**
     * Autoriser les utilisateurs à uploader des fichier dans la rédaction des messages.
     * vous pouvez alors préciser quelles extentions sont autorisées
     * (si rien n'est spécifié, toute les extentions sont autorisées)
     * 
     * (saisissez les extensions de fichiers (ex : .gif) que vous souhaitez
     * autoriser pour l'envoi des fichiers, séparés par des espaces, virgules
     * ou point-virgule)
     */
    'bn_allowed_extensions' => function($extension_autorise = '') {
        if (!empty($extension_autorise)) {
            return str_replace(' ', ' .', $extension_autorise);
        } else {
            return str_replace(' ', ' .', config('upload.extension_autorise'));

        }
    },
    
    /**
     * quelles extentions sont interdites (si rien n'est spécifié, aucune extention n'est interdite) 
     * 
     * (saisissez les extensions de fichiers (ex : .gif) que vous souhaitez
     * interdire pour l'envoi des fichiers, séparés par des espaces, virgules
     * ou point-virgule)
     */
    'bn_banned_extensions' => '.php .php3 .phps .htpasswd',

    /**
     * Autoriser les utilisateurs à uploader des fichier dans la rédaction des messages.
     * vous pouvez alors préciser quelles mimetypes sont autorisées
     * (si rien n'est spécifié, toute les mimetypes sont autorisées)
     */
    'bn_allowed_mimetypes' => '',
    
    /**
     * quelles mimetypes sont interdites
     * 
     * (si rien n'est spécifié, aucune mimetypes n'est interdite)
     */
    'bn_banned_mimetypes' => '',

    /**
     * ne sert plus a rien mais a confirmer !!!
     */
    // 'upload_conf' => 1,

];
