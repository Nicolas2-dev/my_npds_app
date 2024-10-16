<?php

namespace Modules\Stats\Controllers\Front\Sitemap;

use Modules\Npds\Core\FrontController;
use Modules\Stats\Support\Facades\Sitemap as LSitemap;


class Sitemap extends FrontController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;


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
    public function index()
    {
        /* -----------------------------------------*/
        // http://www.example.com/cache/sitemap.xml 
        $filename = "storage/sitemap/sitemap.xml";

        // delais = 6 heures (21600 secondes)
        $refresh = 21600;

        global $PAGES;

        if (file_exists($filename)) {
            if (time() - filemtime($filename) - $refresh > 0) {
                $this->sitemap_create($PAGES, $filename);
            }
        } else {
            $this->sitemap_create($PAGES, $filename);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $PAGES
     * @param [type] $filename
     * @return void
     */
    private function sitemap_create($PAGES, $filename)
    {
        $ibid  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $ibid .= "<urlset\n";
        $ibid .= "xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
        $ibid .= "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
        $ibid .= "xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n\n";
    
        if (isset($PAGES['article.php']['sitemap'])) {
            $ibid .= LSitemap::sitemaparticle($PAGES['article.php']['sitemap']);
        }
    
        if (isset($PAGES['forum.php']['sitemap'])) {
            $ibid .= LSitemap::sitemapforum($PAGES['forum.php']['sitemap']);
        }
    
        if (isset($PAGES['sections.php']['sitemap'])) {
            $ibid .= LSitemap::sitemaprub($PAGES['sections.php']['sitemap']);
        }
    
        if (isset($PAGES['download.php']['sitemap'])) {
            $ibid .= LSitemap::sitemapdown($PAGES['download.php']['sitemap']);
        }
    
        $ibid .= LSitemap::sitemapothers($PAGES);
        $ibid .= "</urlset>";
    
        $file = fopen($filename, "w");
        fwrite($file, $ibid);
        fclose($file);
    
        Ecr_Log("sitemap", "sitemap generated : " . date("H:i:s", time()), "");
    }

}
