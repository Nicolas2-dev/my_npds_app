<?php

namespace Modules\Sections\Controllers\Admin\Section;

use Modules\Npds\Core\AdminController;


class Sections extends AdminController
{
/**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = "";

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = '';


    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        $this->f_titre = __d('', '');

        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * [after description]
     *
     * @param   [type]  $result  [$result description]
     *
     * @return  [type]           [return description]
     */
    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    function sections()
    {
        $result = $radminsuper == 1 ?
            sql_query("SELECT rubid, rubname, enligne, ordre FROM rubriques ORDER BY ordre") :
            sql_query("SELECT DISTINCT r.rubid, r.rubname, r.enligne, r.ordre FROM rubriques r, sections s, publisujet p WHERE (r.rubid=s.rubid AND s.secid=p.secid2 AND p.aid='$aid') ORDER BY ordre");
    
        $nb_rub = sql_num_rows($result);
    
        echo '
        <hr />
        <ul class="list-group">';
    
        if ($nb_rub > 0)
            echo '<li class="list-group-item list-group-item-action"><a href="admin.php?op=sections#ajouter publication"><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('sections', 'Ajouter une publication') . '</a></li>';
    
        echo '<li class="list-group-item list-group-item-action"><a href="admin.php?op=new_rub_section&amp;type=rub"><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('sections', 'Ajouter une nouvelle Rubrique') . '</a></li>';
    
        if ($nb_rub > 0)
            echo '<li class="list-group-item list-group-item-action"><a href="admin.php?op=new_rub_section&amp;type=sec" ><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('sections', 'Ajouter une nouvelle Sous-Rubrique') . '</a></li>';
    
        if ($radminsuper == 1)
            echo '
            <li class="list-group-item list-group-item-action"><a href="admin.php?op=ordremodule"><i class="fa fa-sort-amount-up fa-lg me-2"></i>' . __d('sections', 'Changer l\'ordre des rubriques') . '</a></li>
            <li class="list-group-item list-group-item-action"><a href="#droits des auteurs"><i class="fa fa-user-edit fa-lg me-2"></i>' . __d('sections', 'Droits des auteurs') . '</a></li>';
    
        echo '
            <li class="list-group-item list-group-item-action"><a href="#publications en attente"><i class="fa fa-clock fa-lg me-2"></i>' . __d('sections', 'Publication(s) en attente de validation') . '</a></li>
        </ul>';
    
