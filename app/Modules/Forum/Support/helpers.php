<?php

use App\Modules\Forum\Library\ForumManager;


if (! function_exists('RecentForumPosts_fab'))
{
    /**
     * [RecentForumPosts_fab description]
     *
     * @param   [type]  $title          [$title description]
     * @param   [type]  $maxforums      [$maxforums description]
     * @param   [type]  $maxtopics      [$maxtopics description]
     * @param   [type]  $displayposter  [$displayposter description]
     * @param   [type]  $topicmaxchars  [$topicmaxchars description]
     * @param   [type]  $hr             [$hr description]
     * @param   [type]  $decoration     [$decoration description]
     *
     * @return  [type]                  [return description]
     */
    function RecentForumPosts_fab($title, $maxforums, $maxtopics, $displayposter, $topicmaxchars, $hr, $decoration)
    {
        return ForumManager::getInstance()->RecentForumPosts_fab($title, $maxforums, $maxtopics, $displayposter, $topicmaxchars, $hr, $decoration);
    }
}

if (! function_exists('get_total_topics'))
{
    /**
     * [get_total_topics description]
     *
     * @param   [type]  $forum_id  [$forum_id description]
     *
     * @return  [type]             [return description]
     */
    function get_total_topics($forum_id)
    {
        return ForumManager::getInstance()->get_total_topics($forum_id);
    }
}

if (! function_exists('get_contributeurs'))
{
    /**
     * [get_contributeurs description]
     *
     * @param   [type]  $fid  [$fid description]
     * @param   [type]  $tid  [$tid description]
     *
     * @return  [type]        [return description]
     */
    function get_contributeurs($fid, $tid)
    {
        return ForumManager::getInstance()->get_contributeurs($fid, $tid);
    }
}

if (! function_exists('get_total_posts'))
{
    /**
     * [get_total_posts description]
     *
     * @param   [type]  $fid   [$fid description]
     * @param   [type]  $tid   [$tid description]
     * @param   [type]  $type  [$type description]
     * @param   [type]  $Mmod  [$Mmod description]
     *
     * @return  [type]         [return description]
     */
    function get_total_posts($fid, $tid, $type, $Mmod)
    {
        return ForumManager::getInstance()->get_total_posts($fid, $tid, $type, $Mmod);
    }
}

if (! function_exists('get_last_post'))
{
    /**
     * [get_last_post description]
     *
     * @param   [type]  $id    [$id description]
     * @param   [type]  $type  [$type description]
     * @param   [type]  $cmd   [$cmd description]
     * @param   [type]  $Mmod  [$Mmod description]
     *
     * @return  [type]         [return description]
     */
    function get_last_post($id, $type, $cmd, $Mmod)
    {
        return ForumManager::getInstance()->get_last_post($id, $type, $cmd, $Mmod);
    }
}

if (! function_exists('does_exists'))
{
    /**
     * [does_exists description]
     *
     * @param   [type]  $id    [$id description]
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function does_exists($id, $type)
    {
        return ForumManager::getInstance()->does_exists($id, $type);
    }
}

if (! function_exists('is_locked'))
{
    /**
     * [is_locked description]
     *
     * @param   [type]  $topic  [$topic description]
     *
     * @return  [type]          [return description]
     */
    function is_locked($topic)
    {
        return ForumManager::getInstance()->is_locked($topic);
    }
}

if (! function_exists('HTML_Add'))
{
    /**
     * [HTML_Add description]
     *
     * @return  [type]  [return description]
     */
    function HTML_Add()
    {
        return ForumManager::getInstance()->HTML_Add();
    }
}

if (! function_exists('emotion_add'))
{
    /**
     * [emotion_add description]
     *
     * @param   [type]  $image_subject  [$image_subject description]
     *
     * @return  [type]                  [return description]
     */
    function emotion_add($image_subject)
    {
        return ForumManager::getInstance()->emotion_add($image_subject);
    }
}

if (! function_exists('searchblock'))
{
    /**
     * [searchblock description]
     *
     * @return  [type]  [return description]
     */
    function searchblock()
    {
        return ForumManager::getInstance()->searchblock();
    }
}

if (! function_exists('member_qualif'))
{
    /**
     * [member_qualif description]
     *
     * @param   [type]  $poster  [$poster description]
     * @param   [type]  $posts   [$posts description]
     * @param   [type]  $rank    [$rank description]
     *
     * @return  [type]           [return description]
     */
    function member_qualif($poster, $posts, $rank)
    {
        return ForumManager::getInstance()->member_qualif($poster, $posts, $rank);
    }
}

if (! function_exists('control_efface_post'))
{
    /**
     * [control_efface_post description]
     *
     * @param   [type]  $apli      [$apli description]
     * @param   [type]  $post_id   [$post_id description]
     * @param   [type]  $topic_id  [$topic_id description]
     * @param   [type]  $IdForum   [$IdForum description]
     *
     * @return  [type]             [return description]
     */
    function control_efface_post($apli, $post_id, $topic_id, $IdForum)
    {
        return ForumManager::getInstance()->control_efface_post($apli, $post_id, $topic_id, $IdForum);
    }
}

if (! function_exists('autorize'))
{
    /**
     * [autorize description]
     *
     * @return  [type]  [return description]
     */
    function autorize()
    {
        return ForumManager::getInstance()->autorize();
    }
}

if (! function_exists('anti_flood'))
{
    /**
     * [anti_flood description]
     *
     * @param   [type]  $modoX       [$modoX description]
     * @param   [type]  $paramAFX    [$paramAFX description]
     * @param   [type]  $poster_ipX  [$poster_ipX description]
     * @param   [type]  $userdataX   [$userdataX description]
     * @param   [type]  $gmtX        [$gmtX description]
     *
     * @return  [type]               [return description]
     */
    function anti_flood($modoX, $paramAFX, $poster_ipX, $userdataX, $gmtX)
    {
        return ForumManager::getInstance()->anti_flood($modoX, $paramAFX, $poster_ipX, $userdataX, $gmtX);
    }
}

if (! function_exists('forum'))
{
    /**
     * [forum description]
     *
     * @param   [type]  $rowQ1  [$rowQ1 description]
     *
     * @return  [type]          [return description]
     */
    function forum($rowQ1)
    {
        return ForumManager::getInstance()->forum($rowQ1);
    }
}

if (! function_exists('sub_forum_folder'))
{
    /**
     * [sub_forum_folder description]
     *
     * @param   [type]  $forum  [$forum description]
     *
     * @return  [type]          [return description]
     */
    function sub_forum_folder($forum)
    {
        return ForumManager::getInstance()->sub_forum_folder($forum);
    }
}
