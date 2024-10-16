<?php

namespace Modules\Npds\Library;

use Modules\Npds\Contracts\PixelInterface;



class PixelManager implements PixelInterface 
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
     * [dataimagetofileurl description]
     *
     * @param   [type]  $base_64_string  [$base_64_string description]
     * @param   [type]  $output_path     [$output_path description]
     *
     * @return  [type]                   [return description]
     */
    public function dataimagetofileurl($base_64_string, $output_path)
    {
        $rechdataimage = '#src=\\\"(data:image/[^"]+)\\\"#m';
    
        preg_match_all($rechdataimage, $base_64_string, $dataimages);
    
        $j = 0;
        $timgw = 800;
        $timgh = 600;
        $ra = rand(1, 999);
    
        foreach ($dataimages[1] as $imagedata) {
            $datatodecode = explode(',', $imagedata);
            $bin = base64_decode($datatodecode[1]);
            $im = imageCreateFromString($bin);
    
            if (!$im) {
                die('Image non valide');
            }
    
            $size = getImageSizeFromString($bin);
            $ext = substr($size['mime'], 6);
    
            if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
                die('Image non supportée');
            }
    
            $output_file = $output_path . $j . "_" . $ra . "_" . time() . "." . $ext;
            $base_64_string = preg_replace($rechdataimage, 'class="img-fluid" src="' . $output_file . '" loading="lazy"', $base_64_string, 1);
    
            if ($size[0] > $timgw or $size[1] > $timgh) {
                $timgh = round(($timgw / $size[0]) * $size[1]);
                $timgw = round(($timgh / $size[1]) * $size[0]);
                $th = imagecreatetruecolor($timgw, $timgh);
                imagecopyresampled($th, $im, 0, 0, 0, 0, $timgw, $timgh, $size[0], $size[1]);
                $args = [$th, $output_file];
            } else {
                $args = [$im, $output_file];
            }
    
            if ($ext == 'png') {
                $args[] = 0;
            } elseif ($ext == 'jpeg') {
                $args[] = 100;
            }
    
            $fonc = "image{$ext}";
            call_user_func_array($fonc, $args);

            $j++;
        }
        
        return $base_64_string;
    } 

}
