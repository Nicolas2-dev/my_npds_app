<?php

namespace Modules\Twitter\Controllers\Front\Map;

use Npds\Config\Config;
use Modules\Npds\Core\FrontController;
use Modules\Twitter\Library\TwitterOauth;


class Twitter extends FrontController
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
        if (!isset($sid)) {
            $result = sql_query("SELECT max(sid) FROM stories");
            list($sid) = sql_fetch_row($result);
        }

        if (Config::get('npds.npds_twi') === 1) {

            // Crée un objet TwitterOauth
            $connection         = new TwitterOauth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
            $connection->host   = "https://api.twitter.com/1.1/";

            // parametres 
            $max_twi = 280;
            $dif_len = 0;

            // raccourci pour les articles
            $query_art_short = ''; 

            // raccourci pour les posts
            $query_topi_short = ''; 

            if ($npds_twi_arti === 1) {
                $query_art_short = 's';

                // préparation du contenu du tweet
                $subj_twi = strip_tags($subject);

                // if (cur_charset !== 'utf-8') {
                //     $subj_twi = utf8_encode($subj_twi);
                // }

                $subj_twi = preg_replace("#''#", '\'', $subj_twi);
                $subj_twi = html_entity_decode($subj_twi);
                $text_twi = strip_tags($hometext);
                $text_twi = html_entity_decode($text_twi);

                // if (cur_charset !== 'utf-8') {
                //     $text_twi = utf8_encode($text_twi);
                // }

                $text_twi = preg_replace("#''#", '\'', $text_twi);
                $text_twi = preg_replace("#yt_video\(([^,]*),([^,]*),([^\)]*)\)#", 'Voir la vidéo...', $text_twi);

                switch ($npds_twi_urshort) {
                    case 1:
                        $link_twi = Config::get('npds.nuke_url') . '/' . $query_art_short . $sid;
                        break;

                    case 2:
                        $link_twi = Config::get('npds.nuke_url') . '/' . $query_art_short . '/' . $sid;
                        break;

                    case 3:
                        $link_twi = Config::get('npds.nuke_url') . '/' . $query_art_short . '.php/' . $sid;
                        break;

                    default:
                        $link_twi = '';
                        break;
                }

                $subj_len       = strlen($subj_twi);
                $homtext_len    = strlen($text_twi);
                $linkback_len   = strlen($link_twi);

                $dif_len = $max_twi - ($subj_len + $linkback_len);

                if (($subj_len + $linkback_len) > $max_twi) {
                    $subj_twi = substr($subj_twi, 0, ($dif_len - 4)) . '.. ' . $link_twi;
                }

                if ((($subj_len + $linkback_len) < $max_twi) and ($dif_len > 10)) {
                    $subj_twi = $subj_twi . ' ' . substr($text_twi, 0, ($dif_len - 4)) . '.. ' . $link_twi;
                } else {
                    $subj_twi = $subj_twi . '.. ' . $link_twi;
                }

                // envoi le tweet du nouvel article 
                $parameters = array('status' => $subj_twi);
                $status = $connection->post('statuses/update', $parameters);
            }
            
            // if ($npds_twi_post === 1) {
            //     a developper
            // }
        }
    }

}
