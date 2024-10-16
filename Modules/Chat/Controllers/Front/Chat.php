<?php

namespace Modules\chat\Controllers\Front;

use Modules\Npds\Core\FrontController;

class Chat extends FrontController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    // protected $pdst = 0; // Note : pas de blocks pour le chat


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();              
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * [after description]
     *
     * @param   [type]  $result  [$result description]
     *
     * @return  [type]           [return description]
     */
    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function chatinput()
    {
        // chatbox avec salon privatif - on utilise id pour filtrer les messages -> id = l'id du groupe au sens autorisation de App (-127,-1,0,1,2...126))

        if ($id === '' || unserialize(decrypt($auto)) != $id) 
            die();

        // Savoir si le 'connecté' a le droit à ce chat ?
        // le problème c'est que tous les groupes qui existent on le droit au chat ... donc il faut trouver une solution pour pouvoir l'interdire
        // soit on vient d'un bloc qui par définition autorise en fabricant l'interface
        // soit on viens de WS et là ....

        if (!autorisation($id)) 
            die();

        global $user;

        // if (isset($user) and $user != '') {
        //     global $cookie;

        //     if ($cookie[9] != '') {
        //         $ibix = explode('+', urldecode($cookie[9]));
        //         if (array_key_exists(0, $ibix)) 
        //             $theme = $ibix[0];
        //         else 
        //             $theme = Config::get('npds.Default_Theme');

        //         if (array_key_exists(1, $ibix)) 
        //             $skin = $ibix[1];
        //         else 
        //             $skin = Config::get('npds.Default_Skin'); //$skin=''; 

        //         $tmp_theme = $theme;

        //         if (!$file = @opendir("themes/$theme")) 
        //             $tmp_theme = Config::get('npds.Default_Theme');
        //     } else
        //         $tmp_theme = Config::get('npds.Default_Theme');
        // } else {
        //     $theme = Config::get('npds.Default_Theme');
        //     $skin = Config::get('npds.Default_Skin');
        //     $tmp_theme = $theme;
        // }

        $Titlesitename = 'Npds';

        include("storage/meta/meta.php");

        echo import_css($tmp_theme, Config::get('npds.language'), $skin, basename($_SERVER['PHP_SELF']), '');

        include("library/javascript/formhelp.java.php");

        echo '</head>';

        // cookie chat_info (1 par groupe)
        echo '<script type="text/javascript" src="assets/js/cookies.js"></script>';
        echo "<body id=\"chat\" onload=\"setCookie('chat_info_$id', '1', '');\" onUnload=\"deleteCookie('chat_info_$id');\">";
        echo'
            <script type="text/javascript" src="assets/shared/jquery/jquery.min.js"></script>
            <script type="text/javascript" src="assets/shared/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
            <link rel="stylesheet" href="assets/shared/font-awesome/css/all.min.css">
            <form name="coolsus" action="chatinput.php" method="post">
            <input type="hidden" name="op" value="set" />
            <input type="hidden" name="id" value="' . $id . '" />
            <input type="hidden" name="auto" value="' . $auto . '" />';

        if (!isset($cookie[1]))
            $pseudo = ((isset($name)) ? ($name) : urldecode(getip()));
        else
            $pseudo = $cookie[1];

        $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';

        echo __d('chat', 'Vous êtes connecté en tant que :') . ' <strong>' . $pseudo . '</strong>&nbsp;';
        echo '<input type="hidden" name="name" value="' . $pseudo . '" />
            <textarea id="chatarea" class="form-control my-3" type="text" rows="2" ' . $xJava . ' placeholder="🖋"></textarea>
            <div class="float-end">';

        putitems("chatarea");

        echo '</div>
            <input class="btn btn-primary btn-sm" type="submit" tabindex="1" value="' . __d('chat', 'Valider') . '" />
            </form>
            <script src="assets/js/npds_adapt.js"></script>
                <script type="text/javascript">
                    //<![CDATA[
                        document.coolsus.message.focus();
                    //]]>
                </script>
            </body>
        </html>';

        // settype($op, 'string');

        switch ($op) {
            case 'set':
                if (!isset($cookie[1]) && isset($name)) {
                    $uname = $name;
                    $dbname = 0;
                } else {
                    $uname = $cookie[1];
                    $dbname = 1;
                }
                
                insertChat($uname, $message, $dbname, $id);
                break;
        }

    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function chatrafraich()
    {
        // chatbox avec salon privatif - on utilise id pour filtrer les messages -> id = l'id du groupe au sens autorisation de App (-127,-1,0,1,2...126))

        if ($id === '' || unserialize(decrypt($auto)) != $id)
            die();

        // Savoir si le 'connecté' a le droit à ce chat ?
        if (!autorisation($id))
            die();

        global $user;

        // if (isset($user) and $user != '') {
        //     global $cookie;

        //     if ($cookie[9] != '') {
        //         $ibix = explode('+', urldecode($cookie[9]));

        //         if (array_key_exists(0, $ibix))
        //             $theme = $ibix[0];
        //         else
        //             $theme = Config::get('npds.Default_Theme');

        //         if (array_key_exists(1, $ibix))
        //             $skin = $ibix[1];
        //         else
        //             $skin = Config::get('npds.Default_Skin'); //$skin=''; 

        //         $tmp_theme = $theme;
        //         if (!$file = @opendir("themes/$theme"))
        //             $tmp_theme = Config::get('npds.Default_Theme');
        //     } else
        //         $tmp_theme = Config::get('npds.Default_Theme');
        // } else {
        //     $theme = Config::get('npds.Default_Theme');
        //     $skin = Config::get('npds.Default_Skin');
        //     $tmp_theme = $theme;
        // }

        $result = sql_query("SELECT username, message, dbname, date FROM chatbox WHERE id='$id' AND date>'$repere' ORDER BY date ASC");
        $thing = '';

        if ($result) {
            include("themes/themes-dynamic/theme.php");

            while (list($username, $message, $dbname, $date_message) = sql_fetch_row($result)) {
                $thing .= "<div class='chatmessage'><div class='chatheure'>" . date(__d('chat', 'Chatdate'), $date_message + ((int) Config::get('npds.gmt') * 3600)) . "</div>";

                if ($dbname == 1) {
                    if ((!$user) and (Config::get('npds.member_list') == 1) and (!$admin))
                        $thing .= "<div class='chatnom'>$username</div>";
                    else
                        $thing .= "<div class='chatnom'><div class='float-start'> " . str_replace('"', '\"', userpopover($username, 36, 1)) . "</div> <a href='user.php?op=userinfo&amp;uname=$username' target='_blank'>$username</a></div>";
                } else
                    $thing .= "<div class='chatnom'>$username</div>";

                $message = smilie($message);

                $chat_forbidden_words = array(
                    "'\"'i" => '&quot;',
                    "'OxOA'i" => '',
                    "'OxOD'i" => '',
                    "'\n'i" => '',
                    "'\r'i" => '',
                    "'\t'i" => ''
                );

                $message = preg_replace(array_keys($chat_forbidden_words), array_values($chat_forbidden_words), $message);
                $message = str_replace('"', '\"', make_clickable($message));
                $thing .= "<div class='chattexte'>" . removeHack($message) . "</div></div>";
                $repere = $date_message;
            }

            $thing = "\"" . $thing . "\"";
        }

        if ($aff_entetes == '1') {
            $meta_op = true;

            settype($Xthing, 'string');

            include("storage/meta/meta.php");

            $Xthing .= $l_meta;

            $Xthing .= str_replace("\n", "", import_css_javascript($tmp_theme, Config::get('npds.language'), $skin, basename($_SERVER['PHP_SELF']), ""));
            $Xthing .= "</head><body id='chat'>";
            $Xthing = "\"" . str_replace("'", "\'", $Xthing) . "\"";
        }

        $result = sql_query("SELECT DISTINCT ip FROM chatbox WHERE id='$id' and date >= " . (time() - (60 * 2)) . "");
        $numofchatters = sql_num_rows($result);

        $rafraich_connectes = 0;

        if (intval($connectes) != $numofchatters) {
            $rafraich_connectes = 1;

            if (($numofchatters == 1) or ($numofchatters == 0)) {
                $nbre_connectes = "'" . $numofchatters . " " . utf8_java(html_entity_decode(__d('chat', 'personne connectée.'), ENT_QUOTES | ENT_HTML401, 'UTF-8')) . " GP [$id]'";
            } else {
                $nbre_connectes = "'" . $numofchatters . " " . utf8_java(html_entity_decode(__d('chat', 'personnes connectées.'), ENT_QUOTES | ENT_HTML401, 'UTF-8')) . " GP [$id]'";
            }
        }

        $commande = "self.location='chatrafraich.php?repere=$repere&aff_entetes=0&connectes=$numofchatters&id=$id&auto=$auto'";

        include("storage/meta/meta.php");

        echo "</head>\n<body id='chat'>
        <script type='text/javascript'>
            //<![CDATA[
                function scroll_messages() {
                    if (typeof(scrollBy) != 'undefined') {
                        parent.frames[1].scrollBy(0, 20000);
                        parent.frames[1].scrollBy(0, 20000);
                    }
                    else if (typeof(scroll) != 'undefined') {
                        parent.frames[1].scroll(0, 20000);
                        parent.frames[1].scroll(0, 20000);
                    }
                }

                function rafraichir() {
                    $commande;
                }

                function sur_chargement() {
                    setTimeout(\"rafraichir();\", 5000);";

        if ($aff_entetes == "1")
            echo "parent.frames[1].document.write($Xthing);";

        if ($thing != "\"\"") {
            echo "parent.frames[1].document.write($thing);
            setTimeout(\"scroll_messages();\", 300);";
        }

        if ($rafraich_connectes == 1) {
            echo "top.document.title=$nbre_connectes;";
        }

        echo "}
                window.onload=sur_chargement();
                //]]>
            </script>
            </body>
            </html>";
                
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function chattop()
    {
        $Titlesitename = 'Npds';

        $nuke_url = '';
        $meta_op = '';

        include('meta/meta.php');

        echo '
        </head>
        <body>
        </body>
        </html>';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function chat()
    {
        $Titlesitename = 'Npds';
        $meta_op = '';
        $meta_doctype = '<!DOCTYPE html>';

        include("storage/meta/meta.php");

        echo '
                    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon" />
                </head>  
                    <div style="height:1vh;" class=""><iframe src="chatrafraich.php?repere=0&amp;aff_entetes=1&amp;connectes=-1&amp;id=' . $id . '&amp;auto=' . $auto . '" frameborder="0" scrolling="no" noresize="noresize" name="rafraich" width="100%" height="100%"></iframe></div>
                    <div style="height:58vh;" class=""><iframe src="chattop.php" frameborder="0" scrolling="yes" noresize="noresize" name="haut" width="100%" height="100%"></iframe></div>
                    <div style="height:39vh;" class=""><iframe src="chatinput.php?id=' . $id . '&amp;auto=' . $auto . '" frameborder="0" scrolling="yes" noresize="noresize" name="bas" width="100%" height="100%"></iframe></div>
            </html>';

    }

}
