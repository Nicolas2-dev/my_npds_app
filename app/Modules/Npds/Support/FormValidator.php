<?php

namespace App\Modules\Npds\Support;

use Npds\view\View;
use Npds\Config\Config;
use App\Modules\Npds\Support\Facades\Language;


class FormValidator
{

    /**
     * [$validator description]
     *
     * @var [type]
     */
    protected $validator;

    /**
     * [$arguments description]
     *
     * @var [type]
     */
    protected $arguments;

    /**
     * [$parameters description]
     *
     * @var [type]
     */
    protected $parameters;

    /**
     * [description]
     */
    protected $sql_num_row;


    /**
     * [sql_num_row description]
     *
     * @param   [type]  $numrow  [$numrow description]
     *
     * @return  [type]           [return description]
     */
    public function sql_num_row($numrow)
    {
        $this->sql_num_row = $numrow;

        return $this;
    }

    /**
     * [parameters description]
     *
     * @return  [type]  [return description]
     */
    public function minPassword()
    {
        return Config::get('npds.minpass');
    }

    /**
     * [parameters description]
     *
     * @return  [type]  [return description]
     */
    public function locale()
    {
        return Language::language_iso(1, "_", 1);
    }

    /**
     * [import_formvalidation_css description]
     *
     * @return  [type]  [return description]
     */
    public function import_formvalidation_css()
    {
        view::share('headerCSSheets', 
            array(
                site_url('assets/shared/formvalidation/dist/css/formValidation.min.css'),
            ) 
        );  
    }

    /**
     * [import_formvalidation_Js description]
     *
     * @return  [type]  [return description]
     */
    public function import_formvalidation_Js()
    {
        view::share('footerJScripts', 
            array(
                site_url('assets/js/es6-shim.min.js'),
                site_url('assets/shared/formvalidation/dist/js/FormValidation.full.min.js'),
                site_url('assets/shared/formvalidation/dist/js/locales/'.$this->locale().'.min.js'), 
                site_url('assets/shared/formvalidation/dist/js/plugins/Bootstrap5.min.js'), 
                site_url('assets/shared/formvalidation/dist/js/plugins/L10n.min.js'), 
                site_url('assets/js/checkfieldinp.js'), 
            )
        );        
    }

    /**
     * [validator_view description]
     *
     * @param   [type]  $validator  [$validator description]
     *
     * @return  [type]              [return description]
     */
    public function validator_view()
    {
        $parametres = $this->validator->parameters();

        if ($parametres) {
            $parametres = explode('!###!', $parametres);
        }

        return View::make(
            'Modules/Npds/Views/Partials/Validator/ValidatorDisplay',
            [
                'formId'       => $this->validator->formId(),
                'arguments'    => $this->validator->arguments(),
                'minPassword'  => $this->validator->minPassword(),
                'locale'       => $this->validator->locale(),
                'parametres'   => $parametres,

            ]
        )->fetch();
    }

    /**
     * [display description]
     *
     * @param   [type]  $validator  [$validator description]
     *
     * @return  [type]              [return description]
     */
    function display()
    {
        //
        $this->import_formvalidation_css();
        
        //
        $this->import_formvalidation_Js();

        //
        view::share('FormvalidationJScripts', $this->validator_view());
    }

}
