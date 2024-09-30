<?php

namespace App\Modules\Users\Validator;

use App\Modules\Npds\Support\FormValidator;


class ValidatorUserEditeHome extends FormValidator
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
        return "['changehome'];\n";
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
            storynum: {
                validators: {
                    regexp: {
                        regexp:/^[1-9](\d{0,2})$/,
                        message: "0-9"
                    },
                    between: {
                        min: 1,
                        max: 127,
                        message: "1 ... 127"
                    }
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
