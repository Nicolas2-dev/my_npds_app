<?php


use Modules\Wspad\Library\HtmlToDoc;
use Modules\Npds\Support\Facades\Crypt;
use Modules\Npds\Support\Facades\Language;


$wspad = rawurldecode(Crypt::decrypt($pad));
$wspad = explode("#wspad#", $wspad);

switch ($type) {

    case "doc":

        $htmltodoc = new HtmlToDoc();
        $row = sql_fetch_assoc(sql_query("SELECT content FROM wspad WHERE page='" . $wspad[0] . "' AND member='" . $wspad[1] . "' AND ranq='" . $wspad[2] . "'"));
        
        // nettoyage des SPAN
        $tmp = preg_replace('#style="[^\"]*\"#', "", Language::aff_langue($row['content']));
        $htmltodoc->createDoc($tmp, $wspad[0] . "-" . $wspad[2], true);
        break;
        
    default:
        break;
}
