<form action="'. site_url('pollBooth') .'" method="post">
    <input type="hidden" name="pollID" value="<?= $pollID; ?>" />
    <input type="hidden" name="forwarder" value="<?= site_url($url); ?>" />
    <legend><?= $poll_title; ?></legend>

<?php if (!$pollClose): ?>
    <div class="mb-3">
        <?php foreach($poll_data as $pollbooth): ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="voteID <?= $pollbooth['j']; ?>" name="voteID" value=" <?= $pollbooth['voteID']; ?>" />
                <label class="form-check-label d-block" for="voteID <?= $pollbooth['j']; ?>" > <?= $pollbooth['optionText']; ?></label>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?php foreach($poll_data as $pollbooth): ?>
        &nbsp;
        <?= $pollbooth['optionText']; ?>
        <br />
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!$pollClose): ?>
    <div class="mb-3">
        <button class="btn btn-outline-primary btn-sm btn-block" type="submit" value="<?= __d('pollbooth', 'Voter'); ?>" title="<?= __d('pollbooth', 'Voter'); ?>" >
            <i class="fa fa-check fa-lg"></i> <?= __d('pollbooth', 'Voter'); ?>
        </button>
    </div>
<?php endif; ?>
</form>
<a href="<?= site_url('pollBooth.php?op=results&amp;pollID=' . $pollID); ?>" title="<?= __d('pollbooth', 'Résultats'); ?>">
    <?= __d('pollbooth', 'Résultats'); ?>
</a>
&nbsp;&nbsp;
<a href="<?= site_url('pollBooth'); ?>">
    <?= __d('pollbooth', 'Anciens sondages'); ?>
</a>
<ul class="list-group mt-3">
    <li class="list-group-item"><?= __d('pollbooth', 'Votes : '); ?> <span class="badge rounded-pill bg-secondary float-end"><?= $sum; ?></span></li>
    <?php if (!is_null($num_count)): ?>
        <li class="list-group-item">
            <?= __d('pollbooth', 'Commentaire(s) : '); ?> <span class="badge rounded-pill bg-secondary float-end"><?= $num_count; ?></span>
        </li>
    <?php endif; ?>
</ul>