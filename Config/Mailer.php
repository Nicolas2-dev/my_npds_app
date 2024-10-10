<?php


return array(

    # Configurer le serveur SMTP
    'smtp_host' => "sandbox.smtp.mailtrap.io",

    # Port TCP, utilisez 587 si vous avez activé le chiffrement TLS
    'smtp_port' => "2525",

    # Activer l'authentification SMTP
    'smtp_auth' => true,

    # Nom d'utilisateur SMTP
    'smtp_username' => "53475359ed57b5",

    # Mot de passe SMTP
    'smtp_password' => "461c674f990dc6",

    # Activer le chiffrement TLS
    'smtp_secure' => 0,

    # Type du chiffrement TLS
    'smtp_crypt' => "tls",

    # DKIM 1 pour celui du dns 2 pour une génération automatique
    'dkim_auto' => 1,

);
