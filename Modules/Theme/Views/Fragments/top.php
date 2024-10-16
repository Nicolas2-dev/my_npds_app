<h2>[transl]Bienvenue sur la page de garde de[/transl] !sitename!</h2>
<hr />
<div class="card card-body mb-3">
    <h3><?php echo $top; ?> [transl]articles les plus lus[/transl] <span class="text-muted float-end">[transl]Lu[/transl]</span></h3>
    <div class="clearfix"></div>
    <ol class="list-group" id="top_stories">
        top_stories(<?php echo $top; ?>)
    </ol>
</div>
<div class="card card-body mb-3">
    <h3><?php echo $top; ?> [transl]Articles les plus commentés[/transl] <span class="text-muted float-end">[transl]Commentaires[/transl]</span></h3>
    <div class="clearfix"></div>
    <ol class="list-group" id="top_commented_stories">
        top_commented_stories(<?php echo $top; ?>)
    </ol>
</div>
<div class="card card-body mb-3">
    <h3><?php echo $top; ?> [transl]Catégories les plus actives[/transl] <span class="text-muted float-end">[transl]Lu[/transl]</span></h3>
    <div class="clearfix"></div>
    <ol class="list-group" id="top_categories">
        top_categories(<?php echo $top; ?>)
    </ol>
</div>
<div class="card card-body mb-3">
    <h3><?php echo $top; ?> [transl]Articles les plus lus dans les rubriques spéciales[/transl] <span class="text-muted float-end">[transl]Hits[/transl]</span></h3>
    <div class="clearfix"></div>
    <ol class="list-group" id="top_sections">
        top_sections(<?php echo $top; ?>)
    </ol>
</div>
<div class="card card-body mb-3">
    <h3><?php echo $top; ?> [transl]Auteurs de news les plus regardées[/transl] <span class="text-muted float-end">[transl]Articles[/transl]</span></h3>
    <div class="clearfix"></div>
    <ol class="list-group" id="top_storie_authors">
        top_storie_authors(<?php echo $top; ?>)
    </ol>
</div>
<div class="card card-body mb-3">
    <h3><?php echo $top; ?> [transl]sondages avec le meilleur taux de participation[/transl] <span class="text-muted float-end">[transl]Votes[/transl]</span></h3>
    <div class="clearfix"></div>
    <ol class="list-group" id="top_polls">
        top_polls(<?php echo $top; ?>)
    </ol>
</div>
<div class="card card-body mb-3">
    <h3><?php echo $top; ?> [transl]Auteurs les plus actifs[/transl] <span class="text-muted float-end">[transl]Articles[/transl]</span></h3>
    <div class="clearfix"></div>
    <ol class="list-group" id="top_authors">
        top_authors(<?php echo $top; ?>)
    </ol>
</div>
<div class="card card-body mb-3">
    <h3><?php echo $top; ?> [transl]Critiques les plus lues[/transl] <span class="text-muted float-end">[transl]Lu[/transl]</span></h3>
    <div class="clearfix"></div>
    <ol class="list-group" id="top_reviews">
        top_reviews(<?php echo $top; ?>)
    </ol>
</div>
<div class="card card-body mb-3">
    <h3><?php echo $top; ?> [transl]Les plus téléchargés[/transl] <span class="text-muted float-end">[transl]Téléchargements[/transl]</span></h3>
    <div class="clearfix"></div>
    <ol class="list-group" id="topdownload">
        <?php echo topdownload_data("long", "dcounter"); ?>
    </ol>
</div>