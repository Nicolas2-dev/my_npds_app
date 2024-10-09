<?php

namespace App\Controllers\Front;

use Npds\Config\Config;
use App\Modules\Backend\Library\FeedItem;
use App\Modules\Backend\Library\FeedImage;
use App\Modules\News\Support\Facades\News;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Date;
use App\Modules\Npds\Support\Facades\Language;
use App\Modules\Npds\Support\Facades\Metalang;
use App\Modules\Backend\Library\UniversalFeedCreator;

class Backend extends FrontController
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
     * [index description]
     *
     * @return  [type]  [return description]
     */
    public function index()
    {
        // Format : RSS0.91, RSS1.0, RSS2.0, MBOX, OPML, ATOM
        settype($op, 'string');

        $op = strtoupper($op);

        switch ($op) {
            case 'MBOX':
                $this->fab_feed('MBOX', 'cache/MBOX-feed', 3600);
                break;

            case 'OPML':
                $this->fab_feed('OPML', 'cache/OPML-feed.xml', 3600);
                break;

            case 'ATOM':
                $this->fab_feed('ATOM', 'cache/ATOM-feed.xml', 3600);
                break;

            case 'RSS1.0':
                $this->fab_feed('RSS1.0', 'cache/RSS1.0-feed.xml', 3600);
                break;

            case 'RSS2.0':
                $this->fab_feed('RSS2.0', 'cache/RSS2.0-feed.xml', 3600);
                break;

            case 'RSS0.91':
                $this->fab_feed('RSS0.91', 'cache/RSS0.91-feed.xml', 3600);
                break;

            default:
                $this->fab_feed('RSS1.0', 'cache/RSS1.0-feed.xml', 3600);
                break;
        }        
    }

    /**
     * [fab_feed description]
     *
     * @param   [type]  $type      [$type description]
     * @param   [type]  $filename  [$filename description]
     * @param   [type]  $timeout   [$timeout description]
     *
     * @return  [type]             [return description]
     */
    public function fab_feed($type, $filename, $timeout)
    {
        $rss = new UniversalFeedCreator();

        $rss->useCached($type, $filename, $timeout);
    
        $rss->title                     = Config::get('npds.sitename');
        $rss->description               = Config::get('npds.slogan');
        $rss->descriptionTruncSize      = 250;
        $rss->descriptionHtmlSyndicated = true;
    
        $rss->link                      = site_url();
        $rss->syndicationURL            = site_url('backend.php?op='. $type);
    
        $image = new FeedImage();

        $image->title                   = Config::get('npds.sitename');
        $image->url                     = Config::get('npds.backend_image');
        $image->link                    = site_url();
        $image->description             = Config::get('npds.backend_title');
        $image->width                   = Config::get('npds.backend_width');
        $image->height                  = Config::get('npds.backend_height');
        $rss->image                     = $image;
    
        $xtab = News::news_aff('index', "WHERE ihome='0' AND archive='0'", Config::get('npds.storyhome'), '');
    
        $story_limit = 0;
    
        while (($story_limit < Config::get('npds.storyhome')) and ($story_limit < sizeof($xtab))) {
            list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[$story_limit];
    
            $story_limit++;
    
            $item = new FeedItem();

            $item->title        = Language::preview_local_langue(Config::get('npds.backend_language'), str_replace('&quot;', '\"', $title));
            $item->link         = site_url('article.php?sid='. $sid);
            $item->description  = Metalang::meta_lang(Language::preview_local_langue(Config::get('npds.backend_language'), $hometext));

            $item->descriptionHtmlSyndicated = true;

            $item->date         = Date::convertdateTOtimestamp($time) + ((int) Config::get('npds.gmt') * 3600);
            $item->source       = site_url();
            $item->author       = $aid;
    
            $rss->addItem($item);
        }
    
        echo $rss->saveFeed($type, $filename);
    }

}
