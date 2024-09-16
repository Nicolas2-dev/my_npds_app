<?php

use App\Modules\Forum\Support\Facades\Forum;
use App\Modules\Theme\Support\Facades\Theme;


/**
 * [RecentForumPosts description]
 *
 * @param   [type] $title          [$title description]
 * @param   [type] $maxforums      [$maxforums description]
 * @param   [type] $maxtopics      [$maxtopics description]
 * @param   [type] $displayposter  [$displayposter description]
 * @param   false  $topicmaxchars  [$topicmaxchars description]
 * @param   [type] $hr             [$hr description]
 * @param   false  $decoration     [$decoration description]
 *
 * @return  [type]                 [return description]
 */
function RecentForumPosts($title, $maxforums, $maxtopics, $displayposter = false, $topicmaxchars = 15, $hr = false, $decoration = '')
{
    global $block_title;

    Theme::themesidebox(
        ($block_title == '' ? __d('forum', 'Forums infos') : $block_title), 
        Forum::RecentForumPosts_fab($title, $maxforums, $maxtopics, $displayposter, $topicmaxchars, $hr, $decoration)
    );
}
