<?php 

if(!isset($pass_does_not_match) or !isset($error_minpass)): ?> 
    <h2><?= __d('users', 'Utilisateur'); ?></h2>
    <hr />
    <h3 class="mb-3"><i class="fa fa-user me-2"></i><?= __d('users', 'Votre fiche d\'inscription'); ?></h3>
    <div class="card">
        <div class="card-body">
            <?= $user_new_confirme_sform; ?>
        </div>
    </div>
    <?= $user_new_hidden_sform; ?>
<?php endif; ?>

<?php if(isset($pass_does_not_match) or isset($error_minpass)): ?>
    <h2><?= __d('users', 'Utilisateur'); ?></h2>
    <div class="alert alert-danger lead">
        <?= $stop_message; ?> 
        <?= $user_new_confirme_hidden_sform; ?> 
    </div>
<?php endif; ?>
