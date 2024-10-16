<?php

namespace Modules\News\Library\Traits;

use Modules\Npds\Support\Facades\Language;
use Modules\Groupes\Support\Facades\Groupe;


/**
 * Undocumented trait
 */
trait NewsStoryTrait
{


    /**
     * Undocumented function
     *
     * @param [type] $ihome
     * @return void
     */
    public function puthome($ihome)
    {
        echo '
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="ihome">' . __d('news', 'Publier dans la racine ?') . '</label>';
    
        $sel1 = 'checked="checked"';
        $sel2 = '';
    
        if ($ihome == 1) {
            $sel1 = '';
            $sel2 = 'checked="checked"';
        }
    
        echo '
                <div class="col-sm-8 my-2">
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="ihome_y" name="ihome" value="0" ' . $sel1 . ' />
                    <label class="form-check-label" for="ihome_y">' . __d('news', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="ihome_n" name="ihome" value="1" ' . $sel2 . ' />
                    <label class="form-check-label" for="ihome_n">' . __d('news', 'Non') . '</label>
                    </div>
                    <p class="help-block">' . __d('news', 'Ne s\'applique que si la catégorie : \'Articles\' n\'est pas sélectionnée.') . '</p>
                </div>
            </div>';
    
        $sel1 = '';
        $sel2 = 'checked="checked"';
    
        echo '
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" >' . __d('news', 'Seulement aux membres') . ', ' . __d('news', 'Groupe') . '.</label>
                <div class="col-sm-8 my-2">
                    <div class="form-check form-check-inline">';
    
        //?? à revoir comprends pas ...
        if ($ihome < 0) {
            $sel1 = 'checked="checked"';
            $sel2 = '';
        }
    
        if (($ihome > 1) and ($ihome <= 127)) {
            $Mmembers = $ihome;
            $sel1 = 'checked="checked"';
            $sel2 = '';
        }
    
        echo '
                    <input class="form-check-input" type="radio" id="mem_y" name="members" value="1" ' . $sel1 . ' />
                    <label class="form-check-label" for="mem_y">' . __d('news', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"  id="mem_n" name="members" value="0" ' . $sel2 . ' />
                    <label class="form-check-label" for="mem_n">' . __d('news', 'Non') . '</label>
                    </div>
                </div>
            </div>';
    
        // Groupes

        $tmp_groupe = '';
    
        foreach (Groupe::liste_group() as $groupe_id => $groupe_name) {
            if ($groupe_id == '0') {
                
            }
    
            if ($Mmembers == $groupe_id) {
                $sel3 = 'selected="selected"';
            } else {
                $sel3 = '';
            }
    
            $tmp_groupe .= '<option value="' . $groupe_id . '" ' . $sel3 . '>' . $groupe_name . '</option>';
        }
    
        echo '
            <div class="mb-3 row" id="choixgroupe">
                <label class="col-sm-4 col-form-label" for="Mmembers">' . __d('news', 'Groupe') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="Mmembers" name="Mmembers">' . $tmp_groupe . '</select>
                </div>
            </div>';
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $cat
     * @return void
     */
    public function SelectCategory($cat)
    {
        $selcat = sql_query("SELECT catid, title FROM stories_cat");
    
        echo ' 
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="catid">' . __d('news', 'Catégorie') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="catid" name="catid">';
    
        if ($cat == 0) {
            $sel = 'selected="selected"';
        } else {
            $sel = '';
        }
    
        echo '<option name="catid" value="0" ' . $sel . '>' . __d('news', 'Articles') . '</option>';
    
        while (list($catidX, $title) = sql_fetch_row($selcat)) {
            if ($catidX == $cat) {
                $sel = 'selected="selected"';
            } else {
                $sel = '';
            }
    
            echo '<option name="catid" value="' . $catidX . '" ' . $sel . '>' . Language::aff_langue($title) . '</option>';
        }
    
        echo '
                    </select>
                    <p class="help-block text-end">
                        <a href="admin.php?op=AddCategory" class="btn btn-outline-primary btn-sm" title="' . __d('news', 'Ajouter') . '" data-bs-toggle="tooltip" >
                            <i class="fa fa-plus-square fa-lg"></i>
                        </a>&nbsp;
                        <a class="btn btn-outline-primary btn-sm" href="admin.php?op=EditCategory" title="' . __d('news', 'Editer') . '" data-bs-toggle="tooltip" >
                            <i class="fa fa-edit fa-lg"></i>
                        </a>&nbsp;
                        <a class="btn btn-outline-danger btn-sm" href="admin.php?op=DelCategory" title="' . __d('news', 'Effacer') . '" data-bs-toggle="tooltip">
                            <i class="fas fa-trash fa-lg"></i>
                        </a>
                    </p>
                </div>
            </div>';
    }

}
