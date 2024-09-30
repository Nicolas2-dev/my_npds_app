<?php

namespace App\Modules\Users\Sform;

use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Sform\SformManager;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Users\Sform\Traits\SformCheckTrait;
use App\Modules\Users\Sform\Traits\SformCharteTrait;
use App\Modules\Users\Sform\Traits\SformHiddenTrait;
use App\Modules\Users\Sform\Traits\SformConsentementTrait;

/**
 * Undocumented class
 */
class SformUserNewFinish 
{

    use SformCheckTrait, SformHiddenTrait, SformCharteTrait, SformConsentementTrait;

    /**
     * 
     */
    protected SformManager $sform;


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
    private function formulaire()
    {
        $this->sform->add_form_field_size(50);

        if (!is_null($user_avatar = Request::post('user_avatar'))) {
                
            $theme      = with(get_instance())->template();
            $theme_dir  = with(get_instance())->template_dir();
        
            $direktori_url  = site_url('assets/images/forum/avatar');
        
            if (method_exists(Theme::class, 'theme_image')) {
                if (Theme::theme_image('forum/avatar/blank.gif')) {
                    $direktori_url  = site_url('themes/'. $theme_dir .'/'. $theme .'/assets/images/forum/avatar');
                }
            } 
                
            $this->sform->add_extra(
                '<img class="img-thumbnail n-ava mb-2" src="' . $direktori_url .'/'. $user_avatar . '" align="top" title="" />'
            );
        }

        if (!is_null($uname = Request::post('uname', null))) {
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
        }
        
        if (!is_null($name = Request::post('name', null))) {
            $this->sform->add_field(
                'name', 
                __d('users', 'Identité'), 
                $name, 
                'text', 
                false, 
                60, 
                '', 
                ''
            );
        }
        
        if (!is_null($email = Request::post('email', null))) {
            $this->sform->add_field(
                'email', 
                __d('users', 'Véritable adresse Email'), 
                $email, 
                'text', 
                true, 
                60, 
                '', 
                ''
            );
        }

        if (!is_null($url = Request::post('url', null))) {
            $this->sform->add_field(
                'url',  
                __d('users', 'Page d\'accueil'), 
                '<a href="' . $url . '" target="_blank">' . $url . '</a>', 
                'text', 
                false, 
                100, 
                '', 
                ''
            );
        }
        
        if (!is_null($user_from = Request::post('user_from', null))) {
            $this->sform->add_field(
                'user_from', 
                __d('users', 'Localisation'), 
                $user_from, 
                'text', 
                false, 
                100, 
                '', 
                ''
            );
        }
        
        if (!is_null($user_occ = Request::post('user_occ', null))) {
            $this->sform->add_field(
                'user_occ', 
                __d('users', 'Votre activité'), 
                $user_occ, 
                'text', 
                false, 
                100, 
                '', 
                ''
            );
        }
        
        if (!is_null($user_intrest = Request::post('user_intrest', null))) {
            $this->sform->add_field(
                'user_intrest', 
                __d('users', 'Centres d\'interêt'), 
                $user_intrest, 
                'text', 
                false, 
                150, 
                '', 
                ''
            );
        }
        
        if (!is_null($bio = Request::post('bio', null))) {
            $this->sform->add_field(
                'bio', 
                __d('users', 'Informations supplémentaires'), 
                $bio, 
                'textarea', 
                false, 
                255, 
                7, 
                '', 
                ''
            );
        }
        
        if (!is_null($user_sign = Request::post('user_sig', null))) {
            $this->sform->add_field(
                'user_sig', 
                __d('users', 'Signature'), 
                stripslashes($user_sign), 
                'textarea', 
                false, 
                255, 
                '', 
                ''
            );
        }
        
        $ch_lat = Config::get('geoloc.config.ch_lat');
        $ch_lon = Config::get('geoloc.config.ch_lon');

        if (!is_null($latitude = Request::post($ch_lat, null))) {
            $this->sform->add_field(
                $ch_lat, 
                'Latitude', 
                $latitude, 
                'text', 
                false, 
                100, 
                '', 
                '', 
                ''
            );
        }
        
        if (!is_null($longitude = Request::post($ch_lon, null))) {
            $this->sform->add_field(
                $ch_lon, 
                'Longitude', 
                $longitude, 
                'text', 
                false, 
                100, 
                '', 
                '', 
                ''
            );
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function display()
    {
        //
        $this->formulaire();

        //
        return $this->sform->aff_response('', 'not_echo');
    }

}
