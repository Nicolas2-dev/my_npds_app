<?= $user_menu; ?>

<?php if (isset($message)): ?>
    <?= $message; ?>
<?php endif; ?>

<h2 class="mb-3"><?= __d('users', 'Editer votre journal'); ?></h2>
<form action="<?= site_url('user/savejournal'); ?>" method="post" name="adminForm">
    <div class="mb-3 row">
        <div class="col-sm-12">
            <textarea class="tin form-control" rows="25" name="journal"><?= $userinfo['user_journal']; ?></textarea>
            <?= Editeur::aff_editeur('journal', ''); ?>
        </div>
    </div>
    <input type="hidden" name="uname" value="<?= $userinfo['uname']; ?>" />
    <input type="hidden" name="uid" value="<?= $userinfo['uid']; ?>" />
    <input type="hidden" name="op" value="savejournal" />
    <div class="mb-3 row">
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="datetime" name="datetime" value="1" />
                <label class="form-check-label" for="datetime"><?= __d('users', 'Ajouter la date et l\'heure'); ?></label>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-12">
            <input class="btn btn-primary" type="submit" value="<?= __d('users', 'Sauvez votre journal'); ?>" />
        </div>
    </div>
</form>