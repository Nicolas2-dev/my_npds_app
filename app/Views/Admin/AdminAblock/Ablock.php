<h3 class="mb-3"><?= adm_translate("Editer le Bloc Administration"); ?></h3>

<form id="adminblock" action="admin.php" method="post" class="needs-validation">
    <div class="form-floating mb-3">
        <textarea class="form-control" type="text" name="title" id="title" maxlength="1000" style="height:70px;"><?= $title ; ?></textarea>
        <label for="title"><?= adm_translate("Titre") ; ?></label>
        <span class="help-block text-end"><span id="countcar_title"></span></span>
    </div>
    <div class="form-floating mb-3">
        <textarea class="form-control" type="text" rows="25" name="content" id="content" style="height:170px;"><?= $content ; ?></textarea>
        <label for="content"><?= adm_translate("Contenu") ; ?></label>
    </div>
    <input type="hidden" name="op" value="changeablock" />
    <button class="btn btn-primary btn-block" type="submit"><?= adm_translate("Valider") ; ?></button>
</form>