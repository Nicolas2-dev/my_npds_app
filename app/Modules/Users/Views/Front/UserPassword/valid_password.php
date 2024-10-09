<?php if(!$not_match): ?>
    <p class="lead"><?= __d('users', 'Vous avez perdu votre mot de passe ?'); ?></p>
    <div class="card border rounded p-3">
        <div class="row">
            <div class="col-sm-7">
                <div class="blockquote"><?= __d('users', 'Pour valider votre nouveau mot de passe, merci de le re-saisir.'); ?><br /><?= __d('users', 'Votre mot de passe est : '); ?> <strong><?= $ibid[1]; ?></strong></div>
            </div>
            <div class="col-sm-5">
                <form id="lostpassword" action="<?= site_url('user/updatepasswd'); ?>" method="post">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="passwd"><?= __d('users', 'Mot de passe'); ?></label>
                        <div class="col-sm-12">
                            <input type="password" class="form-control" name="passwd" placeholder="<?= $ibid[1]; ?>" required="required" />
                        </div>
                    </div>
                    <input type="hidden" name="op" value="updatepasswd" />
                    <input type="hidden" name="code" value="<?= $code; ?>" />
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <input class="btn btn-primary" type="submit" value="<?= __d('users', 'Valider'); ?>" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-danger lead text-center"><?= __d('users', 'Erreur'); ?></div>
<?php endif; ?>

<?php if(isset($dad_hash)): ?>  
    <div class="alert alert-danger lead text-center"><?= __d('users', 'Erreur'); ?></div>
<?php endif; ?>