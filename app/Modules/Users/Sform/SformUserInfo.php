<?php

namespace App\Modules\Users\Sform;

use Npds\Config\Config;
use Npds\Sform\SformManager;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Hack;

/**
 * Undocumented class
 */
class SformUserInfo 
{

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
    public function __construct($user_temp)
    {
        $this->sform = new SformManager();

        $this->user = $user_temp;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function formulaire()
    {
        $this->sform->add_form_fields_globals($this->user);

        $this->sform->add_form_field_size(50);

        if (Auth::guard('user')) {

            $uname = stripslashes(Hack::remove($this->user['uname']));

            $act_uname = '<a href="'. site_url('powerpack.php?op=instant_message&amp;to_userid="'. $uname) .'" title="' . __d('users', 'Envoyer un message interne') . '">
                '. $uname .'
            </a>';

            $this->sform->add_form_fields_globals(['act_uname' => $act_uname]);

            $this->sform->add_field(
                'act_uname', 
                __d('users', 'ID utilisateur (pseudo)'), 
                $act_uname, 
                'text', 
                true, 
                25, 
                '', 
                ''
            );
        } else {
            $this->sform->add_field(
                'uname', 
                __d('users', 'ID utilisateur (pseudo)'), 
                stripslashes(Hack::remove($this->user['uname'])), 
                'text', 
                true, 
                25, 
                '', 
                ''
            );
        }
        
        if ($name = stripslashes(Hack::remove($this->user['name']))) {
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
        
        if ($email = stripslashes(Hack::remove($this->user['femail']))) {
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

        if ($url = stripslashes(Hack::remove($this->user['url']))) {
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
        
        if ($user_from = stripslashes(Hack::remove($this->user['user_from']))) {
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
        
        if ($user_occ = stripslashes(Hack::remove($this->user['user_occ']))) {
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
        
        if ($user_intrest = stripslashes(Hack::remove($this->user['user_intrest']))) {
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
        
        if ($bio = stripslashes(Hack::remove($this->user['bio']))) {
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

        $ch_lat = Config::get('geoloc.config.ch_lat');
        $ch_lon = Config::get('geoloc.config.ch_lon');

        if ($latitude = $this->user[$ch_lat]) {
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
        
        if ($longitude = $this->user[$ch_lon]) {
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

        return $this->sform->aff_response('', 'not_echo');
    }

}
