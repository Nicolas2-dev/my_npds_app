
<div id="adm_men_art" class="adm_workarea">
    <h2><img src="<?= site_url('assets/images/admin/submissions.'.config('npds.admf_ext')); ?>" class="adm_img" title="<?= __d('npds', 'Articles'); ?>" alt="icon_<?= __d('npds', 'Articles'); ?>" />&nbsp;<?= __d('npds', 'Derniers'); ?> <?= config('npds.admart'); ?> <?= __d('npds', 'Articles'); ?></h2>
    

<?php if (isset($nbre_articles)): ?>



<?php endif; ?>



<?php if (isset($dashboard)): ?>
    <?= $dashboard; ?>
<?php endif; ?>

</div>