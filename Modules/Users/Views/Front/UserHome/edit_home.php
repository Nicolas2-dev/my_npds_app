<?= $user_menu; ?>

<?php if (isset($message)): ?>
    <?= $message; ?>
<?php endif; ?>

<h2 class="mb-3"><?= __d('users', 'Editer votre page principale') ; ?></h2>
<form id="changehome" action="<?= site_url('user/savehome'); ?>" method="post">
    <div class="mb-3 row">
        <label class="col-form-label col-sm-7" for="storynum"><?= __d('users', 'Nombre d\'articles sur la page principale') ; ?> (max. 127) :</label>
        <div class="col-sm-5">
            <input class="form-control" type="text" min="0" max="127" id="storynum" name="storynum" maxlength="3" value="<?= $userinfo['storynum'] ; ?>" />
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-sm-10">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="ublockon" name="ublockon" value="1" <?= ($userinfo['ublockon'] == 1 ? 'checked="checked"' : '' ) ; ?> />
                <label class="form-check-label" for="ublockon"><?= __d('users', 'Activer votre menu personnel') ; ?></label>
            </div>
        </div>
    </div>
    <ul>
        <li><?= __d('users', 'Validez cette option et le texte suivant apparaîtra sur votre page d\'accueil') ; ?></li>
        <li><?= __d('users', 'Vous pouvez utiliser du code html, pour créer un lien par exemple') ; ?></li>
    </ul>
    <div class="mb-3 row">
        <div class="col-sm-12">
            <textarea class="form-control" rows="20" name="ublock"><?= $userinfo['ublock'] ; ?></textarea>
        </div>
    </div>
    <div class="mb-3 row">
        <input type="hidden" name="uname" value="<?= $userinfo['uname'] ; ?>" />
        <input type="hidden" name="uid" value="<?= $userinfo['uid'] ; ?>" />
        <input type="hidden" name="op" value="savehome" />
        <div class="col-sm-12">
            <input class="btn btn-primary" type="submit" value="<?= __d('users', 'Sauver les modifications') ; ?>" />
        </div>
    </div>
</form>