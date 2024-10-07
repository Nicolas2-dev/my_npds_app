<p class="text-center">
    <?= __d('users', 'Il y a actuellement'); ?> 
    <span class="badge bg-secondary"><?= $guest_online_num; ?></span> <?= __d('users', 'visiteur(s) et'); ?> 
    <span class="badge bg-secondary"><?= $member_online_num; ?></span> <?= __d('users', 'membre(s) en ligne.'); ?>
    <br />
    <?php if (guard('user')): ?>
        <br />
        <?= __d('users', 'Vous êtes connecté en tant que ',); ?> <strong><?= $username; ?></strong>.<br />
        <?= __d('users', 'Vous avez'); ?> 
        <a href="<?= site_url('viewpmsg'); ?>">
            <span class="badge bg-primary"><?= $count_msg; ?></span>
        </a><?= __d('users', 'message(s) personnel(s).'); ?>
    <?php else: ?>
        <br />
        <?= __d('users', 'Devenez membre privilégié en cliquant'); ?> 
        <a href="<?= site_url('user.php?op=only_newuser'); ?>">
            <?= __d('users', 'ici'); ?>
        </a>
    <?php endif; ?>
</p>