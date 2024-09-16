<?php

namespace App\Modules\Blocks\Library;


use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Npds\Support\Facades\Language;
use App\Modules\Blocks\Contracts\BlockInterface;
use App\Modules\Npds\Library\Supercache\SuperCacheEmpty;
use App\Modules\Npds\Library\Supercache\SuperCacheManager;


class BlockManager implements BlockInterface 
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
     * [block_fonction description]
     *
     * @param   [type]  $title     [$title description]
     * @param   [type]  $contentX  [$contentX description]
     *
     * @return  [type]             [return description]
     */
    public function block_fonction($title, $contentX)
    {
        global $block_title;

        $block_title = $title;

        //For including PHP functions in block
        if (stristr($contentX, "function#")) {
            $contentX = str_replace('<br />', '', $contentX);
            $contentX = str_replace('<BR />', '', $contentX);
            $contentX = str_replace('<BR>', '', $contentX);
            $contentY = trim(substr($contentX, 9));

            if (stristr($contentY, "params#")) {
                $pos = strpos($contentY, "params#");
                $contentII = trim(substr($contentY, 0, $pos));
                $params = substr($contentY, $pos + 7);
                $prm = explode(',', $params);

                // Remplace le param "False" par la valeur false (idem pour True)
                for ($i = 0; $i <= count($prm) - 1; $i++) {
                    if ($prm[$i] == "false") {
                        $prm[$i] = false;
                    }

                    if ($prm[$i] == "true") {
                        $prm[$i] = true;
                    }
                }

                // En fonction du nombre de params de la fonction : limite actuelle : 8
                if (function_exists($contentII)) {
                    switch (count($prm)) {
                        case 1:
                            $contentII($prm[0]);
                            break;

                        case 2:
                            $contentII($prm[0], $prm[1]);
                            break;

                        case 3:
                            $contentII($prm[0], $prm[1], $prm[2]);
                            break;

                        case 4:
                            $contentII($prm[0], $prm[1], $prm[2], $prm[3]);
                            break;

                        case 5:
                            $contentII($prm[0], $prm[1], $prm[2], $prm[3], $prm[4]);
                            break;

                        case 6:
                            $contentII($prm[0], $prm[1], $prm[2], $prm[3], $prm[4], $prm[5]);
                            break;

                        case 7:
                            $contentII($prm[0], $prm[1], $prm[2], $prm[3], $prm[4], $prm[5], $prm[6]);
                            break;

                        case 8:
                            $contentII($prm[0], $prm[1], $prm[2], $prm[3], $prm[4], $prm[5], $prm[6], $prm[7]);
                            break;
                    }

                    return true;
                } else {
                    return false;
                }
            } else {
                if (function_exists($contentY)) {
                    $contentY();

                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * [fab_block description]
     *
     * @param   [type]  $title    [$title description]
     * @param   [type]  $member   [$member description]
     * @param   [type]  $content  [$content description]
     * @param   [type]  $Xcache   [$Xcache description]
     *
     * @return  [type]            [return description]
     */
    public function fab_block($title, $member, $content, $Xcache)
    {
        global $SuperCache, $CACHE_TIMINGS;

        // Multi-Langue
        $title = Language::aff_langue($title);

        // Bloc caché
        $hidden = false;

        if (substr($content, 0, 7) == "hidden#") {
            $content = str_replace("hidden#", '', $content);
            $hidden = true;
        }

        // Si on cherche à charger un JS qui a déjà été chargé par pages.php alors on ne le charge pas ...
        global $pages_js;

        if ($pages_js != '') {
            preg_match('#src="([^"]*)#', $content, $jssrc);

            if (is_array($pages_js)) {
                foreach ($pages_js as $jsvalue) {
                    if (array_key_exists('1', $jssrc)) {
                        if ($jsvalue == $jssrc[1]) {
                            $content = '';
                            break;
                        }
                    }
                }
            } else {
                if (array_key_exists('1', $jssrc)) {
                    if ($pages_js == $jssrc[1]) {
                        $content = "";
                    }
                }
            }
        }

        $content = Language::aff_langue($content);

        if (($SuperCache) and ($Xcache != 0)) {
            $cache_clef = md5($content);

            $CACHE_TIMINGS[$cache_clef] = $Xcache;

            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingBlock($cache_clef);
        } else {
            $cache_obj = new SuperCacheEmpty();
        }

        if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!$SuperCache) or ($Xcache == 0)) {

            global $user, $admin;

            // For including CLASS AND URI in Block
            global $B_class_title, $B_class_content;

            $B_class_title = '';
            $B_class_content = '';
            $R_uri = '';

            if (stristr($content, 'class-') or stristr($content, 'uri')) {
                $tmp = explode("\n", $content);
                $content = '';

                foreach ($tmp as $id => $class) {
                    $temp = explode("#", $class);

                    if ($temp[0] == "class-title") {
                        $B_class_title = str_replace("\r", "", $temp[1]);
                    } else if ($temp[0] == "class-content") {
                        $B_class_content = str_replace("\r", "", $temp[1]);
                    } else if ($temp[0] == "uri") {
                        $R_uri = str_replace("\r", '', $temp[1]);
                    } else {
                        if ($content != '') {
                            $content .= "\n ";
                        }

                        $content .= str_replace("\r", '', $class);
                    }
                }
            }

            // For BLOC URIs
            if ($R_uri) {
                global $REQUEST_URI;

                $page_ref = basename($REQUEST_URI);
                $tab_uri = explode(" ", $R_uri);
                $R_content = false;
                $tab_pref = parse_url($page_ref);
                $racine_page = $tab_pref['path'];

                if (array_key_exists('query', $tab_pref)) {
                    $tab_pref = explode('&', $tab_pref['query']);
                }

                foreach ($tab_uri as $RR_uri) {
                    $tab_puri = parse_url($RR_uri);
                    $racine_uri = $tab_puri['path'];

                    if ($racine_page == $racine_uri) {
                        if (array_key_exists('query', $tab_puri)) {
                            $tab_puri = explode('&', $tab_puri['query']);
                        }

                        foreach ($tab_puri as $idx => $RRR_uri) {
                            if (substr($RRR_uri, -1) == "*") {

                                // si le token contient *
                                if (substr($RRR_uri, 0, strpos($RRR_uri, "=")) == substr($tab_pref[$idx], 0, strpos($tab_pref[$idx], "="))) {
                                    $R_content = true;
                                }
                            } else {
                                if ($RRR_uri != $tab_pref[$idx]) {
                                    $R_content = false;
                                } else {
                                    $R_content = true;
                                }
                            }
                        }
                    }

                    if ($R_content == true) {
                        break;
                    }
                }

                if (!$R_content) {
                    $content = '';
                }
            }

            // For Javascript in Block
            if (!stristr($content, 'javascript')) {
                $content = nl2br($content);
            }

            // For including externale file in block / the return MUST BE in $content
            if (stristr($content, 'include#')) {
                $Xcontent = false;

                // You can now, include AND cast a fonction with params in the same bloc !
                if (stristr($content, "function#")) {
                    $content = str_replace('<br />', '', $content);
                    $content = str_replace('<BR />', '', $content);
                    $content = str_replace('<BR>', '', $content);
                    $pos = strpos($content, 'function#');
                    $Xcontent = substr(trim($content), $pos);
                    $content = substr(trim($content), 8, $pos - 10);
                } else {
                    $content = substr(trim($content), 8);
                }

                include_once($content);

                if ($Xcontent) {
                    $content = $Xcontent;
                }
            }

            if (!empty($content)) {
                if (($member == 1) and (isset($user))) {
                    if (!$this->block_fonction($title, $content)) {
                        if (!$hidden) {
                            Theme::themesidebox($title, $content);
                        } else {
                            echo $content;
                        }
                    }
                } elseif ($member == 0) {
                    if (!$this->block_fonction($title, $content)) {
                        if (!$hidden) {
                            Theme::themesidebox($title, $content);
                        } else {
                            echo $content;
                        }
                    }
                } elseif (($member > 1) and (isset($user))) {
                    $tab_groupe = valid_group($user);
                    if (groupe_autorisation($member, $tab_groupe)) {
                        if (!$this->block_fonction($title, $content)) {
                            if (!$hidden) {
                                Theme::themesidebox($title, $content);
                            } else {
                                echo $content;
                            }
                        }
                    }
                } elseif (($member == -1) and (!isset($user))) {
                    if (!$this->block_fonction($title, $content)) {
                        if (!$hidden) {
                            Theme::themesidebox($title, $content);
                        } else {
                            echo $content;
                        }
                    }
                } elseif (($member == -127) and (isset($admin)) and ($admin)) {
                    if (!$this->block_fonction($title, $content)) {
                        if (!$hidden) {
                            Theme::themesidebox($title, $content);
                        } else {
                            echo $content;
                        }
                    }
                }
            }

            if (($SuperCache) and ($Xcache != 0)) {
                $cache_obj->endCachingBlock($cache_clef);
            }
        }
    }

    /**
     * [leftblocks description]
     *
     * @param   [type]  $moreclass  [$moreclass description]
     *
     * @return  [type]              [return description]
     */
    public function leftblocks($moreclass)
    {
        $this->Pre_fab_block('', 'LB', $moreclass);
    }

    /**
     * [rightblocks description]
     *
     * @param   [type]  $moreclass  [$moreclass description]
     *
     * @return  [type]              [return description]
     */
    public function rightblocks($moreclass)
    {
        $this->Pre_fab_block('', 'RB', $moreclass);
    }

    /**
     * [oneblock description]
     *
     * @param   [type]  $Xid     [$Xid description]
     * @param   [type]  $Xblock  [$Xblock description]
     *
     * @return  [type]           [return description]
     */
    public function oneblock($Xid, $Xblock)
    {
        ob_start();
            $this->Pre_fab_block($Xid, $Xblock, '');
            $tmp = ob_get_contents();
        ob_end_clean();

        return $tmp;
    }

    /**
     * [Pre_fab_block description]
     *
     * @param   [type]  $Xid        [$Xid description]
     * @param   [type]  $Xblock     [$Xblock description]
     * @param   [type]  $moreclass  [$moreclass description]
     *
     * @return  [type]              [return description]
     */
    public function Pre_fab_block($Xid, $Xblock, $moreclass)
    {
        global $htvar; // modif Jireck

        if ($Xid)
            $result = $Xblock == 'RB' 
                ? sql_query("SELECT title, content, member, cache, actif, id, css FROM rblocks WHERE id='$Xid'") 
                : sql_query("SELECT title, content, member, cache, actif, id, css FROM lblocks WHERE id='$Xid'");
        else
            $result = $Xblock == 'RB' 
                ? sql_query("SELECT title, content, member, cache, actif, id, css FROM rblocks ORDER BY Rindex ASC") 
                : sql_query("SELECT title, content, member, cache, actif, id, css FROM lblocks ORDER BY Lindex ASC");

        global $bloc_side;

        $bloc_side = $Xblock == 'RB' ? 'RIGHT' : 'LEFT';

        while (list($title, $content, $member, $cache, $actif, $id, $css) = sql_fetch_row($result)) {
            if (($actif) or ($Xid)) {
                if ($css == 1) {
                    $htvar = '<div class="' . $moreclass . '" id="' . $Xblock . '_' . $id . '">'; // modif Jireck
                } else {
                    $htvar = '<div class="' . $moreclass . ' ' . strtolower($bloc_side) . 'bloc">'; // modif Jireck
                }

                $this->fab_block($title, $member, $content, $cache);
                // echo "</div>"; // modif Jireck
            }
        }

        sql_free_result($result);
    }

    /**
     * [niv_block description]
     *
     * @param   [type]  $Xcontent  [$Xcontent description]
     *
     * @return  [type]             [return description]
     */
    public function niv_block($Xcontent)
    {
        $result = sql_query("SELECT member, actif FROM rblocks WHERE content REGEXP '$Xcontent'");

        if (sql_num_rows($result)) {
            list($member, $actif) = sql_fetch_row($result);

            return ($member . ',' . $actif);
        }

        sql_free_result($result);

        $result = sql_query("SELECT member, actif FROM lblocks WHERE content REGEXP '$Xcontent'");
        
        if (sql_num_rows($result)) {
            list($member, $actif) = sql_fetch_row($result);

            return ($member . ',' . $actif);
        }

        sql_free_result($result);
    }

    /**
     * [autorisation_block description]
     *
     * @param   [type]  $Xcontent  [$Xcontent description]
     *
     * @return  [type]             [return description]
     */
    public function autorisation_block($Xcontent)
    {
        $autoX = array(); //notice .... to follow
        $auto = explode(',', $this->niv_block($Xcontent));

        // le dernier indice indique si le bloc est actif
        $actif = $auto[count($auto) - 1];

        // on dépile le dernier indice
        array_pop($auto);

        foreach ($auto as $autovalue) {
            if (autorisation($autovalue)) {
                $autoX[] = $autovalue;
            }
        }

        if ($actif) {
            return $autoX;
        } else {
            return '';
        }
    }

    /**
     * [block_pdst description]
     *
     * @param   [type]  $pdst  [$pdst description]
     *
     * @return  [type]         [return description]
     */
    public function block_pdst($pdst)
    {
        

        /*
        Nomination des div par l'attribut id:
        col_princ contient le contenu principal
        col_LB contient les blocs historiquement dit de gauche
        col_RB contient les blocs historiquement dit de droite
        
        Dans ce thème la variable $pdst permet de gérer le nombre et la disposition (de gauche à droite) des colonnes.
        "-1" -> col_princ
        "0"  -> col_LB + col_princ
        "1"  -> col_LB + col_princ + col_RB
        "2"  -> col_princ + col_RB
        "3"  -> col_LB + col_RB + col_princ
        "4"  -> col_princ + col_LB + col_RB
        "5"  -> col_RB + col_princ
        "6"  -> col_princ + col_LB
         
        La gestion de ce paramètre s'effectue dans le fichier "pages.php" du dossier "themes
        */

        $blg_actif = sql_query("SELECT * FROM lblocks WHERE actif ='1'");
        $nb_blg_actif = sql_num_rows($blg_actif);
        
        if ($nb_blg_actif == 0) {
            switch ($pdst) {
                case '0':
                    $pdst = '-1';
                    break;
        
                case '1':
                    $pdst = '2';
                    break;
        
                case '3':
                    $pdst = '5';
                    break;
        
                case '4':
                    $pdst = '2';
                    break;
        
                case '6':
                    $pdst = '-1';
                    break;
            }
        }
        
        $bld_actif = sql_query("SELECT * FROM rblocks WHERE actif ='1'");
        $nb_bld_actif = sql_num_rows($bld_actif);

        if ($nb_bld_actif == 0) {
            switch ($pdst) {
                case '1':
                    $pdst = '0';
                    break;
        
                case '2':
                    $pdst = '-1';
                    break;
        
                case '3':
                    $pdst = '0';
                    break;
        
                case '4':
                    $pdst = '6';
                    break;
        
                case '5':
                    $pdst = '-1';
                    break;
            }
        } 
        
        return $pdst;
    }

}
