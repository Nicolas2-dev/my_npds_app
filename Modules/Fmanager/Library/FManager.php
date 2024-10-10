<?php

namespace Modules\Fmanager\Library;

use Modules\Fmanager\Contracts\FmanagerInterface;



class FManager implements FmanagerInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * Gestion Ascii étendue
     *
     * @param [type] $ibid
     * @return void
     */
    public function extend_ascii($ibid)
    {
        $tmp = urlencode($ibid);
        $tmp = str_replace("%82", "È", $tmp);
        $tmp = str_replace("%85", "‡", $tmp);
        $tmp = str_replace("%87", "Á", $tmp);
        $tmp = str_replace("%88", "Í", $tmp);
        $tmp = str_replace("%97", "˘", $tmp);
        $tmp = str_replace("%8A", "Ë", $tmp);
        $tmp = urldecode($tmp);
    
        return $tmp;
    }
 
    /**
     * Gestion des fichiers autorisés
     *
     * @param [type] $type
     * @param [type] $filename
     * @param [type] $Extension
     * @return void
     */
    public function fma_filter($type, $filename, $Extension)
    {
        $autorise = false;
        $error = '';

        if ($type == "f") {
            $filename = removeHack($filename);
        }

        $filename = preg_replace('#[/\\\:\*\?"<>|]#i', '', rawurldecode($filename));
        $filename = str_replace('..', '', $filename);

        // Liste des extensions autorisées
        $suffix = strtoLower(substr(strrchr($filename, '.'), 1));

        if (($suffix != '') or ($type == 'd')) {
            if ((in_array($suffix, $Extension)) or ($Extension[0] == '*') or $type == 'd') {

                // Fichiers interdits en fonction de qui est connecté
                if (fma_autorise($type, $filename)) {
                    $autorise = true;
                } else {
                    $error = __d('fmanager', 'Fichier interdit');
                }
            } else {
                $error = __d('fmanager', 'Type de fichier interdit');
            }
        } else {
            $error = __d('fmanager', 'Fichier interdit');
        }

        $tab[] = $autorise;
        $tab[] = $error;
        $tab[] = $filename;

        return $tab;
    }
 
    /**
     * Gestion des autorisations sur les répertoires et les fichiers
     *
     * @param [type] $type
     * @param [type] $dir
     * @return void
     */
    public function fma_autorise($type, $dir)
    {
        global $user, $admin, $dirlimit_fma, $ficlimit_fma, $access_fma, $dir_minuscptr, $fic_minuscptr;

        $autorise_arbo = false;

        if ($type == 'a') {
            $autorise_arbo = $access_fma;
        }

        if ($type == 'd') {
            if (is_array($dirlimit_fma)) {
                if (array_key_exists($dir, $dirlimit_fma)) {
                    $autorise_arbo = $dirlimit_fma[$dir];
                }
            }
        }

        if ($type == 'f') {
            if (is_array($ficlimit_fma)) {
                if (array_key_exists($dir, $ficlimit_fma)) {
                    $autorise_arbo = $ficlimit_fma[$dir];
                }
            }
        }

        if ($autorise_arbo) {
            $auto_dir = '';

            if (($autorise_arbo == 'membre') and ($user)) {
                $auto_dir = true;
            } elseif (($autorise_arbo == 'anonyme') and (!$user)) {
                $auto_dir = true;
            } elseif (($autorise_arbo == 'admin') and ($admin)) {
                $auto_dir = true;
            } elseif (($autorise_arbo != 'membre') and ($autorise_arbo != 'anonyme') and ($autorise_arbo != 'admin') and ($user)) {

                $tab_groupe = valid_group($user);

                if ($tab_groupe) {
                    foreach ($tab_groupe as $groupevalue) {

                        $tab_auto = explode(',', $autorise_arbo);

                        foreach ($tab_auto as $gp) {
                            if ($gp > 0) {
                                if ($groupevalue == $gp) {
                                    $auto_dir = true;
                                    break;
                                }
                            } else {
                                $auto_dir = true;

                                if (-$groupevalue == $gp) {
                                    $auto_dir = false;
                                    break;
                                }
                            }
                        }

                        if ($auto_dir) {
                            break;
                        }
                    }
                }
            }
        } else {
            $auto_dir = true;
        }

        if ($auto_dir != true) {
            if ($type == 'd') {
                $dir_minuscptr++;
            }

            if ($type == 'f') {
                $fic_minuscptr++;
            }
        }

        return ($auto_dir);
    }

    /**
     * Undocumented function
     *
     * @param [type] $ibid
     * @param [type] $champ
     * @return void
     */
    public function chmod_pres($ibid, $champ)
    {
        $sel = '';

        if ($ibid[0] == 400) {
            $sel = "selected=\"selected\"";
        } else {
            $sel = '';
        }

        $chmod = "<option name=\"$champ\" value=\"400\" $sel> 400 (r--------)</option>";

        if ($ibid[0] == 444) {
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"444\" $sel> 444 (r-x------)</option>";

        if ($ibid[0] == 500) {
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"500\" $sel> 500 (r--------)</option>";

        if ($ibid[0] == 544) {
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"544\" $sel> 544 (r-xr--r--)</option>";

        if ($ibid[0] == 600){ 
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"600\" $sel> 600 (rw-------)</option>";

        if ($ibid[0] == 644) {
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"644\" $sel> 644 (rw-r--r--)</option>";

        if ($ibid[0] == 655) {
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"655\" $sel> 655 (rw-r-xr-x)</option>";

        if ($ibid[0] == 666) {
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"666\" $sel> 666 (rw-rw-rw-)</option>";

        if ($ibid[0] == 700) { 
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"700\" $sel> 700 (rwx------)</option>";

        if ($ibid[0] == 744) {
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"744\" $sel> 744 (rwxr--r--)</option>";

        if ($ibid[0] == 755) {
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"755\" $sel> 755 (rwxr-xr-x)</option>";

        if ($ibid[0] == 766) {
            $sel = "selected=\"selected\"";
        }  else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"766\" $sel> 766 (rwxrw-rw-)</option>";

        if ($ibid[0] == 777) {
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }

        $chmod .= "<option name=\"$champ\" value=\"777\" $sel> 777 (rwxrwxrwx)</option>";
        $chmod .= "</select>";

        return $chmod;
    }

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @param [type] $Max_thumb
     * @return void
     */
    public function imagesize($name, $Max_thumb)
    {
        $size = getimagesize($name);
    
        $h_i = $size[1]; //hauteur
        $w_i = $size[0]; //largeur
    
        if (($h_i > $Max_thumb) || ($w_i > $Max_thumb)) {
            if ($h_i > $w_i) {
                $convert = $Max_thumb / $h_i;
                $h_i = $Max_thumb;
                $w_i = ceil($w_i * $convert);
            } else {
                $convert = $Max_thumb / $w_i;
                $w_i = $Max_thumb;
                $h_i = ceil($h_i * $convert);
            }
        }
    
        $s_img['hauteur'][0] = $h_i;
        $s_img['hauteur'][1] = $size[1];
    
        $s_img['largeur'][0] = $w_i;
        $s_img['largeur'][1] = $size[0];
    
        return $s_img;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $Image
     * @param [type] $Source
     * @param [type] $Destination
     * @param [type] $Max
     * @param [type] $ext
     * @return void
     */
    public function CreateThumb($Image, $Source, $Destination, $Max, $ext)
    {
        switch ($ext) {
            case (preg_match('/jpeg|jpg/i', $ext) ? true : false):
                if (function_exists('imagecreatefromjpeg')) {
                    $src = @imagecreatefromjpeg($Source . $Image);
                }
                break;
    
            case (preg_match('/gif/i', $ext) ? true : false):
                if (function_exists('imagecreatefromgif')) {
                    $src = @imagecreatefromgif($Source . $Image);
                }
                break;
    
            case (preg_match('/png/i', $ext) ? true : false):
                if (function_exists('imagecreatefrompng')) {
                    $src = @imagecreatefrompng($Source . $Image);
                }
                break;
        }
    
        $size = imagesize($Source . '/' . $Image, $Max);
    
        $h_i = $size['hauteur'][0]; //hauteur
        $w_i = $size['largeur'][0]; //largeur
    
        if ($src) {
            if (function_exists('imagecreatetruecolor')) {
                $im = @imagecreatetruecolor($w_i, $h_i);
            } else {
                $im = @imagecreate($w_i, $h_i);
            }
    
            @imagecopyresized($im, $src, 0, 0, 0, 0, $w_i, $h_i, $size['largeur'][1], $size['hauteur'][1]);
            @imageinterlace($im, 1);
    
            switch ($ext) {
                case (preg_match('/jpeg|jpg/i', $ext) ? true : false):
                    @imagejpeg($im, $Destination . $Image, 100);
                    break;
    
                case (preg_match('/gif/i', $ext) ? true : false):
                    @imagegif($im, $Destination . $Image);
                    break;
    
                case (preg_match('/png/i', $ext) ? true : false):
                    @imagepng($im, $Destination . $Image, 6);
                    break;
            }
    
            @chmod($Destination . $Image, 0766);
            $size['gene-img'][0] = true;
        }
    
        return $size;
    }

}
