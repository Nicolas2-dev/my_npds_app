<?php

use Npds\Config\Config;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Theme\Support\Facades\Theme;

global $cookie;

$ava = '';
$cha = '';
$bal = '';
$menuser = '';

if ($user) {
    $userdata   = User::get_userdata_from_id($cookie[0]);
    $username   = $cookie[1];
    $ibix       = explode('+', urldecode($cookie[9]));
    $skinname   = array_key_exists(1, $ibix) ? $ibix[1] : "default";
} else {
    $skinname   = Config::get('npds.Default_Skin');
    $username   = '';
}

switch ($skinname) {
    case 'cyborg':
    case 'solar':
    case 'superhero':
        $headerclasses = 'navbar navbar-expand-md navbar-dark bg-dark fixed-top';
        break;
    case 'lumen':
    case 'journal':
    case 'materia':
        $headerclasses = 'navbar navbar-expand-md navbar-dark bg-primary fixed-top';
        break;
    case 'simplex':
    case 'litera':
    case 'spacelab':
        $headerclasses = 'navbar navbar-expand-md navbar-light bg-light fixed-top';
        break;
    default:
        $headerclasses = 'navbar navbar-expand-md navbar-dark bg-primary fixed-top'; // empty & cerulean cosmo darkly flatly lux minty pulse sandstone slate united yeti default
        break;
}

if (autorisation(-1)) {
    $btn_con = '<a class="dropdown-item" href="'. site_url('user') .'">
            <i class="fas fa-sign-in-alt fa-lg me-2 align-middle"></i>
            ' . __d('npdsboost_sk', 'Connexion') . '
        </a>';

    $ava = '<a class="dropdown-item" href="'. site_url('user') .'">
            <i class="fa fa-user fa-3x text-muted"></i>
        </a>';

} elseif (autorisation(1)) {
    list($nbmes) = sql_fetch_row(sql_query("SELECT COUNT(*) FROM priv_msgs WHERE from_userid='" . $cookie[0] . "' AND read_msg='0'"));
    
    $cl = $nbmes > 0 ? ' faa-shake animated ' : '';
    
    $menuser .= '
        <li>
            <a class="dropdown-item" href="'. site_url('user/edituser?op=edituser') .'" title="' . __d('npdsboost_sk', 'Vous') . '"  >
                <i class="fa fa-user-edit fa-lg me-2"></i>' . __d('npdsboost_sk', 'Vous') . '
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="'. site_url('user/editjournal?op=editjournal') .'" title="' . __d('npdsboost_sk', 'Editer votre journal') . '" >
                <i class="fa fa-edit fa-lg me-2"></i>' . __d('npdsboost_sk', 'Journal') . '
            </a>
        </li>';
    
    /*
    include_once ("modules/upload/config/upload.conf.php");

    if (($userdata['mns']) and ($autorise_upload_p)) {
        include_once ("modules/blog/upload_minisite.php");
        
        $Pop = win_upload("popup");
        
        $menuser .= '<li>
            <a class="dropdown-item" href="javascript:void(0);" onclick="window.open('.$Pop.')" title="'. __d('npdsboost_sk', 'Gérer votre miniSite') .'">
                <i class="fa fa-desktop fa-lg me-2"></i>'. __d('npdsboost_sk', 'MiniSite') .'
                </a>
            </li>';
    }
    */
    $menuser .= '<li>
            <a class="dropdown-item " href="'. site_url('user/edithome?op=edithome') .'" title="' . __d('npdsboost_sk', 'Editer votre page principale') . '" >
                <i class="fa fa-edit fa-lg me-2 "></i>' . __d('npdsboost_sk', 'Page') . '
            </a>
        </li>
        <li>
            <a class="dropdown-item " href="'. site_url('user/chgtheme?op=chgtheme') .'" title="' . __d('npdsboost_sk', 'Changer le thème') . '" >
                <i class="fa fa-paint-brush fa-lg me-2"></i>' . __d('npdsboost_sk', 'Thème') . '
            </a>
        </li>
        <li>
            <a class="dropdown-item " href="'. site_url('reseaux?op=sociaux') .'" title="' . __d('npdsboost_sk', 'Réseaux sociaux') . '" >
                <i class="fa fa-share-alt-square fa-lg me-2"></i>' . __d('npdsboost_sk', 'Réseaux sociaux') . '
            </a>
        </li>
        <li>
            <a class="dropdown-item " href="'. site_url('messenger/viewpmsg?op=viewpmsg') .'" title="' . __d('npdsboost_sk', 'Message personnel') . '" >
                <i class="fa fa-envelope fa-lg me-2 ' . $cl . '"></i>' . __d('npdsboost_sk', 'Message') . '
            </a>
        </li>';
    
    settype($cookie[0], 'integer');

    list($user_avatar) = sql_fetch_row(sql_query("SELECT user_avatar FROM users WHERE uname='" . $username . "'"));

    if (!$user_avatar) {
        $imgtmp = 'assets/images/forum/avatar/blank.gif';

    } else if (stristr($user_avatar, "users_private")) {
        $imgtmp = $user_avatar;

    } else {
        if ($ibid = Theme::theme_image('forum/avatar/' . $user_avatar)) {
            $imgtmp = $ibid;
        } else {
            $imgtmp = 'assets/images/forum/avatar/' . $user_avatar;
        }

        if (!file_exists($imgtmp)) {
            $imgtmp = 'assets/images/forum/avatar/blank.gif';
        }
    }

    if ($nbmes > 0) {
        $bal = '<li class="nav-item">
            <a class="nav-link" href="viewpmsg.php">
                <i class="fa fa-envelope fs-4 faa-shake animated" title="' . __d('npdsboost_sk', 'Message personnel') . ' <span class=\'badge rounded-pill bg-danger ms-2\'>' . $nbmes . '</span>" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right"></i>
            </a>
        </li>';
    }

    $ava = '<a class="dropdown-item" href="'. site_url('user?op=dashboard') .'" >
        <img src="' . site_url($imgtmp) . '" class="n-ava-64" alt="avatar" title="' . __d('npdsboost_sk', 'Votre compte') . '" data-bs-toggle="tooltip" data-bs-placement="right" />
    </a>
    <li class="dropdown-divider"></li>';

    $btn_con = '<a class="dropdown-item" href="'. site_url('user/logout?op=logout') .'">
        <i class="fas fa-sign-out-alt fa-lg text-danger me-2"></i>' . __d('npdsboost_sk', 'Déconnexion') . '
    </a>';
}
?>

