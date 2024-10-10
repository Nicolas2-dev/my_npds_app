<?php if(isset($utilisateur_info)): ?>
    <h2><?= __d('users', 'Utilisateur'); ?></h2>
    <hr />
    <h2><i class="fa fa-user me-2"></i><?= __d('users', 'Inscription'); ?></h2>
    <p class="lead"><?= __d('users', 'Votre mot de passe est : '); ?><strong><?= $makepass; ?></strong></p>
    <p class="lead"><?= __d('users', 'Vous pourrez le modifier après vous être connecté sur'); ?> : <br />
        <a href="<?= site_url('user/login/auto?op=login&uname='. $uname .'&pass='. $makepass_url_encode); ?>">
            <i class="fas fa-sign-in-alt fa-lg me-2"></i><strong><?= config('npds.sitename'); ?></strong>
        </a>
    </p>
<?php elseif(isset($inscription_mail)): ?>
    <h2><?= __d('users', 'Utilisateur'); ?></h2>
    <h2><i class="fa fa-user me-2"></i><?= __d('users', 'Inscription'); ?></h2>
    <div class="alert alert-success lead">
        <i class="fa fa-exclamation me-2"></i>
        <?= __d('users', 'Vous êtes maintenant enregistré. Vous allez recevoir un code de confirmation dans votre boîte à lettres électronique.'); ?>
    </div>
<?php endif; ?>

<?php if(isset($stop_message)): ?>
    <h2><?= __d('users', 'Utilisateur'); ?></h2>
    <div class="alert alert-danger lead">
        <?= $stop_message; ?>  
    </div>
<?php endif; ?>