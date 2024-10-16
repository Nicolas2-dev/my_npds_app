<?php

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Language;

/**
 * [MM_img description]
 *
 * @param   [type]  $ibid  [$ibid description]
 *
 * @return  [type]         [return description]
 */
function MM_img($ibid)
{
	$ibid = arg_filter($ibid);

	$ibidX = theme_image($ibid);

	if ($ibidX) {
		$ret = "<img src=\"$ibidX\" border=\"0\" alt=\"\" />";
	} else {
		if (@file_exists("assets/images/$ibid")) {
			$ret = "<img src=\"assets/images/$ibid\" border=\"0\" alt=\"\" />";
		} else {
			$ret = false;
		}
	}

	return $ret;
}

function MM_banner()
{
	global $hlpfile;

	if ((config('npds.banners')) and (!$hlpfile)) {
		ob_start();
		//include("banners.php");
		$MT_banner = ob_get_contents();
		ob_end_clean();
	} else {
		$MT_banner = "";
	}

	return $MT_banner;
}

function MM_Scalcul($opex, $premier, $deuxieme)
{
	if ($opex  == "+") {
		$tmp = $premier + $deuxieme;
	}

	if ($opex == "-") {
		$tmp = $premier - $deuxieme;
	}

	if ($opex == "*") {
		$tmp = $premier * $deuxieme;
	}

	if ($opex == "/") {
		if ($deuxieme  == 0) {
			$tmp = "Division by zero !";
		} else {
			$tmp = $premier / $deuxieme;
		}
	}

	return $tmp;
}

function MM_anti_spam($arg)
{
	return ("<a href=\"mailto:" . anti_spam($arg, 1) . "\" target=\"_blank\">" . anti_spam($arg, 0) . "</a>");
}

function MM_msg_foot()
{	$MT_foot = '';

	if ($foot1 = config('npds.foot1'))
		$MT_foot .= stripslashes($foot1) . "<br />";

	if ($foot2 = config('npds.foot2'))
		$MT_foot .= stripslashes($foot2) . "<br />";

	if ($foot3 = config('npds.foot3'))
		$MT_foot .= stripslashes($foot3) . "<br />";

	if ($foot4 = config('npds.foot4'))
		$MT_foot .= stripslashes($foot4);

	return Language::aff_langue($MT_foot);
}

function MM_date()
{
	setlocale(LC_TIME, aff_langue(config('npds.locale')));
	$MT_date = \PHP81_BC\strftime(__d('npds', 'daydate'), time() + ((int) config('npds.gmt') * 3600));

	return $MT_date;
}

function MM_search_topics()
{
	$MT_search_topics = "<form action=\"search.php\" method=\"post\"><label class=\"col-form-label\">" . __d('npds', 'Sujets') . " </label>";
	$MT_search_topics .= "<select class=\"form-select\" name=\"topic\"onChange='submit()'>";
	$MT_search_topics .= "<option value=\"\">" . __d('npds', 'Tous les sujets') . "</option>\n";

	$rowQ = Q_select("select topicid, topictext from topics order by topictext", 86400);

	foreach ($rowQ as $myrow) {
		$MT_search_topics .= "<option value=\"" . $myrow['topicid'] . "\">" . aff_langue($myrow['topictext']) . "</option>\n";
	}

	$MT_search_topics .= "</select></form>";

	return $MT_search_topics;
}

function MM_search()
{
	$MT_search = "<form action=\"search.php\" method=\"post\"><label>" . __d('npds', 'Recherche') . "</label>
	<input class=\"form-control\" type=\"text\" name=\"query\" size=\"10\"></form>";

	return $MT_search;
}

function MM_member() {}

function MM_nb_online()
{
	list($MT_nb_online, $MT_whoim) = Who_Online();

	return $MT_nb_online;
}

function MM_whoim()
{
	list($MT_nb_online, $MT_whoim) = Who_Online();

	return $MT_whoim;
}

function MM_membre_nom()
{
	global $cookie;

	if (isset($cookie[1])) {

		$uname = arg_filter($cookie[1]);
		$MT_name = "";

		$rowQ = Q_select("SELECT name FROM users WHERE uname='$uname'", 3600);
		$myrow = $rowQ[0];

		$MT_name = $myrow['name'];

		return $MT_name;
	}
}

function MM_membre_pseudo()
{
	global $cookie;

	return $cookie[1];
}

function MM_blocID($arg)
{
	return (@oneblock(substr($arg, 1), substr($arg,  0, 1) . "B"));
}

function MM_block($arg)
{
	return meta_lang("blocID($arg)");
}

function MM_leftblocs($arg)
{
	ob_start();
	leftblocks($arg);
	$M_Lblocs = ob_get_contents();
	ob_end_clean();

	return $M_Lblocs;
}

function MM_rightblocs($arg)
{
	ob_start();
	rightblocks($arg);
	$M_Lblocs = ob_get_contents();
	ob_end_clean();

	return $M_Lblocs;
}

