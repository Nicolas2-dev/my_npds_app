<?php

namespace App\Modules\Npds\Support;

use Npds\view\View;
use Npds\Config\Config;
use Npds\Support\Facades\DB;

use DirectoryIterator;

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
     * Undocumented variable
     *
     * @var [type]
     */
    private static $aid;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private static $radminsuper;

    
    /**
     * [read_versus description]
     *
     * @return  [type]  [return description]
     */
    private static function read_versus()
    {
        // recuperation traitement des messages de Npds
        $QM = sql_query("SELECT * FROM fonctions WHERE fnom REGEXP'mes_npds_[[:digit:]]'");
            
        // settype($f_mes, 'array');

        while ($SQM = sql_fetch_assoc($QM)) {
            $f_mes[] = $SQM['fretour_h'];
        }

        $messagerie_npds = file_get_contents(static::$support_url);
        
        static::$messages_npds = explode("\n", $messagerie_npds);

        array_pop(static::$messages_npds);

        // traitement specifique car message permanent versus
        $versus_info = explode('|', static::$messages_npds[0]);
        
        if ($versus_info[1] == Config::get('npds.Version_Sub') and $versus_info[2] == Config::get('npds.Version_Num')) {
            sql_query("UPDATE fonctions SET fetat='1', fretour='', fretour_h='Version Npds " . Config::get('npds.Version_Sub') . " " . Config::get('npds.Version_Num') . "', furlscript='' WHERE fid='36'");
        } else {
            sql_query("UPDATE fonctions SET fetat='1', fretour='N', furlscript='data-bs-toggle=\"modal\" data-bs-target=\"#versusModal\"', fretour_h='Une nouvelle version Npds est disponible !<br />" . $versus_info[1] . " " . $versus_info[2] . "<br />Cliquez pour télécharger.' WHERE fid='36'");
        }

        $mess = array_slice(static::$messages_npds, 1);

        if (empty($mess)) {
            // si pas de message on nettoie la base
            sql_query("DELETE FROM fonctions WHERE fnom REGEXP'mes_npds_[[:digit:]]'");
            sql_query("ALTER TABLE fonctions AUTO_INCREMENT = (SELECT MAX(fid)+1 FROM fonctions)");
        } else {
            $fico = '';
    
            $o = 0;
            foreach ($mess as $v) {
                $ibid = explode('|', $v);
                $fico = $ibid[0] != 'Note' ? 'message_npds_a' : 'message_npds_i';
    
                $QM = sql_num_rows(sql_query("SELECT * FROM fonctions WHERE fnom='mes_npds_" . $o . "'"));
                
                if ($QM === false) {
                    sql_query("INSERT INTO fonctions (fnom,fretour_h,fcategorie,fcategorie_nom,ficone,fetat,finterface,fnom_affich,furlscript) VALUES ('mes_npds_" . $o . "','" . addslashes($ibid[1]) . "','9','Alerte','" . $fico . "','1','1','" . addslashes($ibid[2]) . "','data-bs-toggle=\"modal\" data-bs-target=\"#messageModal\");\n");
                }

                $o++;
            }
        }
    
        // si message on compare avec la base
        if ($mess) {
            $fico = '';
    
            for ($i = 0; $i < count($mess); $i++) {
                $ibid = explode('|', $mess[$i]);
                $fico = $ibid[0] != 'Note' ? 'message_a' : 'message_i';
    
                //si on trouve le contenu du fichier dans la requete
                if (in_array($ibid[1], $f_mes, true)) {
                    $k = (array_search($ibid[1], $f_mes));
    
                    unset($f_mes[$k]);
    
                    $result = sql_query("SELECT fnom_affich FROM fonctions WHERE fnom='mes_npds_$i'");
    
                    if (sql_num_rows($result) == 1) {
                        $alertinfo = sql_fetch_assoc($result);
    
                        if ($alertinfo['fnom_affich'] != $ibid[2]) {
                            sql_query('UPDATE fonctions SET fdroits1_descr="", fnom_affich="' . addslashes($ibid[2]) . '" WHERE fnom="mes_npds_' . $i . '"');
                        }
                    }
                } else {
                    sql_query('REPLACE fonctions SET fnom="mes_npds_' . $i . '",fretour_h="' . $ibid[1] . '",fcategorie="9", fcategorie_nom="Alerte", ficone="' . $fico . '",fetat="1", finterface="1", fnom_affich="' . addslashes($ibid[2]) . '", furlscript="data-bs-toggle=\"modal\" data-bs-target=\"#messageModal\"",fdroits1_descr=""');
                }
            }
    
            if (count($f_mes) !== 0) {
                foreach ($f_mes as $v) {
                    sql_query('DELETE from fonctions where fretour_h="' . $v . '" and fcategorie="9"');
                }
            }
        }
    }

    /**
     * [$SAQ description]
     *
     * @var [type]
     */
    public static function check($SAQ, $adminico, $aid, $radminsuper)
    {
        //
        static::read_versus();

        static::$aid = $aid;

        static::$radminsuper = $radminsuper;

        $arraylecture = explode('|', $SAQ['fdroits1_descr']);

        $bloc_foncts_A = '';

        if ($SAQ['fcategorie'] == 9) {
            if (preg_match('#messageModal#', $SAQ['furlscript'])) {
                $furlscript = 'data-bs-toggle="modal" data-bs-target="#bl_messageModal"';
            }

            if (preg_match('#mes_npds_\d#', $SAQ['fnom'])) {
                if (!in_array(static::$aid, $arraylecture, true)) {

                    $bloc_foncts_A .= View::make('Modules/Npds/Views/Alerte/AlerteRow',
                        [
                            'mes_npds'      => true,
                            'SAQ'           => $SAQ,
                            'adminico'      => site_url($adminico),
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
                        'adminico'      => site_url($adminico),
                        'furlscript'    => $furlscript,
                    ]
                );
            }
        } 

        // 
        static::globals();

        // 
        static::modules();

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
     * Undocumented function
     *
     * @return void
     */
    public static function modules()
    {
        $alert_modules = sql_query("SELECT * FROM fonctions f LEFT JOIN modules m ON m.mnom = f.fnom WHERE m.minstall=1 AND fcategorie=9");
        
        if ($alert_modules) {
            while ($am = sql_fetch_array($alert_modules)) {
    
                include(module_path($am['fnom'] . "/admin/adm_alertes.php"));
    
                $nr = count($reqalertes);
                $i = 0;
    
                while ($i < $nr) {
                    $ibid = sql_num_rows(sql_query($reqalertes[$i][0]));
    
                    if ($ibid) {
                        $fr = $reqalertes[$i][1] != 1 ? $reqalertes[$i][1] : $ibid;
                        
                        sql_query("UPDATE fonctions SET fetat='1',fretour='" . $fr . "', fretour_h='" . $reqalertes[$i][2] . "' WHERE fid=" . $am['fid'] . "");
                    } else {
                        sql_query("UPDATE fonctions SET fetat='0',fretour='' WHERE fid=" . $am['fid'] . "");
                    }
                    $i++;
                }
            }
        }        
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function globals()
    {
        //==> recupérations des états des fonctions d'ALERTE ou activable et maj (faire une fonction avec cache court dev ..)
                
        // article à valider
        $newsubs = sql_num_rows(sql_query("SELECT qid FROM queue"));
            
        if ($newsubs) {
            sql_query("UPDATE fonctions SET fetat='1',fretour='" . $newsubs . "',fretour_h='" . __d('npds', 'Articles en attente de validation !') . "' WHERE fid='38'");
        } else {
            sql_query("UPDATE fonctions SET fetat='0',fretour='0' WHERE fid='38'");
        }
            

        // news auto
        $newauto = sql_num_rows(sql_query("SELECT anid FROM autonews"));

        if ($newauto) {
            sql_query("UPDATE fonctions SET fetat='1',fretour='" . $newauto . "',fretour_h='" . __d('npds', 'Articles programmés pour la publication.') . "' WHERE fid=37");
        } else {
            sql_query("UPDATE fonctions SET fetat='0',fretour='0',fretour_h='' WHERE fid=37");
        }


        // etat filemanager
        if (Config::get('filemanager.filemanager')) {
            sql_query("UPDATE fonctions SET fetat='1' WHERE fid='27'");
        } else {
            sql_query("UPDATE fonctions SET fetat='0' WHERE fid='27'");
        }

        // utilisateur à valider
        $newsuti = sql_num_rows(sql_query("SELECT uid FROM users_status WHERE uid!='1' AND open='0'"));

        if ($newsuti) {
            sql_query("UPDATE fonctions SET fetat='1',fretour='" . $newsuti . "',fretour_h='" . __d('npds', 'Utilisateur en attente de validation !') . "' WHERE fid='44'");
        } else { 
            sql_query("UPDATE fonctions SET fetat='0',fretour='0' WHERE fid='44'");
        }


        // référants à gérer
        if (Config::get('npds.httpref') == 1) {
            $result = sql_fetch_assoc(sql_query("SELECT COUNT(*) AS total FROM referer"));

            if ($result['total'] >= Config::get('npds.httprefmax')) {
                sql_query("UPDATE fonctions set fetat='1', fretour='!!!' WHERE fid='39'");
            } else {
                sql_query("UPDATE fonctions SET fetat='0' WHERE fid='39'");
            }
        }

        // critique en attente
        $critsubs = sql_num_rows(sql_query("SELECT * FROM reviews_add"));

        if ($critsubs) {
            sql_query("UPDATE fonctions SET fetat='1',fretour='" . $critsubs . "', fretour_h='" . __d('npds', 'Critique en attente de validation.') . "' WHERE fid='35'");
        } else {
            sql_query("UPDATE fonctions SET fetat='0',fretour='0' WHERE fid='35'");
        }


        // nouveau lien à valider
        $newlink = sql_num_rows(sql_query("SELECT * FROM links_newlink"));

        if ($newlink) {
            sql_query("UPDATE fonctions SET fetat='1',fretour='" . $newlink . "', fretour_h='" . __d('npds', 'Liens à valider.') . "' WHERE fid='41'");
        } else {
            sql_query("UPDATE fonctions SET fetat='0',fretour='0' WHERE fid='41'");
        }


        // lien rompu à valider
        $brokenlink = sql_num_rows(sql_query("SELECT * FROM links_modrequest where brokenlink='1'"));

        if ($brokenlink) {
            sql_query("UPDATE fonctions SET fetat='1',fretour='" . $brokenlink . "', fretour_h='" . __d('npds', 'Liens rompus à valider.') . "' WHERE fid='42'");
        } else {
            sql_query("UPDATE fonctions SET fetat='0',fretour='0' WHERE fid='42'");
        }

        // nouvelle publication
        // $newpubli = $Q['radminsuper'] == 1 ?
        $newpubli = static::$radminsuper == 1 ?
            sql_num_rows(sql_query("SELECT * FROM seccont_tempo")) :
            sql_num_rows(sql_query("SELECT * FROM seccont_tempo WHERE author='". static::$aid ."'"));

        if ($newpubli) {
            sql_query("UPDATE fonctions SET fetat='1',fretour='" . $newpubli . "', fretour_h='" . __d('npds', 'Publication(s) en attente de validation') . "' WHERE fid='50'");
        } else {
            sql_query("UPDATE fonctions SET fetat='0',fretour='0' WHERE fid='50'");
        }


        // utilisateur(s) en attente de groupe
        $directory = module_path("Users/storage/users_private/groupe");
        $iterator = new DirectoryIterator($directory);

        $j = 0;

        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() and strpos($fileinfo->getFilename(), 'ask4group') !== false)
                $j++;
        }

        if ($j > 0){
            sql_query("UPDATE fonctions SET fetat='1',fretour='" . $j . "',fretour_h='" . __d('npds', 'Utilisateur en attente de groupe !') . "' WHERE fid='46'");
        } else {
            sql_query("UPDATE fonctions SET fetat='0',fretour='0' WHERE fid='46'");
        }
        //<== etc...etc recupérations des états des fonctions d'ALERTE et maj

    }


    /**
     * [display description]
     *
     * @return  [type]  [return description]
     */
    public static function display()
    {
        return View::make('Modules/Npds/Views/Alerte/AlerteNpds', 
            [
                'Version_Sub' => config::get('npds.Version_Sub'),
                'Version_Num' => config::get('npds.Version_Num'),
                'versus_info' => static::update_versus(),
            ]
        );
    }

}
