<?php

use Modules\Npds\Library\Sform\SformManager;

global $ModPath, $ModStart;

$pos = strpos($ModPath, '/admin');

if ($pos > 0) 
    $ModPathX = substr($ModPath, 0, $pos);
else 
    $ModPathX = $ModPath;

global $sform_path;

$sform_path = 'library/sform/';


global $m;
$m = new SformManager();

$m->add_form_title($ModPathX);
$m->add_field($ModPathX . '_id', $ModPathX . '_id', '', 'text', true, 11, '', 'a-9');
$m->add_key($ModPathX . '_id');
$m->add_submit_value('link_fiche_detail');
$m->add_url("modules.php?ModStart=$ModStart&ModPath=$ModPath");

include_once($sform_path . $ModPathX . '/formulaire.php');

// Fabrique le formulaire et assure sa gestion
switch ($link_fiche_detail) {

    case 'fiche_detail':
        if ($m->sform_read_mysql($browse_key)) {
            $m->add_extra("<tr><td colspan=\"2\" align=\"center\">");
            $m->add_extra('<a href="javascript: history.go(-1)" class="btn btn-primary">' . __d('links', 'Retour en arrière') . '</a>');
            $m->add_extra("</td></tr>");
            $m->key_lock("close");
            echo aff_langue($m->print_form("class=\"ligna\""));
        } else
            redirect_url($m->url);
        break;

    default:
        if ($m->sform_read_mysql($browse_key))
            echo '<a class="me-3" href="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=fiche_detail&amp;lid=' . $browse_key . '" ><i class="fa fa-info fa-lg" title="' . __d('links', 'Détails supplémentaires') . '" data-bs-toggle="tooltip"></i></a>';
        break;
}
