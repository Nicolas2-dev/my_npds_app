<?php

namespace Modules\Users\Sform\Traits;

/**
 * Undocumented trait
 */
trait SformConsentementTrait
{

    /**
     * Undocumented function
     *
     * @return void
     */
    public function consentement()
    {
        // CONSENTEMENT
        $this->sform->add_checkbox(
            'consent', 
            __d('users', 'En soumettant ce formulaire j\'accepte que les informations saisies soient exploit&#xE9;es dans le cadre de l\'utilisation et du fonctionnement de ce site.'), 
            "1", 
            true, 
            false
        );
        // CONSENTEMENT
    }

}
