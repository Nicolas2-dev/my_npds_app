<h2 class="mb-3"><?= __d('users', 'Utilisateur'); ?></h2>
<div class="card card-body">
    <div class="alert alert-danger lead"><i class="fa fa-exclamation me-2"></i><?= __d('users', 'Vous avez perdu votre mot de passe ?'); ?></div>
    <div class="alert alert-success"><i class="fa fa-exclamation me-2"></i><?= __d('users', 'Pas de problème. Saisissez votre identifiant et le nouveau mot de passe que vous souhaitez utiliser puis cliquez sur envoyer pour recevoir un Email de confirmation.'); ?></div>
    <form id="forgetpassword" action="<?= site_url('user/forget/password'); ?>" method="post">
        <div class="row g-2">
            <div class="col-sm-6 ">
                <div class="mb-3 form-floating">
                    <input type="text" class="form-control" name="uname" id="inputuser" placeholder="<?= __d('users', 'Identifiant'); ?>" required="required" />
                    <label for="inputuser"><?= __d('users', 'Identifiant'); ?></label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-3 form-floating">
                    <input type="password" class="form-control" name="code" id="inputpassuser" placeholder="<?= __d('users', 'Mot de passe'); ?>" required="required" />
                    <label for="inputpassuser"><?= __d('users', 'Mot de passe'); ?></label>
                </div>
                <div class="progress" style="height: 0.4rem;">
                    <div id="passwordMeter_cont" class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                </div>
            </div>
        </div>
        <input type="hidden" name="op" value="mailpasswd" />
        <input class="btn btn-primary btn-lg my-3" type="submit" value="<?= __d('users', 'Envoyer'); ?>" />
    </form>
</div>