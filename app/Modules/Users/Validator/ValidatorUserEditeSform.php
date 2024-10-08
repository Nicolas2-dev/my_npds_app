<?php

namespace App\Modules\Users\Validator;

use Npds\Config\Config;
use App\Modules\Npds\Support\FormValidator;
use App\Modules\Users\Sform\SformUserEdite;
use App\Modules\Npds\Support\Facades\Language;


class ValidatorUserEditeSform extends FormValidator
{

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private SformUserEdite $sform;


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct(SformUserEdite $sform)
    {
        $this->validator = $this;

        $this->sform = $sform;
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
        //
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
    inpandfieldlen("C2",40);
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
        //
        $avatar_wh = explode('*', Config::get('npds.avatar_size', '80*100'));

        return '
                    B1: {
                        validators: {
                            file: {
                                extension: "jpeg,jpg,png,gif",
                                type: "image/jpeg,image/png,image/gif",
                                maxSize: ' . $this->sform::TAILLE_FICHIER . ',
                                message: "Type ou/et poids ou/et extension de fichier incorrect"
                            },
                            promise: {
                                promise: function (input) {
                                    return new Promise(function(resolve, reject) {
                                        const files = input.element.files
                                        if (!files.length || typeof FileReader === "undefined") {
                                            resolve({
                                                valid: true
                                            });
                                        }
                                        const img = new Image();
                                        img.addEventListener("load", function() {
                                            const w = this.width;
                                            const h = this.height;

                                            resolve({
                                                valid: (w <= ' . $avatar_wh[0] . ' && h <= ' . $avatar_wh[1] . '),
                                                message: "Dimension(s) incorrecte(s) largeur > ' . $avatar_wh[0] . ' px ou/et hauteur > ' . $avatar_wh[1] . ' px !",
                                                meta: {
                                                    source: img.src,    // We will use it later to show the preview
                                                    width: w,
                                                    height: h,
                                                },
                                            });
                                        });
                                        img.addEventListener("error", function() {
                                            reject({
                                                valid: false,
                                                message: "Please choose an image",
                                            });
                                        });
                                        const reader = new FileReader();
                                        reader.readAsDataURL(files[0]);
                                        reader.addEventListener("loadend", function(e) {
                                            img.src = e.target.result;
                                        });
                                    });
                                }
                            },
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
                    ' . Config::get('geoloc.config.ch_lat') . ': {
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
                    ' . Config::get('geoloc.config.ch_lon') . ': {
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
