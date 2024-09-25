<p class="text-center"><?= __d('stats', 'Pages vues depuis'); ?> <?= config('npds.startdate'); ?> : <span class="fw-semibold"><?= $totalz; ?></span></p>
    <ul class="list-group mb-3" id="site_active">
        <li class="my-1"><?= __d('stats', 'Nb. de membres'); ?> <span class="badge rounded-pill bg-secondary float-end"><?= $membres; ?></span></li>
        <li class="my-1"><?= __d('stats', 'Nb. d\'articles'); ?> <span class="badge rounded-pill bg-secondary float-end"><?= $totala; ?></span></li>
        <li class="my-1"><?= __d('stats', 'Nb. de forums'); ?> <span class="badge rounded-pill bg-secondary float-end"><?= $totalc; ?></span></li>
        <li class="my-1"><?= __d('stats', 'Nb. de sujets'); ?> <span class="badge rounded-pill bg-secondary float-end"><?= $totald; ?></span></li>
        <li class="my-1"><?= __d('stats', 'Nb. de critiques'); ?> <span class="badge rounded-pill bg-secondary float-end"><?= $totalb; ?></span></li>
    </ul>

<?php if ($imgtmptop && $imgtmpstat): ?>
    <p class="text-center">
        <a href="<?= site_url('top'); ?>">
            <img src="<?= $imgtmptop; ?>" alt="<?= __d('stats', 'Top'); ?> <?= $top; ?>" />
        </a>&nbsp;&nbsp;
        <a href="<?= site_url('stats'); ?>">
            <img src="<?= $imgtmpstat; ?>" alt="<?= __d('stats', 'Statistiques'); ?>" />
        </a>
    </p>
<?php else: ?>
    <p class="text-center">
        <a href="<?= site_url('top'); ?>">
            <?= __d('stats', 'Top'); ?> <?= $top; ?>
        </a>&nbsp;&nbsp;
        <a href="<?= site_url('stats'); ?>" >
            <?= __d('stats', 'Statistiques'); ?>
        </a>
    </p>
<?php endif; ?>