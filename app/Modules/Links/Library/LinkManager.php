<?php

namespace App\Modules\Links\Library;



/**
 * Undocumented class
 */
class LinkManager implements LinkInterface 
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
     * Undocumented function
     *
     * @return void
     */
    public function menu()
    {
        // case 'menu':
        //     menu($mainlink);
        //     break;

        global $ModPath, $ModStart,$op;

        $ne_l = $op == 'NewLinks' ? 'active' : '';
        $ad_l = $op == 'AddLink' ? 'active' : '';
        $in_l = $op == '' ? 'active' : '';

        echo '
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item"><a class="nav-link ' . $in_l . '" href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '" >' . __d('links', 'Index') . '</a></li>';

        if (autorisation(Config::get('npds.links_anonaddlinklock')))
            echo '
            <li class="nav-item" ><a class="nav-link ' . $ad_l . '" href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=AddLink" >' . __d('links', 'Ajouter') . '</a></li>';

        echo '
            <li class="nav-item"><a class="nav-link ' . $ne_l . '" href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=NewLinks" >' . __d('links', 'Nouveautés') . '</a></li>
            <li class="nav-item"><a class="nav-link " href="#linksearchblock">' . __d('links', 'Recherche') . '</a></li>
        </ul>';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function mainheader()
    {
        menu();
    }

    /**
     * Undocumented function
     *
     * @param [type] $lid
     * @param [type] $aff
     * @return void
     */
    public function autorise_mod($lid, $aff)
    {
        global $ModPath, $ModStart, $links_DB, $user, $admin;

        if ($admin) {
            $Xadmin = base64_decode($admin);
            $Xadmin = explode(':', $Xadmin);

            $result = sql_query("SELECT radminsuper FROM authors WHERE aid='$Xadmin[0]'");
            list($radminsuper) = sql_fetch_row($result);

            if ($radminsuper == 1) { // faut remettre le controle des droits probablement pour les admin qui ont le droit link ??!!
                if ($aff)
                    echo '<a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=modifylinkrequest&amp;lid=' . $lid . '&amp;author=-9" title="' . __d('links', 'Modifier') . '" data-bs-toggle="tooltip"><i class="fa fa-edit fa-lg"></i></a>';
                
                return (true);
            }

        } elseif ($user != '') {
            global $cookie;

            $resultX = sql_query("SELECT submitter FROM " . $links_DB . "links_links WHERE submitter='$cookie[1]' AND lid='$lid'");
            list($submitter) = sql_fetch_row($resultX);

            if ($submitter == $cookie[1]) {
                if ($aff)
                    echo '<a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=modifylinkrequest&amp;lid=' . $lid . '&amp;author=' . $cookie[1] . '" title="' . __d('links', 'Modifier') . '" data-bs-toggle="tooltip" ><i class="fa fa-edit fa-lg"></i></a>';
                return (true);
            } else
                return (false);
        } else
            return (false);
    }

    /**
     * Undocumented function
     *
     * @param [type] $cid
     * @param [type] $sid
     * @param [type] $orderbyTrans
     * @param [type] $linkop
     * @return void
     */
    public function FooterOrderBy($cid, $sid, $orderbyTrans, $linkop)
    {
        global $ModPath, $ModStart;

        echo '<p align="center"><span class="">' . __d('links', 'Classement') . ' : ';

        if ($linkop == "viewlink") {
            echo __d('links', 'Titre') . ' (<a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewlink&amp;cid=' . $cid . '&amp;orderby=titleA"><i class="fas fa-sort-alpha-down"></i>A</a>\<a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewlink&amp;cid=' . $cid . '&amp;orderby=titleD">D</a>)
            ' . __d('links', 'Date') . ' (<a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewlink&amp;cid=' . $cid . '&amp;orderby=dateA">A</a>\<a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewlink&amp;cid=' . $cid . '&amp;orderby=dateD">D</a>)';
        } else {
            echo __d('links', 'Titre') . ' <a class="me-3" href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewslink&amp;sid=' . $sid . '&amp;orderby=titleA"><i class="fas fa-sort-alpha-down fa-lg align-middle"></i></a><a class="me-3" href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewslink&amp;sid=' . $sid . '&amp;orderby=titleD"><i class="fas fa-sort-alpha-down-alt fa-lg align-middle"></i></a>
            ' . __d('links', 'Date') . ' <a class="me-3" href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewslink&amp;sid=' . $sid . '&amp;orderby=dateA"><i class="fas fa-sort-numeric-down fa-lg align-middle"></i></a><a class="me-3" href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=viewslink&amp;sid=' . $sid . '&amp;orderby=dateD"><i class="fas fa-sort-numeric-down-alt fa-lg align-middle"></i></a>';
        }

        echo '<br />' . __d('links', 'Sites classés par') . ' : <strong>' . $orderbyTrans . '</strong></span></p>';

        /*
        echo '
        <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        '.__d('links', 'Classement').'
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">'.__d('links', 'Titre').'<i class="ms-2 fas fa-sort-alpha-down fa-lg align-middle"></i></a>
            <a class="dropdown-item" href="#">'.__d('links', 'Titre').'<i class="ms-2 fas fa-sort-alpha-down-alt fa-lg align-middle"></i></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">'.__d('links', 'Date').'<i class="fas fa-sort-numeric-down fa-lg align-middle"></i></a>
            <a class="dropdown-item" href="#">'.__d('links', 'Date').'<i class="fas fa-sort-numeric-down-alt fa-lg align-middle"></i></a>
        </div>
        </div>';
        */
    }

    /**
     * Undocumented function
     *
     * @param [type] $count
     * @return void
     */
    function popgraphics($count)
    {
        if ($count < 1) 
            echo '<span class="btn btn-danger btn-sm float-end" title="' . __d('links', 'Les nouveaux liens de cette catégorie ajoutés aujourd\'hui') . '" data-bs-toggle="tooltip" data-bs-placement="left">N</span>';
        
        if ($count <= 3 && $count >= 1) 
            echo '<span class="btn btn-success btn-sm float-end" title="' . __d('links', 'Les nouveaux liens ajoutés dans cette catégorie dans les 3 derniers jours') . '" data-bs-toggle="tooltip" data-bs-placement="left">N</span>';
        
        if ($count <= 7 && $count > 3) 
            echo '<span class="btn btn-infos btn-sm float-end" title="' . __d('links', 'Les nouveaux Liens ajoutés dans cette catégorie cette semaine') . '" data-bs-toggle="tooltip" data-bs-placement="left">N</span>';
    }

    /**
     * Undocumented function
     *
     * @param [type] $orderby
     * @return void
     */
    function convertorderbyin($orderby)
    {
        $orderbyIn = 'title ASC';

        if ($orderby == 'titleA')          
            $orderbyIn = 'title ASC';

        if ($orderby == 'dateA')           
            $orderbyIn = 'date ASC';

        if ($orderby == 'titleD')          
            $orderbyIn = 'title DESC';

        if ($orderby == 'dateD')           
            $orderbyIn = 'date DESC';

        return $orderbyIn;
    }

    /**
     * Undocumented function
     *
     * @param [type] $orderby
     * @return void
     */
    function convertorderbytrans($orderby)
    {
        $orderbyTrans = __d('links', 'Title (A to Z)');

        if ($orderby == 'title ASC')       
            $orderbyTrans = __d('links', 'Titre (de A à Z)');

        if ($orderby == 'title DESC')      
            $orderbyTrans = __d('links', 'Titre (de Z à A)');

        if ($orderby == 'date ASC')        
            $orderbyTrans = __d('links', 'Date (les plus vieux liens en premier)');

        if ($orderby == 'date DESC')       
            $orderbyTrans = __d('links', 'Date (les liens les plus récents en premier)');

        return $orderbyTrans;
    }

    /**
     * Undocumented function
     *
     * @param [type] $orderby
     * @return void
     */
    function convertorderbyout($orderby)
    {
        $orderbyOut = 'titleA';

        if ($orderby == 'title ASC')       
            $orderbyOut = 'titleA';

        if ($orderby == 'date ASC')        
            $orderbyOut = 'dateA';

        if ($orderby == 'title DESC')      
            $orderbyOut = 'titleD';

        if ($orderby == 'date DESC')       
            $orderbyOut = 'dateD';

        return $orderbyOut;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $cat
     * @return void
     */
    function categorynewlinkgraphic($cat)
    {
        global $links_DB;

        if (Config::get('npds.OnCatNewLink') == '1') {
            $newresult = sql_query("SELECT date FROM " . $links_DB . "links_links WHERE cid='$cat' ORDER BY date DESC LIMIT 1");
            list($time) = sql_fetch_row($newresult);

            if (isset($ime)) {
                setlocale(LC_TIME, aff_langue(Config::get('npds.locale')));
                preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime);
                $count = round((time() - mktime($datetime[4], $datetime[5], $datetime[6], $datetime[2], $datetime[3], $datetime[1])) / 86400, 0);

                popgraphics($count);
            }
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function SearchForm()
    {
        global $ModPath, $ModStart, $links_topic;

        echo '
        <div class="card card-body mb-3" id="linksearchblock">
            <h3 class="mb-3">' . __d('links', 'Recherche') . '</h3>
            <form action="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=search" method="post">';

        if ($links_topic) {
            echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="topicL" >' . __d('links', 'Sélectionner un sujet') . '</label>
                    <div class="col-sm-8">
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
                    <label class="col-form-label col-sm-4" for="query">' . __d('links', 'Votre requête') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" type="text" id="query" name="query" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-8 ms-sm-auto">
                    <button class="btn btn-primary" type="submit">' . __d('links', 'Recherche') . '</button>
                    </div>
                </div>
            </form>
        </div>';
    }

    /**
     * Undocumented function
     *
     * @param [type] $class
     * @return void
     */
    public function error_head($class)
    {
        $mainlink = 'ad_l';
        
        menu($mainlink);
        SearchForm();

        echo '<div class="alert ' . $class . '" role="alert" align="center">';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function error_foot()
    {
        echo '</div>';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function link_view()
    {
        $x = 0;

        while (list($lid, $url, $title, $description, $time, $hits, $topicid_card, $xcid, $xsid) = sql_fetch_row($result)) {

            //compare the description with "nohtml description"
            if (!empty($query)) {
                $affichT = false;
                $affichD = false;

                if (strcasecmp($title, strip_tags($title)) == 0) {
                    if ($title != preg_replace("#$query#", "<b>$query</b>", $title)) {
                        $title = preg_replace("#$query#", "<b>$query</b>", $title);
                        $affichT = true;
                    }
                } else
                    $affichT = true;

                if (strcasecmp($description, strip_tags($description)) == 0) {
                    if ($description != preg_replace("#$query#", "<b>$query</b>", $description)) {
                        $description = preg_replace("#$query#", "<b>$query</b>", $description);
                        $affichD = true;
                    }
                } else
                    $affichD = true;

                $affich = ($affichT or $affichD) ? true : false;
            } else
                $affich = true;

            if ($affich) {
                $title = stripslashes($title);
                $description = stripslashes($description);

                settype($datetime, 'string');

                echo '
                    <div class="card mb-3">
                        <div class="card-body ibid_descr">';

                if ($url == '')
                    echo '<h4 class="text-muted"><i class="fas fa-external-link-alt"></i>&nbsp;' . aff_langue($title);
                else
                    echo '<h4><a href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=visit&amp;lid=' . $lid . '" target="_blank" ><i class="fas fa-external-link-alt"></i>&nbsp;' . aff_langue($title) . '</a>';

                echo '&nbsp;' . newlinkgraphic($datetime, $time) . '</h4>';

                if (!empty($xcid)) {
                    $result3 = sql_query("SELECT title FROM " . $links_DB . "links_categories WHERE cid='$xcid'");
                    $result4 = sql_query("SELECT title FROM " . $links_DB . "links_subcategories WHERE sid='$xsid'");

                    list($ctitle) = sql_fetch_row($result3);
                    list($stitle) = sql_fetch_row($result4);

                    if ($stitle == '') 
                        $slash = '';
                    else 
                        $slash = '/';

                    echo __d('links', 'Catégorie : ') . "<strong>" . aff_langue($ctitle) . "</strong> $slash <b>" . aff_langue($stitle) . "</b>";
                }

                global $links_topic;
                if ($links_topic and $topicid_card != 0) {
                    list($topicLX) = sql_fetch_row(sql_query("SELECT topictext FROM topics WHERE topicid='$topicid_card'"));
                    echo '<br />' . __d('links', 'Sujets') . ' : <strong>' . $topicLX . '</strong>';
                }

                echo '<div class="ibid_descr "><p>' . aff_langue($description) . '</p></div>';

                if ($url != '') {
                    echo '<div class="d-flex justify-content-between">';

                    if ($hits > Config::get('npds.popular'))
                        echo '<span class="text-success"><i class="fa fa-star-o fa-lg"></i></span><span class="ms-auto">' . __d('links', 'Hits: ') . '<span class=" badge bg-secondary">' . $hits . '</span></span>';
                    else
                        echo '<span class="ms-auto">' . __d('links', 'Nb hits : ') . '<span class=" badge bg-secondary">' . $hits . '</span></span>';

                    echo '</div>';
                }

                echo '
                    </div>
                    <div class="card-footer d-flex justify-content-start">';

                $datetime = formatTimestampShort($time);

                echo '
                    <span class="small">' . __d('links', 'Ajouté le : ') . $datetime . '</span>
                    <span class="ms-auto">';

                if ($url != '')
                    echo '<a class="me-3" href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=brokenlink&amp;lid=' . $lid . '" title="' . __d('links', 'Rapporter un lien rompu') . '" data-bs-toggle="tooltip"><i class="fas fa-unlink fa-lg"></i></a>';
                
                // Advance infos via the class sform.php
                $browse_key = $lid;

                include("library/sform/$ModPath/link_detail.php");

                detecteditorial($lid, urlencode($title));

                echo '<a class="me-3" href="print.php?DB=' . $links_DB . '&amp;lid=' . $lid . '" title="' . __d('links', 'Page spéciale pour impression') . '" data-bs-toggle="tooltip"><i class="fa fa-print fa-lg"></i></a>';
                
                autorise_mod($lid, true);
                
                echo '
                    </span>
                    </div>
                </div>';
                $x++;
            }
        }

        sql_free_result($result);

        if (isset($result2))
            sql_free_result($result2);

        if (isset($result3))
            sql_free_result($result3);        
    }

}
