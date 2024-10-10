<?php

namespace Modules\Npds\Library;

use Npds\Config\Config;
use Modules\Npds\Contracts\SubscribeInterface;


class SubscribeManager implements SubscribeInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * [subscribe_mail description]
     *
     * @param   [type]  $Xtype    [$Xtype description]
     * @param   [type]  $Xtopic   [$Xtopic description]
     * @param   [type]  $Xforum   [$Xforum description]
     * @param   [type]  $Xresume  [$Xresume description]
     * @param   [type]  $Xsauf    [$Xsauf description]
     *
     * @return  [type]            [return description]
     */
    public function subscribe_mail($Xtype, $Xtopic, $Xforum, $Xresume, $Xsauf)
    {
        // $Xtype : topic, forum ... / $Xtopic clause WHERE / $Xforum id of forum / $Xresume Text passed / $Xsauf not this userid

        if ($Xtype == 'topic') {
            $result = sql_query("SELECT topictext FROM topics WHERE topicid='$Xtopic'");
            list($abo) = sql_fetch_row($result);

            $result = sql_query("SELECT uid FROM subscribe WHERE topicid='$Xtopic'");
        }

        if ($Xtype == 'forum') {
            $result = sql_query("SELECT forum_name, arbre FROM forums WHERE forum_id='$Xforum'");
            list($abo, $arbre) = sql_fetch_row($result);

            if ($arbre) {
                $hrefX = 'viewtopicH.php';
            } else {
                $hrefX = 'viewtopic.php';
            }

            $resultZ = sql_query("SELECT topic_title FROM forumtopics WHERE topic_id='$Xtopic'");
            list($title_topic) = sql_fetch_row($resultZ);

            $result = sql_query("SELECT uid FROM subscribe WHERE forumid='$Xforum'");
        }

        include_once("language/lang-multi.php");

        while (list($uid) = sql_fetch_row($result)) {
            if ($uid != $Xsauf) {
                $resultX = sql_query("SELECT email, user_langue FROM users WHERE uid='$uid'");
                list($email, $user_langue) = sql_fetch_row($resultX);

                if ($Xtype == 'topic') {
                    $entete = translate_ml($user_langue, "Vous recevez ce Mail car vous vous êtes abonné à : ") . translate_ml($user_langue, "Sujet") . " => " . strip_tags($abo) . "\n\n";
                    $resume = translate_ml($user_langue, "Le titre de la dernière publication est") . " => $Xresume\n\n";
                    $url = translate_ml($user_langue, "L'URL pour cet article est : ") . "<a href=\"".Config::get('npds.nuke_url')."/search.php?query=&topic=$Xtopic\">".Config::get('npds.nuke_url')."/search.php?query=&topic=$Xtopic</a>\n\n";
                }

                if ($Xtype == 'forum') {
                    $entete = translate_ml($user_langue, "Vous recevez ce Mail car vous vous êtes abonné à : ") . translate_ml($user_langue, "Forum") . " => " . strip_tags($abo) . "\n\n";
                    $url = translate_ml($user_langue, "L'URL pour cet article est : ") . "<a href=\"".Config::get('npds.nuke_url')."/$hrefX?topic=$Xtopic&forum=$Xforum&start=9999#lastpost\">".Config::get('npds.nuke_url')."/$hrefX?topic=$Xtopic&forum=$Xforum&start=9999</a>\n\n";
                    $resume = translate_ml($user_langue, "Le titre de la dernière publication est") . " => ";

                    if ($Xresume != '') {
                        $resume .= $Xresume . "\n\n";
                    } else {
                        $resume .= $title_topic . "\n\n";
                    }
                }

                $subject = html_entity_decode(translate_ml($user_langue, "Abonnement"), ENT_COMPAT | ENT_HTML401, cur_charset) . " / ".Config::get('npds.sitename');
                $message = $entete;
                $message .= $resume;
                $message .= $url;

                include("signat.php");

                send_email($email, $subject, $message, '', true, 'html');
            }
        }
    }

    /**
     * [subscribe_query description]
     *
     * @param   [type]  $Xuser  [$Xuser description]
     * @param   [type]  $Xtype  [$Xtype description]
     * @param   [type]  $Xclef  [$Xclef description]
     *
     * @return  [type]          [return description]
     */
    function subscribe_query($Xuser, $Xtype, $Xclef)
    {
        if ($Xtype == 'topic') {
            $result = sql_query("SELECT topicid FROM subscribe WHERE uid='$Xuser' AND topicid='$Xclef'");
        }

        if ($Xtype == 'forum') {
            $result = sql_query("SELECT forumid FROM subscribe WHERE uid='$Xuser' AND forumid='$Xclef'");
        }

        list($Xtemp) = sql_fetch_row($result);

        if ($Xtemp != '') {
            return true;
        } else {
            return false;
        }
    }

}