        if ($nb_rub > 0) {
            $i = -1;
    
            echo '
            <hr />
            <h3 class="my-3">' . __d('sections', 'Liste des rubriques') . '</h3>';
    
            while (list($rubid, $rubname, $enligne, $ordre) = sql_fetch_row($result)) {
                $i++;
    
                if ($radminsuper == 1) {
                    $href1 = '<a href="admin.php?op=rubriquedit&amp;rubid=' . $rubid . '" title="' . __d('sections', 'Editer la rubrique') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-edit fa-lg me-2"></i>&nbsp;';
                    $href2 = '</a>';
                    $href3 = '<a href="admin.php?op=rubriquedelete&amp;rubid=' . $rubid . '" class="text-danger" title="' . __d('sections', 'Supprimer la rubrique') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fas fa-trash fa-lg"></i></a>';
                } else {
                    $href1 = '';
                    $href2 = '';
                    $href3 = '';
                }
    
                $rubname = aff_langue($rubname);
    
                if ($rubname == '') 
                    $rubname = __d('sections', 'Sans nom');
    
                if ($enligne == 0)
                    $online = '<span class="badge bg-danger ms-1 p-2">' . __d('sections', 'Hors Ligne') . '</span>';
                else if ($enligne == 1)
                    $online = '<span class="badge bg-success ms-1 p-2">' . __d('sections', 'En Ligne') . '</span>';
    
                echo '
                <div class="list-group-item bg-light py-2 lead">
                    <a href="" class="arrow-toggle text-primary" data-bs-toggle="collapse" data-bs-target="#srub' . $i . '" ><i class="toggle-icon fa fa-caret-down fa-lg"></i></a>&nbsp;' . $rubname . ' ' . $online . ' <span class="float-end">' . $href1 . $href2 . $href3 . '</span>
                </div>';
    
                if ($radminsuper == 1)
                    $result2 = sql_query("SELECT DISTINCT secid, secname, ordre FROM sections WHERE rubid='$rubid' ORDER BY ordre");
                else
                    $result2 = sql_query("SELECT DISTINCT sections.secid, sections.secname, sections.ordre FROM sections, publisujet WHERE sections.rubid='$rubid' AND sections.secid=publisujet.secid2 AND publisujet.aid='$aid' ORDER BY ordre");
    
                if (sql_num_rows($result2) > 0) {
                    echo '
                    <div id="srub' . $i . '" class=" mb-3 collapse ">
                    <div class="list-group-item d-flex py-2"><span class="badge bg-secondary me-2 p-2">' . sql_num_rows($result2) . '</span><strong class="">' . __d('sections', 'Sous-rubriques') . '</strong>';
                    
                    if ($radminsuper == 1)
                        echo '<span class="ms-auto"><a href="admin.php?op=ordrechapitre&amp;rubid=' . $rubid . '&amp;rubname=' . $rubname . '" title="' . __d('sections', 'Changer l\'ordre des sous-rubriques') . '" data-bs-toggle="tooltip" data-bs-placement="left" ><i class="fa fa-sort-amount-up fa-lg"></i></a></span>';
                    
                    echo '</div>';
    
                    while (list($secid, $secname) = sql_fetch_row($result2)) {
                        $droit_pub = droits_publication($secid);
                        $secname = aff_langue($secname);
    
                        $result3 = sql_query("SELECT artid, title FROM seccont WHERE secid='$secid' ORDER BY ordre");
    
                        echo '<div class="list-group-item d-flex py-2">';
    
                        echo (sql_num_rows($result3) > 0) ?
                            '<a href="" class="arrow-toggle text-primary " data-bs-toggle="collapse" data-bs-target="#lst_sect_' . $secid . '" ><i class="toggle-icon fa fa-caret-down fa-lg"></i></a>' :
                            '<span class=""> - </span>';
    
                        echo ' 
                            &nbsp;
                        ' . $secname . '
                        <span class="ms-auto"><a href="sections.php?op=listarticles&amp;secid=' . $secid . '&amp;prev=1" ><i class="fa fa-eye fa-lg me-2 py-2"></i></a>';
                        
                        if ($droit_pub > 0 and $droit_pub != 4) // à revoir pas suffisant
                            echo '<a href="admin.php?op=sectionedit&amp;secid=' . $secid . '" title="' . __d('sections', 'Editer la sous-rubrique') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-edit fa-lg py-2 me-2"></i></a>';
                        
                        if (($droit_pub == 7) or ($droit_pub == 4))
                            echo '<a href="admin.php?op=sectiondelete&amp;secid=' . $secid . '" title="' . __d('sections', 'Supprimer la sous-rubrique') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fas fa-trash fa-lg text-danger py-2"></i></a>';
                        
                        echo '</span>
                        </div>';
    
                        if (sql_num_rows($result3) > 0) {
                            $ibid = true;
    
                            echo '
                            <div id="lst_sect_' . $secid . '" class=" collapse">
                            <li class="list-group-item d-flex">
                            <span class="badge bg-secondary ms-4 p-2">' . sql_num_rows($result3) . '</span>&nbsp;<strong class=" text-capitalize">' . __d('sections', 'publications') . '</strong>';
                            
                            if ($radminsuper == 1)
                                echo '<span class="ms-auto"><a href="admin.php?op=ordrecours&secid=' . $secid . '&amp;secname=' . $secname . '" title="' . __d('sections', 'Changer l\'ordre des publications') . '" data-bs-toggle="tooltip" data-bs-placement="left">&nbsp;<i class="fa fa-sort-amount-up fa-lg"></i></a></span>';
                            
                            echo '</li>';
    
                            while (list($artid, $title) = sql_fetch_row($result3)) {
                                if ($title == '') 
                                    $title = __d('sections', 'Sans titre');
    
                                echo '
                                <li class="list-group-item list-group-item-action d-flex"><span class="ms-4">' . aff_langue($title) . '</span>
                                    <span class="ms-auto">
                                    <a href="sections.php?op=viewarticle&amp;artid=' . $artid . '&amp;prev=1"><i class="fa fa-eye fa-lg"></i></a>&nbsp;';
    
                                if ($droit_pub > 0 and $droit_pub != 4) // suffisant ?
                                    echo '<a href="admin.php?op=secartedit&amp;artid=' . $artid . '" ><i class="fa fa-edit fa-lg"></i></a>&nbsp;';
    
                                if (($droit_pub == 7) or ($droit_pub == 4))
                                    echo '<a href="admin.php?op=secartdelete&amp;artid=' . $artid . '" class="text-danger" title="' . __d('sections', 'Supprimer') . '" data-bs-toggle="tooltip"><i class="far fa-trash fa-lg"></i></a>';
                                
                                echo '
                                    </span>
                                </li>';
                            }
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                }
            }
    
            echo '
                <hr />
                <h3 class="my-3">' . __d('sections', 'Editer une publication') . '</h3>
                <form action="admin.php" method="post">
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-4" for="artid">ID</label>
                        <div class="col-sm-8">
                        <input type="number" class="form-control" id="artid" name="artid" min="0" max="999999999" />
                        </div>
                    </div>
                    <input type="hidden" name="op" value="secartedit" />
                </form>';
    
            // Ajout d'une publication
            $autorise_pub = sousrub_select('');
    
            if ($autorise_pub) {
                echo '
                <hr />
                <h3 class="mb-3"><a name="ajouter publication">' . __d('sections', 'Ajouter une publication') . '</a></h3>
                <form action="admin.php" method="post" name="adminForm">
                    <div class="mb-3 row">
                    <label class="col-form-label col-12" for="secid">' . __d('sections', 'Sous-rubrique') . '</label>
                    <div class="col-12">
                    ' . $autorise_pub . '
                    </div>
                    </div>
                    <div class="mb-3 row">
                    <label class="col-form-label col-12" for="title">' . __d('sections', 'Titre') . '</label>
                    <div class=" col-12">
                        <textarea class="form-control" name="title" rows="2"></textarea>
                    </div>
                    </div>
                    <div class="mb-3 row">
                    <label class="col-form-label col-12" for="content">' . __d('sections', 'Contenu') . '</label>
                    <div class=" col-12">
                        <textarea class="tin form-control" name="content" rows="30"></textarea>
                    </div>
                    </div>
                    ' . aff_editeur('content', '') . '
                    <input type="hidden" name="op" value="secarticleadd" />
                    <input type="hidden" name="autho" value="' . $aid . '" />';
    
                droits("0");
    
                echo '
                    <div class="mb-3">
                    <input class="btn btn-primary" type="submit" value="' . __d('sections', 'Ajouter') . '" />
                    </div>
                </form>';
    
                // ca c'est pas bon incomplet
                if ($radminsuper != 1)
                    echo '<p class="blockquote">' . __d('sections', 'Une fois que vous aurez validé cette publication, elle sera intégrée en base temporaire, et l\'administrateur sera prévenu. Il visera cette publication et la mettra en ligne dans les meilleurs délais. Il est normal que pour l\'instant, cette publication n\'apparaisse pas dans l\'arborescence.') . '</p>';
            }
        }
    
        $enattente = '';
    
        if ($radminsuper == 1) {
            $result = sql_query("SELECT distinct artid, secid, title, content, author FROM seccont_tempo ORDER BY artid");
            $nb_enattente = sql_num_rows($result);
    
            while (list($artid, $secid, $title, $content, $author) = sql_fetch_row($result)) {
                $enattente .= '<li class="list-group-item list-group-item-action" ><div class="d-flex flex-row align-items-center"><span class="flex-grow-1 pe-4">' . aff_langue($title) . '<br /><span class="text-muted"><i class="fa fa-user fa-lg me-1"></i>[' . $author . ']</span></span><span class="text-center"><a href="admin.php?op=secartupdate&amp;artid=' . $artid . '">' . __d('sections', 'Editer') . '<br /><i class="fa fa-edit fa-lg"></i></a></span></div>';
            }
        } else {
            $result = sql_query("SELECT distinct seccont_tempo.artid, seccont_tempo.title, seccont_tempo.author FROM seccont_tempo, publisujet WHERE seccont_tempo.secid=publisujet.secid2 AND publisujet.aid='$aid' AND (publisujet.type='1' OR publisujet.type='2')");
            $nb_enattente = sql_num_rows($result);
            
            while (list($artid, $title, $author) = sql_fetch_row($result)) {
                $enattente .= '<li class="list-group-item list-group-item-action" ><div class="d-flex flex-row align-items-center"><span class="flex-grow-1 pe-4">' . aff_langue($title) . '<br /><span class="text-muted"><i class="fa fa-user fa-lg me-1"></i>[' . $author . ']</span></span><span class="text-center"><a href="admin.php?op=secartupdate&amp;artid=' . $artid . '">' . __d('sections', 'Editer') . '<br /><i class="fa fa-edit fa-lg"></i></a></span></div>';
            }
        }
    
        echo '
        <hr />
        <h3 class="mb-3"><a name="publications en attente"><i class="far fa-clock fa-lg me-1"></i>' . __d('sections', 'Publication(s) en attente de validation') . '</a><span class="badge bg-danger float-end">' . $nb_enattente . '</span></h3>
        <ul class="list-group">
        ' . $enattente . '
        </ul>';
    
        if ($radminsuper == 1) {
            echo  '
            <hr />
            <h3 class="mb-3"><a name="droits des auteurs"><i class="fa fa-user-edit me-2"></i>' . __d('sections', 'Droits des auteurs') . '</a></h3>';
    
            $result = sql_query("SELECT aid, name, radminsuper FROM authors");
    
            echo '<div class="row">';
    
            while (list($Xaid, $name, $Xradminsuper) = sql_fetch_row($result)) {
                if (!$Xradminsuper) {
                    echo '
                    <div class="col-sm-4">
                    <div class="card my-2 p-1">
                        <div class="card-body p-1">
                            <i class="fa fa-user fa-lg me-1"></i><br />' . $Xaid . '&nbsp;/&nbsp;' . $name . '<br />
                            <a href="admin.php?op=droitauteurs&amp;author=' . $Xaid . '">' . __d('sections', 'Modifier l\'information') . '</a>
                        </div>
                    </div>
                    </div>';
                }
            }
    
            echo '</div>';
        }
    
        adminfoot('', '', '', '');
    }
    
