<?php

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Npds\Core\AdminController;


/**
 * Undocumented class
 */
class Newsletter extends AdminController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = 'lnl';

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = 'lnl';


    /**
     * Call the parent construct
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
        $this->f_titre = __d('newsletter', 'Petite Lettre D\'information');

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
    public function send()
    {
        $deb = 0;
        $limit = 50; // nombre de messages envoyé par boucle.
        if (!isset($debut)) 
            $debut = 0;

        if (!isset($number_send)) 
            $number_send = 0;

        $result = sql_query("SELECT text, html FROM lnl_head_foot WHERE type='HED' AND ref='$Xheader'");
        $Yheader = sql_fetch_row($result);

        $result = sql_query("SELECT text, html FROM lnl_body WHERE html='$Yheader[1]' AND ref='$Xbody'");
        $Ybody = sql_fetch_row($result);

        $result = sql_query("SELECT text, html FROM lnl_head_foot WHERE type='FOT' AND html='$Yheader[1]' AND ref='$Xfooter'");
        $Yfooter = sql_fetch_row($result);

        $subject = stripslashes($Xsubject);
        $message = $Yheader[0] . $Ybody[0] . $Yfooter[0];


        $Xmime = $Yheader[1] == 1 ? 'html-nobr' : 'text';

        if ($Xtype == "All") {
            $Xtype = "Out";
            $OXtype = "All";
        }

        // Outside Users
        if ($Xtype == "Out") {
            $mysql_result = sql_query("SELECT email FROM lnl_outside_users WHERE status='OK'");
            $nrows = sql_num_rows($mysql_result);

            $result = sql_query("SELECT email FROM lnl_outside_users WHERE status='OK' ORDER BY email limit $debut,$limit");

            while (list($email) = sql_fetch_row($result)) {
                if (($email != "Anonyme") or ($email != "Anonymous")) {
                    if ($email != '') {
                        if (($message != '') and ($subject != '')) {

                            if ($Xmime == "html-nobr") {
                                $Xmessage = $message . "<br /><br /><hr noshade>";
                                $Xmessage .= __d('newsletter', 'Pour supprimer votre abonnement à notre Lettre, suivez ce lien') . " : <a href=\"Config::get('npds.nuke_url')/lnl.php?op=unsubscribe&email=$email\">" . __d('newsletter', 'Modifier') . "</a>";
                            } else {
                                $Xmessage = $message . "\n\n------------------------------------------------------------------\n";
                                $Xmessage .= __d('newsletter', 'Pour supprimer votre abonnement à notre Lettre, suivez ce lien') . " : Config::get('npds.nuke_url')/lnl.php?op=unsubscribe&email=$email";
                            }

                            send_email($email, $subject, meta_lang($Xmessage), "", true, $Xmime, '');

                            $number_send++;
                        }
                    }
                }
            }
        }

        // App Users
        if ($Xtype == 'Mbr') {
            if ($Xgroupe != '') {
                $result = '';

                $mysql_result = sql_query("SELECT u.uid FROM users u, users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND (s.groupe LIKE '%$Xgroupe,%' OR s.groupe LIKE '%,$Xgroupe' OR s.groupe='$Xgroupe') AND u.user_lnl='1'");
                $nrows = sql_num_rows($mysql_result);
                
                $resultGP = sql_query("SELECT u.email, u.uid, s.groupe FROM users u, users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND (s.groupe LIKE '%$Xgroupe,%' OR s.groupe LIKE '%,$Xgroupe' OR s.groupe='$Xgroupe') AND u.user_lnl='1' ORDER BY u.email LIMIT $debut,$limit");
                
                while (list($email, $uid, $groupe) = sql_fetch_row($resultGP)) {
                    $tab_groupe = explode(',', $groupe);
                    
                    if ($tab_groupe)
                        foreach ($tab_groupe as $groupevalue) {
                            if ($groupevalue == $Xgroupe)
                                $result[] = $email;
                        }
                }

                $fonction = "each"; ///???gloups

                if (is_array($result)) 
                    $boucle = true;
                else 
                    $boucle = false;
            } else {
                $mysql_result = sql_query("SELECT u.uid FROM users u, users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND u.user_lnl='1'");
                $nrows = sql_num_rows($mysql_result);
                $result = sql_query("SELECT u.uid, u.email FROM users u, users_status s WHERE s.open='1' AND u.uid=s.uid AND u.user_lnl='1' ORDER BY email LIMIT $debut,$limit");
                $fonction = "sql_fetch_row";
                $boucle = true;
            }

            if ($boucle) {
                while (list($bidon, $email) = $fonction($result)) { ///???gloups réinterprété comme each .. ???
                    if (($email != "Anonyme") or ($email != "Anonymous")) {
                        if ($email != '') {
                            if (($message != '') and ($subject != '')) {
                                send_email($email, $subject, meta_lang($message), "", true, $Xmime, '');
                                $number_send++;
                            }
                        }
                    }
                }
            }
        }

        $deb = $debut + $limit;
        $chartmp = '';

        settype($OXtype, 'string');

        if ($deb >= $nrows) {
            if ((($OXtype == "All") and ($Xtype == "Mbr")) or ($OXtype == "")) {
                if (($message != '') and ($subject != '')) {
                    $timeX = date("Y-m-d H:m:s", time());

                    if ($OXtype == "All") {
                        $Xtype = "All";
                    }

                    if (($Xtype == "Mbr") and ($Xgroupe != "")) {
                        $Xtype = $Xgroupe;
                    }

                    sql_query("INSERT INTO lnl_send VALUES ('0', '$Xheader', '$Xbody', '$Xfooter', '$number_send', '$Xtype', '$timeX', 'OK')");
                }

                header("location: admin.php?op=lnl");
            } else {
                if ($OXtype == "All") {
                    $chartmp = "$Xtype : $nrows / $nrows";
                    $deb = 0;
                    $Xtype = "Mbr";
                    $mysql_result = sql_query("SELECT u.uid FROM users u, users_status s WHERE s.open='1' and u.uid=s.uid and u.email!='' and u.user_lnl='1'");
                    $nrows = sql_num_rows($mysql_result);
                }
            }
        }

        if ($chartmp == '') 
            $chartmp = "$Xtype : $deb / $nrows";

        include("storage/meta/meta.php");

        echo "<script type=\"text/javascript\">
                //<![CDATA[
                function redirect() {
                    window.location=\"admin.php?op=lnl_Send&debut=" . $deb . "&OXtype=$OXtype&Xtype=$Xtype&Xgroupe=$Xgroupe&Xheader=" . $Xheader . "&Xbody=" . $Xbody . "&Xfooter=" . $Xfooter . "&number_send=" . $number_send . "&Xsubject=" . $Xsubject . "\";
                }
                setTimeout(\"redirect()\",10000);
                //]]>
                </script>";

        echo '
            <link href="' . Config::get('npds.nuke_url') . '/themes/App-boost_sk/style/style.css" title="default" rel="stylesheet" type="text/css" media="all">
            <link id="bsth" rel="stylesheet" href="' . Config::get('npds.nuke_url') . '/assets/skins/default/bootstrap.min.css">
            </head>
                <body>
                <div class="d-flex justify-content-center mt-4">
                    <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <div class="text-center mt-4">
                    ' . __d('newsletter', 'Transmission LNL en cours') . ' => ' . $chartmp . '<br /><br />App - Portal System
                    </div>
                </div>
                </body>
            </html>';
    }

}
