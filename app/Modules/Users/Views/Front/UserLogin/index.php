<h2><?= translate("Utilisateur"); ?></h2>

<?php if (!empty($stop) && ($stop == 99)): ?>
    <p class="alert alert-danger">
        <i class="fa fa-exclamation me-2"></i><?= translate("Vous n'êtes pas encore autorisé à vous connecter."); ?>
    </p>
<?php elseif (!empty($stop) && ($stop)): ?>
    <p class="alert alert-danger">
        <i class="fa fa-exclamation me-2"></i><?= translate("Identifiant incorrect !"); ?>
        <br /><?= translate("ou"); ?>
        <br /><i class="fa fa-exclamation me-2"></i><?= translate("Mot de passe erroné, refaites un essai."); ?>
    </p>
<?php endif; ?>

<div class="card card-body mb-3">
    <h3><a href="user.php?op=only_newuser" role="button" title="<?= translate("Nouveau membre"); ?>"><i class="fa fa-user-plus"></i>&nbsp;<?= translate("Nouveau membre"); ?></a></h3>
</div>
<div class="card card-body">
    <h3 class="mb-4"><i class="fas fa-sign-in-alt fa-lg me-2 align-middle"></i><?= translate("Connexion"); ?></h3>
    <form action="<?= site_url('user/submit'); ?>" method="post" name="userlogin">
        <div class="row g-2">
            <div class="col-sm-6">
                <div class="mb-3 form-floating">
                    <input type="text" class="form-control" name="uname" id="inputuser" placeholder="<?= translate("Identifiant"); ?>" required="required" autocomplete="off" />
                    <label for="inputuser"><?= translate("Identifiant"); ?></label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-0 form-floating">
                    <input type="password" class="form-control" name="pass" id="inputPassuser" placeholder="<?= translate("Mot de passe"); ?>" required="required" autocomplete="off" />
                    <label for="inputPassuser"><?= translate("Mot de passe"); ?></label>
                </div>
                <span class="help-block small float-end"><a href="user.php?op=forgetpassword" title="<?= translate("Vous avez perdu votre mot de passe ?"); ?>"><?= translate("Vous avez perdu votre mot de passe ?"); ?></a></span>
            </div>
        </div>
        <input type="hidden" name="op" value="login" />
        <button class="btn btn-primary btn-lg" type="submit" title="<?= translate("Valider"); ?>"><?= translate("Valider"); ?></button>
    </form>
</div>
<script type="text/javascript">
    //<![CDATA[document.userlogin.uname.focus();//]]>
</script>