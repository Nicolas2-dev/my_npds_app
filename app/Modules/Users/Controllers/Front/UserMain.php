<?php

namespace App\Modules\Users\Controllers\Front;


use Npds\view\View;
use Npds\Routing\Url;
use Npds\Config\Config;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Date;
use App\Modules\Npds\Support\Facades\Hack;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Npds\Support\Facades\Cookie;


/**
 * [UserLogin description]
 */
class UserMain extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();

        //     case 'userinfo':
        //         if ((Config::get('npds.member_list') == 1) and ((!isset($user)) and (!isset($admin))))
        //             Header("Location: index.php");
        
        //         if ($uname != '')
        //             userinfo($uname);
        //         else
        //             main($user);
        //         break;

        //     case 'main':
        //         if (!AutoReg()) 
        //             unset($user);
        
        //         main($user);
        //         break;
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
        global $user;

        $this->title(__('user info'));

        if (!User::AutoReg()) {
            unset($user);
        }

        if (empty($user)) {
            Url::redirect('user/login');

        } elseif (isset($user)) {
           $cookie = Cookie::cookiedecode($user);

           $this->userinfo($cookie[1]);
        }
    }

    /**
     * [userinfo description]
     *
     * @param   [type]  $uname  [$uname description]
     *
     * @return  [type]          [return description]
     */
    public function userinfo($uname)
    {
        global $admin;
        global $user;
        global $name, $email, $url, $bio, $user_avatar, $user_from, $user_occ, $user_intrest, $user_sig, $user_journal, $C7, $C8;
    
        $uname = Hack::remove($uname);
    
        $result = sql_query("SELECT uid, name, femail, url, bio, user_avatar, user_from, user_occ, user_intrest, user_sig, user_journal, mns FROM users WHERE uname='$uname'");
        list($uid, $name, $femail, $url, $bio, $user_avatar, $user_from, $user_occ, $user_intrest, $user_sig, $user_journal, $mns) = sql_fetch_row($result);
        
        if (!$uid) {
            Url::redirect('index');
        }
    
        global $cookie;
    
        $email          = Hack::remove($femail);
        $name           = stripslashes(Hack::remove($name));
        $url            = Hack::remove($url);
        $bio            = stripslashes(Hack::remove($bio));
        $user_from      = stripslashes(Hack::remove($user_from));
        $user_occ       = stripslashes(Hack::remove($user_occ));
        $user_intrest   = stripslashes(Hack::remove($user_intrest));
        $user_sig       = nl2br(Hack::remove($user_sig));
        $user_journal   = stripslashes(Hack::remove($user_journal));
    
        $op = 'userinfo';
    
        if (stristr($user_avatar, 'users_private')) {
            $direktori = '';
        } else {
            global $theme;
            $direktori = 'assets/images/forum/avatar/';
    
            if (function_exists('theme_image')) {
                if (theme_image('forum/avatar/blank.gif')) {
                    $direktori = 'themes/'. $theme .'/assets/images/forum/avatar/';
                }
            }
        }
    
        $socialnetworks     = [];
        $posterdata_extend  = [];
        $res_id             = [];
    
        $my_rs = '';
    
        $posterdata_extend = get_userdata_extend_from_id($uid);
    
        if (!Config::get('npds.short_user')) {
            include('modules/reseaux-sociaux/config/reseaux-sociaux.conf.php');
    
            if (array_key_exists('M2', $posterdata_extend)) {
                $socialnetworks = explode(';', $posterdata_extend['M2']);
    
                foreach ($socialnetworks as $socialnetwork) {
                    $res_id[] = explode('|', $socialnetwork);
                }
    
                sort($res_id);
                sort($rs);
    
                foreach ($rs as $v1) {
                    foreach ($res_id as $y1) {
                        $k = array_search($y1[0], $v1);
    
                        if (false !== $k) {
                            $my_rs .= '<a class="me-3" href="';
    
                            if ($v1[2] == 'skype') {
                                $my_rs .= $v1[1] . $y1[1] . '?chat';
                            } else {
                                $my_rs .= $v1[1] . $y1[1];
                            }
    
                            $my_rs .= '" target="_blank"><i class="fab fa-' . $v1[2] . ' fa-2x"></i></a> ';
                            break;
                        } else {
                            $my_rs .= '';
                        }
                    }
                }
            }
        }
    
        $posterdata = get_userdata_from_id($uid);
    
        $useroutils = '';
    
        if (($user) and ($uid != 1)) {
            $useroutils .= '<a class=" text-primary me-3" href="powerpack.php?op=instant_message&amp;to_userid=' . $posterdata["uname"] . '" ><i class="far fa-envelope fa-2x" title="' . __d('users', 'Envoyer un message interne') . '" data-bs-toggle="tooltip"></i></a>&nbsp;';
        }

        if (array_key_exists('femail', $posterdata)) {
            if ($posterdata['femail'] != '')  {
                $useroutils .= '<a class=" text-primary me-3" href="mailto:' . anti_spam($posterdata['femail'], 1) . '" target="_blank" ><i class="fa fa-at fa-2x" title="' . __d('users', 'Email') . '" data-bs-toggle="tooltip"></i></a>&nbsp;';
            }
        }

        if (array_key_exists('url', $posterdata)) {
            if ($posterdata['url'] != '') {
                $useroutils .= '<a class=" text-primary me-3" href="' . $posterdata['url'] . '" target="_blank" ><i class="fas fa-external-link-alt fa-2x" title="' . __d('users', 'Visiter ce site web') . '" data-bs-toggle="tooltip"></i></a>&nbsp;';
            }
        }

        if (array_key_exists('mns', $posterdata)) {
            if ($posterdata['mns']) {
                $useroutils .= '<a class=" text-primary me-3" href="minisite.php?op=' . $posterdata['uname'] . '" target="_blank" ><i class="fa fa-desktop fa-2x" title="' . __d('users', 'Visitez le minisite') . '" data-bs-toggle="tooltip"></i></a>&nbsp;';
            }
        }

        $userinfo = '
        <div class="d-flex flex-row flex-wrap">
            <div class="me-2 my-auto"><img src="' . $direktori . $user_avatar . '" class=" rounded-circle center-block n-ava-64 align-middle" /></div>
            <div class="align-self-center">
                <h2>' . __d('users', 'Utilisateur') . '<span class="d-inline-block text-muted ms-1">' . $uname . '</span></h2>';
    
        if (isset($cookie[1])) {
            if ($uname !== $cookie[1]) {
                $userinfo .= $useroutils;
            }
        }
    
        $userinfo .= $my_rs;
    
        if (isset($cookie[1])) {
            if ($uname == $cookie[1]) {
                $userinfo .= '<p class="lead">' . __d('users', 'Si vous souhaitez personnaliser un peu le site, c\'est l\'endroit indiqué. ') . '</p>';
            }
        }

        $userinfo .= '
            </div>
        </div>
        <hr />';
    
        if (isset($cookie[1])) {
            if ($uname == $cookie[1]) {
                $userinfo .= User::member_menu($mns, $uname);
            }
        }

        $userinfo .= '
        <div class="card card-body">
            <div class="row">';

        if (array_key_exists($ch_lat = Config::get('geoloc.config.ch_lat'), $posterdata_extend) 
        and array_key_exists($ch_lon = Config::get('geoloc.config.ch_lon'), $posterdata_extend)) 
        {
            if ($posterdata_extend[$ch_lat] != '' and $posterdata_extend[$ch_lon] != '') {
                $C7 = $posterdata_extend[$ch_lat];
                $C8 = $posterdata_extend[$ch_lon];

                $userinfo .= '<div class="col-md-6">';
            } else {
                $userinfo .= '<div class="col-md-12">';
            }
        }

        $userinfo .= include(module_path('Users/Sform/aff_extend-user.php'));
    
        $userinfo .= '</div>';
    
        //==> geoloc
        if (array_key_exists($ch_lat, $posterdata_extend) and array_key_exists($ch_lon, $posterdata_extend)) {
            if ($posterdata_extend[$ch_lat] != '' and $posterdata_extend[$ch_lon] != '') {
                $content = '';
    
                $content .= '
                <div class="col-md-6">
                    <div id="map_user" tabindex="300" style="width:100%; height:400px;" lang="' . language_iso(1, 0, 0) . '">
                    <div id="ol_popup"></div>
                </div>
                <script type="module">
                    //<![CDATA[
                    if (typeof ol=="undefined")
                        $("head").append($("<script />").attr({"type":"text/javascript", "src":"'. site_url('assets/shared/ol/ol.js') .'"}));
                    $(function(){
                    var 
                        iconFeature = new ol.Feature({
                            geometry: new ol.geom.Point(
                            ol.proj.fromLonLat([' . $posterdata_extend[$ch_lon] . ',' . $posterdata_extend[$ch_lat] . '])
                            ),
                            name: "' . $uname . '"
                        }),
                        iconStyle = new ol.style.Style({
                            image: new ol.style.Icon({
                            src: "' . Config::get('geoloc.config.ch_img') . Config::get('geoloc.config.nm_img_mbcg') . '"
                            })
                        });
                    iconFeature.setStyle(iconStyle);
                    var vectorSource = new ol.source.Vector({features: [iconFeature]}),
                        vectorLayer = new ol.layer.Vector({source: vectorSource}),
                        map = new ol.Map({
                            interactions: new ol.interaction.defaults({
                                constrainResolution: true, onFocusOnly: true
                            }),
                            target: document.getElementById("map_user"),
                            layers: [
                            new ol.layer.Tile({
                                source: new ol.source.OSM()
                            })
                            ],
                            view: new ol.View({
                                center: ol.proj.fromLonLat([' . $posterdata_extend[$ch_lon] . ', ' . $posterdata_extend[$ch_lat] . ']),
                                zoom: 12
                            })
                        });

                    //Adding a marker on the map
                    map.addLayer(vectorLayer);
    
                    var element = document.getElementById("ol_popup");
                    var popup = new ol.Overlay({
                        element: element,
                        positioning: "bottom-center",
                        stopEvent: false,
                        offset: [0, -20]
                    });
                    map.addOverlay(popup);
    
                    // display popup on click
                    map.on("click", function(evt) {
                    var feature = map.forEachFeatureAtPixel(evt.pixel,
                        function(feature) {
                        return feature;
                        });
                    if (feature) {
                        var coordinates = feature.getGeometry().getCoordinates();
                        popup.setPosition(coordinates);
                        $(element).popover({
                        placement: "top",
                        html: true,
                        content: feature.get("name")
                        });
                        $(element).popover("show");
                    } else {
                        $(element).popover("hide");
                    }
                    });
                    // change mouse cursor when over marker
                    map.on("pointermove", function(e) {
                    if (e.dragging) {
                        $(element).popover("hide");
                        return;
                    }
                    var pixel = map.getEventPixel(e.originalEvent);
                    });
                    // Create the graticule component
                    var graticule = new ol.layer.Graticule();
                    graticule.setMap(map);';
    
                $content .= file_get_contents(module_path('Geoloc/assets/js/ol-dico.js'));
    
                $content .= '
                    const targ = map.getTarget();
                    const lang = targ.lang;
                    for (var i in dic) {
                        if (dic.hasOwnProperty(i)) {
                            $("#map_user "+dic[i].cla).prop("title", dic[i][lang]);
                        }
                    }
                    $("#map_user .ol-zoom-in, #map_user .ol-zoom-out").tooltip({placement: "right", container: "#map_user",});
                    $("#map_user .ol-rotate-reset, #map_user .ol-attribution button[title]").tooltip({placement: "left", container: "#map_user",});
                    });
    
                    //]]>
                    </script>';
    
                $content .= '
                <div class="mt-3">
                    <a href="modules.php?ModPath=geoloc&amp;ModStart=geoloc"><i class="fa fa-globe fa-lg"></i>&nbsp;[french]Carte[/french][english]Map[/english][chinese]&#x5730;&#x56FE;[/chinese][spanish]Mapa[/spanish][german]Karte[/german]</a>';
                
                if ($admin) {
                    $content .= '<a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=geoloc&amp;ModStart=admin/geoloc_set"><i class="fa fa-cogs fa-lg ms-3"></i>&nbsp;[french]Admin[/french][english]Admin[/english][chinese]Admin[/chinese][spanish]Admin[/spanish][german]Admin[/german]</a>';
                }

                $content .= '
                    </div>
                </div>';
    
                $content = aff_langue($content);
    
                $userinfo .= $content;
            }
        }
        //<== geoloc
    
        $userinfo .= '
            </div>
        </div>';
    
        if ($uid != 1) {
            $userinfo .= '
            <br />
            <h4>' . __d('users', 'Journal en ligne de ') . ' ' . $uname . '.</h4>
            <div id="online_user_journal" class="card card-body mb-3">' . meta_lang($user_journal) . '</div>';
        }

        $file = '';
        $handle = opendir(module_path('Comments/Config'));

vd(module_path('Comments/Config'), readdir($handle));

        while (false !== ($file = readdir($handle))) {
            if (!preg_match('#_config\.php$#i', $file)) {
                continue;
            }

            $topic = "#topic#";
    
vd(explode('.php', $file)[0], Config::get('comments.'.explode('.php', $file)[0].'.forum'));

            $forum = Config::get('comments.'.explode('.php', $file)[0].'.forum');
            $url_ret = Config::get('comments.'.explode('.php', $file)[0].'.url_ret');

            // include(module_path('Comments/Config/'.$file));

            // $filelist[$forum] = $url_ret;

            $filelist[$forum] = $url_ret;
        }
    
        closedir($handle);
    
vd($filelist);

//dd();

        $userinfo .= '
        <h4 class="my-3">' . __d('users', 'Les derniers commentaires de') . ' ' . $uname . '.</h4>
        <div id="last_comment_by" class="card card-body mb-3">';
    
        $url = '';
    
        $result = sql_query("SELECT topic_id, forum_id, post_text, post_time FROM posts WHERE forum_id<0 and poster_id='$uid' ORDER BY post_time DESC LIMIT 0,10");
        
        while (list($topic_id, $forum_id, $post_text, $post_time) = sql_fetch_row($result)) {
    

vd($filelist[$forum_id]($topic_id, 0));

            $url = str_replace("#topic#", $topic_id, $filelist[$forum_id]($topic_id, 0));
            $userinfo .= '<p><a href="' . $url . '">' . __d('users', 'Posté : ') . Date::convertdate($post_time) . '</a></p>';
    
            $message = smilie(stripslashes($post_text));
            $message = aff_video_yt($message);
            $message = str_replace('[addsig]', '', $message);
    
            if (stristr($message, "<a href")) {
                $message = preg_replace('#_blank(")#i', '_blank\1 class=\1noir\1', $message);
            }
    
            $userinfo .= nl2br($message) . '<hr />';
        }
    
        $userinfo .= '
        </div>
        <h4 class="my-3">' . __d('users', 'Les derniers articles de') . ' ' . $uname . '.</h4>
        <div id="last_article_by" class="card card-body mb-3">';
    
        $xtab = news_aff("libre", "WHERE informant='$uname' ORDER BY sid DESC LIMIT 10", '', 10);
    
        $story_limit = 0;
    
        while (($story_limit < 10) and ($story_limit < sizeof($xtab))) {
            list($sid, $catid, $aid, $title, $time) = $xtab[$story_limit];
    
            $story_limit++;
    
            $userinfo .= '
            <div class="d-flex">
                <div class="p-2"><a href="article.php?sid=' . $sid . '">' . aff_langue($title) . '</a></div>
                <div class="ms-auto p-2">' . $time . '</div>
            </div>';
        }
    
        $userinfo .= '
        </div>
        <h4 class="my-3">' . __d('users', 'Les dernières contributions de') . ' ' . $uname . '</h4>';
    
        $nbp = 10;
        $content = '';
    
        $result = sql_query("SELECT * FROM posts WHERE forum_id > 0 AND poster_id=$uid ORDER BY post_time DESC LIMIT 0,50");
    
        $j = 1;
    
        while (list($post_id, $post_text) = sql_fetch_row($result) and $j <= $nbp) {
            // Requete detail dernier post
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
    
            if (($forum_type == '5') or ($forum_type == '7')) {
                $ok_affich = false;
                $tab_groupe = valid_group($user);
                $ok_affich = groupe_forum($forum_pass, $tab_groupe);
            } else {
                $ok_affich = true;
            }

            if ($ok_affich) {
                // Nbre de postes par sujet
                $TableRep = sql_query("SELECT * FROM posts WHERE forum_id > 0 AND topic_id = '$topic_id'");
    
                $replys = sql_num_rows($TableRep) - 1;
                $id_lecteur = isset($cookie[0]) ? $cookie[0] : '0';
    
                $sqlR = "SELECT rid FROM forum_read WHERE topicid = '$topic_id' AND uid = '$id_lecteur' AND status != '0'";
    
                if (sql_num_rows(sql_query($sqlR)) == 0) {
                    $image = '<a href="" title="' . __d('users', 'Non lu') . '" data-bs-toggle="tooltip"><i class="far fa-file-alt fa-lg faa-shake animated text-primary "></i></a>';
                } else {
                    $image = '<a title="' . __d('users', 'Lu') . '" data-bs-toggle="tooltip"><i class="far fa-file-alt fa-lg text-primary"></i></a>';
                }

                $content .= '
                <p class="mb-0 list-group-item list-group-item-action flex-column align-items-start" >
                    <span class="d-flex w-100 mt-1">
                    <span>' . $post_time . '</span>
                    <span class="ms-auto">
                    <span class="badge bg-secondary ms-1" title="' . __d('users', 'Réponses') . '" data-bs-toggle="tooltip" data-bs-placement="left">' . $replys . '</span>
                    </span>
                </span>
                <span class="d-flex w-100"><br /><a href="viewtopic.php?topic=' . $topic_id . '&forum=' . $forum_id . '" data-bs-toggle="tooltip" title="' . $forum_name . '">' . $topic_title . '</a><span class="ms-auto mt-1">' . $image . '</span></span>
                </p>';
    
                $j++;
            }
        }
    
        $userinfo .= $content;
        $userinfo .= '<hr />';
    
        if ($posterdata['attachsig'] == 1) {
            $userinfo .= '<p class="n-signature">' . $user_sig . '</p>';
        }

        View::share('userinfo', $userinfo);
    }

}