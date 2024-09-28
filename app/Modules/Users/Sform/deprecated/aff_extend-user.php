<?php

use Npds\Sform\SformManager;

global $m;
$m = new SformManager();

$m->add_form_title('Register');
$m->add_form_method('post');
$m->add_form_check('false');
$m->add_url('user.php');

include(module_path('Users/Sform/deprecated/aff_formulaire.php'));

$m->add_form_fields_globals($posterdata);
$m->add_form_fields_globals($posterdata_extend);


//vd($m->get_form_fields_globals());

return $m->aff_response('', 'not_echo');



class SformUserOnlyNewUser
{

    
}
