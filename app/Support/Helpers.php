<?php

/**
 * [adm_translate description]
 *
 * @param   [type]  $msg  [$msg description]
 *
 * @return  [type]        [return description]
 */
function adm_translate($msg)
{
    return $msg;
}

/**
 * [translate description]
 *
 * @param   [type]  $msg  [$msg description]
 *
 * @return  [type]        [return description]
 */
function translate($msg)
{
    return $msg;
}

/**
 * [check_install description]
 *
 * @return  [type]  [return description]
 */
function check_install()
{
    // Modification pour IZ-Xinstall - EBH - JPB & PHR
    if (file_exists("storage/IZ-Xinstall.ok")) {
        if (file_exists("install.php") or is_dir("install")) {
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                    <title>App IZ-Xinstall - Installation Configuration</title>
                </head>
                <body>
                    <div style="text-align: center; font-size: 20px; font-family: Arial; font-weight: bold; color: #000000"><br />
                        App IZ-Xinstall - Installation &amp; Configuration
                    </div>
                    <div style="text-align: center; font-size: 20px; font-family: Arial; font-weight: bold; color: #ff0000"><br />
                        Vous devez supprimer le r&eacute;pertoire "install" ET le fichier "install.php" avant de poursuivre !<br />
                        You must remove the directory "install" as well as the file "install.php" before continuing!
                    </div>
                </body>
            </html>';
            die();
        }
    } else {
        if (file_exists("install.php") and is_dir("install")) {
            header("location: install.php");
        }
    }  
}
