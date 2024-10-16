<?php

namespace Modules\Stats\Library;


use Modules\Stats\Contracts\SitemapInterface;


class SitemapManager implements SitemapInterface 
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
     * @param [type] $prio
     * @return void
     */
    public function sitemapforum($prio)
    {
        $tmp = '';
    
        $result = sql_query("SELECT forum_id FROM forums WHERE forum_access='0' ORDER BY forum_id");
    
        while (list($forum_id) = sql_fetch_row($result)) {
            // Forums
            $tmp .= "<url>\n";
            $tmp .= "<loc>Config::get('npds.nuke_url')/viewforum.php?forum=$forum_id</loc>\n";
            $tmp .= "<lastmod>" . date("Y-m-d", time()) . "</lastmod>\n";
            $tmp .= "<changefreq>hourly</changefreq>\n";
            $tmp .= "<priority>$prio</priority>\n";
            $tmp .= "</url>\n\n";
    
            $sub_result = sql_query("SELECT topic_id, topic_time FROM forumtopics WHERE forum_id='$forum_id' AND topic_status!='2' ORDER BY topic_id");
            
            while (list($topic_id, $topic_time) = sql_fetch_row($sub_result)) {
                // Topics
                $tmp .= "<url>\n";
                $tmp .= "<loc>Config::get('npds.nuke_url')/viewtopic.php?topic=$topic_id&amp;forum=$forum_id</loc>\n";
                $tmp .= "<lastmod>" . substr($topic_time, 0, 10) . "</lastmod>\n";
                $tmp .= "<changefreq>hourly</changefreq>\n";
                $tmp .= "<priority>$prio</priority>\n";
                $tmp .= "</url>\n\n";
            }
        }
    
        return $tmp;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $prio
     * @return void
     */
    public function sitemaparticle($prio)
    {
        $tmp = '';
    
        $result = sql_query("SELECT sid,time FROM stories WHERE ihome='0' AND archive='0' ORDER BY sid");
        
        while (list($sid, $time) = sql_fetch_row($result)) {
            // Articles
            $tmp .= "<url>\n";
            $tmp .= "<loc>Config::get('npds.nuke_url')/article.php?sid=$sid</loc>\n";
            $tmp .= "<lastmod>" . substr($time, 0, 10) . "</lastmod>\n";
            $tmp .= "<changefreq>daily</changefreq>\n";
            $tmp .= "<priority>$prio</priority>\n";
            $tmp .= "</url>\n\n";
        }
    
        return $tmp;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $prio
     * @return void
     */
    public function sitemaprub($prio)
    {
        $tmp = '';
    
        // Sommaire des rubriques
        $tmp .= "<url>\n";
        $tmp .= "<loc>Config::get('npds.nuke_url')/sections.php</loc>\n";
        $tmp .= "<lastmod>" . date("Y-m-d", time()) . "</lastmod>\n";
        $tmp .= "<changefreq>weekly</changefreq>\n";
        $tmp .= "<priority>$prio</priority>\n";
        $tmp .= "</url>\n\n";
    
        $result = sql_query("SELECT artid, timestamp FROM seccont WHERE userlevel='0' ORDER BY artid");
        
        while (list($artid, $timestamp) = sql_fetch_row($result)) {
            // Rubriques
            $tmp .= "<url>\n";
            $tmp .= "<loc>Config::get('npds.nuke_url')/sections.php?op=viewarticle&amp;artid=$artid</loc>\n";
            $tmp .= "<lastmod>" . date("Y-m-d", $timestamp) . "</lastmod>\n";
            $tmp .= "<changefreq>weekly</changefreq>\n";
            $tmp .= "<priority>$prio</priority>\n";
            $tmp .= "</url>\n\n";
        }
    
        return $tmp;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $prio
     * @return void
     */
    public function sitemapdown($prio)
    {
        $tmp = '';
    
        // Sommaire des downloads
        $tmp .= "<url>\n";
        $tmp .= "<loc>Config::get('npds.nuke_url')/download.php</loc>\n";
        $tmp .= "<lastmod>" . date("Y-m-d", time()) . "</lastmod>\n";
        $tmp .= "<changefreq>weekly</changefreq>\n";
        $tmp .= "<priority>$prio</priority>\n";
        $tmp .= "</url>\n\n";
    
        $result = sql_query("SELECT did, ddate FROM downloads WHERE perms='0' ORDER BY did");
        
        while (list($did, $ddate) = sql_fetch_row($result)) {
            $tmp .= "<url>\n";
            $tmp .= "<loc>Config::get('npds.nuke_url')/download.php?op=geninfo&amp;did=$did</loc>\n";
            $tmp .= "<lastmod>$ddate</lastmod>\n";
            $tmp .= "<changefreq>weekly</changefreq>\n";
            $tmp .= "<priority>$prio</priority>\n";
            $tmp .= "</url>\n\n";
        }
    
        return $tmp;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $PAGES
     * @return void
     */
    public function sitemapothers($PAGES)
    { 
        $tmp = '';
        
        foreach ($PAGES as $name => $loc) {
            if (isset($PAGES[$name]['sitemap'])) {
                if (($PAGES[$name]['run'] == "yes") and ($name != "article.php") and ($name != "forum.php") and ($name != "sections.php") and ($name != "download.php")) {
                    $tmp .= "<url>\n";
                    $tmp .= "<loc>Config::get('npds.nuke_url')/" . str_replace("&", "&amp;", $name) . "</loc>\n";
                    $tmp .= "<lastmod>" . date("Y-m-d", time()) . "</lastmod>\n";
                    $tmp .= "<changefreq>daily</changefreq>\n";
                    $tmp .= "<priority>" . $PAGES[$name]['sitemap'] . "</priority>\n";
                    $tmp .= "</url>\n\n";
                }
            }
        }
    
        return $tmp;
    }

}
