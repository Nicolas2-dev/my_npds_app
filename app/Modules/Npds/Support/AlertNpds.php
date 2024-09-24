<?php

namespace App\Modules\Npds\Support;

use Npds\view\View;
use Npds\Config\Config;
use Npds\Support\Facades\DB;

/**
 * Undocumented class
 */
class AlertNpds
{

    /**
     * [$support_url description]
     *
     * @var [type]
     */
    private static $support_url = 'https://raw.githubusercontent.com/npds/npds_dune/master/versus.txt';

    /**
     * [$messages_npds description]
     *
     * @var [type]
     */
    private static $messages_npds;

    
    /**
     * [read_versus description]
     *
     * @return  [type]  [return description]
     */
    private static function read_versus()
    {
        $messagerie_npds = file_get_contents(static::$support_url);
        
        static::$messages_npds = explode("\n", $messagerie_npds);

        array_pop(static::$messages_npds);
    }

    /**
     * [$SAQ description]
     *
     * @var [type]
     */
    public static function check($SAQ, $aid, $adminico)
    {
        $arraylecture = explode('|', $SAQ['fdroits1_descr']);

        $bloc_foncts_A = '';

        if ($SAQ['fcategorie'] == 9) {
            if (preg_match('#messageModal#', $SAQ['furlscript'])) {
                $furlscript = 'data-bs-toggle="modal" data-bs-target="#bl_messageModal"';
            }

            if (preg_match('#mes_npds_\d#', $SAQ['fnom'])) {
                if (!in_array($aid, $arraylecture, true)) {

                    $bloc_foncts_A .= View::make('Modules/Npds/Views/Alerte/AlerteRow',
                        [
                            'mes_npds'      => true,
                            'SAQ'           => $SAQ,
                            'adminico'      => $adminico,
                            'furlscript'    => $furlscript,
                        ]
                    );
                }
            } else {
                $furlscript = preg_match('#versusModal#', $SAQ['furlscript']) 
                    ? 'data-bs-toggle="modal" data-bs-target="#bl_versusModal"' 
                    : $SAQ['furlscript'];

                if (preg_match('#Npds#', $SAQ['fretour_h'])) {
                    $SAQ['fretour_h'] = str_replace('Npds', 'Npds^', $SAQ['fretour_h']);
                }

                $bloc_foncts_A .= View::make('Modules/Npds/Views/Alerte/AlerteRow',
                    [
                        'versusModal'   => true,
                        'SAQ'           => $SAQ,
                        'adminico'      => $adminico,
                        'furlscript'    => $furlscript,
                    ]
                );
            }
        } 
        
        return $bloc_foncts_A;
    }

    /**
     * [update_versus description]
     *
     * @return  [type]  [return description]
     */
    private static function update_versus()
    {
        // traitement specifique car fonction permanente versus
        $versus_info = explode('|', static::$messages_npds[0]);

        if ($versus_info[1] == Config::get('npds.Version_Sub') 
        and $versus_info[2] == Config::get('npds.Version_Num')) 
        {
            DB::table('fonctions')->where('fid', 36)->update(
                [
                    'fetat'         => 1,
                    'fretour'       => '',
                    'fretour_h'     => 'Version Npds ' . Config::get('npds.Version_Sub') . ' ' . Config::get('npds.Version_Num'), 
                    'furlscript'    => '', 
                ]
            );
        } else {
            DB::table('fonctions')->where('fid', 36)->update(
                [
                    'fetat'         => 1, 
                    'fretour'       => 'N',
                    'fretour_h'     => 'Une nouvelle version Npds est disponible !<br />' . $versus_info[1] . ' ' . $versus_info[2] . '<br />Cliquez pour télécharger.', 
                    'furlscript'    => 'data-bs-toggle="modal" data-bs-target="#versusModal"', 
                ]
            );
        }
        
        return $versus_info;
    }

    /**
     * [display description]
     *
     * @return  [type]  [return description]
     */
    public static function display()
    {
        //
        static::read_versus();

        return View::make('Modules/Npds/Views/Alerte/AlerteNpds', 
            [
                'Version_Sub' => config::get('npds.Version_Sub'),
                'Version_Num' => config::get('npds.Version_Num'),
                'versus_info' => static::update_versus(),
            ]
        );
    }

}
