<?php

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Support\Facades\Hack;
use Modules\Wspad\Support\Facades\Wspad;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;
use Modules\Groupes\Support\Facades\Groupe;


global $title, $user, $admin;
// For More security

if (file_exists("modules/$ModPath/pages.php")) {
    include("modules/$ModPath/pages.php");
}

// limite l'utilisation aux membres et admin

if ($user or $admin) {
    $tab_groupe = Groupe::valid_group($user);

    if (Groupe::groupe_autorisation($member, $tab_groupe)) {
        $groupe = $member;
        $auteur = $cookie[1];
    } else {
        if ($pad_membre) {
            $groupe = 1;
            $auteur = $cookie[1];
        } elseif ($admin) {
            $groupe = -127;
            $auteur = $aid;
        } else
            header("location: index.php");
    }
} else {
    header("location: index.php");
}

// Filtre les caractères interdits dans les noms de pages
$page = preg_replace('#[^a-zA-Z0-9\\s\\_\\.\\-]#i', '_', Hack::remove(stripslashes(urldecode($page))));

switch ($op) {
    case "sauve":
        $content    = Hack::remove(stripslashes(Sanitize::FixQuotes(dataimagetofileurl($content, 'modules/upload/upload/ws'))));
        $auteur     = Hack::remove(stripslashes(Sanitize::FixQuotes($auteur)));

        $row = sql_fetch_assoc(sql_query("SELECT MAX(ranq) AS ranq FROM wspad WHERE page='$page' AND member='$groupe'"));
        $result = sql_query("INSERT INTO wspad VALUES ('0', '$page', '$content', '" . time() . "', '$auteur', '" . ($row['ranq'] + 1) . "', '$groupe','')");

        sql_query("UPDATE wspad SET verrou='' WHERE verrou='$auteur'");
        
        @unlink("modules/Wspad/locks/$page-vgp-$groupe.txt");
        $mess = __d('wspad', 'révision') . " " . ($row['ranq'] + 1) . " " . __d('wspad', 'sauvegardée');
        break;

    case "supp":
        $auteur = Hack::remove(stripslashes(Sanitize::FixQuotes($auteur)));

        $result = sql_query("DELETE FROM wspad WHERE page='$page' AND member='$groupe' AND ranq='$ranq'");
        sql_query("UPDATE wspad SET verrou='' WHERE verrou='$auteur'");
        break;

    case "suppdoc":

        $result = sql_query("DELETE FROM wspad WHERE page='$page' AND member='$member'");

        @unlink("modules/Wspad/locks/$page-vgp-$groupe.txt");
        break;

    case "renomer":
        // Filtre les caractères interdits dans les noms de pages
        $newpage = preg_replace('#[^a-zA-Z0-9\\s\\_\\.\\-]#i', '_', Hack::remove(stripslashes(urldecode($newpage))));

        $result = sql_query("UPDATE wspad SET page='$newpage', verrou='' WHERE page='$page' AND member='$member'");

        @unlink("modules/Wspad/locks/$page-vgp-$groupe.txt");
        break;

    case "conv_new":
        $row = sql_fetch_assoc(sql_query("SELECT content FROM wspad WHERE page='$page' AND member='$groupe' AND ranq='$ranq'"));

        $date_debval    = date("Y-d-m H:i:s", time());
        $deb_year       = substr($date_debval, 0, 4);
        $date_finval    = ($deb_year + 99) . "-01-01 00:00:00";

        $result = sql_query("INSERT INTO queue VALUES (NULL, $cookie[0], '$auteur', '$page', '" . Sanitize::FixQuotes($row['content']) . "', '', now(), '','$date_debval','$date_finval','0')");
        break;
}

// For IE 
header("X-UA-Compatible: IE=8");

// Head banner de présentation
if (file_exists("modules/Wspad/html/head.html")) {
    $Xcontent = join('', file("modules/Wspad/html/head.html"));
    $Xcontent = Metalang::meta_lang(Language::aff_langue($Xcontent));
    
    echo $Xcontent;
}

switch ($op) {

    case 'sauve':
        Wspad::Liste_Page();
        Wspad::Page($page, ($row['ranq'] + 1));
        break;

    case 'creer':
        Wspad::Liste_Page();
        Wspad::Page($page, 1);
        break;

    case 'relo':
        Wspad::Liste_Page();
        Wspad::Page($page, $ranq);
        break;

    default:
        Wspad::Liste_Page();
        break;
}

// Foot banner de présentation
if (file_exists("modules/Wspad/html/foot.html")) {
    $Xcontent = join("", file("modules/Wspad/html/foot.html"));
    $Xcontent .= '<p class="text-end">App WsPad ' . $version . ' by Dev&nbsp;&&nbsp;Jpb&nbsp;</p>';
    $Xcontent = Metalang::meta_lang(Language::aff_langue($Xcontent));
    
    echo $Xcontent;
}

