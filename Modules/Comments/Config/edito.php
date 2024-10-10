<?php

/**
 * 
 */
return [

    /**
     * racine du nom de ce fichier  (article, pollBoth, ...)
     */
    'file_name' => 'edito',

    /**
     * permet d'allouer un numéro de forum pour chaque 'type de commentaires' (article, sondage, ...)
     * le numéro de forum doit impérativement être NEGATIF
     */
    'forum' => -99,
 
    /**
     * permet d'allouer un numéro UNIQUE pour chaque publication sur laquelle
     * un commentaire peut être réalisé (article numéro X, sondage numéro Y, ...)
     */
    'topic' => 1,

    /**
     * URL de retour lorsque la soumission du commentaire est OK
     */
    'url_ret' => site_url('index?op=edito'),

    /**
     * Formulaire SFORM si vous souhaitez avoir une grille de saisie
     * en lieu et place de l'interface standard de saisie - sinon ''
     */
    'formulaire' => '',
 
    /**
     * Nombre de commentaire sur chaque page
     */
    'comments_per_page' => 2,

    // Mise à jour de champ d'une table externe à la table des commentaires
 
    /**
     * opération à effectuer lorsque je rajoute un commentaire
     */
    'comments_req_add' => '',
 
    /**
     * opération à effectuer lorsque je cache un commentaire
     */
    'comments_req_del' => '',

    /**
     * opération à effectuer lorsque je supprime tous les commentaires
     */
    'comments_req_raz' => '',

];
