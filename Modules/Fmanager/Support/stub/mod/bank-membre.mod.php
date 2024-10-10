<?php

use Npds\Config\Config;

// cette variable fonctionne si $url_fma_modifier=true;
// $url_modifier permet de modifier le comportement du lien (a href ....) se trouvant sur les fichiers affichés par FMA

if (($obj->FieldView == "jpg") or ($obj->FieldView == "gif") or ($obj->FieldView == "png") or ($obj->FieldView == "jpeg"))
    $url_modifier = Config::get('npds.tiny_mce') ?
        "\"#\" onclick=\"javascript:parent.tinymce.activeEditor.selection.setContent('<img class=img-fluid src=" . str_replace(" ", "%20", "users_private" . str_replace(dirname($basedir_fma), "", $cur_nav_back) . "/" . basename($cur_nav) . "/" . $obj->FieldName) . " />'); top.tinymce.activeEditor.windowManager.close();\"" :
        "\"#\"";
else
    $url_modifier = Config::get('npds.tiny_mce') ?
        "\"#\" onclick=\"javascript:parent.tinymce.activeEditor.selection.setContent('<a href=" . str_replace(" ", "%20", "users_private" . str_replace(dirname($basedir_fma), "", $cur_nav_back) . "/" . basename($cur_nav) . "/" . $obj->FieldName) . " target=_blank>" . $obj->FieldName . "</a>'); top.tinymce.activeEditor.windowManager.close();\"" :
        "\"" . str_replace(" ", "%20", "users_private" . str_replace(dirname($basedir_fma), "", $cur_nav_back) . "/" . basename($cur_nav) . "/" . $obj->FieldName) . "\"";
