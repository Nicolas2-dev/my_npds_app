<?php

namespace Modules\Headline\Library;


use Npds\Config\Config;
use Modules\Theme\Support\Facades\Theme;
use Modules\Headline\Contracts\HeadlineInterface;


class HeadlineManager implements HeadlineInterface
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
     * [verifi_rss_host description]
     *
     * @return  [type]  [return description]
     */
    public function verifie_rss_host($url)
    {
        if (Config::get('npds.rss_host_verif') == true) {
            
            $rss = parse_url($url);
            
            $verif = fsockopen($rss['host'], 80, $errno, $errstr, Config::get('headline.config.rss_timeout'));

            if ($verif) {
                fclose($verif);
                $verif = true;
            }
        } else {
            $verif = true;
        }

        return $verif;
    }

    /**
     * [flux_atom description]
     *
     * @param   [type]  $flux  [$flux description]
     *
     * @return  [type]         [return description]
     */
    public function flux_atom($fpwrite, $flux)
    {
        // ATOM
        if ($flux->entry) {
            $j = 0;
            $cont = '';

            foreach ($flux->entry as $entry) {
                if ($entry->content) {
                    $cont = (string) $entry->content;
                }

                fputs($fpwrite, '   <li><a href="' . (string)$entry->link . '" target="_blank">' . (string) $entry->title . '</a>' . $cont . '</li>' . "\n");

                if ($j == Config::get('headline.config.max_items')) {
                    break;
                }
                $j++;
            }
        }
    }

    /**
     * [flux_item description]
     *
     * @param   [type]  $fpwrite  [$fpwrite description]
     * @param   [type]  $flux     [$flux description]
     *
     * @return  [type]            [return description]
     */
    public function flux_item($fpwrite, $flux)
    {
        if ($flux->item) {
            $j = 0;

            foreach ($flux->item as $item) {
                fputs($fpwrite, '   <li><a href="' . (string) $item->link . '" target="_blank">' . (string) $item->title . '</a></li>'."\n");
                
                if ($j == Config::get('headline.config.max_items')) {
                    break;
                }

                $j++;
            }
        }        
    }

    /**
     * [flux_rss description]
     *
     * @param   [type]  $fpwrite  [$fpwrite description]
     * @param   [type]  $flux     [$flux description]
     *
     * @return  [type]            [return description]
     */
    public function flux_rss($fpwrite, $flux)
    {
        if ($flux->channel) {
            $j = 0;
            $cont = '';

            foreach ($flux->channel->item as $item) {
                if ($item->description) {
                    $cont = (string) $item->description;
                }

                fputs($fpwrite, '   <li><a href="' . (string) $item->link . '" target="_blank">' . (string) $item->title . '</a>' . $cont . '</li>'."\n");
                
                if ($j == Config::get('headline.config.max_items')) {
                    break;
                }
                $j++;
            }
        }
    }

    /**
     * [flux_image description]
     *
     * @param   [type]  $fpwrite  [$fpwrite description]
     * @param   [type]  $flux     [$flux description]
     *
     * @return  [type]            [return description]
     */
    public function flux_image($fpwrite, $flux)
    {
        $j      = 0;
        $ico    = '';

        if (($flux->image) && file_exists('http://' . $flux->image->url)) {
            $ico = '<img class="img-fluid" src="http://' . $flux->image->url . '" />&nbsp;';
        }

        foreach ($flux->item as $item) {
            fputs($fpwrite, '   <li>' . $ico . '<a href="http://' . (string) $item->link . '" target="_blank">' . (string) $item->title . '</a></li>'."\n");
            
            if ($j == Config::get('headline.config.max_items')) {
                break;
            }
            $j++;
        }        
    }

    /**
     * [generate_cache_flux description]
     *
     * @param   [type]  $cache_file    [$cache_file description]
     * @param   [type]  $headlinesurl  [$headlinesurl description]
     *
     * @return  [type]                 [return description]
     */
    public function generate_cache_flux($cache_file, $headlinesurl)
    {
        $fpwrite = fopen($cache_file, 'w');

        if ($fpwrite) { 
            fputs($fpwrite, "<ul>\n");
            
            $flux = simplexml_load_file($headlinesurl, 'SimpleXMLElement', LIBXML_NOCDATA);

            //
            $this->flux_atom($fpwrite, $flux);
            $this->flux_item($fpwrite, $flux);
            $this->flux_rss($fpwrite, $flux);
            $this->flux_image($fpwrite, $flux);

            fputs($fpwrite,  '</ul>');
            fclose($fpwrite);
        }            
    }

    /**
     * [read_flux description]
     *
     * @param   [type]  $cache_file  [$cache_file description]
     * @param   [type]  $url         [$url description]
     *
     * @return  [type]               [return description]
     */
    public function read_cache_flux($cache_file, $url)
    {
        if (file_exists($cache_file)) {
            ob_start();
                readfile($cache_file);
                $boxstuff =  '<span class="small">' . ob_get_contents() . '</span>';
            ob_end_clean();
        }

        $boxstuff .= '<div class="text-end"><a href="' . $url . '" target="_blank">' . __d('headline', 'Lire la suite...') . '</a></div>';  
        
        return $boxstuff;
    }

    /**
     * [cache_file_headline description]
     *
     * @param   [type]  $sitename  [$sitename description]
     * @param   [type]  $hid       [$hid description]
     *
     * @return  [type]             [return description]
     */
    public function cache_file_headline($sitename, $hid)
    {
        $hid = (empty($hid) ? '' : '_'.$hid);

        return module_path('Headline/storage/cache/headlines_' . preg_replace(Config::get('headline.config.regex'), '', strtolower($sitename)) . $hid . '.cache');
    }

    /**
     * [cache_file_security description]
     *
     * @param   [type]  $cache_file  [$cache_file description]
     * @param   [type]  $sitename    [$sitename description]
     *
     * @return  [type]               [return description]
     */
    public function cache_file_security($cache_file, $sitename)
    {
        $cache_file_sec = $cache_file . ".security";

        if (file_exists($cache_file)) {
            rename($cache_file, $cache_file_sec);
        }

        Theme::themesidebox($sitename, "Security Error");        
    }

}
