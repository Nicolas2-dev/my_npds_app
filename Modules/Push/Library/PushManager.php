<?php

namespace Modules\Push\Library;

use Npds\Config\Config;
use Modules\Push\Contracts\PushInterface;
use Modules\Npds\Support\Facades\Language;


class PushManager implements PushInterface 
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
     * Undocumented function
     *
     * @return void
     */
    public function push_menu()
    {
        global $options, $push_br;
    
        echo "document.write('<p align=\"center\" style=\"font-size: 11px;\">');\n";
    
        if (substr($options, 0, 1) == 1) {
            echo "document.write('[<a href=\"#article\">Article(s)</a>]$push_br');\n";
        }
    
        if (substr($options, 1, 1) == 1) {
            echo "document.write('[<a href=\"#faq\">Faqs</a>]$push_br');\n";
        }
    
        if (substr($options, 2, 1) == 1) {
            echo "document.write('[<a href=\"#poll\">" . __d('push', 'Poll') . "</a>]$push_br');\n";
        }
    
        if (substr($options, 3, 1) == 1) {
            echo "document.write('[<a href=\"#member\">" . __d('push', 'Member(s)') . "</a>]$push_br');\n";
        }
    
        if (substr($options, 4, 1) == 1) {
            echo "document.write('[<a href=\"#link\">" . __d('push', 'Web links') . "</a>]');\n";
        }
    
        echo "document.write('</p>');\n";
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function push_news()
    {
        global $push_news_limit;
    
        // settype($push_news_limit, "integer");
    
        $result = sql_query("SELECT sid, title, ihome, catid FROM stories ORDER BY sid DESC limit $push_news_limit");
    
        if ($result) {
            echo "document.write('<a name=\"article\"></a>');\n";
            echo "document.write('<li><b>" . __d('push', 'Latest Articles') . "</b></li><br />');\n";
    
            $ibid = sql_num_rows($result);
    
            for ($m = 0; $m < $ibid; $m++) {
                list($sid, $title, $ihome, $catid) = sql_fetch_row($result);
    
                if (ctrl_aff($ihome, $catid)) {
                    $title = str_replace("'", "\'", $title);
                    echo "document.write('&nbsp;-&nbsp;<a href=javascript:onclick=register(\"App-push\",\"op=new_show&sid=$sid&offset=$m\"); style=\"font-size: 11px;\">" . htmlspecialchars(Language::aff_langue($title), ENT_COMPAT | ENT_HTML401, cur_charset) . "</a><br />');\n";
                }
            }
        }
    
        echo "document.write('<br />');\n";
    
        sql_free_result($result);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function push_poll()
    {
        echo "document.write('<a name=\"poll\"></a>');\n";
        echo "document.write('<li><b>" . __d('push', 'Latest Poll Results') . "</b></li>');\n";
    
        $result = sql_query("SELECT pollID, polltitle FROM poll_desc ORDER BY pollID DESC LIMIT 1");
        list($pollID, $polltitle) = sql_fetch_row($result);
        sql_free_result($result);
    
        if ($pollID) {
            $result = sql_query("SELECT SUM(optionCount) FROM poll_data WHERE pollID='$pollID'");
            list($sum) = sql_fetch_row($result);
            sql_free_result($result);
    
            $result = sql_query("SELECT optionText, optionCount FROM poll_data WHERE pollID='$pollID' and optionText != \"\" ORDER BY voteID");
            echo "document.write('<p align=\"center\"><b>.:|" . Language::aff_langue($polltitle) . "|:.</b></p><table width=\"100%\" border=\"0\">');\n";
    
            $ibid = sql_num_rows($result);
    
            for ($m = 0; $m < $ibid; $m++) {
                list($optionText, $optionCount) = sql_fetch_row($result);
    
                if ($sum > 0) {
                    $percent = (int) 100 * $optionCount / $sum;
                } else {
                    $percent = 0;
                }
    
                $optionText = str_replace("'", "\'", $optionText);
    
                echo "document.write('<tr><td width=\"50%\" style=\"font-size: 11px;\">" . Language::aff_langue($optionText) . "</td><td width=\"20%\" style=\"font-size: 11px;\">');\n";
                echo "document.write('" . sprintf("%.1f%%", $percent) . "');\n";
                echo "document.write('</td><td align=\"center\" style=\"font-size: 11px;\">($optionCount)</td></tr>');\n";
            }
    
            echo "document.write('<tr><td width=\"50%\" style=\"font-size: 11px;\">" . __d('push', 'Total Votes:') . "</td><td width=\"20%\">&nbsp;</td><td align=\"center\" style=\"font-size: 11px;\"><b>$sum</b></td>');\n";
            echo "document.write('</tr></table>');\n";
        }
    
        echo "document.write('<br />');\n";
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function push_faq()
    {
        echo "document.write('<a name=\"faq\"></a>');\n";
        echo "document.write('<li><b>Faqs</b></li><br />');\n";
    
        $result = sql_query("SELECT id_cat, categories FROM faqcategories ORDER BY id_cat ASC");
    
        while (list($id_cat, $categories) = sql_fetch_row($result)) {
            $categories = str_replace("'", "\'", $categories);
            echo "document.write('&nbsp;-&nbsp;<a href=javascript:onclick=register(\"App-push\",\"op=faq_show&id_cat=$id_cat\"); style=\"font-size: 11px;\">" . Language::aff_langue($categories) . "</a><br />');\n";
        }
    
        echo "document.write('<br />');\n";
    
        sql_free_result($result);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function push_members()
    {
        global $push_member_col, $push_member_limit;
        global $page;
        
    
        echo "document.write('<a name=\"member\"></a>');\n";
        echo "document.write('<li><b>" . __d('push', 'Member(s)') . "</b></li><br />');\n";
        echo "document.write('<table border=\"0\" width=\"100%\"><tr>');\n";
    
        if (!$page) {
            $page = 0;
        }
    
        $offset = 0;
        $count_user = 0;
    
        // settype($page, "integer");
        // settype($push_member_limit, "integer");
    
        $result = sql_query("SELECT uname FROM users ORDER BY uname ASC LIMIT $page,$push_member_limit");
    
        while (list($uname) = sql_fetch_row($result)) {
            $offset = $offset + 1;
    
            if ($uname != Config::get('npds.anonymous')) {
                echo "document.write('<td><a href=\"Config::get('npds.nuke_url')/user.php?op=userinfo&amp;uname=$uname\" target=\"_blank\" style=\"font-size: 11px;\">$uname</a></td>');\n";
            } else {
                echo "document.write('<td style=\"font-size: 11px;\">$uname</td>');\n";
            }
    
            if ($offset == $push_member_col) {
                echo "document.write('</tr><tr>');\n";
                $offset = 0;
            }
    
            $page = $page + 1;
            $count_user = $count_user + 1;
        }
    
        if ($count_user < $push_member_limit) {
            $page = 0;
            echo "document.write('<td><b><a href=javascript:onclick=register(\"App-push\",\"op=next_page&page=$page\"); style=\"font-size: 11px;\">" . __d('push', 'Home') . "</a></b></td>');\n";
        } else {
            echo "document.write('<td><b><a href=javascript:onclick=register(\"App-push\",\"op=next_page&page=$page\"); style=\"font-size: 11px;\">" . __d('push', 'Next') . "</a></b></td>');\n";
        }
    
        echo "document.write('</tr></table>');\n";
        echo "document.write('<br />');\n";
    
        sql_free_result($result);
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function push_links()
    {
        global $push_orderby;
        
        $orderby = "title " . $push_orderby;
    
        echo "document.write('<a name=\"link\"></a>');\n";
        echo "document.write('<li><b>" . __d('push', 'Web links') . "</b></li><br />');\n";
    
        echo "document.write('<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>');\n";
    
        $result = sql_query("SELECT cid, title, cdescription FROM links_categories ORDER BY $orderby");
    
        $count = 0;
    
        while (list($cid, $title, $cdescription) = sql_fetch_row($result)) {
    
            $cresult = sql_query("SELECT * FROM links_links WHERE cid='$cid'");
            $cnumrows = sql_num_rows($cresult);
    
            $title = str_replace("'", "\'", $title);
    
            echo "document.write('<td width=\"49%\" valign=\"top\"><a href=javascript:onclick=register(\"App-push\",\"op=viewlink&cid=$cid\"); style=\"font-size: 11px;\"><b>" . Language::aff_langue($title) . "</b></a> ($cnumrows)');\n";
            
            if ($cdescription) {
                $cdescription = links($this->convert_nl(str_replace("'", "\'", $cdescription), "win", "html"));
                echo "document.write('<br /><i>" . Language::aff_langue($cdescription) . "</i><br />');\n";
            } else {
                echo "document.write('<br />');\n";
            }
    
            $result2 = sql_query("SELECT sid, title FROM links_subcategories WHERE cid='$cid' ORDER BY $orderby LIMIT 0,3");
    
            $space = 0;
    
            while (list($sid, $stitle) = sql_fetch_row($result2)) {
                if ($space > 0) {
                    echo "document.write('<br />');\n";
                }
    
                $title = str_replace("'", "\'", $title);
                echo "document.write('&nbsp;<a href=javascript:onclick=register(\"App-push\",\"op=viewslink&sid=$sid\"); style=\"font-size: 11px;\">" . Language::aff_langue($stitle) . "</a>');\n";
                $space++;
            }
    
            if ($count < 1) {
                echo "document.write('</td><td>&nbsp;</td>');\n";
            }
    
            $count++;
    
            if ($count == 2) {
                echo "document.write('</td></tr><tr><td>&nbsp;</td></tr><tr>');\n";
                $count = 0;
            }
        }
    
        echo "document.write('</td></tr></table>');\n";
        echo "document.write('<br />');\n";
    
        sql_free_result($result);
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $string
     * @param [type] $from
     * @param [type] $to
     * @return void
     */
    public function convert_nl($string, $from, $to)
    {
        $OS['mac'] = chr(13);
        $OS['win'] = chr(13) . chr(10);
        $OS['nix'] = chr(10);
        $OS['html'] = "<br />";
    
        if ($to == $from) {
            return true;
        }
    
        if (!in_array($from, array_keys($OS))) {
            return false;
        }
    
        if (!in_array($to, array_keys($OS))) {
            return false;
        }
    
        return str_replace($OS[$from], $OS[$to], $string);
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function links($ibid)
    {
        global $follow_links;
    
        if ($follow_links == false) {
            if (stristr($ibid, "<a href") == true) {
                $ibid = strip_tags($ibid);
            }
        }
    
        if ((stristr($ibid, "<img src"))) {
            if ((!stristr($ibid, "<img src=http")) and (!stristr($ibid, "<img src=\"http"))) {
                $ibid = str_replace("<img src=", "<img src=Config::get('npds.nuke_url')/", $ibid);
            }
        }
    
        return $ibid;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $operation
     * @return void
     */
    public function push_header($operation)
    {
        global $push_largeur, $push_largeur_suite, $push_titre, $push_logo;
        
        if ($operation == "suite") {
            $push_largeur = $push_largeur_suite;
        }
    
        $temp  = "<table width=\"$push_largeur\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
        $temp .= "<tr><td width=\"100%\">";
        $temp .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
        $temp .= "<tr>";
        $push_titre = str_replace("'", "\'", $push_titre);
        $temp .= "<td width=\"100%\" align=\"center\"><span style=\"font-size: 11px;\"><b>" . htmlspecialchars($push_titre, ENT_COMPAT | ENT_HTML401, cur_charset) . "</b></td>";
        
        if ($push_logo != "") {
            $temp .= "</tr><tr><td width=\"100%\" background=\"$push_logo\">";
        } else {
            $temp .= "</tr><tr><td width=\"100%\">";
        }
    
        echo "<script type=\"text/javascript\">\n//<![CDATA[\ndocument.write('$temp');\n//]]>\n</script>";
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function push_footer()
    {
        $temp = "</td></tr></table></td></tr></table>";
        echo "document.write('$temp');\n";
    }


}
