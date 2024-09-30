<?php
/**
 * Npds - Modules/Users/Config/config.php
 *
 * @author  Nicolas Devoy
 * @email   nicolas@nicodev.fr 
 * @version 1.0.0
 * @date    26 Septembre 2024
 */

/*
 * Ce fichier vous permet d'envoyer un MI personnalisé lorsqu'un nouveau membre s'inscrit sur votre site
 */
return array(

    /*
     * id de l'émetteur du Message Interne lorsque un nouveau membre est créé : 1 = anonyme
     */
    'emetteur_id'   => 1,

    /*
     * sujet du MI
     */
    'sujet'         => __d('user', 'Bonjour'),

    /*
     * contenu du message (html, meta-mot, ...) SANS les <br />, App fera le nécessaire
     */
    'message'       => __d('user', 'Vous êtes maintenant un membre à part entière.
Ce site vous offre de nombreuses ressources alors ne vous privez pas : participez !

L\'équipe du site.'),
);
