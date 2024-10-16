<?php


global $user;

include_once("modules/$ModPath/lang/Config::get('npds.language').php");
// For More security

if (isset($user) and $user != '') {
    global $cookie;

    if ($cookie[9] != '') {
        $ibix = explode('+', urldecode($cookie[9]));
        if (array_key_exists(0, $ibix)) 
            $theme = $ibix[0];
        else 
            $theme = Config::get('npds.Default_Theme');

        if (array_key_exists(1, $ibix)) 
            $skin = $ibix[1];
        else 
            $skin = Config::get('npds.Default_Skin');

        $tmp_theme = $theme;

        if (!$file = @opendir("themes/$theme")) 
            $tmp_theme = Config::get('npds.Default_Theme');
    } else
        $tmp_theme = Config::get('npds.Default_Theme');
} else {
    $theme = Config::get('npds.Default_Theme');
    $skin = Config::get('npds.Default_Skin');
    $tmp_theme = $theme;
}

$Titlesitename = "Npds wspad";

include("storage/meta/meta.php");

echo '<link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon" />';

echo import_css($tmp_theme, $skin, '', '');

echo '
    </head>
    <body style="padding: 10px; background:#ffffff;">';

$wspad = rawurldecode(decrypt($pad));
$wspad = explode("#wspad#", $wspad);

$row = sql_fetch_assoc(sql_query("SELECT content, modtime, editedby, ranq  FROM wspad WHERE page='" . $wspad[0] . "' AND member='" . $wspad[1] . "' AND ranq='" . $wspad[2] . "'"));

echo '
        <h2>' . $wspad[0] . '</h2>
        <span class="">[ ' . __d('wspad', 'révision') . ' : ' . $row['ranq'] . ' - ' . $row['editedby'] . " / " . date(__d('wspad', 'dateinternal'), $row['modtime'] + ((int) Config::get('npds.gmt') * 3600)) . ' ]</span>
        <hr />
        ' . aff_langue($row['content']) . '
    </body>
    </html>';
