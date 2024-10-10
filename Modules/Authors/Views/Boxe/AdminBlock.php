<?php if(isset($content)): ?>
    <?= $content; ?>
<?php endif; ?>

<div class="d-flex justify-content-start flex-wrap" id="adm_block">
    <?= $bloc_foncts_A; ?>

    <?php if ($radminsuper == 1): ?>
        <a class="btn btn-outline-primary btn-sm me-2 my-1" title="<?= __d('authors', 'Vider la table chatBox'); ?>" data-bs-toggle="tooltip" href="<?= site_url('powerpack.php?op=admin_chatbox_write&amp;chatbox_clearDB=OK'); ?>">
            <img src="<?= site_url('assets/images/admin/chat.png'); ?>" class="adm_img" />&nbsp;<span class="badge bg-danger ms-1">X</span></a>
    <?php endif; ?>

</div>
<div class="mt-3">
    <small class="text-muted"><i class="fas fa-user-cog fa-2x align-middle"></i><?= $aid; ?></small>
</div>

<?php if(isset($alert_npds)): ?>
    <?= $alert_npds; ?>
<?php endif; ?>