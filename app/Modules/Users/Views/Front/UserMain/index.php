<div class="d-flex flex-row flex-wrap">
    <div class="me-2 my-auto"><img src="<?= $user_avatar; ?>" class=" rounded-circle center-block n-ava-64 align-middle" /></div>
    <div class="align-self-center">
        <h2><?= __d('users', 'Utilisateur'); ?><span class="d-inline-block text-muted ms-1"><?= $uname; ?></span></h2>
        <?= $useroutils; ?>
        <?php if (isset($reseaux_sociaux)): ?>
            <?= $reseaux_sociaux; ?>
        <?php endif; ?>
        <?php if (isset($personnaliser)): ?>
            <p class="lead"><?= __d('users', 'Si vous souhaitez personnaliser un peu le site, c\'est l\'endroit indiqué. '); ?></p>
        <?php endif; ?>
    </div>
</div>
<?php if (isset($message)): ?>
    <?= $message; ?>
<?php endif; ?>
<hr />
<?php if (isset($user_menu)): ?>
    <?= $user_menu; ?>
<?php endif; ?>
<div class="card card-body">
    <div class="row">
        <?= $div_row; ?>
            <?= $sform_user_info; ?>
        </div>
        <?= $geoloc_carte; ?>
    </div>
</div> 

<?php if (isset($user_journal)): ?>
    <br />
    <h4><?= __d('users', 'Journal en ligne de {0}.', $uname); ?></h4>
    <div id="online_user_journal" class="card card-body mb-3"><?= $user_journal; ?></div>
<?php endif; ?>

<?php if (isset($user_commentaires)): ?>
    <?= $user_commentaires; ?>
<?php endif; ?>

<?php if (isset($user_articles)): ?>
    <?= $user_articles; ?>
<?php endif; ?>

<?php if (isset($user_contributions)): ?>
    <?= $user_contributions; ?>
<?php endif; ?>

<?php if (isset($user_sig)): ?>
    <p class="n-signature"><?= $user_sig; ?></p>
<?php endif; ?>
