<?php


include_once("library/sform/sform.php");

global $m;
$m = new form_handler();

//********************
$m->add_form_title("coolsus");
$m->add_form_method("post");
$m->add_form_check("false");
$m->add_mess("[french]* désigne un champ obligatoire[/french][english]* required field[/english]");
$m->add_submit_value("submitS");
$m->add_url("modules.php");

include("modules/comments/$formulaire");

if (!isset($GLOBALS["submitS"]))
    echo aff_langue($m->print_form(''));
else
    $message = aff_langue($m->aff_response('', "not_echo", ''));
