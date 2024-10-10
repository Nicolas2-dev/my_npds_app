<?php if(!empty($download_data)): ?>
    <?php foreach($download_data as $download): ?>
        <?php if ($form == 'short'): ?>
            <li class="list-group-item list-group-item-action d-flex justify-content-start p-2 flex-wrap">
                <?= $download['lugar']; ?> 
                <a class="ms-2" href="<?= site_url('download.php?op=geninfo&amp;did=' .$download['id'] .'&out_template=1'); ?>" title="<?= $download['ori_dfilename']; ?> <?= $download['download_date']; ?>">
                    <?= $download['filename']; ?>
                </a>
                <span class="badge bg-secondary ms-auto align-self-center"><?= $download['download_date']; ?></span>
            </li>
        <?php else: ?>
            <li class="ms-4 my-1">
                <a href="<?= site_url('download.php?op=mydown&did=' . $download['id']); ?>">
                    <?= $download['filename']; ?>
                </a>
                (<?= __d('download', 'Catégorie'); ?> : <?= $download['category']; ?>)&nbsp;
                <span class="badge bg-secondary float-end align-self-center"><?= $download['counter']; ?></span>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php if($ordre == 'dcounter'): ?> 
        <p class="text-end"><span class="badge rounded-pill text-bg-secondary"><?= __d('download', 'Les plus téléchargés, aucun résultat ...'); ?></span></p>
    <?php else: ?>
        <p class="text-end"><span class="badge rounded-pill text-bg-secondary"><?= __d('download', 'Fichiers les plus récents, aucun résultat ...'); ?></span></p>
    <?php endif; ?>
<?php endif; ?>