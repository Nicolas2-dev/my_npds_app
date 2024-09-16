<?php

namespace App\Modules\Forum\Contracts;


interface ForumInterface {

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
    public function RecentForumPosts_fab($title, $maxforums, $maxtopics, $displayposter, $topicmaxchars, $hr, $decoration);

    /**
     * [get_total_topics description]
     *
     * @param   [type]  $forum_id  [$forum_id description]
     *
     * @return  [type]             [return description]
     */
    public function get_total_topics($forum_id);

    /**
     * [get_contributeurs description]
     *
     * @param   [type]  $fid  [$fid description]
     * @param   [type]  $tid  [$tid description]
     *
     * @return  [type]        [return description]
     */
    public function get_contributeurs($fid, $tid);

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
    public function get_total_posts($fid, $tid, $type, $Mmod);

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
    public function get_last_post($id, $type, $cmd, $Mmod);

    /**
     * [does_exists description]
     *
     * @param   [type]  $id    [$id description]
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    public function does_exists($id, $type);

    /**
     * [is_locked description]
     *
     * @param   [type]  $topic  [$topic description]
     *
     * @return  [type]          [return description]
     */
    public  function is_locked($topic);

    /**
     * [HTML_Add description]
     *
     * @return  [type]  [return description]
     */
    public function HTML_Add();

    /**
     * [emotion_add description]
     *
     * @param   [type]  $image_subject  [$image_subject description]
     *
     * @return  [type]                  [return description]
     */
    public function emotion_add($image_subject);

    /**
     * [searchblock description]
     *
     * @return  [type]  [return description]
     */
    public function searchblock();

    /**
     * [member_qualif description]
     *
     * @param   [type]  $poster  [$poster description]
     * @param   [type]  $posts   [$posts description]
     * @param   [type]  $rank    [$rank description]
     *
     * @return  [type]           [return description]
     */
    public function member_qualif($poster, $posts, $rank);

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
    public function control_efface_post($apli, $post_id, $topic_id, $IdForum);

    /**
     * [autorize description]
     *
     * @return  [type]  [return description]
     */
    public function autorize();

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
    public function anti_flood($modoX, $paramAFX, $poster_ipX, $userdataX, $gmtX);

    /**
     * [forum description]
     *
     * @param   [type]  $rowQ1  [$rowQ1 description]
     *
     * @return  [type]          [return description]
     */
    public function forum($rowQ1);

    /**
     * [sub_forum_folder description]
     *
     * @param   [type]  $forum  [$forum description]
     *
     * @return  [type]          [return description]
     */
    public function sub_forum_folder($forum);

}
