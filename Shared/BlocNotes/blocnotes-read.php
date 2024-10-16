<?php

/************************************************************************/
/* DUNE by App                                                         */
/* ===========================                                          */
/*                                                                      */
/* BLOC-NOTES engine for App - Philippe Brunier & Arnaud Latourrette   */
/*                                                                      */
/* App Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (strstr($bnid, '..') || strstr($bnid, './') || stristr($bnid, 'script') || stristr($bnid, 'cookie') || stristr($bnid, 'iframe') || stristr($bnid, 'applet') || stristr($bnid, 'object') || stristr($bnid, 'meta'))
    die();


$result = sql_query("SELECT texte FROM blocnotes WHERE bnid='$bnid'");

if (sql_num_rows($result) > 0) {
    list($texte) = sql_fetch_row($result);

    $texte = stripslashes($texte);
    $texte = str_replace(chr(13) . chr(10), "\\n", str_replace("'", "\'", $texte));
    
    echo '$(function(){ $("#texteBlocNote_' . $bnid . '").val(unescape("' . str_replace('"', '\\"', $texte) . '")); })';
}
