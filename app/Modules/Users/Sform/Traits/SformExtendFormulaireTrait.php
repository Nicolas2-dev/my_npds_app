<?php

namespace App\Modules\Users\Sform\Traits;

use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Facades\Language;

/**
 * Undocumented trait
 */
trait SformExtendFormulaireTrait
{

    /**
     * Undocumented function
     *
     * @return void
     */
    public function extend_formulaire()
    {
        if (!is_null($this->user)) {
            $users_extend = DB::table('users_extend')
                            ->select('C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7', 'C8', 'M1', 'M2', 'T1', 'T2', 'B1')
                            ->where('uid', $this->user['uid'])
                            ->first();
        } else {
            $users_extend = [];
        }

        $ch_lat = Config::get('geoloc.config.ch_lat');
        $ch_lon = Config::get('geoloc.config.ch_lon');

        $this->sform->add_comment(
            '<div class="row">
                <p class="lead">
                    '. __d('users', 'En savoir plus') .'
                </p>
            </div>'
        );
        
        $this->sform->add_field(
            'C1',
            __d('users', 'Activité professionnelle'), 
            (!is_null($this->user) ? $users_extend['C1'] : Request::post('C1')),
            'text', 
            false, 
            100, 
            '', 
            ''
        );
        
        $this->sform->add_extender(
            'C1', 
            '', 
            '<span class="help-block text-end" id="countcar_C1"></span>'
        );
        
        $this->sform->add_field(
            'C2', 
            __d('users', 'Code postal'), 
            (!is_null($this->user) ? $users_extend['C2'] : Request::post('C2')), 
            'text', 
            false, 
            5, 
            '', 
            ''
        );
        
        $this->sform->add_extender(
            'C2',
            '', 
            '<span class="help-block text-end" id="countcar_C2"></span>'
        );
        
        $this->sform->add_date(
            'T1', 
            __d('users', 'Date de naissance'), 
            (!is_null($this->user) ? $users_extend['T1'] : Request::post('T1')),
            'text', 
            '', 
            false, 
            20
        );
        
        $this->sform->add_extender(
            'T1', 
            '', 
            '<span class="help-block">JJ/MM/AAAA</span>'
        );
        
        $this->sform->add_field(
            'M2', 
            "Réseaux sociaux", 
            (!is_null($this->user) ? $users_extend['M2'] : Request::post('M2')),
            'hidden', 
            false
        );

        $ch_lat = Config::get('geoloc.config.ch_lat');
        $ch_lon = Config::get('geoloc.config.ch_lon');
        
        $this->sform->add_comment(
            '<div class="row">
                <p class="lead">
                <a href="'. site_url('modules.php?ModPath=geoloc&amp;ModStart=geoloc') .'">
                    <i class="fas fa-map-marker-alt fa-2x" title="'. __d('users', 'Modifier ou définir votre position') .'" data-bs-toggle="tooltip" data-bs-placement="right"></i>
                    </a>
                    &nbsp;'. __d('users', 'Géolocalisation') .'
                </p>
            </div>'
        );
        
        $this->sform->add_field(
            $ch_lat, 
            __d('users', 'Latitude'), 
            (!is_null($this->user) ? $users_extend[$ch_lat] : Request::post($ch_lat)),
            'text', 
            false, 
            '', 
            '', 
            'lat'
        );
        
        $this->sform->add_field(
            $ch_lon, 
            __d('users', 'Longitude'), 
            (!is_null($this->user) ? $users_extend[$ch_lon] : Request::post($ch_lon)),
            'text', 
            false, 
            '', 
            '', 
            'long'
        );
        
        // Les champ B1 et M2 sont utilisé par App dans le cadre des fonctions USERs
        // Si vous avez besoin d'un ou de champs ci-dessous - le(s) définir selon vos besoins et l'(les) enlever du tableau $fielddispo
        $fielddispo = array('C3', 'C4', 'C5', 'C6', 'C7', 'C8', 'M1', 'T2');
        $geofield   = array($ch_lat, $ch_lon);
        $fieldrest  = array_diff($fielddispo, $geofield);

        // reset($fieldrest);
        foreach ($fieldrest as $k => $v) {
            $this->sform->add_field($v, $v, '', 'hidden', false);
        }

        $this->sform->add_extra('
                <script type="text/javascript" src="'. site_url('assets/shared/flatpickr/dist/flatpickr.min.js') .'"></script>
                <script type="text/javascript" src="'. site_url('assets/shared/flatpickr/dist/l10n/' . Language::language_iso(1, '', '') . '.js') .'"></script>
                <script type="text/javascript">
                //<![CDATA[
                    $(document).ready(function() {
                        $("<link>").appendTo("head").attr({type: "text/css", rel: "stylesheet",href: "'. site_url("assets/shared/flatpickr/dist/themes/npds.css") .'"});
                    })
                //]]>
                </script>'
        );
    }

}