<nav id="uppernavbar" class="<?php echo $headerclasses; ?>">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><span data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right" title="&lt;i class='fa fa-home fa-lg' &gt;&lt;/i&gt;">Npds^ 16</span></a>
        <button href="#" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#barnav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="barnav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown"><a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">News</a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="dropdown-item" href="index.php?op=index.php"><?php echo  __d('npdsboost_sk', 'Les articles'); ?></a></li>
                        <li><a class="dropdown-item" href="search.php"><?php echo  __d('npdsboost_sk', 'Les archives'); ?></a></li>
                        <li><a class="dropdown-item" href="submit.php"><?php echo  __d('npdsboost_sk', 'Soumettre un article'); ?></a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="forum.php"><?php echo  __d('npdsboost_sk', 'Forums'); ?></a></li>
                <li class="nav-item"><a class="nav-link" href="download.php"><?php echo  __d('npdsboost_sk', 'Téléchargements'); ?></a></li>
                <li class="nav-item"><a class="nav-link" href="modules.php?ModPath=links&amp;ModStart=links"><?php echo  __d('npdsboost_sk', 'Liens'); ?></a></li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-user fa-lg"></i>&nbsp;<?php echo $username; ?></a>
                    <ul class="dropdown-menu">
                        <li><?php echo $ava; ?></li>
                        <?php echo $menuser; ?>
                        <li class="dropdown-divider"></li>
                        <li><?php echo $btn_con; ?></li>
                    </ul>
                </li>
                <?php echo $bal; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="page-header">
    <div class="row">
        <div class="col-sm-2"><img class="img-fluid" src="<?php echo site_url('themes/frontend/!theme!/assets/images/header.png'); ?>" alt="image_entete" /></div>
        <div id="logo_header" class="col-sm-6">
            <h1 class="my-4">App<br /><small class="text-muted">Responsive</small></h1>
        </div>
        <div id="ban" class="col-sm-4 text-end">!banner!</div>
    </div>
    <div class="row">
        <div id="slogan" class="col-sm-8 text-muted slogan"><strong>!slogan!</strong></div>
        <div id="online" class="col-sm-4 text-muted text-end">!nb_online!</div>
    </div>
</div>
<script type="text/javascript">
    //<![CDATA[
    $(document).ready(function() {
        var chat_pour = ['chat_tous', 'chat_membres', 'chat_anonyme', 'chat_admin'];
        chat_pour.forEach(function(ele) {
            if ($('#' + ele + '_encours').length) {
                var clon = $('#' + ele + '_encours').clone().attr('id', ele + '_ico');
                $(".navbar-nav").append(clon);
                $('#' + ele + '_ico').wrapAll('<li class="nav-item" />');
            }
        })
    })
    //]]>
</script>