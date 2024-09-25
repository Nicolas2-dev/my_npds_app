<h2><?= __d('users', 'Utilisateur'); ?></h2>

<?php if (!empty($stop) && ($stop == 99)): ?>
    <p class="alert alert-danger">
        <i class="fa fa-exclamation me-2"></i><?= __d('users', 'Vous n\'êtes pas encore autorisé à vous connecter.'); ?>
    </p>
<?php elseif (!empty($stop) && ($stop)): ?>
    <p class="alert alert-danger">
        <i class="fa fa-exclamation me-2"></i><?= __d('users', 'Identifiant incorrect !'); ?>
        <br /><?= __d('users', 'ou'); ?>
        <br /><i class="fa fa-exclamation me-2"></i><?= __d('users', 'Mot de passe erroné, refaites un essai.'); ?>
    </p>
<?php endif; ?>

<div class="card card-body mb-3">
    <h3><a href="user.php?op=only_newuser" role="button" title="<?= __d('users', 'Nouveau membre'); ?>"><i class="fa fa-user-plus"></i>&nbsp;<?= __d('users', 'Nouveau membre'); ?></a></h3>
</div>
<div class="card card-body">
    <h3 class="mb-4"><i class="fas fa-sign-in-alt fa-lg me-2 align-middle"></i><?= __d('users', 'Connexion'); ?></h3>
    <form action="<?= site_url('user/submit'); ?>" method="post" name="userlogin">
        <div class="row g-2">
            <div class="col-sm-6">
                <div class="mb-3 form-floating">
                    <input type="text" class="form-control" name="uname" id="inputuser" placeholder="<?= __d('users', 'Identifiant'); ?>" required="required" autocomplete="off" />
                    <label for="inputuser"><?= __d('users', 'Identifiant'); ?></label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-0 form-floating">
                    <input type="password" class="form-control" name="pass" id="inputPassuser" placeholder="<?= __d('users', 'Mot de passe'); ?>" required="required" autocomplete="off" />
                    <label for="inputPassuser"><?= __d('users', 'Mot de passe'); ?></label>
                </div>
                <span class="help-block small float-end"><a href="user.php?op=forgetpassword" title="<?= __d('users', 'Vous avez perdu votre mot de passe ?'); ?>"><?= __d('users', 'Vous avez perdu votre mot de passe ?'); ?></a></span>
            </div>
        </div>
        <input type="hidden" name="op" value="login" />
        <button class="btn btn-primary btn-lg" type="submit" title="<?= __d('users', 'Valider'); ?>"><?= __d('users', 'Valider'); ?></button>
    </form>
</div>
<script type="text/javascript">
    //<![CDATA[document.userlogin.uname.focus();//]]>
</script>