<?php

use Npds\Sform\SformManager;

global $m;
$m = new SformManager();

$m->add_form_title('Register');
$m->add_form_method('post');
$m->add_form_check('false');
$m->add_url('user.php');

include(module_path('Users/Sform/aff_formulaire.php'));

return $m->aff_response('', 'not_echo');


class SformUserOnlyNewUser
{

    
}
