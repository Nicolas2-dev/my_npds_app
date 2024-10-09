<form action="<?= site_url('user'); ?>" method="post">
    <div class="mb-3">
        <label for="uname"><?= __d('users', 'Identifiant'); ?></label>
        <input class="form-control" type="text" name="uname" maxlength="25" />
    </div>
    <div class="mb-3">
        <label for="pass"><?= __d('users', 'Mot de passe'); ?></label>
        <input class="form-control" type="password" name="pass" maxlength="20" />
    </div>
    <div class="mb-3">
        <input type="hidden" name="op" value="login" />
        <button class="btn btn-primary" type="submit"><?= __d('users', 'Valider'); ?></button>
    </div>
    <div class="help-block">
        <?= __d('users', 'Vous n\'avez pas encore de compte personnel ? Vous devriez'); ?> <a href="user.php"><?= __d('users', 'en créer un'); ?></a>. <?= __d('users', 'Une fois enregistré vous aurez certains avantages, comme pouvoir modifier l\'aspect du site, ou poster des commentaires signés...'); ?>
    </div>
</form>