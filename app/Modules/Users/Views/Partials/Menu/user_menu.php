<ul class="nav nav-tabs d-flex flex-wrap">
    <li class="nav-item">
        <a class="nav-link <?= $cl_u; ?>" href="<?= site_url('user?op=dashboard'); ?>" title="<?= __d('users', 'Votre compte'); ?>" data-bs-toggle="tooltip">
            <i class="fas fa-user fa-2x d-xl-none"></i>
            <span class="d-none d-xl-inline"><i class="fas fa-user fa-lg"></i></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $ed_u; ?>" href="<?= site_url('user/edituser?op=edituser'); ?>" title="<?= __d('users', 'Vous'); ?>" data-bs-toggle="tooltip">
            <i class="fas fa-user-edit fa-2x d-xl-none"></i>
            <span class="d-none d-xl-inline">&nbsp;<?= __d('users', 'Vous'); ?></span>
        </a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle tooltipbyclass <?= ($cl_edj or $cl_edh) ? 'active' : '' ?>" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" data-bs-html="true" title="<?= __d('users', 'Editer votre journal'); ?><br /><?= __d('users', 'Editer votre page principale'); ?>">
            <i class="fas fa-edit fa-2x d-xl-none me-2"></i>
            <span class="d-none d-xl-inline"><?= __d('users', 'Editer'); ?></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item <?= $cl_edj; ?>" href="<?= site_url('user/editjournal?op=editjournal'); ?>" title="<?= __d('users', 'Editer votre journal'); ?>" data-bs-toggle="tooltip">
                    <?= __d('users', 'Journal'); ?>
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= $cl_edh; ?>" href="<?= site_url('user/edithome?op=edithome'); ?>" title="<?= __d('users', 'Editer votre page principale'); ?>" data-bs-toggle="tooltip">
                    <?= __d('users', 'Page'); ?>
                </a>
            </li>
        </ul>
    </li>
    <?php if ($userinfo['mns'] and config('upload.config.autorise_upload_p')):  ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle tooltipbyclass" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" title="<?= __d('users', 'Gérer votre miniSite'); ?>"><i class="fas fa-desktop fa-2x d-xl-none me-2"></i><span class="d-none d-xl-inline"><?= __d('users', 'MiniSite'); ?></span></a>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="<?= site_url('minisite?op='. $userinfo['uname']); ?>" target="_blank">
                        <?= __d('users', 'MiniSite'); ?>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="window.open(<?= minisite_win_upload('popup'); ?>)">
                        <?= __d('users', 'Gérer votre miniSite'); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?>
    <li class="nav-item">
        <a class="nav-link <?= $cl_cht; ?>" href="<?= site_url('user/chgtheme?op=chgtheme'); ?>" title="<?= __d('users', 'Changer le thème'); ?>" data-bs-toggle="tooltip">
            <i class="fas fa-paint-brush fa-2x d-xl-none"></i>
            <span class="d-none d-xl-inline">&nbsp;<?= __d('users', 'Thème'); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $cl_rs; ?>" href="<?= site_url('reseaux?op=sociaux'); ?>" title="<?= __d('users', 'Réseaux sociaux'); ?>" data-bs-toggle="tooltip">
            <i class="fas fa-share-alt-square fa-2x d-xl-none"></i>
            <span class="d-none d-xl-inline">&nbsp;<?= __d('users', 'Réseaux sociaux'); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $cl_pm; ?>" href="<?= site_url('messenger/viewpmsg?op=viewpmsgviewpmsg'); ?>" title="<?= __d('users', 'Message personnel'); ?>" data-bs-toggle="tooltip">
            <i class="far fa-envelope fa-2x d-xl-none"></i>
            <span class="d-none d-xl-inline">&nbsp;<?= __d('users', 'Message'); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link " href="<?= site_url('user/logout?op=logout'); ?>" title="<?= __d('users', 'Déconnexion'); ?>" data-bs-toggle="tooltip">
            <i class="fas fa-sign-out-alt fa-2x text-danger d-xl-none"></i>
            <span class="d-none d-xl-inline text-danger">&nbsp;<?= __d('users', 'Déconnexion'); ?></span>
        </a>
    </li>
</ul>
<div class="mt-3"></div>