function MM_articleID($arg)
{
	$arg = arg_filter($arg);

	$rowQ = Q_select("SELECT title FROM stories WHERE sid='$arg'", 3600);
	$myrow = $rowQ[0];

	return "<a href=\"" . config('npds.nuke_url') . "/article.php?sid=$arg\">" . $myrow['title'] . "</a>";
}

function MM_article($arg)
{
	return meta_lang("articleID($arg)");
}

function MM_article_completID($arg)
{
	if ($arg > 0) {
		$story_limit = 1;
		$news_tab = prepa_aff_news("article", $arg, "");
	} else {
		$news_tab = prepa_aff_news("index", "", "");
		$story_limit = abs($arg) + 1;
	}

	$aid = unserialize($news_tab[$story_limit]['aid']);
	$informant = unserialize($news_tab[$story_limit]['informant']);
	$datetime = unserialize($news_tab[$story_limit]['datetime']);
	$title = unserialize($news_tab[$story_limit]['title']);
	$counter = unserialize($news_tab[$story_limit]['counter']);
	$topic = unserialize($news_tab[$story_limit]['topic']);
	$hometext = unserialize($news_tab[$story_limit]['hometext']);
	$notes = unserialize($news_tab[$story_limit]['notes']);
	$morelink = unserialize($news_tab[$story_limit]['morelink']);
	$topicname = unserialize($news_tab[$story_limit]['topicname']);
	$topicimage = unserialize($news_tab[$story_limit]['topicimage']);
	$topictext = unserialize($news_tab[$story_limit]['topictext']);
	$s_id = unserialize($news_tab[$story_limit]['id']);

	if ($aid) {
		ob_start();
		themeindex($aid, $informant, $datetime, $title, $counter, $topic, $hometext, $notes, $morelink, $topicname, $topicimage, $topictext, $s_id);
		$remp = ob_get_contents();
		ob_end_clean();
	} else {
		$remp = "";
	}

	return $remp;
}

function MM_article_complet($arg)
{
	return meta_lang("article_completID($arg)");
}

function MM_headlineID($arg)
{
	return @headlines($arg, "");
}

function MM_headline($arg)
{
	return meta_lang("headlineID($arg)");
}

function MM_list_mns()
{

	$query = sql_query("SELECT uname FROM users WHERE mns='1'");

	$MT_mns = "<ul class=\"list-group list-group-flush\">";

	while (list($uname) = sql_fetch_row($query)) {
		$MT_mns .= "<li class=\"list-group-item\"><a href=\"minisite.php?op=$uname\" target=\"_blank\">$uname</a></li>";
	}

	$MT_mns .= "</ul>";

	return $MT_mns;
}

function MM_LastMember()
{

	$query = sql_query("SELECT uname FROM users ORDER BY uid DESC LIMIT 0,1");
	$result = sql_fetch_row($query);

	return $result[0];
}

function MM_edito()
{
	list($affich, $M_edito) = fab_edito();

	if ((!$affich) or ($M_edito == "")) {
		$M_edito = "";
	}

	return $M_edito;
}

function MM_groupe_text($arg)
{
	global $user;

	$affich = false;
	$remp = "";

	if ($arg != "") {
		if (groupe_autorisation($arg, valid_group($user)))
			$affich = true;
	} else {
		if ($user)
			$affich = true;
	}

	if (!$affich) {
		$remp = "!delete!";
	}

	return $remp;
}

function MM_no_groupe_text($arg)
{
	global $user;

	$affich = true;
	$remp = "";

	if ($arg != "") {
		if (groupe_autorisation($arg, valid_group($user)))
			$affich = false;

		if (!$user)
			$affich = false;
	} else {
		if ($user)
			$affich = false;
	}

	if (!$affich) {
		$remp = "!delete!";
	}

	return $remp;
}

function MM_note()
{
	return "!delete!";
}

function MM_debugON()
{
	global $NPDS_debug, $NPDS_debug_str, $NPDS_debug_time, $NPDS_debug_cycle;

	$NPDS_debug_cycle = 1;
	$NPDS_debug = true;
	$NPDS_debug_str = "<br />";
	$NPDS_debug_time = microtime(true);

	return "";
}

function MM_debugOFF()
{
	global $NPDS_debug, $NPDS_debug_str, $NPDS_debug_time, $NPDS_debug_cycle;

	$time_end = microtime(true);
	$NPDS_debug_str .= "=> !DebugOFF!<br /><b>=> exec time for meta-lang : " . round($time_end - $NPDS_debug_time, 4) . " / cycle(s) : $NPDS_debug_cycle</b><br />";
	$NPDS_debug = false;
	echo $NPDS_debug_str;

	return "";
}

function MM_forum_all()
{

	$rowQ1 = Q_Select("SELECT * FROM catagories ORDER BY cat_id", 3600);
	$Xcontent = @forum($rowQ1);

	return $Xcontent;
}

