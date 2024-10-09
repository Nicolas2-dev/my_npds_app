<?php

namespace App\Modules\Users\Sform\Traits;

use Npds\Http\Request;
use App\Modules\Npds\Support\Facades\Hack;

/**
 * Undocumented trait
 */
trait SformHiddenTrait
{

    /**
     * Undocumented function
     *
     * @return void
     */
    public function form_hidden_formulaire()
    {
        // users table 
        $this->sform->add_field('uname',            '', Request::post('uname', null), 'hidden', false);
        $this->sform->add_field('name',             '', Hack::remove(Request::post('name', null)), 'hidden', false);
        $this->sform->add_field('email',            '', Request::post('email', null), 'hidden', false);
        $this->sform->add_field('user_avatar',      '', Request::post('user_avatar', 'blank.gif'), 'hidden', false);
        $this->sform->add_field('user_from',        '', StripSlashes(Hack::remove(Request::post('user_from', null))), 'hidden', false);
        $this->sform->add_field('user_occ',         '', StripSlashes(Hack::remove(Request::post('user_occ', null))), 'hidden', false);
        $this->sform->add_field('user_intrest',     '', StripSlashes(Hack::remove(Request::post('user_intrest', null))), 'hidden', false);
        $this->sform->add_field('user_sig',         '', StripSlashes(Hack::remove(Request::post('user_sig', null))), 'hidden', false);
        $this->sform->add_field('user_viewemail',   '', Request::post('user_viewemail', null), 'hidden', false);
        $this->sform->add_field('pass',             '', Hack::remove(Request::post('pass', null)), 'hidden', false);
        $this->sform->add_field('user_lnl',         '', Hack::remove(Request::post('user_lnl', null)), 'hidden', false);

        // users_extend table
        $this->sform->add_field('C1',               '', StripSlashes(Hack::remove(Request::post('C1', null))), 'hidden', false);
        $this->sform->add_field('C2',               '', StripSlashes(Hack::remove(Request::post('C2', null))), 'hidden', false);
        $this->sform->add_field('C3',               '', StripSlashes(Hack::remove(Request::post('C3', null))), 'hidden', false);
        $this->sform->add_field('C4',               '', StripSlashes(Hack::remove(Request::post('C4', null))), 'hidden', false);
        $this->sform->add_field('C5',               '', StripSlashes(Hack::remove(Request::post('C5', null))), 'hidden', false);
        $this->sform->add_field('C6',               '', StripSlashes(Hack::remove(Request::post('C6', null))), 'hidden', false);
        $this->sform->add_field('C7',               '', StripSlashes(Hack::remove(Request::post('C7', null))), 'hidden', false);
        $this->sform->add_field('C8',               '', StripSlashes(Hack::remove(Request::post('C8', null))), 'hidden', false);
        $this->sform->add_field('M1',               '', StripSlashes(Hack::remove(Request::post('M1', null))), 'hidden', false);
        $this->sform->add_field('M2',               '', StripSlashes(Hack::remove(Request::post('M2', null))), 'hidden', false);
        $this->sform->add_field('T1',               '', StripSlashes(Hack::remove(Request::post('T1', null))), 'hidden', false);
        $this->sform->add_field('T2',               '', StripSlashes(Hack::remove(Request::post('T2', null))), 'hidden', false);
        $this->sform->add_field('B1',               '', StripSlashes(Hack::remove(Request::post('B1', null))), 'hidden', false);
    }

}
