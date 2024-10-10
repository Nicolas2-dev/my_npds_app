<div>
    <h2 class="mb-3"><?= __d('users', 'Utilisateur'); ?></h2>
    <div class="card card-body mb-3">
        <h3><?= __d('users', 'Notes'); ?></h3>
        <p>
            <?= __d('users', 'Les préférences de compte fonctionnent sur la base des cookies.'); ?> <?= __d('users', 'Nous ne vendons ni ne communiquons vos informations personnelles à autrui.'); ?> <?= __d('users', 'En tant qu\'utilisateur enregistré vous pouvez'); ?> :
        <ul>
            <li><?= __d('users', 'Poster des commentaires signés'); ?></li>
            <li><?= __d('users', 'Proposer des articles en votre nom'); ?></li>
            <li><?= __d('users', 'Disposer d\'un bloc que vous seul verrez dans le menu (pour spécialistes, nécessite du code html)'); ?></li>
            <li><?= __d('users', 'Télécharger un avatar personnel'); ?></li>
            <li><?= __d('users', 'Sélectionner le nombre de news que vous souhaitez voir apparaître sur la page principale.'); ?></li>
            <li><?= __d('users', 'Personnaliser les commentaires'); ?></li>
            <li><?= __d('users', 'Choisir un look différent pour le site (si plusieurs proposés)'); ?></li>
            <li><?= __d('users', 'Gérer d\'autres options et applications'); ?></li>
        </ul>
        </p>
        <?php if (!config('npds.memberpass')): ?>
            <div class="alert alert-success lead">
                <i class="fa fa-exclamation me-2"></i><?= __d('users', 'Le mot de passe vous sera envoyé à l\'adresse Email indiquée.'); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="card card-body mb-3">
        <?= $user_new_sform; ?>
    </div>
</div>