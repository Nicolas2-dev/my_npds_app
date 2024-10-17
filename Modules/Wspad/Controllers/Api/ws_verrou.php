<?php


// For More security
if (!stristr($_SERVER['HTTP_REFERER'], "modules.php?ModPath=wspad&ModStart=wspad")) {
    die();
}

$verrou_page = stripslashes(htmlspecialchars(urldecode($verrou_page), ENT_QUOTES, 'utf-8')); 
$verrou_user = stripslashes(htmlspecialchars(urldecode($verrou_user), ENT_QUOTES, 'utf-8'));
// For More security

// For IE cache control
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-chek=0", false);
header("Pragma: no-cache");
// For IE cache control

$fp = fopen("locks/$verrou_page-vgp-$verrou_groupe.txt", 'w');
fwrite($fp, $verrou_user);
fclose($fp);
