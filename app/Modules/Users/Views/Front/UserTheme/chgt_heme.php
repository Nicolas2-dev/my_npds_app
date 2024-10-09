<?= $user_menu; ?>

<?php if (isset($message)): ?>
    <?= $message; ?>
<?php endif; ?>

<h2 class="mb-3"><?= __d('users', 'Changer le thème'); ?></h2>
<form action="<?= site_url('user/savetheme?op=savetheme'); ?>" method="post">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3 form-floating">
                <select class="form-select" id="theme_local" name="theme_local">
                <?php foreach ($themelist as $key => $name): ?>
                        <option value="<?= $name; ?>"
                        <?php if ((($theme[0] == '') && ($name == config('npds.Default_Theme'))) || ($theme[0] == $name)): ?>
                            selected="selected"
                        <?php endif; ?>
                        ><?= $name; ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="theme_local"><?= __d('users', 'Sélectionnez un thème d\'affichage'); ?></label>
            </div>
            <p class="help-block mb-4">
                <span><?= __d('users', 'Cette option changera l\'aspect du site.'); ?></span>
                <span><?= __d('users', 'Les modifications seront seulement valides pour vous.'); ?></span>
                <span><?= __d('users', 'Chaque utilisateur peut voir le site avec un thème graphique différent.'); ?></span>
            </p>

            <div class="mb-3 form-floating" id="skin_choice">
                <select class="form-select" id="skins" name="skins">
                    <?php foreach ($skins as $k => $v): ?>
                        <option value="<?= $skins[$k]['name']; ?>"
                        <?php if ($skins[$k]['name'] == $skin): ?>
                            selected="selected"
                        <?php elseif ($skin == '' and $skins[$k]['name'] == 'default'): ?>
                            selected="selected"
                        <?php endif; ?>
                        ><?= $skins[$k]['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="skins"><?= __d('users', 'Choisir une charte graphique'); ?></label>
            </div>
        </div>
        <div class="col-md-6">
            <div id="skin_thumbnail"></div>
        </div>
    </div>
    <input type="hidden" name="uname" value="<?= $userinfo['uname']; ?>" />
    <input type="hidden" name="uid" value="<?= $userinfo['uid']; ?>" />
    <input type="hidden" name="op" value="savetheme" />
    <input class="btn btn-primary my-3" type="submit" value="<?= __d('users', 'Sauver les modifications'); ?>" />
</form>
<script type="text/javascript">
    //<![CDATA[
    $(function() {
        $("#theme_local").change(function() {
                sk = $("#theme_local option:selected").text().substr(-3);
                if (sk == "_sk") {
                    $("#skin_choice").removeClass("collapse");
                    $("#skins").change(function() {
                        sl = $("#skins option:selected").text();
                        $("#skin_thumbnail").html('<a href="<?= site_url('assets/skins/'); ?>' + sl + '" class="btn btn-outline-primary"><img class="img-fluid img-thumbnail" src="<?= site_url('assets/skins/'); ?>' + sl + '/thumbnail.png" /></a>');
                    }).change();
                } else {
                    $("#skin_choice").addClass("collapse");
                    $("#skin_thumbnail").html('');
                }
            })
            .change();
    });
    //]]
</script>