    function new_rub_section($type)
    {
        $arg1 = '';
    
        if ($type == 'sec') {
            echo '
            <hr />
            <h3 class="mb-3">' . __d('sections', 'Ajouter une nouvelle Sous-Rubrique') . '</h3>
            <form action="admin.php" method="post" id="newsection" name="adminForm">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="rubref">' . __d('sections', 'Rubriques') . '</label>
                    <div class="col-sm-8">
                    <select class="form-select" id="rubref" name="rubref">';
    
            if ($radminsuper == 1)
                $result = sql_query("SELECT rubid, rubname FROM rubriques ORDER BY ordre");
            else
                $result = sql_query("SELECT DISTINCT r.rubid, r.rubname FROM rubriques r LEFT JOIN sections s on r.rubid= s.rubid LEFT JOIN publisujet p on s.secid= p.secid2 WHERE p.aid='$aid'");
            
            while (list($rubid, $rubname) = sql_fetch_row($result)) {
                echo '<option value="' . $rubid . '">' . aff_langue($rubname) . '</option>';
            }
    
            echo '
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4 col-md-4" for="image">' . __d('sections', 'Image pour la Sous-Rubrique') . '</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="image" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-form-label" for="secname">' . __d('sections', 'Titre') . '</label>
                    <textarea  class="form-control" id="secname" name="secname" maxlength="255" rows="2" required="required"></textarea>
                    <span class="help-block text-end"><span id="countcar_secname"></span></span>
                </div>
                <div class="mb-3">
                    <label class="col-form-label" for="introd">' . __d('sections', 'Texte d\'introduction') . '</label>
                    <textarea class="tin form-control" name="introd" rows="30"></textarea>';
    
            echo aff_editeur("introd", '');
    
            echo '</div>';
    
            droits("0");
    
            echo '
            <div class="mb-3">
                <input type="hidden" name="op" value="sectionmake" />
                <button class="btn btn-primary col-sm-6 col-12 col-md-4" type="submit" /><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('sections', 'Ajouter') . '</button>
                <button class="btn btn-secondary col-sm-6 col-12 col-md-4" type="button" onclick="javascript:history.back()">' . __d('sections', 'Retour en arrière') . '</button>
            </div>
            </form>';
    
            $arg1 = '
                var formulid = ["newsection"];
                inpandfieldlen("secname",255);';
        } else if ($type == "rub") {
            echo '
                <hr />
                <h3 class="mb-3">' . __d('sections', 'Ajouter une nouvelle Rubrique') . '</h3>
                <form action="admin.php" method="post" id="newrub" name="adminForm">
                    <div class="mb-3">
                    <label class="col-form-label" for="rubname">' . __d('sections', 'Nom de la Rubrique') . '</label>
                    <textarea class="form-control" id="rubname" name="rubname" rows="2" maxlength="255" required="required"></textarea>
                    <span class="help-block text-end" id="countcar_rubname"></span>
                    </div>
                    <div class="mb-3">
                    <label class="col-form-label" for="introc">' . __d('sections', 'Texte d\'introduction') . '</label>
                    <textarea class="tin form-control" id="introc" name="introc" rows="30" ></textarea>
                    </div>';
    
