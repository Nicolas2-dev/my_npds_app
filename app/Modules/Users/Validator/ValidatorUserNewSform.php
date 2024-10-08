<?php

namespace App\Modules\Users\Validator;

use Npds\Config\Config;
use App\Modules\Npds\Support\Facades\Js;
use App\Modules\Npds\Support\FormValidator;
use App\Modules\Npds\Support\Facades\Language;

/**
 * Undocumented class
 */
class ValidatorUserNewSform extends FormValidator
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
        return "['register'];\n";
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function extra_sql()
    {
        return Js::auto_complete('aruser', 'uname', 'users', '', '0')."\n";
    }

    /**
     * [arguments description]
     *
     * @return  [type]  [return description]
     */
    public function arguments()
    {
        return 'inpandfieldlen("name",60);
        inpandfieldlen("email",60);
        inpandfieldlen("femail",60);
        inpandfieldlen("url",100);
        inpandfieldlen("user_from",100);
        inpandfieldlen("user_occ",100);
        inpandfieldlen("user_intrest",150);
        inpandfieldlen("bio",255);
        inpandfieldlen("user_sig",255);
        inpandfieldlen("pass",40);
        inpandfieldlen("vpass",40);
        inpandfieldlen("C2",5);
        inpandfieldlen("C1",100);
        inpandfieldlen("T1",40);';
    }

    /**
     * [parameters description]
     *
     * @return  [type]  [return description]
     */
    public function parameters()
    {
        $ch_lat = Config::get('geoloc.config.ch_lat');
        $ch_lon = Config::get('geoloc.config.ch_lon');

        return '
                    uname: {
                        validators: {
                        callback: {
                            message: "Ce surnom n\'est pas disponible",
                            callback: function(input) {
                                if($.inArray(btoa(input.value), aruser) !== -1)
                                    return false;
                                else
                                    return true;
                            }
                        }
                        }
                    },
                    pass: {
                        validators: {
                        checkPassword: {
                            message: "Le mot de passe est trop simple."
                        },
                        }
                    },
                    vpass: {
                        validators: {
                            identical: {
                            compare: function() {
                            return register.querySelector(\'[name="pass"]\').value;
                            },
                            }
                        }
                    },
                    ' . $ch_lat . ': {
                        validators: {
                        regexp: {
                            regexp: /^[-]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/,
                            message: "La latitude doit être entre -90.0 and 90.0"
                        },
                        numeric: {
                            thousandsSeparator: "",
                            decimalSeparator: "."
                        },
                        between: {
                            min: -90,
                            max: 90,
                            message: "La latitude doit être entre -90.0 and 90.0"
                        }
                        }
                    },
                    ' . $ch_lon . ': {
                        validators: {
                        regexp: {
                            regexp: /^[-]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/,
                            message: "La longitude doit être entre -180.0 and 180.0"
                        },
                        numeric: {
                            thousandsSeparator: "",
                            decimalSeparator: "."
                        },
                        between: {
                            min: -180,
                            max: 180,
                            message: "La longitude doit être entre -180.0 and 180.0"
                        }
                        }
                    },
                    !###!
                    register.querySelector(\'[name="pass"]\').addEventListener("input", function() {
                        fvitem.revalidateField("vpass");
                    });

                    flatpickr("#T1", {
                        altInput: true,
                        altFormat: "l j F Y",
                        maxDate:"today",
                        minDate:"' . date("Y-m-d", (time() - 3784320000)) . '",
                        dateFormat:"d/m/Y",
                        "locale": "' . Language::language_iso(1, '', '') . '",
                    });';
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
