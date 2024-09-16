<h1><?= __d('authors', 'Administration'); ?></h1>
<div id="adm_men">
    <h2 class="mb-3"><i class="fas fa-sign-in-alt fa-lg align-middle me-2"></i><?= __d('authors', 'Connexion'); ?></h2>
    <form action="<?= site_url('admin/login/submit'); ?>" method="post" id="adminlogin" name="adminlogin">
    <div class="row g-3">
        <div class="col-sm-6">
            <div class="mb-3 form-floating">
                <input id="aid" class="form-control" type="text" name="aid" maxlength="20" placeholder="<?= __d('authors', 'Administrateur ID'); ?>" required="required" />
                <label for="aid"><?= __d('authors', 'Administrateur ID'); ?></label>
            </div>
            <span class="help-block text-end"><span id="countcar_aid"></span></span>
        </div>
        <div class="col-sm-6">
            <div class="mb-3 form-floating">
                <input id="pwd" class="form-control" type="password" name="pwd" maxlength="18" placeholder="<?= __d('authors', 'Mot de Passe'); ?>" required="required" />
                <label for="pwd"><?= __d('authors', 'Mot de Passe'); ?></label>
            </div>
            <span class="help-block text-end"><span id="countcar_pwd"></span></span>
        </div>
    </div>
    <button class="btn btn-primary btn-lg" type="submit"><?= __d('authors', 'Valider'); ?></button>
    <input type="hidden" name="op" value="login" />
</form>
<script type="text/javascript">
    //<![CDATA[
        document.adminlogin.aid.focus();
        $(document).ready(function() {
            inpandfieldlen("pwd",18);
            inpandfieldlen("aid",20);
        });
    //]]>
</script>
<?= $adminfoot; ?>