function MM_forum_categorie($arg)
{

	$arg = arg_filter($arg);
	$bid_tab = explode(",", $arg);
	$sql = "";

	foreach ($bid_tab as $cat) {
		$sql .= "cat_id='$cat' OR ";
	}

	$sql = substr($sql, 0, -4);
	$rowQ1 = Q_Select("SELECT * FROM catagories WHERE $sql", 3600);
	$Xcontent = @forum($rowQ1);

	return $Xcontent;
}

function MM_forum_message()
{
	global $user;

	$ibid = "";
	if (!$user) {
		$ibid = __d('npds', 'Devenez membre et vous disposerez de fonctions spécifiques : abonnements, forums spéciaux (cachés, membres, ..), statut de lecture, ...');
	}
	if ((config('npds.subscribe')) and ($user)) {
		$ibid = __d('npds', 'Cochez un forum et cliquez sur le bouton pour recevoir un Email lors d\'une nouvelle soumission dans celui-ci.');
	}

	return $ibid;
}

function MM_forum_recherche()
{

	$Xcontent = @searchblock();

	return $Xcontent;
}

function MM_forum_icones()
{

	if ($ibid = theme_image("forum/icons/red_folder.gif")) {
		$imgtmpR = $ibid;
	} else {
		$imgtmpR = "assets/images/forum/icons/red_folder.gif";
	}

	if ($ibid = theme_image("forum/icons/folder.gif")) {
		$imgtmp = $ibid;
	} else {
		$imgtmp = "assets/images/forum/icons/folder.gif";
	}

	$ibid = "<img src=\"$imgtmpR\" border=\"\" alt=\"\" /> = " . __d('npds', 'Les nouvelles contributions depuis votre dernière visite.') . "<br />";
	$ibid .= "<img src=\"$imgtmp\" border=\"\" alt=\"\" /> = " . __d('npds', 'Aucune nouvelle contribution depuis votre dernière visite.');

	return $ibid;
}

function MM_forum_subscribeON()
{
	global $user;

	$ibid = "";
	if ((config('npds.subscribe')) and ($user)) {
		$userX = base64_decode($user);
		$userR = explode(':', $userX);

		if (isbadmailuser($userR[0]) === false) {
			$ibid = "<form action=\"forum.php\" method=\"post\">
			<input type=\"hidden\" name=\"op\" value=\"maj_subscribe\" />";
		}
	}

	return $ibid;
}

function MM_forum_bouton_subscribe()
{

	global $user;
	if ((config('npds.subscribe')) and ($user)) {
		$userX = base64_decode($user);
		$userR = explode(':', $userX);

		if (isbadmailuser($userR[0]) === false) {
			return '<input class="btn btn-secondary" type="submit" name="Xsub" value="' . __d('npds', 'OK') . '" />';
		}
	} else {
		return '';
	}
}

function MM_forum_subscribeOFF()
{
	global $user;

	$ibid = "";
	if ((config('npds.subscribe')) and ($user)) {
		$userX = base64_decode($user);
		$userR = explode(':', $userX);
		if (isbadmailuser($userR[0]) === false) {
			$ibid = "</form>";
		}
	}

	return $ibid;
}

function MM_forum_subfolder($arg)
{

	$forum = arg_filter($arg);
	$content = sub_forum_folder($forum);

	return $content;
}

function MM_insert_flash($name, $width, $height, $bgcol)
{
	return "<object codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflas
	classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\"
	h.cab#version=6,0,0,0\" width=\"" . $width . "\"
	height=\"" . $height . "\"
	id=\"" . $name . "\" align=\"middle\">
  
	<param name=\"allowScriptAccess\"
	value=\"sameDomain\" />
  
	<param name=\"movie\"
	value=\"flash/" . $name . "\" />
  
	<param name=\"quality\" value=\"high\" />
	<param name=\"bgcolor\"
	value=\"" . $bgcol . "\" />
 
	<embed src=\"flash/" . $name . "\"
	quality=\"high\" bgcolor=\"" . $bgcol . "\"
	width=\"" . $width . "\"
	height=\"" . $height . "\"
	name=\"" . $name . "\" align=\"middle\"
	allowScriptAccess=\"sameDomain\"
	type=\"application/x-shockwave-flash\"
	pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
 
	</object>";
}

