<?php


$wspad = rawurldecode(decrypt($pad));
$wspad = explode("#wspad#", $wspad);

switch ($type) {

    case "doc":
        include "library/html2doc.php";

        $htmltodoc = new HTML_TO_DOC();
        $row = sql_fetch_assoc(sql_query("SELECT content FROM wspad WHERE page='" . $wspad[0] . "' AND member='" . $wspad[1] . "' AND ranq='" . $wspad[2] . "'"));
        
        // nettoyage des SPAN
        $tmp = preg_replace('#style="[^\"]*\"#', "", aff_langue($row['content']));
        $htmltodoc->createDoc($tmp, $wspad[0] . "-" . $wspad[2], true);
        break;
        
    default:
        break;
}
