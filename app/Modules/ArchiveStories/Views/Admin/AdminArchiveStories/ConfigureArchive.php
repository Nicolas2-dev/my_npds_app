<hr />

<?php if (isset($message)): ?>
    <?= $message; ?>
<?php endif; ?>

<h3 class="mb-3"><?= __d('archivestories', 'Paramètres'); ?></h3>
<form id="archiveadm" action="<?= site_url('admin/archive/save'); ?>" method="post">
    <div class="form-floating mb-3">
        <textarea id="arch_titre" class="form-control" type="text" name="arch_titre" maxlength="400" style="height: 100px" placeholder="<?= __d('archivestories', ' Titre de votre page'); ?>" ><?= $arch_titre; ?></textarea>
        <label for="arch_titre"><?= __d('archivestories', 'Titre de la page'); ?></label>
    </div>
    <span class="help-block text-end"><span id="countcar_arch_titre"></span></span>
    <div class="form-floating mb-3">
        <select class="form-select" name="arch">
            <option name="status" value="1" <?= (($arch == 1) ? 'selected="selected"' : ''); ?>><?= __d('archivestories', 'Les articles en archive'); ?></option>
            <option name="status" value="0" <?= (($arch == 0) ? 'selected="selected"' : ''); ?>><?= __d('archivestories', 'Les articles en ligne'); ?></option>
        </select>
        <label for="arch"><?= __d('archivestories', 'Affichage'); ?></label>
    </div>
    <div class="row g-2">
        <div class="col-sm-6">
            <div class="form-floating mb-3">
                <input class="form-control" type="text" id="maxcount" name="maxcount" value="<?= $maxcount; ?>" min="0" max="500" maxlength="3" required="required" />
                <label for="maxcount"><?= __d('archivestories', 'Nombre d\'article par page'); ?></label>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-floating mb-3">
                <input class="form-control" type="text" id="retcache" name="retcache" value="<?= $retcache; ?>" min="0" maxlength="7" required="required" />
                <label for="retcache"><?= __d('archivestories', 'Rétention'); ?></label>
            </div>
            <span class="help-block text-end"><?= __d('archivestories', 'Temps de rétention en secondes'); ?></span>
        </div>
    </div>
    <input type="hidden" name="op" value="archive_stories_save" />
    <input type="hidden" name="adm_img_mod" value="1" />
    <button class="btn btn-primary" type="submit"><?= __d('archivestories', 'Sauver'); ?></button>
</form>
<hr />
<a href= "modules.php?ModPath=" ><i class="fas fa-external-link-alt fa-lg me-1" title="Voir le module en mode utilisation." data-bs-toggle="tooltip" data-bs-placement="right"></i>Voir le module en mode utilisation.</a>