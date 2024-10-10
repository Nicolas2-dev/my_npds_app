<?php

namespace Modules\Memberlists\Controllers;

use Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class Forum extends FrontController
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
        // Make Member_list Private or not
        if (!AutoReg()) 
            unset($user);

        if ((Config::get('npds.member_list') == 1) and !isset($user) and !isset($admin))
            Header("Location: user.php");

        if (isset($gr_from_ws) and ($gr_from_ws != 0)) {

            settype($gr_from_ws, 'integer');

            $uid_from_ws = "^(";
            $re = mysqli_get_client_info() <= '8.0' 
                ? sql_query("SELECT uid, groupe FROM users_status WHERE groupe REGEXP '[[:<:]]" . $gr_from_ws . "[[:>:]]' ORDER BY uid ASC") 
                : sql_query("SELECT uid, groupe FROM users_status WHERE `groupe` REGEXP \"\\\\b$gr_from_ws\\\\b\" ORDER BY uid ASC;");

            while (list($ws_uid) = sql_fetch_row($re)) {
                $uid_from_ws .= $ws_uid . "|";
            }

            $uid_from_ws = substr($uid_from_ws, 0, -1) . ")\$";
        } else {
            $uid_from_ws = '';
            $gr_from_ws = 0;
        }

        $pagesize = Config::get('npds.show_user');

        if (!isset($letter) or ($letter == '')) 
            $letter = __d('memberlists', 'Tous');

        $letter = removeHack(stripslashes(htmlspecialchars($letter, ENT_QUOTES, cur_charset)));

        if (!isset($sortby)) 
            $sortby = 'uid DESC';

        $sortby = removeHack($sortby);

        if (!isset($page)) 
            $page = 1;

        if (isset($list)) {
            $tempo = unique(explode(',', $list));
            $list = urlencode(implode(',', $tempo));
        }

        $result = sql_query("SELECT u.uname, u.user_avatar FROM users AS u LEFT JOIN users_status AS us ON u.uid = us.uid where us.open='1' ORDER BY u.uid DESC LIMIT 0,1");
        list($lastuser, $lastava) = sql_fetch_row($result);

        echo '
        <h2><img src="assets/images/admin/users.png" alt="' . __d('memberlists', 'Liste des membres') . '" />' . __d('memberlists', 'Liste des membres');

        if (isset($uid_from_ws) and ($uid_from_ws != ''))
            echo '<span class="text-muted"> ' . __d('memberlists', 'pour le groupe') . ' #' . $gr_from_ws . '</span>';

        echo '</h2>
        <hr />';

        if (!isset($gr_from_ws)) {
            echo '
            <div class="row">';

            if ($ibid_avatar = avatar($lastava))
                echo '
                <div class="col-md-1">
                    <img src="' . $ibid_avatar . '" class="n-ava img-thumbnail" alt="avatar" loading="lazy" />
                </div>';

            echo '
                <div class="col">
                ' . __d('memberlists', 'Bienvenue au dernier membre affilié : ') . ' <br /><h4><a href="user.php?op=userinfo&amp;uname=' . $lastuser . '">' . $lastuser . '</a></h4>
                </div>
            </div>
            <hr />';
        }

        echo '
            <div class="card card-body mb-3">
                <p>';

        alpha();
        echo '</p>';

        SortLinks($letter);

        echo '
            </div>';

        if ($page == '') 
            $page = 1;

        $min = $pagesize * ($page - 1);
        $max = $pagesize;
        $ws_req = '';

        if (isset($uid_from_ws) and ($uid_from_ws != '')) $ws_req = 'WHERE uid REGEXP \'' . $uid_from_ws . '\' ';

        $count = "SELECT COUNT(uid) AS total FROM users ";
        $select = "SELECT uid, name, uname, femail, url, user_regdate, user_from, email, is_visible, user_viewemail, user_avatar, mns, user_lastvisit FROM users ";

        if (($letter != __d('memberlists', 'Autres')) and ($letter != __d('memberlists', 'Tous'))) {
            if ($admin and (preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $letter)))
                $where = "WHERE uname LIKE '" . $letter . "%' OR email LIKE '%" . strtolower($letter) . "%'" . str_replace('WHERE', ' AND', $ws_req);
            else
                $where = "WHERE uname LIKE '" . $letter . "%'" . str_replace('WHERE', ' AND', $ws_req);
        } else if (($letter == __d('memberlists', 'Autres')) and ($letter != __d('memberlists', 'Tous')))
            $where = "WHERE uname REGEXP \"^\[1-9]\" " . str_replace('WHERE', ' AND', $ws_req);

        else
            $where = $ws_req;

        if (Config::get('npds.member_invisible')) {
            if ($admin)
                $and = '';
            else
                $and = $where ? ' AND is_visible=1 ' : ' WHERE is_visible=1 ';
        } else
            $and = '';

        $sort = " ORDER BY $sortby";
        $limit = ' LIMIT ' . $min . ', ' . $max;

        $count_result = sql_query($count . $where);
        list($num_rows_per_order) = sql_fetch_row($count_result);

        $result = sql_query($select . $where . $and . $sort . $limit);

        if ($letter != 'front') {
            echo '
            <table class="table table-no-bordered table-sm " data-toggle="table" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa" data-show-columns="true">
                <thead>
                    <tr>
                        <th class="n-t-col-xs-1 align-middle text-muted" data-halign="center" data-align="center"><i class="fa fa-user-o fa-lg"></i></th>
                        <th class="align-middle" data-sortable="true">' . __d('memberlists', 'Identifiant') . '</th>
                        <th class="align-middle" data-sortable="true">' . __d('memberlists', 'Identité') . '</th>
                        ';

            if ($sortby != 'user_from ASC')
                echo '
                    <th class="align-middle " data-sortable="true" data-halign="center">' . __d('memberlists', 'Email') . '</th>';
            else
                echo '
                    <th class="align-middle " data-sortable="true" data-halign="center" >' . __d('memberlists', 'Localisation') . '</th>';

            echo '
                    <th class="align-middle " data-halign="center">' . __d('memberlists', 'Url') . '</th>';

            $cols = 6;

            if ($admin) {
                $cols = 7;
                echo '<th class="n-t-col-xs-2 align-middle " data-halign="center" data-align="right">' . __d('memberlists', 'Fonctions') . '</th>';
            }

            echo '
                </tr>
            </thead>
            <tbody>';

            $num_users = sql_num_rows($result);

            if ($num_rows_per_order > 0) {

                global $user;

                while ($temp_user = sql_fetch_assoc($result)) {

                    $socialnetworks = array();
                    $posterdata_extend = array();
                    $res_id = array();
                    $my_rs = '';

                    if (!Config::get('npds.short_user')) {
                        $posterdata_extend = get_userdata_extend_from_id($temp_user['uid']);

                        include('modules/reseaux-sociaux/reseaux-sociaux.conf.php');
                        include('modules/geoloc/geoloc.conf');

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
                                        $my_rs .= '<a class="me-2" href="';

                                        if ($v1[2] == 'skype') 
                                            $my_rs .= $v1[1] . $y1[1] . '?chat';
                                        else 
                                            $my_rs .= $v1[1] . $y1[1];

                                        $my_rs .= '" target="_blank"><i class="fab fa-' . $v1[2] . ' fa-lg fa-fw mb-2"></i></a> ';
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    settype($ch_lat, 'string');

                    $useroutils = '';

                    if ($temp_user['uid'] != 1 and $temp_user['uid'] != '')
                        $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="user.php?op=userinfo&amp;uname=' . $temp_user['uname'] . '" target="_blank" title="' . __d('memberlists', 'Profil') . '" ><i class="fa fa-user fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">' . __d('memberlists', 'Profil') . '</span></a>';
                    
                    if ($temp_user['uid'] != 1 and $temp_user['uid'] != '')
                        $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="powerpack.php?op=instant_message&amp;to_userid=' . urlencode($temp_user['uname']) . '" title="' . __d('memberlists', 'Envoyer un message interne') . '" ><i class="far fa-envelope fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">' . __d('memberlists', 'Message') . '</span></a>';
                    
                    if ($temp_user['femail'] != '')
                        $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="mailto:' . anti_spam($temp_user['femail'], 1) . '" target="_blank" title="' . __d('memberlists', 'Email') . '" ><i class="fa fa-at fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">' . __d('memberlists', 'Email') . '</span></a>';
                    
                    if ($temp_user['url'] != '')
                        $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="' . $temp_user['url'] . '" target="_blank" title="' . __d('memberlists', 'Visiter ce site web') . '"><i class="fas fa-external-link-alt fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">' . __d('memberlists', 'Visiter ce site web') . '</span></a>';
                    
                    if ($temp_user['mns'])
                        $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="minisite.php?op=' . $temp_user['uname'] . '" target="_blank" target="_blank" title="' . __d('memberlists', 'Visitez le minisite') . '" ><i class="fa fa-desktop fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">' . __d('memberlists', 'Visitez le minisite') . '</span></a>';
                    
                    if ($user)
                        if ($temp_user['uid'] != 1)
                            $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="memberslist.php?letter=' . $letter . '&amp;sortby=' . $sortby . '&amp;list=' . $list . urlencode($temp_user['uname']) . ',&amp;page=' . $page . '&amp;gr_from_ws=' . $gr_from_ws . '" title="' . __d('memberlists', 'Ajouter à la liste de diffusion') . '" ><i class="fa fa-plus-circle fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">' . __d('memberlists', 'Liste de diffusion') . '</span></a>';

                    if ($temp_user['uid'] != 1 and array_key_exists($ch_lat, $posterdata_extend)) {
                        if ($posterdata_extend[$ch_lat] != '')
                            $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="modules.php?ModPath=geoloc&amp;ModStart=geoloc&op=u' . $temp_user['uid'] . '" title="' . __d('memberlists', 'Localisation') . '" ><i class="fas fa-map-marker-alt fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">' . __d('memberlists', 'Localisation') . '</span></a>';
                    }

                    $op_result = sql_query("SELECT open FROM users_status WHERE uid='" . $temp_user['uid'] . "'");
                    list($open_user) = sql_fetch_row($op_result);

                    $clconnect = '';

                    if (($open_user == 1 and $user) || ($admin)) {
                        if ($open_user == 0) {
                            $clconnect = 'danger';
                            echo '
                            <tr class="table-danger" title="' . __d('memberlists', 'Connexion non autorisée') . '" data-bs-toggle="tooltip">
                                <td title="' . __d('memberlists', 'Connexion non autorisée') . '" data-bs-toggle="tooltip">';
                        } else {
                            $clconnect = 'primary';
                            echo '
                            <tr>
                                <td>';
                        }

                        if ($ibid_avatar = avatar($temp_user['user_avatar']))
                            echo '<a tabindex="0" data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="focus" data-bs-html="true" data-bs-title="' . $temp_user['uname'] . '" data-bs-content=\'<div class="list-group mb-3 text-center">' . $useroutils . '</div><div class="mx-auto text-center" style="max-width:170px;">' . $my_rs . '</div>\'></i><img data-bs-html="true" class=" btn-outline-' . $clconnect . ' img-thumbnail img-fluid n-ava-40" src="' . $ibid_avatar . '" alt="' . $temp_user['uname'] . '" loading="lazy" /></a>
                            </td>
                            <td><a href="user.php?op=userinfo&amp;uname=' . $temp_user['uname'] . '" title="' . __d('memberlists', 'Inscription') . ' : ' . date(__d('memberlists', 'dateinternal'), (int)$temp_user['user_regdate']);
                        
                        if ($admin and $temp_user['user_lastvisit'] != '')
                            echo '<br />' . __d('memberlists', 'Connexion') . ' : ' . date(__d('memberlists', 'dateinternal'), (int)$temp_user['user_lastvisit']);

                        echo '"  data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right">' . $temp_user['uname'] . '</a>
                        </td>
                        <td>' . $temp_user['name'] . '</td>
                        ';

                        if ($sortby != 'user_from ASC') {
                            if ($admin) {
                                if (isbadmailuser($temp_user['uid']) === true)
                                    echo '<td class="table-danger"><small>' . $temp_user['email'] . '</small></td>';
                                else
                                    echo '<td><small>' . preg_anti_spam($temp_user['email']) . '</small></td>';
                            } else {
                                if ($temp_user['user_viewemail']) {
                                    echo '<td><small>' . preg_anti_spam($temp_user['email']) . '</small></td>';
                                } else {
                                    echo '<td><small>' . substr($temp_user['femail'], 0, strpos($temp_user['femail'], "@")) . '</small></td>';
                                }
                            }
                        } else
                            echo '<td><small>' . $temp_user['user_from'] . '</small></td>';

                        echo '<td><small>';

                        if ($temp_user['url'] != '')
                            echo '<a href="' . $temp_user['url'] . '" target="_blank">' . $temp_user['url'] . '</a>';

                        echo '</small></td>'
                        ;
                        if ($admin) {
                            echo '
                            <td>
                            <a class="me-3" href="admin.php?chng_uid=' . $temp_user['uid'] . '&amp;op=modifyUser" ><i class="fa fa-edit fa-lg" title="' . __d('memberlists', 'Editer') . '" data-bs-toggle="tooltip"></i></a> 
                            <a href="admin.php?op=delUser&amp;chng_uid=' . $temp_user['uid'] . '" ><i class="fas fa-trash fa-lg text-danger" title="' . __d('memberlists', 'Effacer') . '" data-bs-toggle="tooltip"></i></a>';
                            
                            if (!$temp_user['is_visible'])
                                echo '<img src="assets/images/admin/ws/user_invisible.gif" alt="' . __d('memberlists', 'Membre invisible') . '" title="' . __d('memberlists', 'Membre invisible') . '" />';
                            else
                                echo '<img src="assets/images/admin/ws/blank.gif" alt="" />';

                            echo '</td>';
                        }

                        echo '</tr>';
                    }
                }
            } else {
                echo '
                <tr>
                    <td colspan="' . $cols . '"><strong>' . __d('memberlists', 'Aucun membre trouvé pour') . ' ' . $letter . '</strong></td>
                </tr>';
            }

            echo '
            </tbody>
            </table>';

            if ($user) {
                echo '
                <div class="mt-3 card card-block-small">
                <p class=""><strong>' . __d('memberlists', 'Liste de diffusion') . ' :</strong>&nbsp;';

                if ($list) {
                    echo urldecode($list);
                    echo '
                    <span class="float-end">
                    <a href="replypmsg.php?send=' . substr($list, 0, strlen($list) - 3) . '" ><i class="far fa-envelope fa-lg" title="' . __d('memberlists', 'Ecrire à la liste') . '" data-bs-toggle="tooltip" ></i></a>
                    <a class="ms-3" href="memberslist.php?letter=' . $letter . '&amp;sortby=' . $sortby . '&amp;page=' . $page . '&amp;gr_from_ws=' . $gr_from_ws . '" ><i class="fas fa-trash fa-lg text-danger" title="' . __d('memberlists', 'Raz de la liste') . '" data-bs-toggle="tooltip" ></i></a>
                    </span>';
                }

                echo '</p>
                </div>';
            }

            settype($total_pages, 'integer');

            if ($num_rows_per_order > $pagesize) {
                echo '
                <div class="mt-3 lead align-middle">
                    <span class="badge bg-secondary lead">' . $num_rows_per_order . '</span> ' . __d('memberlists', 'Utilisateurs trouvés pour') . ' <strong>' . $letter . '</strong> (' . $total_pages . ' ' . __d('memberlists', 'pages') . ', ' . $num_users . ' ' . __d('memberlists', 'Utilisateurs montrés') . ').
                </div>
                <ul class="pagination pagination-sm my-3 flex-wrap">';

                $total_pages = ceil($num_rows_per_order / $pagesize);
                $nbPages = ceil($num_rows_per_order / $pagesize);
                $current = 0;

                if ($page >= 1)
                    $current = $page;
                else if ($page < 1)
                    $current = 1;
                else
                    $current = $nbPages;
                
                echo paginate_single('memberslist.php?letter=' . $letter . '&amp;sortby=' . $sortby . '&amp;list=' . $list . '&amp;gr_from_ws=' . $gr_from_ws . '&amp;page=', '', $nbPages, $current, $adj = 3, '', '');
            } else
                echo '<div class="mt-3 lead align-middle"><span class="badge bg-secondary lead">' . $num_rows_per_order . '</span> ' . __d('memberlists', 'Utilisateurs trouvés') . '</div>';
        }
    }

}
