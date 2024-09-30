<?php

namespace App\Modules\Users\Sform;

use Npds\Support\Csrf;
use Npds\Config\Config;
use Npds\Sform\SformManager;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Facades\Mailer;
use App\Modules\Users\Sform\Traits\SformCheckTrait;
use App\Modules\Users\Sform\Traits\SformAvatarTrait;
use App\Modules\Users\Validator\ValidatorUserEditeSform;
use App\Modules\Users\Sform\Traits\SformConsentementTrait;
use App\Modules\Users\Sform\Traits\SformExtendFormulaireTrait;


class SformUserEdite 
{

    use SformAvatarTrait, SformCheckTrait, SformConsentementTrait, SformExtendFormulaireTrait;

    /**
     * 
     */
    protected SformManager $sform;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $user;


    /**
     * Undocumented function
     */
    public function __construct($user)
    {
        $this->sform = new SformManager();

        $this->user = $user;
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
        $this->sform->add_form_method("post");
        $this->sform->add_form_check('false');
        $this->sform->add_url(site_url('user/saveuser'));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function forum_hidden()
    {
        $this->sform->add_field('csrfToken', '', Csrf::makeToken(), 'hidden', false);
        $this->sform->add_field('op', '', 'saveuser', 'hidden', false);
        $this->sform->add_field('uname', '', $this->user['uname'], 'hidden', false);
        $this->sform->add_field('uid', '', $this->user['uid'], 'hidden', false);
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
                        <button type="submit" class="btn btn-primary">' . __d('users', 'Valider') . '</button>
                    </div>
                </div>');

        $this->sform->add_extra(aff_langue('
                <div class="mb-3 row">
                    <div class="col-sm-8 ms-sm-auto small">
                    ' . __d('users', 'Pour connaètre et exercer vos droits notamment de retrait de votre consentement à l\'utilisation des données collectées veuillez consulter notre {0}', 
                        '<a href="'. site_url('static.php?op=politiqueconf.html&amp;App=1&amp;metalang=1') .'">'. __d('users', 'politique de confidentialitée') .'</a>'
                    ) . '
                    </div>
                </div>'));

        // form validation
        $this->sform->add_extra(with(new ValidatorUserEditeSform($this))->display());
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function formulaire()
    {
        $this->sform->add_title(__d('users', 'Utilisateur'));

        $this->sform->add_mess(__d('users', '* Désigne un champ obligatoire'));

        //$this->sform->add_form_field_size(50);
        
        //
        $this->sform->add_field(
            'name', 
            __d('users', 'Votre véritable identité') . ' ' . __d('users', '(optionnel)'), 
            $this->user['name'], 
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
        
        //
        $this->sform->add_field(
            'email', 
            __d('users', 'Véritable adresse Email'), 
            $this->user['email'], 
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
        
        //
        $this->sform->add_field(
            'femail', 
            __d('users', 
            'Votre adresse mèl \'truquée\''), 
            $this->user['femail'], 
            'email', 
            false, 
            60, 
            "", 
            ""
        );

        $this->sform->add_extender(
            'femail', 
            '', 
            '<span class="help-block">
                ' . __d('users', '(Cette adresse Email sera publique. Vous pouvez saisir ce que vous voulez mais attention au Spam)') . '
                <span class="float-end" id="countcar_femail"></span>
            </span>');
        
        //
        $this->sform->add_checkbox(
            'user_viewemail', 
            __d('users', 'Autoriser les autres utilisateurs à voir mon Email'), 
            1, 
            false, 
            $this->form_check($this->user['user_viewemail']));

        //
        $this->sform->add_field(
            'url', 
            __d('users', 'Votre page Web'), 
            $this->user['url'], 
            'url', 
            false, 
            100, 
            '', 
            ''
        );

        $this->sform->add_extender(
            'url', 
            '', 
            '<span class="help-block"><span class="float-end" id="countcar_url"></span></span>'
        );

        // SUBSCRIBE and INVISIBLE

        //
        if (Config::get('npds.subscribe')) {
            if (Mailer::isbadmailuser($this->user['uid']) === false) {

                $this->sform->add_checkbox(
                    'usend_email', 
                    __d('users', 'M\'envoyer un Email lorsqu\'un message interne arrive'), 
                    1, 
                    false, 
                    $this->form_check_value($this->user['send_email'], 1)
                );
            }
        }
    
        //
        if (Config::get('npds.member_invisible')) {
            $this->sform->add_checkbox(
                'uis_visible', 
                __d('users', 'Membre invisible') . " (" . __d('users', 'pas affiché dans l\'annuaire, message à un membre, ...') . ")", 
                1, 
                false, 
                $this->form_check_value($this->user['is_visible'], 1)
            );
        }

        // SUBSCRIBE and INVISIBLE

        //
        if (Mailer::isbadmailuser($this->user['uid']) === false) {
            $this->sform->add_checkbox(
                'user_lnl', 
                __d('users', 'S\'inscrire à la liste de diffusion du site'), 
                1, 
                false, 
                $this->form_check($this->user['user_lnl'])
            );
        }

        //
        $this->sform_avatar_user();

        //
        $this->sform->add_field(
            'user_from', 
            __d('users', 'Votre situation géographique'), 
            $this->user['user_from'], 
            'text', 
            false, 
            100, 
            '', 
            ''
        );

        $this->sform->add_extender(
            'user_from', 
            '', 
            '<span class="help-block text-end" id="countcar_user_from"></span>'
        );

        //
        $this->sform->add_field(
            'user_occ', 
            __d('users', 'Votre activité'), 
            $this->user['user_occ'], 
            'text', 
            false, 
            100, 
            '', 
            ''
        );

        $this->sform->add_extender(
            'user_occ', 
            '', 
            '<span class="help-block text-end" id="countcar_user_occ"></span>'
        );

        //
        $this->sform->add_field(
            'user_intrest', 
            __d('users', 'Vos centres d\'intérêt'), 
            $this->user['user_intrest'], 
            'text', 
            false, 
            150, 
            '', 
            ''
        );

        $this->sform->add_extender(
            'user_intrest', 
            '', 
            '<span class="help-block text-end" id="countcar_user_intrest"></span>'
        );
        
        //  SIGNATURE
        $user_status = DB::table('users_status')->select('attachsig')->where('uid', $this->user['uid'])->first();
        
        $this->sform->add_checkbox(
            'attach', 
            __d('users', 'Afficher la signature'), 
            1, 
            false, 
            $this->form_check_value($user_status['attachsig'], 1)
        );

        $this->sform->add_field(
            'user_sig', 
            __d('users', 'Signature'), 
            $this->user['user_sig'], 
            'textarea', 
            false, 
            255, 
            4, 
            '', 
            ''
        );

        $this->sform->add_extender(
            'user_sig', 
            '', 
            '<span class="help-block">
                ' . __d('users', '(255 caractères max. Entrez votre signature (mise en forme html))') . '<span class="float-end" id="countcar_user_sig"></span>
            </span>'
        );
        // SIGNATURE
        
        $this->sform->add_field(
            'bio', 
            __d('users', 'Informations supplémentaires'), 
            $this->user['bio'], 
            'textarea', 
            false, 
            255, 
            4, 
            '', 
            ''
        );

        $this->sform->add_extender(
            'bio', 
            '', 
            '<span class="help-block">
                ' . __d('users', '(255 caractères max). Précisez qui vous êtes, ou votre identification sur ce site)') . '
                <span class="float-end" id="countcar_bio"></span>
            </span>'
        );
        
        
        $this->sform->add_field(
            'pass', 
            __d('users', 'Mot de passe'), 
            '', 
            'password', 
            false, 
            40, 
            '', 
            ''
        );

        $this->sform->add_extra(
            '<div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto" >
                    <div class="progress" style="height: 0.2rem;">
                        <div id="passwordMeter_cont" class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>'
        );

        $this->sform->add_extender(
            'pass', 
            '', 
            '<span class="help-block text-end" id="countcar_pass"></span>'
        );
        
        $this->sform->add_field(
            'vpass', 
            __d('users', 'Entrez à nouveau votre mot de Passe'), 
            '', 
            'password', 
            false, 
            40, 
            '', 
            ''
        );

        $this->sform->add_extender(
            'vpass', 
            '', 
            '<span class="help-block text-end" id="countcar_vpass"></span>'
        );
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
        $this->extend_formulaire();

        //
        $this->consentement();

        //  
        $this->forum_hidden();

        //
        $this->form_submit();

        return $this->sform->print_form('', 'not_echo');
    }

}
