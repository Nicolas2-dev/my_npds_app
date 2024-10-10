<?php

namespace Modules\Npds\Library;



class PageRefManager 
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

    public function a_faire_cette_class()
    {

        // LOAD pages.php and Go ...
        settype($PAGES, 'array');

        global $pdst, $PAGES;

        require_once("app/Themes/pages.php");

        // import pages.php specif values from theme
        if (file_exists("app/Themes/" . $tmp_theme . "/pages.php"))
            include("app/Themes/" . $tmp_theme . "/pages.php");

        $page_uri = preg_split("#(&|\?)#", $_SERVER['REQUEST_URI']); //var_dump($page_uri);
        $Npage_uri = count($page_uri);
        $pages_ref = basename($page_uri[0]); //var_dump($pages_ref);

        // Static page and Module can have Bloc, Title ....
        if ($pages_ref == "static.php")
            $pages_ref = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "static.php"));

        if ($pages_ref == "modules.php") {
            if (isset($PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['title']))
                $pages_ref = "modules.php?ModPath=$ModPath&ModStart=$ModStart*";
            else
                $pages_ref = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "modules.php"));
        }

        // Admin function can have all the PAGES attributs except Title
        if ($pages_ref == "admin.php") {
            if (array_key_exists(1, $page_uri)) {
                if (array_key_exists($pages_ref . "?" . $page_uri[1], $PAGES)) {
                    if (array_key_exists('title', $PAGES[$pages_ref . "?" . $page_uri[1]]))
                        $pages_ref .= "?" . $page_uri[1];
                }
            }
        }

        // extend usage of pages.php : blocking script with part of URI for user, admin or with the value of a VAR
        if ($Npage_uri > 1) {
            for ($uri = 1; $uri < $Npage_uri; $uri++) {
                if (array_key_exists($page_uri[$uri], $PAGES)) {
                    if (!$$PAGES[$page_uri[$uri]]['run']) {
                        header("location: " . $PAGES[$page_uri[$uri]]['title']);
                        die();
                    }
                }
            }
        }

        // -----------------------
        // A partir de ce niveau - $PAGES[$pages_ref] doit exister - sinon c'est que la page n'est pas dans pages.php
        // -----------------------
        if (array_key_exists($pages_ref, $PAGES)) {
            // what a bloc ... left, right, both, ...
            if (array_key_exists('blocs', $PAGES[$pages_ref]))
                $pdst = $PAGES[$pages_ref]['blocs'];

            // block execution of page with run attribute = no
            if ($PAGES[$pages_ref]['run'] == "no") {
                if ($pages_ref == "index.php") {
                    $Titlesitename = "Npds";

                    if (file_exists("storage/meta/meta.php"))
                        include("storage/meta/meta.php");

                    if (file_exists("storage/static/webclosed.txt"))
                        include("storage/static/webclosed.txt");
                    die();
                } else
                    header("location: index.php");

                // run script to another 'location'
            } elseif (($PAGES[$pages_ref]['run'] != "yes") and (($PAGES[$pages_ref]['run'] != "")))
                header("location: " . $PAGES[$pages_ref]['run']);

            // Assure la gestion des titres ALTERNATIFS
            $tab_page_ref = explode("|", $PAGES[$pages_ref]['title']);

            if (count($tab_page_ref) > 1) {
                if (strlen($tab_page_ref[1]) > 1)
                    $PAGES[$pages_ref]['title'] = $tab_page_ref[1];
                else
                    $PAGES[$pages_ref]['title'] = $tab_page_ref[0];

                $PAGES[$pages_ref]['title'] = strip_tags($PAGES[$pages_ref]['title']);
            }

            $fin_title = substr($PAGES[$pages_ref]['title'], -1);
            $TitlesitenameX = aff_langue(substr($PAGES[$pages_ref]['title'], 0, strlen($PAGES[$pages_ref]['title']) - 1));

            if ($fin_title == "+")
                $Titlesitename = $TitlesitenameX . " - " . $Titlesitename;
            else if ($fin_title == '-')
                $Titlesitename = $TitlesitenameX;

            if ($Titlesitename == '') 
                $Titlesitename = Config::get('npds.sitename');

            // globalisation de la variable title pour marquetapage mais protection pour la zone admin
            if ($pages_ref != "admin.php")
                global $title;

            if (!$title) {
                if ($fin_title == "+" or $fin_title == "-")
                    $title = $TitlesitenameX;
                else
                    $title = aff_langue(substr($PAGES[$pages_ref]['title'], 0, strlen($PAGES[$pages_ref]['title'])));
            } else
                $title = removeHack($title);

            // meta description
            settype($m_description, 'string');

            if (array_key_exists('meta-description', $PAGES[$pages_ref]) and ($m_description == ''))
                $m_description = aff_langue($PAGES[$pages_ref]['meta-description']);

            // meta keywords
            settype($m_keywords, 'string');

            if (array_key_exists('meta-keywords', $PAGES[$pages_ref]) and ($m_keywords == ''))
                $m_keywords = aff_langue($PAGES[$pages_ref]['meta-keywords']);
        }

        // Initialisation de TinyMce
        global $tiny_mce_theme, $tiny_mce_relurl;

        if (Config::get('npds.tiny_mce')) {
            if (array_key_exists($pages_ref, $PAGES)) {
                if (array_key_exists('TinyMce', $PAGES[$pages_ref])) {
                    $tiny_mce_init = true;

                    if (array_key_exists('TinyMce-theme', $PAGES[$pages_ref]))
                        $tiny_mce_theme = $PAGES[$pages_ref]['TinyMce-theme'];

                    if (array_key_exists('TinyMceRelurl', $PAGES[$pages_ref]))
                        $tiny_mce_relurl = $PAGES[$pages_ref]['TinyMceRelurl'];
                } else {
                    $tiny_mce_init = false;
                    Config::set('npds.tiny_mce', false);
                }
            } else {
                $tiny_mce_init = false;
                Config::set('npds.tiny_mce', false);
            }
        } else {
            $tiny_mce_init = false;
        }

        // Chargeur de CSS via PAGES.PHP

        if (array_key_exists($pages_ref, $PAGES)) {
            if (array_key_exists('css', $PAGES[$pages_ref])) {
                $css_pages_ref = $pages_ref;
                $css = $PAGES[$pages_ref]['css'];
            } else {
                $css_pages_ref = '';
                $css = '';
            }
        } else {
            $css_pages_ref = '';
            $css = '';
        }

        // Mod by Jireck - Chargeur de JS via PAGES.PHP
        if (array_key_exists($pages_ref, $PAGES)) {
            if (array_key_exists('js', $PAGES[$pages_ref])) {
                $js = $PAGES[$pages_ref]['js'];

                if ($js != '') {
                    global $pages_js;
                    $pages_js = $js;
                }
            } else
                $js = '';
        } else
            $js = '';


    }

}
