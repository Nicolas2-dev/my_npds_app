<?php

namespace Modules\Users\Sform\Traits;

use Npds\view\View;
use Npds\Config\Config;
use Modules\Users\Support\Facades\Avatar;

/**
 * Undocumented trait
 */
trait SformAvatarTrait
{

    /**
     * 
     */
    public const TAILLE_FICHIER = 81920;


    /**
     * Undocumented function
     *
     * @return void
     */
    private function sform_avatar_user()
    {
        if (Config::get('npds.smilies')) {
            if (stristr($this->user['user_avatar'], "users_private")) {
                $this->sform->add_field(
                    'user_avatar', 
                    __d('users', 'Votre Avatar'), 
                    $this->user['user_avatar'], 
                    'show-hidden', 
                    false, 
                    30, 
                    '', 
                    ''
                );
                
                $this->sform->add_extender(
                    'user_avatar', 
                    '', 
                    '<img class="img-thumbnail n-ava" src="' . $this->user['user_avatar'] . '" name="avatar" alt="avatar" />
                        <span class="ava-meca lead"><i class="fa fa-angle-right fa-lg text-muted mx-3"></i></span>
                        <img class="ava-meca img-thumbnail n-ava" id="ava_perso" src="#" alt="Your next avatar" />'
                    );
        
            } else {

                list($filelist, $url) = Avatar::directory();

                foreach ($filelist as $key => $file) {
                    if (!preg_match('#\.gif|\.jpg|\.jpeg|\.png$#i', $file)) {
                        continue;
                    }
        
                    $tmp_tempo[$file]['en'] = $file;
        
                    if ($this->user['user_avatar'] == $file) {
                        $tmp_tempo[$file]['selected'] = true;
                    } else {
                        $tmp_tempo[$file]['selected'] = false;
                    }
                }
        
                $vatar_url = $url . '/' . $this->user['user_avatar'];

                $this->sform->add_select(
                    'user_avatar', 
                    __d('users', 'Votre Avatar'), 
                    $tmp_tempo, 
                    false, 
                    '', 
                    false
                );
                
                $this->sform->add_extender(
                    'user_avatar', 
                    'onkeyup="showimage();$(\'#avatar,#tonewavatar\').show();" onchange="showimage();$(\'#avatar,#tonewavatar\').show();"', 
                    '<div class="help-block">
                        <img class="img-thumbnail n-ava" src="' . $vatar_url . '" align="top" title="" />
                            <span id="tonewavatar" class="lead"><i class="fa fa-angle-right fa-lg text-muted mx-3"></i></span>
                            <img class="img-thumbnail n-ava " src="' . $vatar_url . '" name="avatar" id="avatar" align="top" title="Your next avatar" data-bs-placement="right" data-bs-toggle="tooltip" />
                            <span class="ava-meca lead"><i class="fa fa-angle-right fa-lg text-muted mx-3"></i></span>
                            <img class="ava-meca img-thumbnail n-ava" id="ava_perso" src="#" alt="your next avatar" title="Your next avatar" data-bs-placement="right" data-bs-toggle="tooltip" />
                    </div>'
                );
            }
        
            // Permet à l'utilisateur de télécharger un avatar (photo) personnel
            // - si vous mettez un // devant les deux lignes B1 et raz_avatar celà équivaut à ne pas autoriser cette fonction de App
            // - le champ B1 est impératif ! La taille maxi du fichier téléchargeable peut-être changée (le dernier paramètre) et est en octets (par exemple 20480 = 20 Ko)
            // - on a une incohérence la dimension de l'image est fixé dans les préférences du site et son poids ici....
        
            //$avatar_wh = explode('*', config('npds.avatar_size', '80*100'));
        
            $this->sform->add_upload(
                'B1', 
                '', 
                '30', 
                static::TAILLE_FICHIER
            );

            $this->sform->add_extender(
                'B1', 
                '', 
                '<span class="help-block text-end">
                    Taille maximum du fichier image :&nbsp;=>&nbsp;
                    <strong>' . static::TAILLE_FICHIER . '</strong> octets et <strong>' . Config::get('npds.avatar_size', '80*100') . '</strong> pixels</span>'
            );
            
            $this->sform->add_extra(
                '<div id="avatarPreview" class="preview"></div>'
            );
            
            $this->sform->add_checkbox(
                'raz_avatar', 
                __d('users', 'Revenir aux avatars standards'), 
                1, 
                false, 
                false
            );

            $this->sform->add_extra(
                View::make('Modules/Users/Views/Partials/Avatar/avatar_scrypt', ['user_avatar' => $this->user['user_avatar']])->fetch()
            );
        }        
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function sform_avatar_new_user()
    {
        if (Config::get('npds.smilies')) {
        
            list($filelist, $url) = Avatar::directory();
        
            foreach ($filelist as $key => $file) {
                if (!preg_match('#\.gif|\.jpg|\.png$#i', $file)) {
                    continue;
                }
        
                $tmp_tempo[$file]['en'] = $file;
                $tmp_tempo[$file]['selected'] = false;
        
                if ($file == 'blank.gif') {
                    $tmp_tempo[$file]['selected'] = true;
                }
            }
        
            $this->sform->add_select('user_avatar', 
                __d('users', 'Votre Avatar'), 
                $tmp_tempo, 
                false, 
                '', 
                false
            );
            
            $this->sform->add_extender(
                'user_avatar', 
                'onkeyup="showimage();" onchange="showimage();"', 
                '<img class="img-thumbnail n-ava mt-3" src="' . $url . '/blank.gif" name="avatar" alt="avatar" />'
            );
            
            $this->sform->add_field(
                'B1', 
                'B1', 
                '', 
                'hidden', 
                false
            );
        }
    }

}
