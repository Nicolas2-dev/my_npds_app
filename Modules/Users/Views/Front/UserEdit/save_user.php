<?= $user_menu; ?>

<?php if (isset($message)): ?>
    <?= $message; ?>
<?php endif; ?>

<?php if(isset($error_password_identique)): ?>
    <div class="alert alert-success lead">
        <i class="fa fa-exclamation me-2"></i>
        <?= __d('users', 'Les mots de passe sont différents. Ils doivent être identiques.'); ?>
        <br />
    </div>
<?php endif; ?>

<?php if(isset($error_password_identique)): ?>
    <div class="alert alert-success lead">
        <i class="fa fa-exclamation me-2"></i>
            <?= __d('users', 'Désolé, votre mot de passe doit faire au moins'); ?> 
            <strong><?= config('npds.minpass'); ?></strong> <?= __d('users', 'caractères'); ?>
            <br />
    </div>
<?php endif; ?>

<?php if(isset($error_password_identique)): ?>
    <div class="alert alert-success lead">
        <i class="fa fa-exclamation me-2"></i>
            <?= __d('users', 'Désolé, votre mot de passe doit faire au moins'); ?> 
            <strong><?= config('npds.minpass'); ?></strong> <?= __d('users', 'caractères'); ?>
            <br />
    </div>
<?php endif; ?>

<?php if(isset($error_check)): ?>
    <div class="alert alert-success lead">
        <i class="fa fa-exclamation me-2"></i>
            <?= $stop_message; ?> 
    </div>
<?php endif; ?>