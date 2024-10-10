<hr />

<h3 class="mb-3"><?= __d('faqs', 'Liste des catégories'); ?></h3>
<table id="tad_faq" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons" data-buttons-class="outline-secondary">
    <thead class="thead-infos">
        <tr>
            <th data-sortable="true" data-halign="center" class="n-t-col-xs-10"><?= __d('faqs', 'Catégories'); ?></th>
            <th data-halign="center" data-align="center" class="n-t-col-xs-2"><?= __d('faqs', 'Fonctions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($faqs as $faq): ?>
            <tr>
                <td><span title="ID : <?= $faq['id_cat']; ?>"><?= Language::aff_langue($faq['categories']); ?></span>
                    <br />
                    <a href="admin.php?op=FaqCatGo&amp;id_cat=<?= $faq['id_cat']; ?>" class="noir">
                        <i class="fa fa-level-up-alt fa-lg fa-rotate-90 " title="<?= __d('faqs', 'Voir'); ?>" data-bs-toggle="tooltip"></i>
                        &nbsp;&nbsp;<?= __d('faqs', 'Questions & Réponses'); ?>&nbsp;
                    </a>
                </td>
                <td>
                    <a href="admin.php?op=FaqCatEdit&amp;id_cat=<?= $faq['id_cat']; ?>">
                        <i class="fa fa-edit fa-lg me-2" title="<?= __d('faqs', 'Editer'); ?>" data-bs-toggle="tooltip"></i>
                    </a>
                    <a href="admin.php?op=FaqCatDel&amp;id_cat=<?= $faq['id_cat']; ?>&amp;ok=0">
                        <i class="fas fa-trash fa-lg text-danger" title="<?= __d('faqs', 'Effacer'); ?>" data-bs-toggle="tooltip"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<hr />

<h3 class="mb-3"><?= __d('faqs', 'Ajouter une catégorie'); ?></h3>
<form id="adminfaqcatad" action="admin.php" method="post">
    <fieldset>
        <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="categories"><?= __d('faqs', 'Nom'); ?></label>
            <div class="col-sm-12">
                <textarea class="form-control" type="text" name="categories" id="categories" maxlength="255" placeholder="<?= __d('faqs', 'Catégories'); ?>" rows="3" required="required"></textarea>
                <span class="help-block text-end"><span id="countcar_categories"></span></span>
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col-sm-12">
                <button class="btn btn-outline-primary col-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;<?= __d('faqs', 'Ajouter une catégorie'); ?></button>
                <input type="hidden" name="op" value="FaqCatAdd" />
            </div>
        </div>
    </fieldset>
</form>
<?= $adminfoot; ?>