function MM_login()
{
	global $user;
	$boxstuff = '
	<div class="card card-body m-3">
	   <h5><a href="user.php?op=only_newuser" role="button" title="' . __d('npds', 'Nouveau membre') . '"><i class="fa fa-user-plus"></i>&nbsp;' . __d('npds', 'Nouveau membre') . '</a></h5>
	</div>
	<div class="card card-body m-3">
	   <h5 class="mb-3"><i class="fas fa-sign-in-alt fa-lg"></i>&nbsp;' . __d('npds', 'Connexion') . '</h5>
	   <form action="user.php" method="post" name="userlogin_b">
		  <div class="row g-2">
			 <div class="col-12">
				<div class="mb-3 form-floating">
				   <input type="text" class="form-control" name="uname" id="inputuser_b" placeholder="' . __d('npds', 'Identifiant') . '" required="required" />            
				   <label for="inputuser_b" >' . __d('npds', 'Identifiant') . '</label>
			   </div>
			</div>
			<div class="col-12">
			   <div class="mb-0 form-floating">
				  <input type="password" class="form-control" name="pass" id="inputPassuser_b" placeholder="' . __d('npds', 'Mot de passe') . '" required="required" />
				  <label for="inputPassuser_b">' . __d('npds', 'Mot de passe') . '</label>
				  <span class="help-block small"><a href="user.php?op=forgetpassword" role="button" title="' . __d('npds', 'Vous avez perdu votre mot de passe ?') . '">' . __d('npds', 'Vous avez perdu votre mot de passe ?') . '</a></span>
				</div>
			 </div>
		  </div>
		  <input type="hidden" name="op" value="login" />
		  <div class="mb-3 row">
			 <div class="ms-sm-auto">
				<button class="btn btn-primary" type="submit" title="' . __d('npds', 'Valider') . '">' . __d('npds', 'Valider') . '</button>
			 </div>
		  </div>
	   </form>
	</div>';

	if (isset($user))
		$boxstuff = '<h5><a class="text-danger" href="user.php?op=logout"><i class="fas fa-sign-out-alt fa-lg align-middle text-danger me-2"></i>' . __d('npds', 'Déconnexion') . '</a></h5>';

	return $boxstuff;
}



function MM_administration()
{
	global $admin;

	if ($admin) {
		return "<a href=\"admin.php\">" . __d('npds', 'Outils administrateur') . "</a>";
	} else {
		return "";
	}
}

function MM_admin_infos($arg)
{

	$arg = arg_filter($arg);

	$rowQ1 = Q_select("SELECT url, email FROM authors WHERE aid='$arg'", 86400);
	$myrow = $rowQ1[0];

	if ($myrow['url'] != '') {
		$auteur = "<a href=\"" . $myrow['url'] . "\">$arg</a>";
	} elseif ($myrow['email'] != '') {
		$auteur = "<a href=\"mailto:" . $myrow['email'] . "\">$arg</a>";
	} else {
		$auteur = $arg;
	}

	return $auteur;
}

function MM_theme_img($arg)
{
	return MM_img($arg);
}


function MM_rotate_img($arg)
{
	mt_srand((float)microtime() * 1000000);

	$arg = arg_filter($arg);
	$tab_img = explode(",", $arg);

	if (count($tab_img) > 1) {
		$imgnum = mt_rand(0, count($tab_img) - 1);
	} else if (count($tab_img) == 1) {
		$imgnum = 0;
	} else {
		$imgnum = -1;
	}

	if ($imgnum != -1) {
		$Xcontent = "<img src=\"" . $tab_img[$imgnum] . "\" border=\"0\" alt=\"" . $tab_img[$imgnum] . "\" title=\"" . $tab_img[$imgnum] . "\" />";
	}

	return $Xcontent;
}


function MM_sql_nbREQ()
{
	global $sql_nbREQ;

	return "SQL REQ : $sql_nbREQ";
}


function MM_comment_system($file_name, $topic)
{
	global $anonpost, $moderate, $admin, $user;

	ob_start();
	if (file_exists("modules/comments/$file_name.conf.php")) {
		include("modules/comments/$file_name.conf.php");
		include("modules/comments/comments.php");
	}

	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}

function MM_top_stories($arg)
{
	$content = '';
	$arg = arg_filter($arg);

	$xtab = news_aff("libre", "ORDER BY counter DESC LIMIT 0, " . $arg * 2, 0, $arg * 2);
	$story_limit = 0;

	while (($story_limit < $arg) and ($story_limit < sizeof($xtab))) {
		list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter) = $xtab[$story_limit];

		$story_limit++;

		if ($counter > 0) {
			$content .= '<li class="ms-4 my-1"><a href="article.php?sid=' . $sid . '" >' . aff_langue($title) . '</a>&nbsp;<span class="badge bg-secondary float-end">' . wrh($counter) . ' ' . __d('npds', 'Fois') . '</span></li>';
		}
	}

	return $content;
}

function MM_top_commented_stories($arg)
{
	$content = '';
	$arg = arg_filter($arg);

	$xtab = news_aff("libre", "ORDER BY comments DESC  LIMIT 0, " . $arg * 2, 0, $arg * 2);

	$story_limit = 0;
	while (($story_limit < $arg) and ($story_limit < sizeof($xtab))) {
		list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments) = $xtab[$story_limit];
		$story_limit++;

		if ($comments > 0) {
			$content .= '<li class="ms-4 my-1"><a href="article.php?sid=' . $sid . '" >' . aff_langue($title) . '</a>&nbsp;<span class="badge bg-secondary float-end">' . wrh($comments) . '</span></li>';
		}
	}

	return $content;
}

