<?php if(isset($password_update)): ?>  
    <div class="alert alert-success lead text-center">
        <a class="alert-link" href="<?= site_url('user'); ?>">
            <i class="fa fa-exclamation me-2"></i>
            <?= __d('users', 'Mot de passe mis à jour. Merci de vous re-connecter'); ?>
            <i class="fas fa-sign-in-alt fa-lg ms-2"></i>
        </a>
    </div> 
<?php endif; ?>

<?php if(isset($not_match)): ?>  
    <div class="alert alert-danger lead text-center">
        <?= __d('users', 'Erreur'); ?> : <?= __d('users', 'Les mots de passe sont différents. Ils doivent être identiques.'); ?>
    </div>
<?php endif; ?>

<?php if(isset($nok_time)): ?>  
    <div class="alert alert-danger lead text-center">
        <?= __d('users', 'Erreur'); ?> : <?= __d('users', 'Votre url de confirmation est expirée'); ?> > 24 h
    </div>
<?php endif; ?>    

<?php if(isset($mail_not_match)): ?>  
    <div class="alert alert-danger lead text-center">
        <?= __d('users', 'Erreur : Email invalide'); ?>
    </div>
<?php endif; ?>

<?php if(isset($mail_bad_user)): ?>  
    <div class="alert alert-danger lead text-center">
        <?= __d('users', 'Erreur'); ?>
    </div>
<?php endif; ?>