<?php

namespace Modules\Sections\Controllers\Admin\Section;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;
use Shared\Editeur\Support\Facades\Editeur;
use Modules\Sections\Support\Facades\Section;


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

    /**
     * Undocumented function
     *
     * @return void
     */
    public function sections()
    {
        $result = $radminsuper == 1 
            ? sql_query("SELECT rubid, rubname, enligne, ordre FROM rubriques ORDER BY ordre") 
            : sql_query("SELECT DISTINCT r.rubid, r.rubname, r.enligne, r.ordre FROM rubriques r, sections s, publisujet p WHERE (r.rubid=s.rubid AND s.secid=p.secid2 AND p.aid='$aid') ORDER BY ordre");
    
        $nb_rub = sql_num_rows($result);
    
        echo '
        <hr />
        <ul class="list-group">';
    
        if ($nb_rub > 0) {
            echo '<li class="list-group-item list-group-item-action"><a href="admin.php?op=sections#ajouter publication"><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('sections', 'Ajouter une publication') . '</a></li>';
        }

        echo '<li class="list-group-item list-group-item-action"><a href="admin.php?op=new_rub_section&amp;type=rub"><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('sections', 'Ajouter une nouvelle Rubrique') . '</a></li>';
    
        if ($nb_rub > 0) {
            echo '<li class="list-group-item list-group-item-action"><a href="admin.php?op=new_rub_section&amp;type=sec" ><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('sections', 'Ajouter une nouvelle Sous-Rubrique') . '</a></li>';
        }

        if ($radminsuper == 1) {
            echo '
            <li class="list-group-item list-group-item-action"><a href="admin.php?op=ordremodule"><i class="fa fa-sort-amount-up fa-lg me-2"></i>' . __d('sections', 'Changer l\'ordre des rubriques') . '</a></li>
            <li class="list-group-item list-group-item-action"><a href="#droits des auteurs"><i class="fa fa-user-edit fa-lg me-2"></i>' . __d('sections', 'Droits des auteurs') . '</a></li>';
        }

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
    
                $rubname = Language::aff_langue($rubname);
    
                if ($rubname == '') {
                    $rubname = __d('sections', 'Sans nom');
                }
    
                if ($enligne == 0) {
                    $online = '<span class="badge bg-danger ms-1 p-2">' . __d('sections', 'Hors Ligne') . '</span>';
                } elseif ($enligne == 1) {
                    $online = '<span class="badge bg-success ms-1 p-2">' . __d('sections', 'En Ligne') . '</span>';
                }

                echo '
                <div class="list-group-item bg-light py-2 lead">
                    <a href="" class="arrow-toggle text-primary" data-bs-toggle="collapse" data-bs-target="#srub' . $i . '" ><i class="toggle-icon fa fa-caret-down fa-lg"></i></a>&nbsp;' . $rubname . ' ' . $online . ' <span class="float-end">' . $href1 . $href2 . $href3 . '</span>
                </div>';
    
                if ($radminsuper == 1) {
                    $result2 = sql_query("SELECT DISTINCT secid, secname, ordre FROM sections WHERE rubid='$rubid' ORDER BY ordre");
                } else {
                    $result2 = sql_query("SELECT DISTINCT sections.secid, sections.secname, sections.ordre FROM sections, publisujet WHERE sections.rubid='$rubid' AND sections.secid=publisujet.secid2 AND publisujet.aid='$aid' ORDER BY ordre");
                }

                if (sql_num_rows($result2) > 0) {
                    echo '
                    <div id="srub' . $i . '" class=" mb-3 collapse ">
                    <div class="list-group-item d-flex py-2"><span class="badge bg-secondary me-2 p-2">' . sql_num_rows($result2) . '</span><strong class="">' . __d('sections', 'Sous-rubriques') . '</strong>';
                    
                    if ($radminsuper == 1) {
                        echo '<span class="ms-auto"><a href="admin.php?op=ordrechapitre&amp;rubid=' . $rubid . '&amp;rubname=' . $rubname . '" title="' . __d('sections', 'Changer l\'ordre des sous-rubriques') . '" data-bs-toggle="tooltip" data-bs-placement="left" ><i class="fa fa-sort-amount-up fa-lg"></i></a></span>';
                    }

                    echo '</div>';
    
                    while (list($secid, $secname) = sql_fetch_row($result2)) {
                        $droit_pub  = Section::droits_publication($secid);
                        $secname    = Language::aff_langue($secname);
    
                        $result3 = sql_query("SELECT artid, title FROM seccont WHERE secid='$secid' ORDER BY ordre");
    
                        echo '<div class="list-group-item d-flex py-2">';
    
                        echo (sql_num_rows($result3) > 0) ?
                            '<a href="" class="arrow-toggle text-primary " data-bs-toggle="collapse" data-bs-target="#lst_sect_' . $secid . '" ><i class="toggle-icon fa fa-caret-down fa-lg"></i></a>' :
                            '<span class=""> - </span>';
    
                        echo ' 
                            &nbsp;
                        ' . $secname . '
                        <span class="ms-auto"><a href="sections.php?op=listarticles&amp;secid=' . $secid . '&amp;prev=1" ><i class="fa fa-eye fa-lg me-2 py-2"></i></a>';
                        
                        // à revoir pas suffisant
                        if ($droit_pub > 0 and $droit_pub != 4) { 
                            echo '<a href="admin.php?op=sectionedit&amp;secid=' . $secid . '" title="' . __d('sections', 'Editer la sous-rubrique') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-edit fa-lg py-2 me-2"></i></a>';
                        }

                        if (($droit_pub == 7) or ($droit_pub == 4))
                        {
                            echo '<a href="admin.php?op=sectiondelete&amp;secid=' . $secid . '" title="' . __d('sections', 'Supprimer la sous-rubrique') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fas fa-trash fa-lg text-danger py-2"></i></a>';
                        }
                        echo '</span>
                        </div>';
    
                        if (sql_num_rows($result3) > 0) {
                            $ibid = true;
    
                            echo '
                            <div id="lst_sect_' . $secid . '" class=" collapse">
                            <li class="list-group-item d-flex">
                            <span class="badge bg-secondary ms-4 p-2">' . sql_num_rows($result3) . '</span>&nbsp;<strong class=" text-capitalize">' . __d('sections', 'publications') . '</strong>';
                            
                            if ($radminsuper == 1) {
                                echo '<span class="ms-auto"><a href="admin.php?op=ordrecours&secid=' . $secid . '&amp;secname=' . $secname . '" title="' . __d('sections', 'Changer l\'ordre des publications') . '" data-bs-toggle="tooltip" data-bs-placement="left">&nbsp;<i class="fa fa-sort-amount-up fa-lg"></i></a></span>';
                            }

                            echo '</li>';
    
                            while (list($artid, $title) = sql_fetch_row($result3)) {
                                if ($title == '') {
                                    $title = __d('sections', 'Sans titre');
                                }
    
                                echo '
                                <li class="list-group-item list-group-item-action d-flex"><span class="ms-4">' . Language::aff_langue($title) . '</span>
                                    <span class="ms-auto">
                                    <a href="sections.php?op=viewarticle&amp;artid=' . $artid . '&amp;prev=1"><i class="fa fa-eye fa-lg"></i></a>&nbsp;';
    
                                // suffisant ?
                                if ($droit_pub > 0 and $droit_pub != 4) {
                                    echo '<a href="admin.php?op=secartedit&amp;artid=' . $artid . '" ><i class="fa fa-edit fa-lg"></i></a>&nbsp;';
                                }

                                if (($droit_pub == 7) or ($droit_pub == 4)) {
                                    echo '<a href="admin.php?op=secartdelete&amp;artid=' . $artid . '" class="text-danger" title="' . __d('sections', 'Supprimer') . '" data-bs-toggle="tooltip"><i class="far fa-trash fa-lg"></i></a>';
                                }

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
            $autorise_pub = Section::sousrub_select('');
    
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
                    ' . Editeur::aff_editeur('content', '') . '
                    <input type="hidden" name="op" value="secarticleadd" />
                    <input type="hidden" name="autho" value="' . $aid . '" />';
    
                Section::droits("0");
    
                echo '
                    <div class="mb-3">
                    <input class="btn btn-primary" type="submit" value="' . __d('sections', 'Ajouter') . '" />
                    </div>
                </form>';
    
                // ca c'est pas bon incomplet
                if ($radminsuper != 1) {
                    echo '<p class="blockquote">' . __d('sections', 'Une fois que vous aurez validé cette publication, elle sera intégrée en base temporaire, et l\'administrateur sera prévenu. Il visera cette publication et la mettra en ligne dans les meilleurs délais. Il est normal que pour l\'instant, cette publication n\'apparaisse pas dans l\'arborescence.') . '</p>';
                }
            }
        }
    
        $enattente = '';
    
        if ($radminsuper == 1) {
            $result = sql_query("SELECT distinct artid, secid, title, content, author FROM seccont_tempo ORDER BY artid");
            $nb_enattente = sql_num_rows($result);
    
            while (list($artid, $secid, $title, $content, $author) = sql_fetch_row($result)) {
                $enattente .= '<li class="list-group-item list-group-item-action" ><div class="d-flex flex-row align-items-center"><span class="flex-grow-1 pe-4">' . Language::aff_langue($title) . '<br /><span class="text-muted"><i class="fa fa-user fa-lg me-1"></i>[' . $author . ']</span></span><span class="text-center"><a href="admin.php?op=secartupdate&amp;artid=' . $artid . '">' . __d('sections', 'Editer') . '<br /><i class="fa fa-edit fa-lg"></i></a></span></div>';
            }
        } else {
            $result = sql_query("SELECT distinct seccont_tempo.artid, seccont_tempo.title, seccont_tempo.author FROM seccont_tempo, publisujet WHERE seccont_tempo.secid=publisujet.secid2 AND publisujet.aid='$aid' AND (publisujet.type='1' OR publisujet.type='2')");
            $nb_enattente = sql_num_rows($result);
            
            while (list($artid, $title, $author) = sql_fetch_row($result)) {
                $enattente .= '<li class="list-group-item list-group-item-action" ><div class="d-flex flex-row align-items-center"><span class="flex-grow-1 pe-4">' . Language::aff_langue($title) . '<br /><span class="text-muted"><i class="fa fa-user fa-lg me-1"></i>[' . $author . ']</span></span><span class="text-center"><a href="admin.php?op=secartupdate&amp;artid=' . $artid . '">' . __d('sections', 'Editer') . '<br /><i class="fa fa-edit fa-lg"></i></a></span></div>';
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
    
        Css::adminfoot('', '', '', '');
    }

}
