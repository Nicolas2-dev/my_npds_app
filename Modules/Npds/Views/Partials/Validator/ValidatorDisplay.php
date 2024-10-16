<script type="text/javascript">
//<![CDATA[
    var formulid = <?=  $formId; ?>
    <?php if(isset($extrasql)): ?>
        <?= $extrasql; ?>
    <?php endif; ?>
    <?=  $arguments; ?>
    var diff;
    document.addEventListener("DOMContentLoaded", function(e) {
        // validateur pour mots de passe
        const strongPassword = function() {
            return {
                validate: function(input) {
                    let score = 0;
                    const value = input.value;
                    if (value === "") {
                        return {
                            valid: true,
                            meta: {
                                score: null
                            },
                        };
                    }
                    if (value === value.toLowerCase()) {
                        return {
                            valid: false,
                            message: "<?=  __d('npds', 'Le mot de passe doit contenir au moins un caractère en majuscule.'); ?>",
                            meta: {
                                score: score - 1
                            },
                        };
                    }
                    if (value === value.toUpperCase()) {
                        return {
                            valid: false,
                            message: "<?=  __d('npds', 'Le mot de passe doit contenir au moins un caractère en minuscule.'); ?>",
                            meta: {
                                score: score - 2
                            },
                        };
                    }
                    if (value.search(/[0-9]/) < 0) {
                        return {
                            valid: false,
                            message: "<?=  __d('npds', 'Le mot de passe doit contenir au moins un chiffre.'); ?>",
                            meta: {
                                score: score - 3
                            },
                        };
                    }
                    if (value.search(/[@\+\-!#$%&^~*_]/) < 0) {
                        return {
                            valid: false,
                            message: "<?=  __d('npds', 'Le mot de passe doit contenir au moins un caractère non alphanumérique.'); ?>",
                            meta: {
                                score: score - 4
                            },
                        };
                    }
                    if (value.length < 8) {
                        return {
                            valid: false,
                            message: "<?=  __d('npds', 'Le mot de passe doit contenir'); ?> <?=  $minPassword; ?> <?=  __d('npds', 'caractères au minimum'); ?>",
                            meta: {
                                score: score - 5
                            },
                        };
                    }
                    score += ((value.length >= <?=  $minPassword; ?>) ? 1 : -1);
                    if (/[A-Z]/.test(value)) score += 1;
                    if (/[a-z]/.test(value)) score += 1;
                    if (/[0-9]/.test(value)) score += 1;
                    if (/[@\+\-!#$%&^~*_]/.test(value)) score += 1;
                    return {
                        valid: true,
                        meta: {
                            score: score
                        },
                    };
                },
            };
        };
        FormValidation.validators.checkPassword = strongPassword;
        formulid.forEach(function(item, index, array) {
            const fvitem = FormValidation.formValidation(
                document.getElementById(item), {
                    locale: "<?=  $locale ?>",
                    localization: FormValidation.locales.<?=  $locale ?>,
                    fields: {
                        <?php if ($parametres): ?>
                            <?=  $parametres[0] ?>
                        <?php endif; ?>
                    },
                    plugins: {
                        declarative: new FormValidation.plugins.Declarative({
                            html5Input: true,
                        }),
                        trigger: new FormValidation.plugins.Trigger(),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".mb-3"
                        }),
                        icon: new FormValidation.plugins.Icon({
                            valid: "fa fa-check",
                            invalid: "fa fa-times",
                            validating: "fa fa-sync",
                            onPlaced: function(e) {
                                e.iconElement.addEventListener("click", function() {
                                    fvitem.resetField(e.field);
                                });
                            },
                        }),
                    },
                })
            .on("core.validator.validated", function(e) {
                if ((e.field === "add_pwd" || e.field === "chng_pwd" || e.field === "pass" || e.field === "add_pass" || e.field === "code" || e.field === "passwd") && e.validator === "checkPassword") {
                    var score = e.result.meta.score;
                    const barre = document.querySelector("#passwordMeter_cont");
                    const width = (score < 0) ? score * -18 + "%" : "100%";
                    barre.style.width = width;
                    barre.classList.add("progress-bar", "progress-bar-striped", "progress-bar-animated", "bg-success");
                    barre.setAttribute("aria-valuenow", width);
                    if (score === null) {
                        barre.style.width = "100%";
                        barre.setAttribute("aria-valuenow", "100%");
                        barre.classList.replace("bg-success", "bg-danger");
                    } else {
                        barre.classList.replace("bg-danger", "bg-success");
                    }
                }
                if (e.field === "B1" && e.validator === "promise") {
                    if (e.result.valid && e.result.meta && e.result.meta.source) {
                        $("#ava_perso").removeClass("border-danger").addClass("border-success")
                    } else if (!e.result.valid) {
                        $("#ava_perso").addClass("border-danger")
                    }
                }
            });
            <?php if ($parametres): ?>
                <?php if (array_key_exists(1, $parametres)): ?>
                    <?=  $parametres[1] ?>
                <?php endif; ?>
            <?php endif; ?>
        })
    });
//]]>
</script>