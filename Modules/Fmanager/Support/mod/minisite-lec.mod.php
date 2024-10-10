<?php

use Npds\Config\Config;

// cette variable fonctionne si $url_fma_modifier=true;
// $url_modifier permet de modifier le comportement du lien (a href ....) se trouvant sur les fichiers affichés par FMA

if (($obj->FieldView == "jpg") or ($obj->FieldView == "gif") or ($obj->FieldView == "png") or ($obj->FieldView == "jpeg"))
    $url_modifier = Config::get('npds.tiny_mce') 
        ? "\"#\" onclick=\"javascript:parent.tinymce.activeEditor.selection.setContent('<img class=img-fluid src=getfile.php?att_id=$ibid&amp;apli=f-manager />');\"" 
        : "\"#\"";
else
    $url_modifier = Config::get('npds.tiny_mce') 
        ? "\"#\" onclick=\"javascript:parent.tinymce.activeEditor.selection.setContent('<a href=getfile.php?att_id=$ibid&amp;apli=f-manager target=_blank>" . $obj->FieldName . "</a>');\"" 
        : "\"getfile.php?att_id=$ibid&amp;apli=f-manager\"";
