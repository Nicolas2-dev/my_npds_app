<form id="lnlblock" action="<?= site_url('lnl'); ?>" method="get">
    <div class="mb-3">
        <select name="op" class=" form-select">
            <option value="subscribe"><?= __d('newsletter', 'Abonnement'); ?></option>
            <option value="unsubscribe"><?= __d('newsletter', 'Désabonnement'); ?></option>
        </select>
    </div>
    <div class="form-floating mb-3">
        <input type="email" id="email_block" name="email" maxlength="254" class="form-control" required="required" />
        <label for="email_block"><?= __d('newsletter', 'Votre adresse Email'); ?></label>
        <span class="help-block"><?= __d('newsletter', 'Recevez par mail les nouveautés du site.'); ?></span>
    </div>
    <button type="submit" class="btn btn-outline-primary btn-block btn-sm"><i class="fa fa-check fa-lg me-2"></i><?= __d('newsletter', 'Valider'); ?></button>
</form>
<?= $adminfoot; ?>