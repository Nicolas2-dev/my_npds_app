<?php

namespace Modules\Users\Validator;

use Modules\Npds\Support\FormValidator;


class ValidatorUserForgetPassword extends FormValidator
{

    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        $this->validator = $this;
    }

    /**
     * [arguments description]
     *
     * @return  [type]  [return description]
     */
    public function formId()
    {
        return "['forgetpassword'];\n";
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function extra_sql()
    {
        //
    }

    /**
     * [arguments description]
     *
     * @return  [type]  [return description]
     */
    public function arguments()
    {
        return '';
    }

    /**
     * [parameters description]
     *
     * @return  [type]  [return description]
     */
    public function parameters()
    {
        return '
            code: {
                validators: {
                    checkPassword: {
                    message: "Le mot de passe est trop simple."
                    },
                }
            },';
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
        parent::display();
    }

}