            echo aff_editeur('introc', '');
    
            echo '
                    <div class="mb-3">
                    <input type="hidden" name="op" value="rubriquemake" />
                    <button class="btn btn-primary" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('sections', 'Ajouter') . '</button>
                    <button class="btn btn-secondary" type="button" onclick="javascript:history.back()">' . __d('sections', 'Retour en arrière') . '</button>
                    </div>
                </form>';
    
            $arg1 = '
                var formulid = ["newrub"];
                inpandfieldlen("rubname",255);';
        }
    
        adminfoot('fv', '', $arg1, '');
    }

    function sectionedit($secid)
    {
        $result = sql_query("SELECT secid, secname, image, userlevel, rubid, intro FROM sections WHERE secid='$secid'");
        list($secid, $secname, $image, $userlevel, $rubref, $intro) = sql_fetch_row($result);
    
        $secname = stripslashes($secname);
        $intro = stripslashes($intro);

        echo '
        <hr />
        <h3 class="mb-3">' . __d('sections', 'Sous-rubrique') . ' : <span class="text-muted">' . aff_langue($secname) . '</span></h3>';
    
        $result2 = sql_query("SELECT artid FROM seccont WHERE secid='$secid'");
    
        $number = sql_num_rows($result2);
    
        if ($number)
            echo '<span class="badge bg-secondary p-2 me-2">' . $number . ' </span>' . __d('sections', 'publication(s) attachée(s)');
    
        echo '
                <form id="sectionsedit" action="admin.php" method="post" name="adminForm">
                <div class="mb-3">
                    <label class="col-form-label" for="rubref">' . __d('sections', 'Rubriques') . '</label>';
    
    
        if ($radminsuper == 1)
            $result = sql_query("SELECT rubid, rubname FROM rubriques ORDER BY ordre");
        else
            $result = sql_query("SELECT DISTINCT r.rubid, r.rubname FROM rubriques r LEFT JOIN sections s on r.rubid= s.rubid LEFT JOIN publisujet p on s.secid= p.secid2 WHERE p.aid='$aid'");
        
        echo '<select class="form-select" id="rubref" name="rubref">';
    
        while (list($rubid, $rubname) = sql_fetch_row($result)) {
            $sel = $rubref == $rubid ? 'selected="selected"' : '';
    
            echo '<option value="' . $rubid . '" ' . $sel . '>' . aff_langue($rubname) . '</option>';
        }
    
        echo '
                    </select>
            </div>';
    
        // ici on a(vait) soit le select qui permet de changer la sous rubrique de rubrique (ca c'est good) soit un input caché avec la valeur fixé de la rubrique...donc ICI un author ne peut pas changer sa sous rubrique de rubrique ...il devrait pouvoir le faire dans une sous-rubrique ou il a des "droits" ??
    
        /*
        if ($radminsuper==1) {
            echo '
                    <select class="form-select" id="rubref" name="rubref">';
            $result = sql_query("SELECT rubid, rubname FROM rubriques ORDER BY ordre");
            while(list($rubid, $rubname) = sql_fetch_row($result)) {
                $sel = $rubref==$rubid?'selected="selected"':'';
                echo '
                    <option value="'.$rubid.'" '.$sel.'>'.aff_langue($rubname).'</option>';
                }
            echo '
                    </select>
            </div>';
        } else {
            echo '<input type="hidden" name="rubref" value="'.$rubref.'" />';
            $result = sql_query("SELECT rubname FROM rubriques WHERE rubid='$rubref'");
            list($rubname) = sql_fetch_row($result);
            echo '<pan class="ms-2">'.aff_langue($rubname).'</span>';
        }
        */
    
        //ici
        echo '
        <div class="mb-3">
            <label class="col-form-label" for="secname">' . __d('sections', 'Sous-rubrique') . '</label>
            <textarea class="form-control" id="secname" name="secname" rows="4" maxlength="255" required="required">' . $secname . '</textarea>
            <span class="help-block text-end"><span id="countcar_secname"></span></span>
        </div>
        <div class="mb-3">
            <label class="col-form-label" for="image">' . __d('sections', 'Image') . '</label>
            <input type="text" class="form-control" id="image" name="image" maxlength="255" value="' . $image . '" />
            <span class="help-block text-end"><span id="countcar_image"></span></span>
        </div>
        <div class="mb-3">
            <label class="col-form-label" for="introd">' . __d('sections', 'Texte d\'introduction') . '</label>
            <textarea class="tin form-control" id="introd" name="introd" rows="20">' . $intro . '</textarea>
        </div>';
    
        echo aff_editeur('introd', '');
    
        droits($userlevel);
    
        $droit_pub = droits_publication($secid);
    
        if ($droit_pub == 3 or $droit_pub == 7) {
            echo '<input type="hidden" name="secid" value="' . $secid . '" />
                <input type="hidden" name="op" value="sectionchange" />
                <button class="btn btn-primary" type="submit">' . __d('sections', 'Enregistrer') . '</button>';
        }
    
        echo '
        <input class="btn btn-secondary" type="button" value="' . __d('sections', 'Retour en arrière') . '" onclick="javascript:history.back()" />
        </form>';
    
        $arg1 = '
        var formulid = ["sectionsedit"];
        inpandfieldlen("secname",255);
        inpandfieldlen("image",255);
        ';
    
        adminfoot('fv', '', $arg1, '');
    }
    
    function sectionmake($secname, $image, $members, $Mmembers, $rubref, $introd)
    {
        global $radminsuper, $aid;
    
        if (is_array($Mmembers) and ($members == 1)) {
            $members = implode(',', $Mmembers);
    
            if ($members == 0) 
                $members = 1;
        }
    
        $secname = stripslashes(FixQuotes($secname));
        $rubref = stripslashes(FixQuotes($rubref));
        $image = stripslashes(FixQuotes($image));
    
        $introd = stripslashes(FixQuotes(dataimagetofileurl($introd, 'modules/upload/upload/sec')));
        sql_query("INSERT INTO sections VALUES (NULL,'$secname', '$image', '$members', '$rubref', '$introd','99','0')");
    
        if ($radminsuper != 1) {
            $result = sql_query("SELECT secid FROM sections ORDER BY secid DESC LIMIT 1");
            list($secid) = sql_fetch_row($result);
    
            droitsalacreation($aid, $secid);
        }
    
        Ecr_Log('security', "CreateSections($secname) by AID : $aid", '');
    
        Header("Location: admin.php?op=sections");
    }
    
    function sectionchange($secid, $secname, $image, $members, $Mmembers, $rubref, $introd)
    {
        if (is_array($Mmembers) and ($members == 1)) {
            $members = implode(',', $Mmembers);
    
            if ($members == 0) 
                $members = 1;
        }
    
        $secname = stripslashes(FixQuotes($secname));
        $image = stripslashes(FixQuotes($image));
        $introd = stripslashes(FixQuotes(dataimagetofileurl($introd, 'modules/upload/upload/sec')));
    
        sql_query("UPDATE sections SET secname='$secname', image='$image', userlevel='$members', rubid='$rubref', intro='$introd' WHERE secid='$secid'");
    
        global $aid;
        Ecr_Log('security', "UpdateSections($secid, $secname) by AID : $aid", '');
    
        Header("Location: admin.php?op=sections");
    }

    function sectiondelete($secid, $ok = 0)
    {
        // protection
        $tmp = droits_publication($secid);

        if (($tmp != 7) and ($tmp != 4)) {
            Header("Location: admin.php?op=sections");
        }
    
        if ($ok == 1) {
            $result = sql_query("SELECT artid FROM seccont WHERE secid='$secid'");
    
            if (sql_num_rows($result) > 0) {
                while (list($artid) = sql_fetch_row($result)) {
                    sql_query("DELETE FROM compatsujet WHERE id1='$artid'");
                }
            }
    
            sql_query("DELETE FROM seccont WHERE secid='$secid'");
            sql_query("DELETE FROM sections WHERE secid='$secid'");
    
            global $aid;
            Ecr_Log("security", "DeleteSections($secid) by AID : $aid", "");
    
            Header("Location: admin.php?op=sections");
        } else {
            $result = sql_query("SELECT secname FROM sections WHERE secid='$secid'");
            list($secname) = sql_fetch_row($result);
    
            echo '
            <hr />
            <h3 class="mb-3 text-danger">' . __d('sections', 'Effacer la sous-rubrique : ') . '<span class="text-muted">' . aff_langue($secname) . '</span></h3>
            <div class="alert alert-danger">
                <strong>' . __d('sections', 'Etes-vous sûr de vouloir effacer cette sous-rubrique ?') . '</strong><br /><br />
                <a class="btn btn-danger btn-sm" href="admin.php?op=sectiondelete&amp;secid=' . $secid . '&amp;ok=1" role="button">' . __d('sections', 'Oui') . '</a>&nbsp;<a class="btn btn-secondary btn-sm" role="button" href="admin.php?op=sections" >' . __d('sections', 'Non') . '</a>
            </div>';
    
            adminfoot('', '', '', '');
        }
    }

}
