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


if ($uriBlocNote) {
    if ($typeBlocNote == "shared")
        $bnid = md5($nomBlocNote);
    elseif ($typeBlocNote == "context") {
        if ($nomBlocNote == "\$username") {

            global $cookie, $admin;
            $nomBlocNote = $cookie[1];
            $cur_admin = explode(':', base64_decode($admin));

            if ($cur_admin)
                $nomBlocNote = $cur_admin[0];
        }

        if (stristr(urldecode($uriBlocNote), "article.php"))
            $bnid = md5($nomBlocNote . substr(urldecode($uriBlocNote), 0, strpos(urldecode($uriBlocNote), "&")));
        else
            $bnid = md5($nomBlocNote . urldecode($uriBlocNote));
    } else
        $bnid = '';

    if ($bnid) {
        if ($supBlocNote == 'RAZ')
            sql_query("DELETE FROM blocnotes WHERE bnid='$bnid'");
        else {
            sql_query("LOCK TABLES blocnotes WRITE");
            $result = sql_query("SELECT texte FROM blocnotes WHERE bnid='$bnid'");
            
            if (sql_num_rows($result) > 0) {
                if ($texteBlocNote != '')
                    sql_query("UPDATE blocnotes SET texte='" . removeHack($texteBlocNote) . "' WHERE bnid='$bnid'");
                else
                    sql_query("DELETE FROM blocnotes WHERE bnid='$bnid'");
            } else {
                if ($texteBlocNote != '')
                    sql_query("INSERT INTO blocnotes (bnid, texte) VALUES ('$bnid', '" . removeHack($texteBlocNote) . "')");
            }
            sql_query("UNLOCK TABLES");
        }
    }
    header("location: " . urldecode($uriBlocNote));
} else
    header("location: index.php");
