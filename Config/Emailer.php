<?php


return array(

    'charset'       => 'iso-8859-1',
    'from_name'     => 'Npds Website',
    'from_email'    => 'Npds@localhost',
    'mailer'        => 'mail',           // Could be 'mail' => 'sendmail' or 'smtp'

    /** Only when using smtp as mailer: */
    'smtp_host'     => 'localhost',
    'smtp_port'     => 25,
    'smtp_secure'   => '',    // Options: '' => 'ssl' or 'tls'
    'smtp_auth'     => false, // Use SMTPAuth, (false or true)
    'smtp_user'     => '',    // Only when using SMTPAuth
    'smtp_pass'     => '',    // Only when using SMTPAuth
    'smtp_authtype' => ''     // Options are LOGIN (default), PLAIN, NTLM, CRAM-MD5. Blank when not use SMTPAuth.
);
