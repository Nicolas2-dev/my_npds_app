<?php

namespace Modules\Users\Sform;

use Npds\Http\Request;
use Npds\Sform\SformManager;
use Modules\Users\Sform\Traits\SformCharteTrait;
use Modules\Users\Sform\Traits\SformHiddenTrait;

/**
 * Undocumented class
 */
class SformUserNewFinishHidden 
{

    use SformHiddenTrait, SformCharteTrait;

    /**
     * 
     */
    protected SformManager $sform;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $user = null;


    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->sform = new SformManager();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function form_method()
    {
        $this->sform->add_form_title('Register_confirme');
        $this->sform->add_form_id('register_confirme');
        $this->sform->add_form_method("post");
        $this->sform->add_form_check('false');
        $this->sform->add_url(site_url('user/finish'));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function form_hidden()
    {
        if (Request::post('charte', 0) == 0) {

            // 
            $this->charte_confirme();

            $this->sform->add_field('op', '', 'only_newuser', 'hidden', false);
        } else {
            $this->sform->add_field('op', '', 'finish', 'hidden', false);
        }   
        
        //
        $this->form_hidden_formulaire(); 
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function form_submit()
    {
        if (Request::post('charte', 0) == 0) {
            $this->sform->add_extra('
            <div class="mb-3 row">
                <div class="col-sm-12 ms-sm-auto" >
                    <button class="btn btn-primary mt-1" type="submit">' . __d('users', 'Retour en arrière') . '</button>
                </div>
            </div>');
        } else {
            $this->sform->add_extra('
            <div class="mb-3 row">
                <div class="col-sm-12 ms-sm-auto" >
                    <button class="btn btn-primary mt-2" type="submit">' . __d('users', 'Terminer') . '</button>
                </div>
            </div>');
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function display()
    {
        //
        $this->form_method();

        //  
        $this->form_hidden();

        //
        $this->form_submit();

        //
        return $this->sform->print_form('', 'not_echo');
    }

}
