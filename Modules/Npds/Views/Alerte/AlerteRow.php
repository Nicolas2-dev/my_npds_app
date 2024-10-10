<?php if (isset($mes_npds)): ?>
    <a class=" btn btn-outline-primary btn-sm me-2 my-1 tooltipbyclass" title="<?= $SAQ['fretour_h']; ?>" data-id="<?= $SAQ['fid']; ?>" data-bs-html="true" <?= $furlscript; ?> >
        <img class="adm_img" src="<?= $adminico; ?>" alt="icon_message" loading="lazy" />
        <span class="badge bg-danger ms-1"><?= $SAQ['fretour']; ?></span>
    </a>
<?php endif; ?>

<?php if (isset($versusModal)): ?>
    <a class=" btn btn-outline-primary btn-sm me-2 my-1 tooltipbyclass" title="<?= $SAQ['fretour_h']; ?>" data-id="<?= $SAQ['fid']; ?>" data-bs-html="true" <?= $furlscript; ?> >
        <img class="adm_img" src="<?= $adminico; ?>" alt="icon_<?= $SAQ['fnom_affich']; ?>" loading="lazy" />
        <span class="badge bg-danger ms-1"><?= $SAQ['fretour']; ?></span>
    </a>
<?php endif; ?>