<?php if(isset($error_not_user)): ?>
    <h2><?= __d('users', 'Utilisateur'); ?></h2>
    <div class="alert alert-danger lead">

    <?= __d('users', 'Désolé, aucune information correspondante pour cet utlilisateur n\'a été trouvée'); ?><br /><br />

    <a class="btn btn-secondary mt-4" href="javascript:history.go(-1)" title="<?= __d('users', 'Retour en arrière'); ?>">
        <?= __d('users', 'Retour en arrière'); ?>
    </a>
</div>
<?php endif; ?>

<?php if(isset($message_pass)): ?>
    <div class="alert alert-success lead text-center">
        <i class="fa fa-exclamation"></i>&nbsp;<?= __d('users', 'Confirmation du code pour'); ?> <?= $uname; ?> <?= __d('users', 'envoyée par courrier.'); ?>
    </div>
<?php endif; ?>

<?php if(isset($error_pass)): ?>
    <h2><?= __d('users', 'Utilisateur'); ?></h2>
    <div class="alert alert-danger lead">

    <i class="fa fa-exclamation"></i>&nbsp;<?= __d('users', 'Mot de passe erroné, refaites un essai.'); ?><br /><br />

    <a class="btn btn-secondary mt-4" href="javascript:history.go(-1)" title="<?= __d('users', 'Retour en arrière'); ?>">
        <?= __d('users', 'Retour en arrière'); ?>
    </a>
</div>
<?php endif; ?>