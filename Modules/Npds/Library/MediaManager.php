<?php

namespace Modules\Npds\Library;


use Modules\Npds\Contracts\MediaInterface;


class MediaManager implements MediaInterface 
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
     * [aff_video_yt description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function aff_video_yt($ibid)
    {
        $videoprovider = array('yt', 'vm', 'dm');
    
        foreach ($videoprovider as $v) {
            $pasfin = true;
    
            while ($pasfin) {
                $pos_deb = strpos($ibid, "[video_$v]", 0);
                $pos_fin = strpos($ibid, "[/video_$v]", 0);
    
                // ne pas confondre la position ZERO et NON TROUVE !
                if ($pos_deb === false) 
                    $pos_deb = -1;
    
                if ($pos_fin === false) 
                    $pos_fin = -1;
    
                if (($pos_deb >= 0) and ($pos_fin >= 0)) {
                    $id_vid = substr($ibid, $pos_deb + 10, ($pos_fin - $pos_deb - 10));
                    $fragment = substr($ibid, 0, $pos_deb);
                    $fragment2 = substr($ibid, ($pos_fin + 11));
    
                    switch ($v) {
                        case 'yt':
                            if (!defined('CITRON'))
                                $ibid_code = '
                                <div class="ratio ratio-16x9 my-3">
                                <iframe src="https://www.youtube.com/embed/' . $id_vid . '?rel=0" allowfullscreen></iframe>
                                </div>';
                            else
                                $ibid_code = '
                                <div class="youtube_player" videoID="' . $id_vid . '"></div>';
                            break;
    
                        case 'vm':
                            if (!defined('CITRON'))
                                $ibid_code = '
                                <div class="ratio ratio-16x9 my-3">
                                    <iframe src="https://player.vimeo.com/video/' . $id_vid . '" allowfullscreen="" frameborder="0"></iframe>
                                </div>';
                            else
                                $ibid_code = '
                                <div class="vimeo_player" videoID="' . $id_vid . '"></div>';
                            break;
    
                        case 'dm':
                            if (!defined('CITRON'))
                                $ibid_code = '
                                <div class="ratio ratio-16x9 my-3">
                                    <iframe src="https://www.dailymotion.com/embed/video/' . $id_vid . '" allowfullscreen="" frameborder="0"></iframe>
                                </div>';
                            else
                                $ibid_code = '
                                <div class="dailymotion_player" videoID="' . $id_vid . '"></div>';
                            break;
                    }
    
                    $ibid = $fragment . $ibid_code . $fragment2;
                } else
                    $pasfin = false;
            }
        }
    
        return $ibid;
    }

}
