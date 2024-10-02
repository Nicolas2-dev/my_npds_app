<?php if ($avpop): ?>
    <img class="btn-outline-primary img-thumbnail img-fluid n-ava-<?= $dim; ?> me-2" src="<?= $avatar_url; ?>" alt="<?= $user['uname']; ?>" loading="lazy" />'
<?php else: ?>
    <div class="dropdown d-inline-block me-4 dropend">
        <a class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
            <img class=" btn-outline-primary img-fluid n-ava-<?= $dim; ?> me-0" src="<?= $avatar_url; ?>" alt="<?= $user['uname']; ?>" />
        </a>
        <ul class="dropdown-menu bg-light">
            <li><span class="dropdown-item-text text-center py-0 my-0">
                <img class="btn-outline-primary img-thumbnail img-fluid n-ava-<?= $ldim; ?> me-2" src="<?= $avatar_url; ?>" alt="<?= $user['uname']; ?>" loading="lazy" /></span>
            </li>
            <li>
                <h6 class="dropdown-header text-center py-0 my-0"><?= $who; ?></h6>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <?php if ($guard): ?>
                <?php if ($user['uid'] != 1 and $user['uid']): ?>
                    <li>
                    <a class="dropdown-item text-center text-md-start" href="<?= site_url('user?op=userinfo&amp;uname='. $user['uname']); ?>" target="_blank" title="<?=  __d('users', 'Profil'); ?>" >
                        <i class="fa fa-lg fa-user align-middle fa-fw"></i>
                        <span class="ms-2 d-none d-md-inline">
                                <?=  __d('users', 'Profil'); ?>
                        </span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-center text-md-start" href="<?= site_url('messenger?op=instant_message&amp;to_userid='. $user['uname']); ?>" title="<?=  __d('users', 'Envoyer un message interne'); ?>" >
                            <i class="far fa-lg fa-envelope align-middle fa-fw"></i>
                            <span class="ms-2 d-none d-md-inline">
                                <?=  __d('users', 'Message'); ?>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($user['femail']): ?>
                    <li>
                    <a class="dropdown-item  text-center text-md-start" href="mailto:<?= anti_spam($user['femail']); ?>" target="_blank" title="<?=  __d('users', 'Email'); ?>" >
                            <i class="fa fa-at fa-lg align-middle fa-fw"></i>
                            <span class="ms-2 d-none d-md-inline">
                                <?=  __d('users', 'Email'); ?>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($geoloc): ?>
                    <li>
                        <a class="dropdown-item text-center text-md-start" href="<?= site_url('geoloc?op=u' . $user['uid']); ?>" title="<?= __d('users', 'Localisation'); ?>" >
                            <i class="fas fa-map-marker-alt fa-lg align-middle fa-fw">&nbsp;</i>
                            <span class="ms-2 d-none d-md-inline">
                                <?= __d('users', 'Localisation'); ?>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($user['url']): ?>
                    <li>
                        <a class="dropdown-item text-center text-md-start" href="<?= $user['url']; ?>" target="_blank" title="<?= __d('users', 'Visiter ce site web'); ?>">
                            <i class="fas fa-external-link-alt fa-lg align-middle fa-fw"></i>
                            <span class="ms-2 d-none d-md-inline">
                                <?= __d('users', 'Visiter ce site web'); ?>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($user['mns']): ?>
                    <li>
                        <a class="dropdown-item text-center text-md-start" href="<?= site_url('minisite.php?op=' . $user['uname']); ?>" target="_blank" title="<?= __d('users', 'Visitez le minisite'); ?>" >
                            <i class="fa fa-lg fa-desktop align-middle fa-fw"></i>
                            <span class="ms-2 d-none d-md-inline">
                                <?= __d('users', 'Visitez le minisite'); ?>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($my_rs): ?>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <div class="mx-auto text-center" style="max-width:170px;"><?= $my_rs; ?></div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>