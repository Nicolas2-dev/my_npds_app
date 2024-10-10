<div><?= __d('ephemerids', 'En ce jour...'); ?></div>
<?php foreach($ephem_data as $ephem): ?>
    <?php if ($ephem['count'] == 1): ?>
        <br />
    <?php endif; ?>

    <b><?= $ephem['yid']; ?></b>
    <br>
    <?= $ephem['content']; ?>
<?php endforeach; ?>
<br>