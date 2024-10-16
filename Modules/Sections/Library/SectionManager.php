<?php

namespace Modules\Sections\Library;

use Modules\Npds\Support\Facades\Auth;
use Modules\Npds\Support\Facades\Language;
use Modules\Groupes\Support\Facades\Groupe;
use Modules\Sections\Contracts\SectionInterface;


/**
 * Undocumented class
 */
class SectionManager implements SectionInterface 
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
     * Undocumented function
     *
     * @param [type] $groupe
     * @return void
     */
    public function groupe($groupe)
    {
        $les_groupes = explode(',', $groupe);
        $mX = Groupe::liste_group();
    
        $nbg = 0;
        $str = '';
    
        foreach ($mX as $groupe_id => $groupe_name) {
            $selectionne = 0;
    
            if ($les_groupes) {
                foreach ($les_groupes as $groupevalue) {
                    if (($groupe_id == $groupevalue) and ($groupe_id != 0)) {
                        $selectionne = 1;
                    }
                }
            }
    
            $str .= $selectionne == 1 
                ? '<option value="' . $groupe_id . '" selected="selected">' . $groupe_name . '</option>' 
                : '<option value="' . $groupe_id . '">' . $groupe_name . '</option>';

            $nbg++;
        }
    
        if ($nbg > 5) {
            $nbg = 5;
        }
    
        return '<select class="form-control" name="Mmembers[]" multiple size="' . $nbg . '">' . $str . '</select>';
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $member
     * @return void
     */
    public function droits($member)
    {
        echo '
        <fieldset>
        <legend>' . __d('sections', 'Droits') . '</legend>
        <div class="mb-3">
            <div class="form-check form-check-inline">';
    
        if ($member == -127) {
            $checked = ' checked="checked"';
        } else {
            $checked = '';
        }
    
        echo '
                <input class="form-check-input" type="radio" id="adm" name="members" value="-127" ' . $checked . ' />
                <label class="form-check-label" for="adm">' . __d('sections', 'Administrateurs') . '</label>
            </div>
            <div class="form-check form-check-inline">';
    
        if ($member == -1) {
            $checked = ' checked="checked"';
        } else {
            $checked = '';
        }
    
        echo '
                <input class="form-check-input" type="radio" id="ano" name="members" value="-1" ' . $checked . ' />
                <label class="form-check-label" for="ano">' . __d('sections', 'Anonymes') . '</label>
            </div>';
    
        echo '<div class="form-check form-check-inline">';
    
        if ($member > 0) {
            echo '
                    <input class="form-check-input" type="radio" id="mem" name="members" value="1" checked="checked" />
                    <label class="form-check-label" for="mem">' . __d('sections', 'Membres') . '</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="tous" name="members" value="0" />
                    <label class="form-check-label" for="tous">' . __d('sections', 'Tous') . '</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="col-form-label" for="Mmember[]">' . __d('sections', 'Groupes') . '</label>';
    
            echo $this->groupe($member) . '
            </div>';
        } else {
            if ($member == 0) {
                $checked = ' checked="checked"';
            } else {
                $checked = '';
            }
    
            echo '
                    <input class="form-check-input" type="radio" id="mem" name="members" value="1" />
                    <label class="form-check-label" for="mem">' . __d('sections', 'Membres') . '</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="tous" name="members" value="0"' . $checked . ' />
                    <label class="form-check-label" for="tous">' . __d('sections', 'Tous') . '</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="col-form-label" for="Mmember[]">' . __d('sections', 'Groupes') . '</label>';
    
            echo $this->groupe($member) . '
                </div>
            </fieldset>';
        }
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $secid
     * @return void
     */
    public function sousrub_select($secid)
    {
        global $radminsuper, $aid;
    
        $ok_pub = false;
    
        $tmp = '<select name="secid" class="form-select">';
    
        $result = sql_query("SELECT distinct rubid, rubname, ordre FROM rubriques ORDER BY ordre");
    
        while (list($rubid, $rubname) = sql_fetch_row($result)) {
            $rubname = Language::aff_langue($rubname);
    
            $tmp .= '<optgroup label="' . Language::aff_langue($rubname) . '">';
    
            if ($radminsuper == 1) {
                $result2 = sql_query("SELECT secid, secname, ordre FROM sections WHERE rubid='$rubid' ORDER BY ordre");
            } else {
                $result2 = sql_query("SELECT distinct sections.secid, sections.secname, sections.ordre FROM sections, publisujet WHERE sections.rubid='$rubid' and sections.secid=publisujet.secid2 and publisujet.aid='$aid' and publisujet.type='1' ORDER BY ordre");
            }

            while (list($secid2, $secname) = sql_fetch_row($result2)) {
                $secname = Language::aff_langue($secname);
                $secname = substr($secname, 0, 50);
                
                $tmp .= '<option value="' . $secid2 . '"';
    
                if ($secid2 == $secid) {
                    $tmp .= ' selected="selected"';
                }
    
                $tmp .= '>' . $secname . '</option>';
                $ok_pub = true;
            }
    
            sql_free_result($result2);
    
            $tmp .= '</optgroup>';
        }
    
        $tmp .= '</select>';
    
        sql_free_result($result);
    
        if (!$ok_pub) {
            $tmp = '';
        }
    
        return $tmp;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $secid
     * @return void
     */
    public function droits_publication($secid)
    {
        global $radminsuper, $aid;
    
        // 3=mod - 4=delete
        $droits = 0; 
    
        if ($radminsuper != 1) {
            $result = sql_query("SELECT type FROM publisujet WHERE secid2='$secid' AND aid='$aid' AND type in(3,4) ORDER BY type");
    
            if (sql_num_rows($result) > 0) {
                while (list($type) = sql_fetch_row($result)) {
                    $droits = $droits + $type;
                }
            }
        } else {
            $droits = 7;
        }
    
        return $droits;
    }

    /**
     * Undocumented function
     *
     * @param [type] $chng_aid
     * @param [type] $secid
     * @return void
     */
    public function droitsalacreation($chng_aid, $secid)
    {
        $lesdroits = array('1', '2', '3');
    
        // if($secid > 0)
            foreach ($lesdroits as $droit) {
                sql_query("INSERT INTO publisujet VALUES ('$chng_aid','$secid','$droit')");
            }
        // else {
        //     sql_query("INSERT INTO publisujet VALUES ('$chng_aid','$secid','1')");
        // }
    }

    /**
     * Undocumented function
     *
     * @param [type] $artid
     * @return void
     */
    public function verif_aff($artid)
    {
        $result = sql_query("SELECT secid FROM seccont WHERE artid='$artid'");
        list($secid) = sql_fetch_row($result);
    
        $result = sql_query("SELECT userlevel FROM sections WHERE secid='$secid'");
        list($userlevel) = sql_fetch_row($result);
    
        $okprint = false;
        $okprint = $this->autorisation_section($userlevel);
    
        return $okprint;
    }

    /**
     * Undocumented function
     *
     * @param [type] $userlevel
     * @return void
     */
    public function autorisation_section($userlevel)
    {
        $okprint = false;
        $tmp_auto = explode(',', $userlevel);
    
        foreach ($tmp_auto as $userlevel) {
            $okprint = Auth::autorisation($userlevel);
    
            if ($okprint) {
                break;
            }
        }
    
        return $okprint;
    }

}
