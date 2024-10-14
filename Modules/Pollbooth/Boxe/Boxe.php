<?php

use Npds\view\View;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use Modules\Theme\Support\Facades\Theme;
use Modules\Npds\Support\Facades\Language;


/**
 * Construit le bloc sondage
 * 
 * syntaxe : function#pollMain($pollID, $pollClose)
 *
 * @param   [type]  $pollID     [$pollID description]
 * @param   [type]  $pollClose  [$pollClose description]
 *
 * @return  [type]              [return description]
 */
function pollMain($pollID, $pollClose)
{
    global $block_title;

    if (!isset($pollID)) {
        $pollID = 1;
    }

    if (!isset($url)) {
        $url = sprintf('pollBooth.php?op=results&amp;pollID=%d', $pollID);
    }

    $poll_title = Language::aff_langue(
        DB::table('poll_desc')->select(DB::raw('pollTitle as poll_title'))->where('pollID', $pollID)->first()['poll_title']
    );
    
    $query = DB::table('poll_data')
                ->select('pollID', 'optionText', 'optionCount', 'voteID')
                ->where('pollID', $pollID)
                ->where('optionText', '<>', '')
                ->orderBy('voteID')
                ->get();

    $poll_data  = [];                
    $sum        = 0;
    $j          = 0;

    if (!$pollClose) {
        foreach ($query as $pollbooth) {
            $poll_data[] = [
                'j'             => $j,
                'voteID'        => $pollbooth['voteID'],
                'optionText'    => Language::aff_langue($pollbooth['optionText']),
            ];

            $sum = $sum + $pollbooth['optionCount'];
            $j++;
        }
    } else {
        foreach ($query as $pollbooth) {
            $poll_data[] = [
                'optionText'    => Language::aff_langue($pollbooth['optionText']),
            ];

            $sum = $sum + $pollbooth['optionCount'];
        }
    }
 
    $num_count = null;

    if (Config::get('npds.pollcomm') and Config::has('comments.pollBooth_config')) {
        $num_count = DB::table('posts')
                    ->where('forum_id', Config::get('comments.pollBooth_config.forum'))
                    ->where('topic_id', $pollID)
                    ->where('post_aff', 1)
                    ->count();
    }

    Theme::themesidebox(
        ($block_title ?: __d('pollbooth', 'Sondage')), 
        View::make('Modules/Pollbooth/Views/Boxe/Poll_Main', 
            compact('url', 'poll_title', 'pollID', 'sum', 'pollClose', 'num_count', 'poll_data')
        )
    );
}
