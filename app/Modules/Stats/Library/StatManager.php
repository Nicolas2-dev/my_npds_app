<?php

namespace App\Modules\Stats\Library;

use App\Modules\Users\Models\User;
use App\Modules\Npds\Models\Counter;
use App\Modules\Stats\Contracts\StatInterface;


class StatManager implements StatInterface 
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
     * [req_stat description]
     *
     * @return  [type]  [return description]
     */
    public function req_stat()
    {
        

        // Les membres
        $xtab[0] = User::stats();

        // Les Nouvelles (News)
        $result = sql_query("SELECT sid FROM stories");
        $xtab[1] = $result ? sql_num_rows($result) : '0';

        // Les Critiques (Reviews))
        $result = sql_query("SELECT id FROM reviews");
        $xtab[2] = $result ? sql_num_rows($result) : '0';

        // Les Forums
        $result = sql_query("SELECT forum_id FROM forums");
        $xtab[3] = $result ? sql_num_rows($result) : '0';

        // Les Sujets (topics)
        $result = sql_query("SELECT topicid FROM topics");
        $xtab[4] = $result ? sql_num_rows($result) : '0';

        // Nombre de pages vues
        $xtab[5] = Counter::stats();

        sql_free_result($result);

        return $xtab;
    }

    // Admin

    /**
     * [generatePourcentageAndTotal description]
     *
     * @param   [type]  $count  [$count description]
     * @param   [type]  $total  [$total description]
     *
     * @return  [type]          [return description]
     */
    public function generatePourcentageAndTotal($count, $total)
    {
        $tab[] = wrh($count);
        $tab[] = substr(sprintf('%f', 100 * $count / $total), 0, 5);

        return $tab;
    }

    /**
     * [theme_stat description]
     *
     * @return  [type]  [return description]
     */
    public function theme_stat()
    {
        

        $resultX = sql_query("SELECT DISTINCT(theme) FROM users");
        
        while (list($themelist) = sql_fetch_row($resultX)) {
            if ($themelist != '') {
                $ibix = explode('+', $themelist);
        
                $T_exist = is_dir("themes/$ibix[0]") ? '' : '<span class="text-danger">' . translate("Ce fichier n'existe pas ...") . '</span>';
        
                if ($themelist == Config::get('npds.Default_Theme')) {
                    $result = sql_query("SELECT uid FROM users WHERE theme='$themelist'");
                    $themeD1 = $result ? sql_num_rows($result) : 0;
        
                    $result = sql_query("SELECT uid FROM users WHERE theme=''");
                    $themeD2 = $result ? sql_num_rows($result) : 0;
        
                    echo '
                        <tr>
                        <td>' . $themelist . ' <b>(' . translate("par défaut") . ')</b></td>
                        <td><b>' . wrh(($themeD1 + $themeD2)) . '</b></td>
                        <td>' . $T_exist . '</td>
                        </tr>';
                } else {
                    $result = sql_query("SELECT uid FROM users WHERE theme='$themelist'");
                    $themeU = $result ? sql_num_rows($result) : 0;
        
                    echo '<tr>';
                    
                    echo substr($ibix[0], -3) == "_sk" ? '
                        <td>' . $themelist . '</td>' : '
                        <td>' . $ibix[0] . '</td>';
        
                    echo '
                        <td><b>' . wrh($themeU) . '</b></td>
                        <td>' . $T_exist . '</td>
                        </tr>';
                }
            }
        }        
    }

    public function stats()
    {
        $result = sql_query("SELECT uid FROM users");
        $unum = $result ? sql_num_rows($result) - 1 : 0;
        
        $result = sql_query("SELECT groupe_id FROM groupes");
        $gnum = $result ? sql_num_rows($result) : 0;
        
        $result = sql_query("SELECT sid FROM stories");
        $snum = $result ? sql_num_rows($result) : 0;
        
        $result = sql_query("SELECT aid FROM authors");
        $anum = $result ? sql_num_rows($result) : 0;
        
        $result = sql_query("SELECT post_id FROM posts WHERE forum_id<0");
        $cnum = $result ? sql_num_rows($result) : 0;
        
        $result = sql_query("SELECT secid FROM sections");
        $secnum = $result ? sql_num_rows($result) : 0;
        
        $result = sql_query("SELECT artid FROM seccont");
        $secanum = $result ? sql_num_rows($result) : 0;
        
        $result = sql_query("SELECT qid FROM queue");
        $subnum = $result ? sql_num_rows($result) : 0;
        
        $result = sql_query("SELECT topicid FROM topics");
        $tnum = $result ? sql_num_rows($result) : 0;
        
        $result = sql_query("SELECT lid FROM links_links");
        $links = $result ? sql_num_rows($result) : 0;
        
        $result = sql_query("SELECT cid FROM links_categories");
        $cat1 = $result ? sql_num_rows($result) : 0;
        
        $result = sql_query("SELECT sid FROM links_subcategories");
        $cat2 = $result ? sql_num_rows($result) : 0;
        $cat = $cat1 + $cat2;        
    }


}