function MM_top_categories($arg)
{
	$content = '';
	$arg = arg_filter($arg);

	$result = sql_query("SELECT catid, title, counter FROM stories_cat order by counter DESC limit 0,$arg");

	while (list($catid, $title, $counter) = sql_fetch_row($result)) {
		if ($counter > 0) {
			$content .= '<li class="ms-4 my-1"><a href="index.php?op=newindex&amp;catid=' . $catid . '" >' . aff_langue($title) . '</a>&nbsp;<span class="badge bg-secondary float-end">' . wrh($counter) . '</span></li>';
		}
	}
	sql_free_result($result);

	return $content;
}

function MM_top_sections($arg)
{

	$content = '';
	$arg = arg_filter($arg);

	$result = sql_query("SELECT artid, title, counter FROM seccont ORDER BY counter DESC LIMIT 0,$arg");

	while (list($artid, $title, $counter) = sql_fetch_row($result)) {
		$content .= '<li class="ms-4 my-1"><a href="sections.php?op=viewarticle&amp;artid=' . $artid . '" >' . aff_langue($title) . '</a>&nbsp;<span class="badge bg-secondary float-end">' . wrh($counter) . ' ' . __d('npds', 'Fois') . '</span></li>';
	}
	sql_free_result($result);

	return $content;
}

function MM_top_reviews($arg)
{

	$content = '';
	$arg = arg_filter($arg);

	$result = sql_query("SELECT id, title, hits FROM reviews ORDER BY hits DESC LIMIT 0,$arg");

	while (list($id, $title, $hits) = sql_fetch_row($result)) {
		if ($hits > 0) {
			$content .= '<li class="ms-4 my-1"><a href="reviews.php?op=showcontent&amp;id=' . $id . '" >' . $title . '</a>&nbsp;<span class="badge bg-secondary float-end">' . wrh($hits) . ' ' . __d('npds', 'Fois') . '</span></li>';
		}
	}
	sql_free_result($result);

	return $content;
}

function MM_top_authors($arg)
{

	$content = '';
	$arg = arg_filter($arg);

	$result = sql_query("SELECT aid, counter FROM authors ORDER BY counter DESC LIMIT 0,$arg");

	while (list($aid, $counter) = sql_fetch_row($result)) {
		if ($counter > 0) {
			$content .= '<li class="ms-4 my-1"><a href="search.php?query=&amp;author=' . $aid . '" >' . $aid . '</a>&nbsp;<span class="badge bg-secondary float-end">' . wrh($counter) . '</span></li>';
		}
	}
	sql_free_result($result);

	return $content;
}

function MM_top_polls($arg)
{

	$content = '';
	$arg = arg_filter($arg);

	$result = sql_query("SELECT pollID, pollTitle, voters FROM poll_desc ORDER BY voters DESC LIMIT 0,$arg");

	while (list($pollID, $pollTitle, $voters) = sql_fetch_row($result)) {
		if ($voters > 0) {
			$content .= '<li class="ms-4 my-1"><a href="pollBooth.php?op=results&amp;pollID=' . $pollID . '" >' . aff_langue($pollTitle) . '</a>&nbsp;<span class="badge bg-secondary float-end">' . wrh($voters) . '</span></li>';
		}
	}
	sql_free_result($result);

	return $content;
}

function MM_top_storie_authors($arg)
{

	$content = '';
	$arg = arg_filter($arg);

	$result = sql_query("SELECT uname, counter FROM users ORDER BY counter DESC LIMIT 0,$arg");

	while (list($uname, $counter) = sql_fetch_row($result)) {
		if ($counter > 0) {
			$content .= '<li class="ms-4 my-1"><a href="user.php?op=userinfo&amp;uname=' . $uname . '" >' . $uname . '</a>&nbsp;<span class="badge bg-secondary float-end">' . wrh($counter) . '</span></li>';
		}
	}
	sql_free_result($result);

	return $content;
}

