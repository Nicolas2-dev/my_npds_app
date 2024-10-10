<?php

use App\Library\Supercache\SuperCacheEmpty;
use App\Library\Supercache\SuperCacheManager;


    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        // Include cache manager
        global $SuperCache;
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else
            $cache_obj = new SuperCacheEmpty();

        if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!$SuperCache)) {
            $mainlink = 'in_l';

            menu($mainlink);

            $filen = "modules/$ModPath/links.ban_01.php";
            if (file_exists($filen)) {
                include($filen);
            }

            echo '<table class="table table-bordered table-striped table-hover">';

            $result = sql_query("SELECT cid, title, cdescription FROM " . $links_DB . "links_categories ORDER BY title");

            if ($result) {
                while (list($cid, $title, $cdescription) = sql_fetch_row($result)) {
                    $cresult = sql_query("SELECT lid FROM " . $links_DB . "links_links WHERE cid='$cid'");
                    $cnumrows = sql_num_rows($cresult);

                    echo '
                    <tr>
                        <td>
                        <h4><a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewlink&amp;cid=' . $cid . '">' . aff_langue($title) . '</a> <span class="badge bg-secondary float-end">' . $cnumrows . '</span></h4>';
                    
                    categorynewlinkgraphic($cid);
                    
                    if ($cdescription)
                        echo '
                        <p>' . aff_langue($cdescription) . '</p>';

                    $result2 = sql_query("SELECT sid, title FROM " . $links_DB . "links_subcategories WHERE cid='$cid' ORDER BY title $subcat_limit");

                    while (list($sid, $stitle) = sql_fetch_row($result2)) {
                        $cresult3 = sql_query("SELECT lid FROM " . $links_DB . "links_links WHERE sid='$sid'");
                        $cnumrows = sql_num_rows($cresult3);

                        echo '<h5 class="ms-4"><a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewslink&amp;sid=' . $sid . '">' . aff_langue($stitle) . '</a> <span class="badge bg-secondary float-end">' . $cnumrows . '</span></h5>';
                    }

                    echo '
                        </td>
                    </tr>';
                }
            }

            echo '
            </table>';

            $result = sql_query("SELECT lid FROM " . $links_DB . "links_links");

            if ($result) {
                $numrows = sql_num_rows($result);
                echo '
                <p class="lead" align="center"><span>' . __d('links', 'Il y a') . ' <b>' . $numrows . '</b> ' . __d('links', 'Liens') . '
                    <span class="btn btn-danger btn-sm" title="' . __d('links', 'Les nouveaux liens de cette catégorie ajoutés aujourd\'hui') . '" data-bs-toggle="tooltip" >N</span>&nbsp;
                    <span class="btn btn-success btn-sm" title="' . __d('links', 'Les nouveaux liens ajoutés dans cette catégorie dans les 3 derniers jours') . '" data-bs-toggle="tooltip" >N</span>&nbsp;
                    <span class="btn btn-primary btn-sm" title="' . __d('links', 'Les nouveaux Liens ajoutés dans cette catégorie cette semaine') . '" data-bs-toggle="tooltip" >N</span>
                </p>';
            }
            SearchForm();
        }

        if ($SuperCache)
            $cache_obj->endCachingPage();

        global $admin;
        if ($admin) {
            $result = sql_query("SELECT requestid FROM " . $links_DB . "links_modrequest WHERE brokenlink=1");

            if ($result) {
                $totalbrokenlinks = sql_num_rows($result);

                $result2 = sql_query("SELECT requestid FROM " . $links_DB . "links_modrequest WHERE brokenlink=0");
                $totalmodrequests = sql_num_rows($result2);

                $result = sql_query("SELECT lid FROM " . $links_DB . "links_newlink");
                $num = sql_num_rows($result);

                echo '
                <p class="lead p-2 text-center border rounded bg-light">
                    <a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '/admin"><i class="fa fa-cogs fa-2x me-2 align-middle" title="Admin" data-bs-toggle="tooltip"></i></a> ' . __d('links', 'Liens') . ' : 
                    <span class="badge bg-danger ms-2" title="' . __d('links', 'Lien(s) en attente de validation') . '" data-bs-toggle="tooltip">' . $num . '</span> 
                    <span class="badge bg-danger ms-2" title="' . __d('links', 'Liens cassés rapportés par un ou plusieurs utilisateurs') . '" data-bs-toggle="tooltip">' . $totalbrokenlinks . '</span> 
                    <span class="badge bg-danger ms-2" title="' . __d('links', 'Proposition de modification') . '" data-bs-toggle="tooltip">' . $totalmodrequests . '</span>';
                
                if ($links_DB != '') 
                    echo 'Ref Tables => <strong>' . $links_DB . '</strong>';

                echo '
                </p>';
            } else
                echo "<p align=\"center\"><span> -: [ <a href=\"modules.php?ModStart=create_tables&amp;ModPath=$ModPath/admin/\">" . __d('links', 'Créer') . "</a> Tables : $links_DB ] :-</span></p>";
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $cid
     * @param [type] $min
     * @param [type] $orderby
     * @param [type] $show
     * @return void
     */
    function viewlink($cid, $min, $orderby, $show)
    {
        global $admin;

        settype($show, 'string');

        if (!isset($min)) 
            $min = 0;

        if (isset($orderby)) 
            $orderby = convertorderbyin($orderby);
        else 
            $orderby = "title ASC";

        // Include cache manager
        global $SuperCache;
        if ($SuperCache) {
            $cache_obj = new supercacheManager();
            $cache_obj->startCachingPage();
        } else
            $cache_obj = new SuperCacheEmpty();

        if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!$SuperCache)) {
            if (!isset($max)) 
                $max = $min + Config::get('npds.perpage');

            mainheader();

            settype($affsouscat, 'string');

            $filen = "modules/$ModPath/links.ban_02.php";
            if (file_exists($filen)) {
                include($filen);
            }

            $result = sql_query("SELECT title FROM " . $links_DB . "links_categories WHERE cid='$cid'");
            list($title) = sql_fetch_row($result);

            echo '<h3 class="mb-3">' . aff_langue($title) . '</h3>';

            $subresult = sql_query("SELECT sid, title FROM " . $links_DB . "links_subcategories WHERE cid='$cid' ORDER BY title");
            $numrows = sql_num_rows($subresult);

            settype($numrows_lst, 'integer');

            $affsouscat .= '
            <ul class="list-group">
                <li class="list-group-item "><h4 class="w-100">' . __d('links', 'Sous-catégories') . '<span class="badge bg-secondary float-end"> ' . $numrows . '</span></h4></li>';
            
            while (list($sid, $title) = sql_fetch_row($subresult)) {
                $result2 = sql_query("SELECT lid FROM " . $links_DB . "links_links WHERE sid='$sid'");
                $numrows_lst = sql_num_rows($result2);
                $affsouscat .= '
                <li class="list-group-item list-group-item-action justify-content-between align-self-start"><a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewslink&amp;sid=' . $sid . '">' . aff_langue($title) . '</a></li>';
            }

            $affsouscat .= '
            </ul>';

            if ($numrows != 0)
                echo $affsouscat;

            $orderbyTrans = convertorderbytrans($orderby);

            settype($min, "integer");

            $result = sql_query("SELECT lid, url, title, description, date, hits, topicid_card, cid, sid FROM " . $links_DB . "links_links WHERE cid='$cid' AND sid=0 ORDER BY $orderby LIMIT $min,Config::get('npds.perpage')");
            $fullcountresult = sql_query("SELECT lid, title, description, date, hits FROM " . $links_DB . "links_links WHERE cid='$cid' AND sid=0");

            $totalselectedlinks = sql_num_rows($fullcountresult);

            echo "<br />\n";
            $link_fiche_detail = '';

            include_once("modules/$ModPath/links-view.php");

            $orderby = convertorderbyout($orderby);
            //Calculates how many pages exist.  Which page one should be on, etc...
            $linkpagesint = ($totalselectedlinks / Config::get('npds.perpage'));
            $linkpageremainder = ($totalselectedlinks % Config::get('npds.perpage'));

            if ($linkpageremainder != 0) {
                $linkpages = ceil($linkpagesint);
                if ($totalselectedlinks < Config::get('npds.perpage'))
                    $linkpageremainder = 0;
            } else
                $linkpages = $linkpagesint;

            $nbPages = ceil($totalselectedlinks / Config::get('npds.perpage'));
            $current = 1;

            if ($min >= 1)
                $current = $min / Config::get('npds.perpage');
            else if ($min < 1)
                $current = 0;
            else
                $current = $nbPages;

            $start = ($current * Config::get('npds.perpage'));

            echo paginate('modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewlink&amp;cid=' . $cid . '&amp;min=', '&amp;orderby=' . $orderby . '&amp;show=' . Config::get('npds.perpage'), $nbPages, $current, $adj = 3, Config::get('npds.perpage'), $start);

            if (isset($sid)) 
                FooterOrderBy($cid, $sid, $orderbyTrans, 'viewlink');

            SearchForm();
        }

        if ($SuperCache)
            $cache_obj->endCachingPage();
    }

    /**
     * Undocumented function
     *
     * @param [type] $sid
     * @param [type] $min
     * @param [type] $orderby
     * @param [type] $show
     * @return void
     */
    function viewslink($sid, $min, $orderby, $show)
    {
        global $admin;

        if (!isset($min)) 
            $min = 0;

        if (isset($orderby)) 
            $orderby = convertorderbyin($orderby);
        else 
            $orderby = "title ASC";

        if (isset($show)) 
            Config::set('npds.perpage', $show);
        else 
            $show = Config::get('npds.perpage');

        // Include cache manager
        global $SuperCache;
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else
            $cache_obj = new SuperCacheEmpty();

        if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!$SuperCache)) {
            mainheader();

            $filen = "modules/$ModPath/links.ban_03.php";

            if (file_exists($filen)) 
                include($filen);

            if (!isset($max)) 
                $max = $min + Config::get('npds.perpage');

            $result = sql_query("SELECT cid, title FROM " . $links_DB . "links_subcategories WHERE sid='$sid'");
            list($cid, $stitle) = sql_fetch_row($result);

            $result2 = sql_query("SELECT cid, title FROM " . $links_DB . "links_categories WHERE cid='$cid'");
            list($cid, $title) = sql_fetch_row($result2);

            echo "<table class=\"table table-bordered\"><tr><td class=\"header\">\n";
            echo "<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"box\">" . __d('links', 'Index') . "</a> / <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewlink&amp;cid=$cid\" class=\"box\">" . aff_langue($title) . "</a> / " . aff_langue($stitle);
            echo "</td></tr></table>";

            $orderbyTrans = convertorderbytrans($orderby);

            settype($min, 'integer');

            $result = sql_query("SELECT lid, url, title, description, date, hits, topicid_card, cid, sid FROM " . $links_DB . "links_links WHERE sid='$sid' ORDER BY $orderby LIMIT $min,Config::get('npds.perpage')");
            $fullcountresult = sql_query("SELECT lid, title, description, date, hits FROM " . $links_DB . "links_links WHERE sid='$sid'");
            $totalselectedlinks = sql_num_rows($fullcountresult);

            echo "<br />\n";
            $link_fiche_detail = '';
            include_once("modules/$ModPath/links-view.php");

            $orderby = convertorderbyout($orderby);

            $nbPages = ceil($totalselectedlinks / Config::get('npds.perpage'));
            $current = 1;

            if ($min >= 1)
                $current = $min / Config::get('npds.perpage');
            else if ($min < 1)
                $current = 0;
            else
                $current = $nbPages;

            $start = ($current * Config::get('npds.perpage'));

            echo paginate('modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewslink&amp;sid=' . $sid . '&amp;min=', '&amp;orderby=' . $orderby . '&amp;show=' . $show, $nbPages, $current, $adj = 3, Config::get('npds.perpage'), $start);
            FooterOrderBy($cid, $sid, $orderbyTrans, "viewslink");
        }

        if ($SuperCache)
            $cache_obj->endCachingPage();
    }

    /**
     * Undocumented function
     *
     * @param [type] $Xlid
     * @return void
     */
    function fiche_detail($Xlid)
    {
        // Include cache manager
        global $SuperCache;
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else
            $cache_obj = new SuperCacheEmpty();

        if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!$SuperCache)) {
            settype($xlid, 'integer');
            $browse_key = $Xlid;
            $link_fiche_detail = "fiche_detail";
            $inter = 'cid';
            include("library/sform/links/link_detail.php");
        }

        if ($SuperCache)
            $cache_obj->endCachingPage();
    }

    /**
     * Undocumented function
     *
     * @param [type] $datetime
     * @param [type] $time
     * @return void
     */
    function newlinkgraphic($datetime, $time)
    {
        setlocale(LC_TIME, aff_langue(Config::get('npds.locale')));
        preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime);
        $count = round((time() - mktime($datetime[4], $datetime[5], $datetime[6], $datetime[2], $datetime[3], $datetime[1])) / 86400, 0);

        popgraphics($count);
    }

    /**
     * Undocumented function
     *
     * @param [type] $lid
     * @param [type] $ttitle
     * @return void
     */
    function detecteditorial($lid, $ttitle)
    {
        global $ModPath, $ModStart, $links_DB;

        $resulted2 = sql_query("SELECT adminid FROM " . $links_DB . "links_editorials WHERE linkid='$lid'");
        $recordexist = sql_num_rows($resulted2);

        if ($recordexist != 0) 
            echo '<a class="me-3" href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewlinkeditorial&amp;lid=' . $lid . '&amp;ttitle=' . $ttitle . '"><i class="far fa-sticky-note fa-lg" title="' . __d('links', 'EDITO') . '" data-bs-toggle="tooltip"></i></a>';
    }

    /**
     * Undocumented function
     *
     * @param [type] $lid
     * @return void
     */
    function visit($lid)
    {
        global $links_DB;

        sql_query("UPDATE " . $links_DB . "links_links SET hits=hits+1 WHERE lid='$lid'");

        $result = sql_query("SELECT url FROM " . $links_DB . "links_links WHERE lid='$lid'");
        list($url) = sql_fetch_row($result);

        Header("Location: $url");
    }

    /**
     * Undocumented function
     *
     * @param [type] $lid
     * @param [type] $ttitle
     * @return void
     */
    function viewlinkeditorial($lid, $ttitle)
    {
        mainheader();

        $result2 = sql_query("SELECT url FROM " . $links_DB . "links_links WHERE lid='$lid'");
        list($url) = sql_fetch_row($result2);

        $result = sql_query("SELECT adminid, editorialtimestamp, editorialtext, editorialtitle FROM " . $links_DB . "links_editorials WHERE linkid = '$lid'");
        $recordexist = sql_num_rows($result);

        $displaytitle = stripslashes($ttitle);

        echo '
        <div class="card card-body">
        <h3>' . __d('links', 'EDITO') . ' : 
            <span class="text-muted">' . aff_langue($displaytitle) . '</span>';

        if ($url != '')
            echo '<span class="float-end"><a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=visit&amp;lid=' . $lid . '" target="_blank" title="' . __d('links', 'Visiter ce site web') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fas fa-external-link-alt"></i></a></span>';
        
        echo '</h3>';

        if ($recordexist != 0) {

            while (list($adminid, $editorialtimestamp, $editorialtext, $editorialtitle) = sql_fetch_row($result)) {
                $editorialtitle = stripslashes($editorialtitle);
                $editorialtext = stripslashes($editorialtext);
                $formatted_date = formatTimestamp($editorialtimestamp);

                echo '
                <h4>' . aff_langue($editorialtitle) . '</h4>
                <p><span class="text-muted small">' . __d('links', 'Editorial par') . ' ' . $adminid . ' - ' . $formatted_date . '</span></p>
                <hr/>' . aff_langue($editorialtext);
            }
        } else
            echo '<p class="text-center">' . __d('links', 'Aucun édito n\'est disponible pour ce site') . '</p><br />';

        echo '
        </div>';
    }

    /**
     * Undocumented function
     *
     * @param [type] $time
     * @return void
     */
    function formatTimestampShort($time)
    {
        global $datetime;

        setlocale(LC_TIME, aff_langue(Config::get('npds.locale')));
        preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime);
        $datetime = strftime("" . __d('links', 'linksdatestring') . "", mktime($datetime[4] + (int) Config::get('npds.gmt'), $datetime[5], $datetime[6], $datetime[2], $datetime[3], $datetime[1]));
        
        if (cur_charset != 'utf-8')
            $datetime = ucfirst($datetime);

        return ($datetime);
    }

    /** */
    function AddLink()
    {
        global $user, $ad_l;

        mainheader();

        if (autorisation(Config::get('npds.links_anonaddlinklock'))) {
            echo '
                  <div class="card card-body mb-3">
                     <h3 class="mb-3">Proposer un lien</h3>
                     <div class="card card-outline-secondary mb-3">
                        <div class="card-body">
                           <span class="help-block">' . __d('links', 'Proposer un seul lien.') . '<br />' . __d('links', 'Tous les liens proposés sont vérifiés avant insertion.') . '<br />' . __d('links', 'Merci de ne pas abuser, le nom d\'utilisateur et l\'adresse IP sont enregistrés.') . '</span>
                        </div>
                     </div>
                     <form id="addlink" method="post" action="modules.php" name="adminForm">
                        <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                        <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                        <div class="mb-3 row">
                           <label class="col-form-label col-sm-3" for="title">' . __d('links', 'Titre') . '</label>
                           <div class="col-sm-9">
                              <input class="form-control" type="text" id="title" name="title" maxlength="100" required="required" />
                              <span class="help-block text-end" id="countcar_title"></span>
                        </div>
                     </div>';

            global $links_url;
            if (($links_url) or ($links_url == -1))
                echo '
                        <div class="mb-3 row">
                           <label class="col-form-label col-sm-3" for="url">URL</label>
                           <div class="col-sm-9">
                              <input class="form-control" type="url" id="url" name="url" maxlength="320" value="http://" required="required" />
                              <span class="help-block text-end" id="countcar_url"></span>
                        </div>
                     </div>';

            $result = sql_query("SELECT cid, title FROM " . $links_DB . "links_categories ORDER BY title");

            echo '
                        <div class="mb-3 row">
                            <label class="col-form-label col-sm-3" for="cat">' . __d('links', 'Catégorie') . '</label>
                            <div class="col-sm-9">
                            <select class="form-select" id="cat" name="cat">';

            while (list($cid, $title) = sql_fetch_row($result)) {
                echo '<option value="' . $cid . '">' . aff_langue($title) . '</option>';

                $result2 = sql_query("select sid, title from " . $links_DB . "links_subcategories WHERE cid='$cid' ORDER BY title");

                while (list($sid, $stitle) = sql_fetch_row($result2)) {
                    echo '<option value="' . $cid . '-' . $sid . '">' . aff_langue($title . '/' . $stitle) . '</option>';
                }
            }

            echo '
                            </select>
                        </div>
                        </div>';

            global $links_topic;
            if ($links_topic) {
                echo '
                        <div class="mb-3 row">
                            <label class="col-form-label col-sm-3" for="topicL">' . __d('links', 'Sujets') . '</label>
                            <div class="col-sm-9">
                            <select class="form-select" id="topicL" name="topicL">';

                $toplist = sql_query("SELECT topicid, topictext FROM topics ORDER BY topictext");

                echo '<option value="">' . __d('links', 'Tous les sujets') . '</option>';

                while (list($topicid, $topics) = sql_fetch_row($toplist)) {
                    echo '<option value="' . $topicid . '">' . $topics . '</option>';
                }

                echo '
                            </select>
                            </div>
                        </div>';
            }

            echo '
                        <div class="mb-3 row">
                            <label class="col-form-label col-sm-12" for="xtext">' . __d('links', 'Description') . '</label>
                            <div class="col-sm-12">
                            <textarea class="tin form-control" name="xtext" id="xtext" rows="10"></textarea>
                            </div>
                        </div>';

            echo aff_editeur('xtext', '');

            global $cookie;
            $nom = isset($cookie) ? $cookie[1] : '';

            echo '
                        <div class="mb-3 row">
                            <label class="col-form-label col-sm-3" for="name">' . __d('links', 'Votre nom') . '</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" maxlength="60" value="' . $nom . '" required="required" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-form-label col-sm-3" for="email">' . __d('links', 'Votre Email') . '</label>
                            <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" maxlength="254" required="required" />
                            <span class="help-block text-end" id="countcar_email"></span>
                            </div>
                        </div>';

            echo Q_spambot();

            echo '
                            <div class="mb-3 row">
                                <input type="hidden" name="op" value="Add" />
                                <div class="col-sm-9 ms-sm-auto">
                                <input type="submit" class="btn btn-primary" value="' . __d('links', 'Ajouter une url') . '" />
                                </div>
                            </div>
                        </form>
                        </div>
                    <div>
                    </div>';

            $arg1 = '
                    var formulid = ["addlink"];
                    inpandfieldlen("title",100);
                    inpandfieldlen("url",320);
                    inpandfieldlen("email",254);
                    ';

            SearchForm();
            adminfoot('fv', '', $arg1, '1');

        } else {
            echo '
                    <div class="alert alert-warning">' . __d('links', 'Vous n\'êtes pas (encore) enregistré ou vous n\'êtes pas (encore) connecté.') . '<br />
                    ' . __d('links', 'Si vous étiez enregistré, vous pourriez proposer des liens.') . '</div>';

            SearchForm();
        }
    }            

    /**
     * Undocumented function
     *
     * @return void
     */
    function Add($title, $url, $name, $cat, $description, $email, $topicL, $asb_question, $asb_reponse)
    {
        global $user, $admin;

        if (!$user and !$admin) {

            //anti_spambot
            if (!R_spambot($asb_question, $asb_reponse, '')) {
                Ecr_Log('security', 'Links Anti-Spam : url=' . $url, '');
                redirect_url("index.php");
                die();
            }
        }

        $result = sql_query("SELECT lid FROM " . $links_DB . "links_newlink");
        $numrows = sql_num_rows($result);

        if ($numrows >= Config::get('npds.troll_limit')) {
            error_head("alert-danger");
            echo __d('links', 'Erreur : cette url est déjà présente dans la base de données') . '<br />';
            error_foot();
            exit();
        }

        global $user;
        if (isset($user)) {
            global $cookie;
            $submitter = $cookie[1];
        } else
            $submitter = Config::get('npds.anonymous');

        if ($title == '') {
            error_head('alert-danger');
            echo __d('links', 'Erreur : vous devez saisir un titre pour votre lien') . '<br />';
            error_foot();
            exit();
        }

        if ($email == '') {
            error_head('alert-danger');
            echo __d('links', 'Erreur : Email invalide') . '<br />';
            error_foot();
            exit();
        }

        global $links_url;
        if (($url == '') and ($links_url == 1)) {
            error_head('alert-danger');
            echo __d('links', 'Erreur : vous devez saisir une url pour votre lien') . '<br />';
            error_foot();
            exit();
        }

        if ($description == '') {
            error_head('alert-danger');
            echo __d('links', 'Erreur : vous devez saisir une description pour votre lien') . '<br />';
            error_foot();
            exit();
        }

        $cat = explode('-', $cat);

        if (!array_key_exists(1, $cat))
            $cat[1] = 0;

        $title = removeHack(stripslashes(FixQuotes($title)));
        $url = removeHack(stripslashes(FixQuotes($url)));
        $description = dataimagetofileurl($description, 'modules/upload/upload/lindes');
        $description = removeHack(stripslashes(FixQuotes($description)));
        $name = removeHack(stripslashes(FixQuotes($name)));
        $email = removeHack(stripslashes(FixQuotes($email)));

        sql_query("INSERT INTO " . $links_DB . "links_newlink VALUES (NULL, '$cat[0]', '$cat[1]', '$title', '$url', '$description', '$name', '$email', '$submitter', '$topicL')");

        error_head('alert-success');

        echo __d('links', 'Nous avons bien reçu votre demande de lien, merci') . '<br />';
        echo __d('links', 'Vous recevrez un mèl quand elle sera approuvée.') . '<br />';

        error_foot();
    }            

    /**
     * Undocumented function
     *
     * @return void
     */
    public function NewLinks($newlinkshowdays)
    {
        if (!isset($newlinkshowdays)) {
            $newlinkshowdays = 7;
        }
        mainheader('nl');

        $counter = 0;
        $allweeklinks = 0;

        while ($counter <= 7 - 1) {
            $newlinkdayRaw = (time() - (86400 * $counter));
            $newlinkday = date("d-M-Y", $newlinkdayRaw);
            $newlinkView = date("F d, Y", $newlinkdayRaw);
            $newlinkDB = Date("Y-m-d", $newlinkdayRaw);

            $result = sql_query("SELECT * FROM " . $links_DB . "links_links WHERE date LIKE '%$newlinkDB%'");
            $totallinks = sql_num_rows($result);

            $counter++;
            $allweeklinks = $allweeklinks + $totallinks;
        }

        $counter = 0;
        $allmonthlinks = 0;

        while ($counter <= 30 - 1) {
            $newlinkdayRaw = (time() - (86400 * $counter));
            $newlinkDB = Date("Y-m-d", $newlinkdayRaw);

            $result = sql_query("SELECT * FROM " . $links_DB . "links_links WHERE date LIKE '%$newlinkDB%'");
            $totallinks = sql_num_rows($result);

            $allmonthlinks = $allmonthlinks + $totallinks;
            $counter++;
        }

        echo '
                    <div class="card card-body mb-3">
                    <h3>' . __d('links', 'Nouveaux liens') . '</h3>
                    ' . __d('links', 'Total des nouveaux liens pour la semaine dernière') . ' : ' . $allweeklinks . ' -/- ' . __d('links', 'Pour les 30 derniers jours') . ' : ' . $allmonthlinks;

        echo "<br />\n";

        echo "<blockquote>" . __d('links', 'Montrer :') . " [<a href=\"modules.php?ModStart=$ModStart&ModPath=$ModPath&op=NewLinks&newlinkshowdays=7\" class=\"noir\">" . __d('links', 'semaine') . "</a>, <a href=\"modules.php?ModStart=$ModStart&ModPath=$ModPath&op=NewLinks&newlinkshowdays=14\" class=\"noir\">2 " . __d('links', 'semaines') . "</a>, <a href=\"modules.php?ModStart=$ModStart&ModPath=$ModPath&op=NewLinks&newlinkshowdays=30\" class=\"noir\">30 " . __d('links', 'jours') . "</a>]</<blockquote>";

        $counter = 0;
        $allweeklinks = 0;

        echo '
                    <blockquote>
                    <ul>';

        while ($counter <= $newlinkshowdays - 1) {
            $newlinkdayRaw = (time() - (86400 * $counter));
            $newlinkday = date("d-M-Y", $newlinkdayRaw);

            $newlinkView = date(str_replace("%", "", __d('links', 'linksdatestring')), $newlinkdayRaw);
            $newlinkDB = Date("Y-m-d", $newlinkdayRaw);

            $result = sql_query("SELECT * FROM " . $links_DB . "links_links WHERE date LIKE '%$newlinkDB%'");
            $totallinks = sql_num_rows($result);

            $counter++;

            $allweeklinks = $allweeklinks + $totallinks;

            if ($totallinks > 0)
                echo "<li><a href=\"modules.php?ModStart=$ModStart&ModPath=$ModPath&op=NewLinksDate&selectdate=$newlinkdayRaw\">$newlinkView</a>&nbsp( $totallinks )</li>";
        }

        echo '
                    </blockquote>
                    </ul>
                    </div>';

        SearchForm();

        $counter = 0;
        $allmonthlinks = 0;           
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    function NewLinksDate($selectdate)
    {
        global $admin;

        $dateDB = (date("d-M-Y", $selectdate));

        mainheader('nl');

        $filen = "modules/$ModPath/links.ban_01.php";

        if (file_exists($filen)) {
            include($filen);
        }

        $newlinkDB = Date("Y-m-d", $selectdate);
        $result = sql_query("SELECT lid FROM " . $links_DB . "links_links WHERE date LIKE '%$newlinkDB%'");

        $totallinks = sql_num_rows($result);
        $result = sql_query("SELECT lid, url, title, description, date, hits, topicid_card, cid, sid FROM " . $links_DB . "links_links WHERE date LIKE '%$newlinkDB%' ORDER BY title ASC");

        $link_fiche_detail = '';

        include_once("modules/$ModPath/links-view.php");
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function brokenlink($lid)
    {
        global $user;
        if (isset($user)) {
            global $cookie;
            $ratinguser = $cookie[1];
        } else
            $ratinguser = Config::get('npds.anonymous');

        mainheader();

        echo '
                    <h3>' . __d('links', 'Rapporter un lien rompu') . '</h3>
                    <div class="alert alert-success my-3">
                            ' . __d('links', 'Merci de contribuer à la maintenance du site.') . '
                            <br />
                            <strong>' . __d('links', 'Pour des raisons de sécurité, votre nom d\'utilisateur et votre adresse IP vont être momentanément conservés.') . '</strong>
                            <br />
                    </div>
                    <form method="post" action="modules.php">
                        <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                        <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                        <input type="hidden" name="lid" value="' . $lid . '" />
                        <input type="hidden" name="modifysubmitter" value="' . $ratinguser . '" />
                        <input type="hidden" name="op" value="brokenlinkS" />
                        <input type="submit" class="btn btn-success" value="' . __d('links', 'Rapporter un lien rompu') . '" />
                    </form>';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function brokenlinkS($lid, $modifysubmitter)
    {
        global $user;

        if (isset($user)) {
            global $cookie;
            $ratinguser = $cookie[1];
        } else
            $ratinguser = Config::get('npds.anonymous');

        if ($modifysubmitter == $ratinguser) {
            settype($lid, 'integer');
            sql_query("INSERT INTO " . $links_DB . "links_modrequest VALUES (NULL, $lid, 0, 0, '', '', '', '$ratinguser', 1,0)");
        }

        mainheader();

        echo '
                    <h3>' . __d('links', 'Rapporter un lien rompu') . '</h3>
                    <div class="alert alert-success my-3">
                    ' . __d('links', 'Merci pour cette information. Nous allons l\'examiner dès que possible.') . '
                    </div>';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function modifylinkrequest($lid, $modifylinkrequest_adv_infos, $author)
    {
        if (autorise_mod($lid, false)) {
            if ($author == '-9')
                Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath/admin&op=LinksModLink&lid=$lid");

            mainheader();

            $result = sql_query("SELECT cid, sid, title, url, description, topicid_card FROM " . $links_DB . "links_links WHERE lid='$lid'");
            list($cid, $sid, $title, $url, $description, $topicid_card) = sql_fetch_row($result);

            $title = stripslashes($title);
            $description = stripslashes($description);

            echo '
                        <h3 class="my-3">' . __d('links', 'Proposition de modification') . ' : <span class="text-muted">' . $title . '</span></h3>
                        <form action="modules.php" method="post" name="adminForm">
                            <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                            <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                            <div class="mb-3 row">
                                <label class="col-form-label col-sm-3" for="title">' . __d('links', 'Titre') . '</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" id="title" name="title" value="' . $title . '"  maxlength="100" required="required" />
                                </div>
                            </div>';

            global $links_url;
            if ($links_url)
                echo '
                            <div class="mb-3 row">
                                <label class="col-form-label col-sm-3" for="url">URL</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="url" id="url" name="url" value="' . $url . '" maxlength="100" required="required" />
                                </div>
                            </div>';

            echo '
                        <div class="mb-3 row">
                            <label class="col-form-label col-sm-3" for="cat">' . __d('links', 'Catégorie') . '</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="cat" name="cat">';

            $result2 = sql_query("SELECT cid, title FROM " . $links_DB . "links_categories ORDER BY title");

            while (list($ccid, $ctitle) = sql_fetch_row($result2)) {
                $sel = '';

                if ($cid == $ccid and $sid == 0)
                    $sel = 'selected';

                echo '<option value="' . $ccid . '" ' . $sel . '>' . aff_langue($ctitle) . '</option>';

                $result3 = sql_query("SELECT sid, title FROM " . $links_DB . "links_subcategories WHERE cid='$ccid' ORDER BY title");

                while (list($ssid, $stitle) = sql_fetch_row($result3)) {
                    $sel = '';

                    if ($sid == $ssid) {
                        $sel = 'selected="selected"';
                    }

                    echo '<option value="' . $ccid . '-' . $ssid . '" ' . $sel . '>' . aff_langue($ctitle . ' / ' . $stitle) . '</option>';
                }
            }

            echo '
                                </select>
                            </div>
                        </div>';

            global $links_topic;
            if ($links_topic) {
                echo '
                            <div class="mb-3 row">
                                <label class="col-form-label col-sm-3" for="topicL">' . __d('links', 'Sujets') . '</label>
                                <div class="col-sm-9">
                                    <select class="form-select" id="topicL" name="topicL">';

                $toplist = sql_query("SELECT topicid, topictext FROM topics ORDER BY topictext");

                echo '<option value="">' . __d('links', 'Tous les sujets') . '</option>';

                while (list($topicid, $topics) = sql_fetch_row($toplist)) {
                    if ($topicid == $topicid_card)
                        $sel = 'selected="selected" ';

                    echo '<option value="' . $topicid . '" ' . $sel . '>' . $topics . '</option>';
                    $sel = '';
                }

                echo '
                                    </select>
                                </div>
                            </div>';
            }

            echo '
                        <div class="mb-3 row">
                            <label class="col-form-label col-sm-12" for="xtext">' . __d('links', 'Description : (255 caractères max)') . '</label>
                            <div class="col-sm-12">
                                <textarea class="form-control tin" id="xtext" name="xtext" rows="10">' . $description . '</textarea>
                            </div>
                        </div>';

            aff_editeur('xtext', '');

            echo '
                            <div class="mb-3 row">
                                <input type="hidden" name="lid" value="' . $lid . '" />
                                <input type="hidden" name="modifysubmitter" value="' . $author . '" />
                                <input type="hidden" name="op" value="modifylinkrequestS" />
                                <div class="col-sm-12">
                                    <input type="submit" class="btn btn-primary" value="' . __d('links', 'Envoyer une demande') . '" />
                                </div>
                            </div>
                        </form>';

            $browse_key = $lid;

            include("library/sform/$ModPath/link_maj.php");

            adminfoot('fv', '', '', 'nodiv');
        } else
            header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function modifylinkrequestS($lid, $cat, $title, $url, $description, $modifysubmitter, $topicL)
    {
        if (autorise_mod($lid, false)) {
            $cat = explode('-', $cat);
            if (!array_key_exists(1, $cat))
                $cat[1] = 0;

            $title = stripslashes(FixQuotes($title));
            $url = stripslashes(FixQuotes($url));
            $description = stripslashes(FixQuotes($description));

            if ($modifysubmitter == -9)
                $modifysubmitter = '';

            $result = sql_query("INSERT INTO " . $links_DB . "links_modrequest VALUES (NULL, $lid, $cat[0], $cat[1], '$title', '$url', '$description', '$modifysubmitter', '0', '$topicL')");

            echo '
                        <h3 class="my-3">' . __d('links', 'Liens') . '</h3>
                        <hr />
                        <h4 class="my-3">' . __d('links', 'Proposition de modification') . '</h4>
                        <div class="alert alert-success">' . __d('links', 'Merci pour cette information. Nous allons l\'examiner dès que possible.') . '</div>
                        <a class="btn btn-primary" href="modules.php?ModPath=links&amp;ModStart=links">Index </a>';
        }
    }

    /**
     * 
     */
    public function search($query, $topicL, $min, $max, $offset)
    {
        $offset = 10;

        if (!isset($min))
            $min = 0;

        if (!isset($max))
            $max = $min + $offset;

        mainheader();

        $filen = "modules/$ModPath/links.ban_02.php";

        if (file_exists($filen)) {
            include($filen);
        }

        $query = removeHack(stripslashes(htmlspecialchars($query, ENT_QUOTES, cur_charset))); // Romano et NoSP

        if ($topicL != '')
            $result = sql_query("SELECT lid, url, title, description, date, hits, topicid_card, cid, sid FROM " . $links_DB . "links_links WHERE topicid_card='$topicL' AND (title LIKE '%$query%' OR description LIKE '%$query%') ORDER BY lid ASC LIMIT $min,$offset");
        else
            $result = sql_query("SELECT lid, url, title, description, date, hits, topicid_card, cid, sid FROM " . $links_DB . "links_links WHERE title LIKE '%$query%' OR description LIKE '%$query%' ORDER BY lid ASC LIMIT $min,$offset");

        if ($result) {

            $link_fiche_detail = '';

            include_once("modules/$ModPath/links-view.php");

            $prev = $min - $offset;

            if ($prev >= 0) {
                echo "$min <a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;op=search&min=$prev&amp;query=$query&amp;topicL=$topicL\" class=\"noir\">";
                echo __d('links', 'réponses précédentes') . "</a>&nbsp;&nbsp;";
            }

            if ($x >= ($offset - 1)) {
                echo "<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;op=search&amp;min=$max&amp;query=$query&amp;topicL=$topicL\" class=\"noir\">";
                echo __d('links', 'réponses suivantes') . "</a>";
            }
        }
    }
