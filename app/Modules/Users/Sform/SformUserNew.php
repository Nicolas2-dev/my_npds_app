<?php

namespace App\Modules\Users\Sform;

use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Sform\SformManager;
use App\Modules\Users\Sform\Traits\SformCheckTrait;
use App\Modules\Users\Sform\Traits\SformAvatarTrait;
use App\Modules\Users\Sform\Traits\SformCharteTrait;
use App\Modules\Users\Validator\ValidatorUserNewSform;
use App\Modules\Users\Sform\Traits\SformConsentementTrait;
use App\Modules\Users\Sform\Traits\SformExtendFormulaireTrait;

/**
 * Undocumented class
 */
class SformUserNew 
{

    use SformAvatarTrait, SformCheckTrait, SformExtendFormulaireTrait, SformCharteTrait, SformConsentementTrait;

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
        $this->sform->add_form_title('Register');
        $this->sform->add_form_id('register');
        $this->sform->add_form_method('post');
        $this->sform->add_form_check('false');
        $this->sform->add_url(site_url('user/newuser'));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function form_hidden()
    {
        $this->sform->add_field('op', '', 'new user', 'hidden', false);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function form_submit()
    {
        $this->sform->add_extra('
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto" >
                    <button class="btn btn-primary" type="submit">' . __d('users', 'Valider') . '</button>
                </div>
            </div>');

        $this->sform->add_extra(aff_langue('
                <div class="mb-3 row">
                    <div class="col-sm-8 ms-sm-auto small" >
                        '. __d('users', 'Pour connaètre et exercer vos droits notamment de retrait de votre consentement à l\'utilisation des données collectées veuillez consulter notre {0}.',
                        '<a href="'. site_url('page?op=politique&npds=1&metalang=1') .'">'. __d('users', 'politique de confidentialité') .'</a>') .'
                    </div>
                </div>'));

        // form validation
        $this->sform->add_extra(with(new ValidatorUserNewSform($this))->display());
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function formulaire()
    {
        $this->sform->add_title(__d('users', 'Inscription'));

        $this->sform->add_mess(__d('users', '* Désigne un champ obligatoire'));
        
        $this->sform->add_field(
            'uname', 
            __d('users', 'ID utilisateur (pseudo)'), 
            Request::post('uname', null), 
            'text', 
            true, 
            25, 
            '', 
            ''
        );

        $this->sform->add_field(
            'name', 
            __d('users', 'Votre véritable identité'), 
            Request::post('name', null), 
            'text', 
            false, 
            60, 
            '', 
            ''
        );

        $this->sform->add_extender(
            'name', 
            '', 
            '<span class="help-block"><span class="float-end" id="countcar_name"></span></span>'
        );

        $this->sform->add_field(
            'email', 
            __d('users', 'Véritable adresse Email'), 
            Request::post('email', null), 
            'email', 
            true, 
            60, 
            '', 
            ''
        );

        $this->sform->add_extender(
            'email', 
            '', 
            '<span class="help-block">
                ' . __d('users', '(Cette adresse Email ne sera pas divulguée, mais elle nous servira à vous envoyer votre Mot de Passe si vous le perdez)') . '
                <span class="float-end" id="countcar_email"></span>
            </span>'
        );
        
        $this->sform->add_checkbox(
            'user_viewemail', 
            __d('users', 'Autoriser les autres utilisateurs à voir mon Email'), 
            "1", 
            false, 
            false
        );
        
        // avatar
        $this->sform_avatar_new_user();

        // formulaire suite
        $this->sform->add_field(
            'user_from', 
            __d('users', 'Votre situation géographique'), 
            StripSlashes(Request::post('user_from', null)), 
            'text', 
            false, 
            100, 
            '', 
            ''
        );
        
        $this->sform->add_extender(
            'user_from', 
            '', 
            '<span class="help-block"><span class="float-end" id="countcar_user_from"></span></span>'
        );

        $this->sform->add_field(
            'user_occ', 
            __d('users', 'Votre activité'), 
            StripSlashes(Request::post('user_occ', null)), 
            'text', 
            false, 
            100, 
            '', 
            ''
        );
        
        $this->sform->add_extender(
            'user_occ', 
            '', 
            '<span class="help-block"><span class="float-end" id="countcar_user_occ"></span></span>'
        );
        
        $this->sform->add_field(
            'user_intrest', 
            __d('users', 'Vos centres d\'intérêt'), 
            StripSlashes(Request::post('user_intrest', null)), 
            'text', 
            false, 
            150, 
            '', 
            ''
        );
        
        $this->sform->add_extender(
            'user_intrest', 
            '', 
            '<span class="help-block"><span class="float-end" id="countcar_user_intrest"></span></span>'
        );

        $this->sform->add_field(
            'user_sig', 
            __d('users', 'Signature'), 
            StripSlashes(Request::post('user_sig', null)), 
            'textarea', 
            false, 
            255, 
            '7', 
            ''
        );
        
        $this->sform->add_extender(
            'user_sig', 
            '', 
            '<span class="help-block">
                ' . __d('users', '(255 characters max. Type your signature with HTML coding)') . '<span class="float-end" id="countcar_user_sig"></span>
            </span>');

        // MEMBER-PASS
        
        if (Config::get('npds.memberpass')) {
            $this->sform->add_field(
                'pass', 
                __d('users', 'Mot de passe'), 
                '', 
                'password', 
                true, 
                40, 
                '', 
                ''
            );
            
            $this->sform->add_extra(
                '<div class="mb-3 row">
                    <div class="col-sm-8 ms-sm-auto" >
                        <div class="progress" style="height: 0.2rem;">
                            <div id="passwordMeter_cont" class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>'
            );
            
            $this->sform->add_field(
                'vpass', 
                __d('users', 'Entrez à nouveau votre mot de Passe'), 
                '', 
                'password', 
                true, 
                40, 
                '', 
                ''
            );
        }

        // MEMBER-PASS

        $this->sform->add_checkbox(
            'user_lnl', 
            __d('users', 'S\'inscrire à la liste de diffusion du site'), 
            1, 
            false, 
            true
        ); 
        
        //
        $this->extend_formulaire();
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
        $this->formulaire();

        //
        $this->charte();

        //
        $this->consentement();

        //  
        $this->form_hidden();

        //
        $this->form_submit();

        //
        return $this->sform->print_form('', 'not_echo');
    }

}
