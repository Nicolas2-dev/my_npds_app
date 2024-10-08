<?php

// cette variable fonctionne si $url_fma_modifier=true;
// $url_modifier permet de modifier le comportement du lien (a href ....) se trouvant sur les fichiers affichés par FMA
$repw = str_replace($basedir_fma, "", $cur_nav);
if ($repw != "")
    if (substr($repw, 0, 1) == "/")
        $repw = substr($repw, 1) . "/" . $obj->FieldName;
    else
        $repw = $obj->FieldName;

$url_modifier = "\"#\" onclick=\"javascript:window.opener.document.adminForm.durl.value='" . $repw . "'; window.opener.document.adminForm.dfilename.value='" . extend_ascii($obj->FieldName) . "';\"";