function MM_topic_all()
{
	$aff = '';
	$aff = '<div class="">';

	$result = sql_query("SELECT topicid, topicname, topicimage, topictext FROM topics ORDER BY topicname");
	while (list($topicid, $topicname, $topicimage, $topictext) = sql_fetch_row($result)) {

		$resultn = sql_query("SELECT COUNT(*) AS total FROM stories WHERE topic='$topicid'");
		$total_news = sql_fetch_assoc($resultn);

		$aff .= '
		   <div class="col-sm-6 col-lg-4 mb-2 griditem px-2">
			  <div class="card my-2">';
		if ((($topicimage) or ($topicimage != '')) and (file_exists("'.config('npds.tipath') . $topicimage")))
			$aff .= '
			  <img class="mt-3 ms-3 n-sujetsize" src="' . config('npds.tipath') . $topicimage . '" alt="topic_icon" />';
		$aff .= '
				 <div class="card-body">';

		if ($total_news['total'] != '0')
			$aff .= '
					<a href="index.php?op=newtopic&amp;topic=' . $topicid . '"><h4 class="card-title">' . aff_langue($topicname) . '</h4></a>';
		else
			$aff .= '
					<h4 class="card-title">' . aff_langue($topicname) . '</h4>';

		$aff .= '
					<p class="card-text">' . aff_langue($topictext) . '</p>
					<p class="card-text text-end"><span class="small">' . __d('npds', 'Nb. d\'articles') . '</span> <span class="badge bg-secondary">' . $total_news['total'] . '</span></p>
				 </div>';
		$aff .= '
			  </div>
		   </div>';
	}

	$aff .= '
		   </div>';

	sql_free_result($result);

	return $aff;
}

function MM_topic_subscribeOFF()
{
	$aff = '<div class="mb-3 row"><input type="hidden" name="op" value="maj_subscribe" />';
	$aff .= '<button class="btn btn-primary ms-3" type="submit" name="ok">' . __d('npds', 'Valider') . '</button>';
	$aff .= '</div></fieldset></form>';

	return $aff;
}

function MM_topic_subscribeON()
{
	global $user, $cookie;

	if (config('npds.subscribe') and $user) {
		if (isbadmailuser($cookie[0]) === false) {
			return ('<form action="topics.php" method="post"><fieldset>');
		}
	}
}

function MM_topic_subscribe($arg)
{
	global $user, $cookie;

	$segment = arg_filter($arg);
	$aff = '';

	if (config('npds.subscribe')) {
		if ($user) {
			$aff = '
			  <div class="mb-3 row">';

			$result = sql_query("SELECT topicid, topictext, topicname FROM topics ORDER BY topicname");

			while (list($topicid, $topictext, $topicname) = sql_fetch_row($result)) {
				$resultX = sql_query("SELECT topicid FROM subscribe WHERE uid='$cookie[0]' AND topicid='$topicid'");

				if (sql_num_rows($resultX) == "1")
					$checked = 'checked';
				else
					$checked = '';

				$aff .= '
					<div class="' . $segment . '">
					   <div class="form-check">
						  <input type="checkbox" class="form-check-input" name="Subtopicid[' . $topicid . ']" id="subtopicid' . $topicid . '" ' . $checked . ' />
						  <label class="form-check-label" for="subtopicid' . $topicid . '">' . aff_langue($topicname) . '</label>
					   </div>
					</div>';
			}

			$aff .= '</div>';

			sql_free_result($result);
		}
	}

	return $aff;
}

function MM_yt_video($id_yt_video)
{
	$content = '';
	$id_yt_video = arg_filter($id_yt_video);

	if (!defined('CITRON'))
		$content .= '
		   <div class="ratio ratio-16x9">
			  <iframe src="https://www.youtube.com/embed/' . $id_yt_video . '" allowfullscreen="" frameborder="0"></iframe>
		   </div>';
	else
		$content .= '<div class="youtube_player" videoID="' . $id_yt_video . '"></div>';

	return $content;
}

function MM_espace_groupe($gr, $t_gr, $i_gr)
{
	$gr = arg_filter($gr);
	$t_gr = arg_filter($t_gr);
	$i_gr = arg_filter($i_gr);

	return fab_espace_groupe($gr, $t_gr, $i_gr);
}

