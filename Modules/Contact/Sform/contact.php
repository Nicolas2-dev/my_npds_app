<?php

use App\Library\Sform\SformManager;



global $ModPath, $ModStart;

$sform_path = 'library/sform/';

include_once($sform_path . 'sform.php');

global $m;
$m = new SformManager();

//********************
$m->add_form_title('contact');
$m->add_form_id('formcontact');
$m->add_form_method('post');
$m->add_form_check('false');
$m->add_url('modules.php');
$m->add_submit_value('subok');
$m->add_field('subok', '', 'Submit', 'hidden', false);

/************************************************/
include($sform_path . 'contact/formulaire.php');

adminfoot('fv', '', 'var formulid = ["' . $m->form_id . '"];', '1');
/************************************************/
// Manage the <form>
switch ($subok) {
    case 'Submit':

        settype($message, 'string');
        settype($sformret, 'string');

        if (!$sformret) {
            $m->make_response();

            //anti_spambot
            if (!R_spambot($asb_question, $asb_reponse, $message)) {
                Ecr_Log('security', 'Contact', '');
                $subok = '';
            } else {
                $message = $m->aff_response('', 'not_echo', '');

                send_email(Config::get('npds.notify_email'), "Contact site", aff_langue($message), '', '', "html", '');

                echo '
                <div class="alert alert-success">
                ' . aff_langue("[french]Votre demande est prise en compte. Nous y r&eacute;pondrons au plus vite[/french][english]Your request is taken into account. We will answer it as fast as possible.[/english][chinese]&#24744;&#30340;&#35831;&#27714;&#24050;&#34987;&#32771;&#34385;&#22312;&#20869;&#12290; &#25105;&#20204;&#20250;&#23613;&#24555;&#22238;&#22797;[/chinese][spanish]Su solicitud es tenida en cuenta. Le responderemos lo m&aacute;s r&aacute;pido posible.[/spanish][german]Ihre Anfrage wird ber&uuml;cksichtigt. Wir werden so schnell wie m&ouml;glich antworten[/german]") . '
                </div>';
                break;
            }
        } else
            $subok = '';

    default:
        echo aff_langue($m->print_form(''));
        break;
}
