<?php

namespace Modules\Comments\Controllers\Admin;

use Npds\Config\Config;
use Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class Comments extends AdminController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = "";

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = '';


    /**
     * Call the parent construct
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
        $this->f_titre = __d('', '');

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
        if ($forum >= 0)
        die();
    
        // gestion des params du 'forum' : type, accès, modérateur ...
        $forum_name = 'comments';
        $forum_type = 0;
        $allow_to_post = false;
        
        if (Config::get('npds.anonpost'))
            $forum_access = 0;
        else
            $forum_access = 1;
        
        if ((Config::get('npds.moderate') == 1) and $admin)
            $Mmod = true;
        elseif (Config::get('npds.moderate') == 2) {
            $userX = base64_decode($user);
            $userdata = explode(':', $userX);
        
            $result = sql_query("SELECT level FROM users_status WHERE uid='" . $userdata[0] . "'");
            list($level) = sql_fetch_row($result);
        
            if ($level >= 2)
                $Mmod = true;
        } else
            $Mmod = false;
        // gestion des params du 'forum' : type, accès, modérateur ...
        
        if ($Mmod) {
            switch ($mode) {
                case 'del':
                    $sql = "DELETE FROM posts WHERE forum_id='$forum' AND topic_id = '$topic'";
        
                    if (!$result = sql_query($sql))
                        forumerror('0009');
        
                    // ordre de mise à jour d'un champ externe ?
                    if ($comments_req_raz != '')
                        sql_query("UPDATE " . $comments_req_raz);
        
                    redirect_url("$url_ret");
                    break;
        
                case 'viewip':
                    // include("header.php");
        
                    $sql = "SELECT u.uname, p.poster_ip, p.poster_dns FROM users u, posts p WHERE p.post_id = '$post' AND u.uid = p.poster_id";
                    
                    if (!$r = sql_query($sql))
                        forumerror('0013');
        
                    if (!$m = sql_fetch_assoc($r))
                        forumerror('0014');
        
                    echo '
                    <h2 class="mb-3">' . __d('comments', 'Commentaire') . '</h2>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h3 class="card-title mb-3">' . __d('comments', 'Adresses IP et informations sur les utilisateurs') . '</h3>
                            <div class="row">
                            <div class="col mb-3">
                                <span class="text-muted">' . __d('comments', 'Identifiant :') . '</span> ' . $m['uname'] . '<br />
                                <span class="text-muted">' . __d('comments', 'Adresse IP de l\'utilisateur :') . '</span> ' . $m['poster_ip'] . '<br />
                                <span class="text-muted">' . __d('comments', 'Adresse DNS de l\'utilisateur :') . '</span> ' . $m['poster_dns'] . '<br />
                            </div>';
        
                    echo localiser_ip($iptoshow = $m['poster_ip']);
        
                    echo '
                        </div>
                    </div>';
        
                    include('modules/geoloc/geoloc.conf');
        
                    if ($geo_ip == 1)
                        echo '
                        <div class="card-footer text-end">
                            <a href="modules.php?ModPath=geoloc&amp;ModStart=geoloc&amp;op=allip"><span><i class=" fa fa-globe fa-lg me-1"></i><i class=" fa fa-tv fa-lg me-2"></i></span><span class="d-none d-sm-inline">Carte des IP</span></a>
                        </div>';
        
                    echo '
                    </div>
                    <p><a href="' . rawurldecode($url_ret) . '" class="btn btn-secondary">' . __d('comments', 'Retour en arrière') . '</a></p>';
        
                    // include("footer.php");
                    break;
        
                case 'aff':
                    $sql = "UPDATE posts SET post_aff = '$ordre' WHERE post_id = '$post'";
                    sql_query($sql);
        
                    // ordre de mise à jour d'un champ externe ?
                    if ($ordre) {
                        if ($comments_req_add != '')
                            sql_query("UPDATE " . $comments_req_add);
                    } else {
                        if ($comments_req_del != '')
                            sql_query("UPDATE " . $comments_req_del);
                    }
        
                    redirect_url("$url_ret");
                    break;
            }
        } else {
            // include("header.php");
        
            echo '
                <p class="text-center">' . __d('comments', 'Vous n\'êtes pas identifié comme modérateur de ce forum. Opération interdite.') . '<br /><br />
                <a href="javascript:history.go(-1)" class="btn btn-secondary">' . __d('comments', 'Retour en arrière') . '</a></p>';
        
            // include("footer.php");
        }
    }

}