function MM_forumP()
{
	global $cookie, $user;


	/*Sujet chaud*/
	$hot_threshold = 10;

	/*Nbre posts a afficher*/
	$maxcount = "15";

	$MM_forumP = '<table cellspacing="3" cellpadding="3" width="top" border="0">'
		. '<tr align="center" class="ligna">'
		. '<th width="5%">' . aff_langue('[french]Etat[/french][english]State[/english]') . '</th>'
		. '<th width="20%">' . aff_langue('[french]Forum[/french][english]Forum[/english]') . '</th>'
		. '<th width="30%">' . aff_langue('[french]Sujet[/french][english]Topic[/english]') . '</th>'
		. '<th width="5%">' . aff_langue('[french]RÃ©ponse[/french][english]Replie[/english]') . '</th>'
		. '<th width="20%">' . aff_langue('[french]Dernier Auteur[/french][english]Last author[/english]') . '</th>'
		. '<th width="20%">' . aff_langue('[french]Date[/french][english]Date[/english]') . '</th>'
		. '</tr>';

	/*Requete liste dernier post*/
	$result = sql_query("SELECT MAX(post_id) FROM posts WHERE forum_id > 0 GROUP BY topic_id ORDER BY MAX(post_id) DESC LIMIT 0,$maxcount");
	while (list($post_id) = sql_fetch_row($result)) {

		/*Requete detail dernier post*/
		$res = sql_query("SELECT 
						us.topic_id, us.forum_id, us.poster_id, us.post_time, 
						uv.topic_title, 
						ug.forum_name, ug.forum_type, ug.forum_pass, 
						ut.uname 
					FROM 
						posts us, 
						forumtopics uv, 
						forums ug, 
						users ut 
					WHERE 
						us.post_id = $post_id 
						AND uv.topic_id = us.topic_id 
						AND uv.forum_id = ug.forum_id 
						AND ut.uid = us.poster_id LIMIT 1");
		list($topic_id, $forum_id, $poster_id, $post_time, $topic_title, $forum_name, $forum_type, $forum_pass, $uname) = sql_fetch_row($res);

		if (($forum_type == "5") or ($forum_type == "7")) {

			$ok_affich = false;
			$tab_groupe = valid_group($user);
			$ok_affich = groupe_forum($forum_pass, $tab_groupe);
		} else {

			$ok_affich = true;
		}

		if ($ok_affich) {

			/*Nbre de postes par sujet*/
			$TableRep = sql_query("SELECT * FROM posts WHERE forum_id > 0 AND topic_id = '$topic_id'");
			$replys = sql_num_rows($TableRep) - 1;

			/*Gestion lu / non lu*/
			$sqlR = "SELECT rid FROM forum_read WHERE topicid = '$topic_id' AND uid = '$cookie[0]' AND status != '0'";

			if ($ibid = theme_image("forum/icons/hot_red_folder.gif")) {
				$imgtmpHR = $ibid;
			} else {
				$imgtmpHR = "assets/images/forum/icons/hot_red_folder.gif";
			}

			if ($ibid = theme_image("forum/icons/hot_folder.gif")) {
				$imgtmpH = $ibid;
			} else {
				$imgtmpH = "assets/images/forum/icons/hot_folder.gif";
			}

			if ($ibid = theme_image("forum/icons/red_folder.gif")) {
				$imgtmpR = $ibid;
			} else {
				$imgtmpR = "assets/images/forum/icons/red_folder.gif";
			}

			if ($ibid = theme_image("forum/icons/folder.gif")) {
				$imgtmpF = $ibid;
			} else {
				$imgtmpF = "assets/images/forum/icons/folder.gif";
			}

			if ($ibid = theme_image("forum/icons/lock.gif")) {
				$imgtmpL = $ibid;
			} else {
				$imgtmpL = "assets/images/forum/icons/lock.gif";
			}

			if ($replys >= $hot_threshold) {

				if (sql_num_rows(sql_query($sqlR)) == 0)
					$image = $imgtmpHR;
				else
					$image = $imgtmpH;
			} else {

				if (sql_num_rows(sql_query($sqlR)) == 0)
					$image = $imgtmpR;
				else
					$image = $imgtmpF;
			}

			if ($myrow['topic_status'] != 0)
				$image = $imgtmpL;

			$MM_forumP .= '<tr class="lignb">'
				. '<td align="center"><img src="' . $image . '"></td>'
				. '<td><a href="viewforum.php?forum=' . $forum_id . '">' . $forum_name . '</a></td>'
				. '<td><a href="viewtopic.php?topic=' . $topic_id . '&forum=' . $forum_id . '">' . $topic_title . '</a></td>'
				. '<td align="center">' . $replys . '</td>'
				. '<td><a href="user.php?op=userinfo&uname=' . $uname . '">' . $uname . '</a></td>'
				. '<td align="center">' . $post_time . '</td>'
				. '</tr>';
		}
	}

	$MM_forumP .= '</table>';

	return $MM_forumP;
}

function MM_forumL()
{

	global $cookie, $user;

	/*Sujet chaud*/
	$hot_threshold = 10;

	/*Nbre posts a afficher*/
	$maxcount = "10";

	$MM_forumL = '<table cellspacing="3" cellpadding="3" width="top" border="0">'
		. '<tr align="center" class="ligna">'
		. '<td width="8%">' . aff_langue('[french]Etat[/french][english]State[/english]') . '</td>'
		. '<td width="35%">' . aff_langue('[french]Forum[/french][english]Forum[/english]') . '</td>'
		. '<td width="50%">' . aff_langue('[french]Sujet[/french][english]Topic[/english]') . '</td>'
		. '<td width="7%">' . aff_langue('[french]RÃ©ponses[/french][english]Replies[/english]') . '</td>'
		. '</tr>';

	/*Requete liste dernier post*/
	$result = sql_query("SELECT MAX(post_id) FROM posts WHERE forum_id > 0 GROUP BY topic_id ORDER BY MAX(post_id) DESC LIMIT 0,$maxcount");
	while (list($post_id) = sql_fetch_row($result)) {

		/*Requete detail dernier post*/
		$res = sql_query("SELECT 
						us.topic_id, us.forum_id, us.poster_id, 
						uv.topic_title, 
						ug.forum_name, ug.forum_type, ug.forum_pass 
					FROM 
						posts us, 
						forumtopics uv, 
						forums ug 
					WHERE 
						us.post_id = $post_id 
						AND uv.topic_id = us.topic_id 
						AND uv.forum_id = ug.forum_id LIMIT 1");
		list($topic_id, $forum_id, $poster_id, $topic_title, $forum_name, $forum_type, $forum_pass) = sql_fetch_row($res);

		if (($forum_type == "5") or ($forum_type == "7")) {

			$ok_affich = false;
			$tab_groupe = valid_group($user);
			$ok_affich = groupe_forum($forum_pass, $tab_groupe);
		} else {

			$ok_affich = true;
		}

		if ($ok_affich) {

			/*Nbre de postes par sujet*/
			$TableRep = sql_query("SELECT * FROM posts WHERE forum_id > 0 AND topic_id = '$topic_id'");
			$replys = sql_num_rows($TableRep) - 1;

			/*Gestion lu / non lu*/
			$sqlR = "SELECT rid FROM forum_read WHERE topicid = '$topic_id' AND uid = '$cookie[0]' AND status != '0'";

			if ($ibid = theme_image("forum/icons/hot_red_folder.gif")) {
				$imgtmpHR = $ibid;
			} else {
				$imgtmpHR = "assets/images/forum/icons/hot_red_folder.gif";
			}

			if ($ibid = theme_image("forum/icons/hot_folder.gif")) {
				$imgtmpH = $ibid;
			} else {
				$imgtmpH = "assets/images/forum/icons/hot_folder.gif";
			}

			if ($ibid = theme_image("forum/icons/red_folder.gif")) {
				$imgtmpR = $ibid;
			} else {
				$imgtmpR = "assets/images/forum/icons/red_folder.gif";
			}

			if ($ibid = theme_image("forum/icons/folder.gif")) {
				$imgtmpF = $ibid;
			} else {
				$imgtmpF = "assets/images/forum/icons/folder.gif";
			}

			if ($ibid = theme_image("forum/icons/lock.gif")) {
				$imgtmpL = $ibid;
			} else {
				$imgtmpL = "assets/images/forum/icons/lock.gif";
			}

			if ($replys >= $hot_threshold) {

				if (sql_num_rows(sql_query($sqlR)) == 0)
					$image = $imgtmpHR;
				else
					$image = $imgtmpH;
			} else {

				if (sql_num_rows(sql_query($sqlR)) == 0)
					$image = $imgtmpR;
				else
					$image = $imgtmpF;
			}

			if ($myrow['topic_status'] != 0)
				$image = $imgtmpL;

			$MM_forumL .= '<tr class="lignb">'
				. '<td align="center"><img src="' . $image . '"></td>'
				. '<td><a href="viewforum.php?forum=' . $forum_id . '">' . $forum_name . '</a></td>'
				. '<td><a href="viewtopic.php?topic=' . $topic_id . '&forum=' . $forum_id . '">' . $topic_title . '</a></td>'
				. '<td align="center">' . $replys . '</td>'
				. '</tr>';
		}
	}

	$MM_forumL .= '</table>';

	return $MM_forumL;
}

function MM_note_admin()
{
	global $admin;

	if (!$admin)
		return "!delete!";
	else
		return "<b>nota</b> : ";
}

function MM_blocnote($arg)
{
	global $REQUEST_URI;
	if (!stristr($REQUEST_URI, "admin.php")) {
		return @oneblock($arg, "RB");
	} else {
		return "";
	}
}


function MM_vm_video($id_vm_video)
{
	$content = '';
	$id_vm_video = arg_filter($id_vm_video);

	if (!defined('CITRON'))
		$content .= '
		   <div class="ratio ratio-16x9">
			  <iframe src="https://player.vimeo.com/video/' . $id_vm_video . '" allowfullscreen="" frameborder="0"></iframe>
		   </div>';
	else
		$content .= '<div class="vimeo_player" videoID="' . $id_vm_video . '"></div>';

	return $content;
}

function MM_dm_video($id_dm_video)
{
	$content = '';
	$id_dm_video = arg_filter($id_dm_video);

	if (!defined('CITRON'))
		$content .= '
		   <div class="ratio ratio-16x9">
			  <iframe src="https://www.dailymotion.com/embed/video/' . $id_dm_video . '" allowfullscreen="" frameborder="0"></iframe>
		   </div>';
	else
		$content .= '<div class="dailymotion_player" videoID="' . $id_dm_video . '"></div>';

	return $content;
}

function MM_noforbadmail()
{
	global $user, $cookie;

	$remp = '';
	if (config('npds.subscribe') and $user) {
		if (isbadmailuser($cookie[0]) === true)
			$remp = '!delete!';
	}

	return $remp;
}
