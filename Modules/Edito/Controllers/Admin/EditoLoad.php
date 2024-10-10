<?php

namespace Modules\Edito\Controllers\Admin;


use Modules\Npds\Core\AdminController;


class EditoLoad extends AdminController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = 'edito';

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = 'edito';


    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        $this->f_titre = __d('edito', 'Edito');

        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * [after description]
     *
     * @param   [type]  $result  [$result description]
     *
     * @return  [type]           [return description]
     */
    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * Undocumented function
     *
     * @param [type] $edito_type
     * @param [type] $contents
     * @param [type] $Xaff_jours
     * @param [type] $Xaff_jour
     * @param [type] $Xaff_nuit
     * @return void
     */
    public function edito($edito_type, $contents, $Xaff_jours, $Xaff_jour, $Xaff_nuit)
    {
        if ($edito_type == 'G') {
            if (file_exists('storage/static/edito.txt')) {
                $fp = fopen('storage/static/edito.txt', 'r');

                if (filesize('storage/static/edito.txt') > 0)
                    $Xcontents = fread($fp, filesize('storage/static/edito.txt'));

                fclose($fp);
            }
        } elseif ($edito_type == 'M') {
            if (file_exists('storage/static/edito_membres.txt')) {
                $fp = fopen('storage/static/edito_membres.txt', 'r');

                if (filesize('storage/static/edito_membres.txt') > 0)
                    $Xcontents = fread($fp, filesize('storage/static/edito_membres.txt'));

                fclose($fp);
            }
        }

        $Xcontents = preg_replace('#<!--|/-->#', '', $Xcontents);

        if ($Xcontents == '')
            $Xcontents = 'Edito ...';
        else {
            $ibid = strstr($Xcontents, 'aff_jours');
            parse_str($ibid, $Xibidout);
        }

        if ($Xibidout['aff_jours'])
            $Xcontents = substr($Xcontents, 0, strpos($Xcontents, 'aff_jours'));
        else {
            $Xibidout['aff_jours'] = 20;
            $Xibidout['aff_jour'] = 'checked="checked"';
            $Xibidout['aff_nuit'] = 'checked="checked"';
        }

        //edito($edito_type, $Xcontents, $Xibidout['aff_jours'], $Xibidout['aff_jour'], $Xibidout['aff_nuit']);

        echo '<hr />';
    
        if ($contents == '') {
            echo '
            <form id="fad_edi_choix" action="admin.php?op=Edito_load" method="post">
                <fieldset>
                    <legend>' . __d('edito', 'Type d\'éditorial') . '</legend>
                    <div class="mb-3">
                    <select class="form-select" name="edito_type" onchange="submit()">
                        <option value="0">' . __d('edito', 'Modifier l\'Editorial') . ' ...</option>
                        <option value="G">' . __d('edito', 'Anonyme') . '</option>
                        <option value="M">' . __d('edito', 'Membre') . '</option>
                    </select>
                    </div>
                </fieldset>
            </form>';
    
            adminfoot('', '', '', '');
        } else {
            if ($edito_type == 'G')
                $edito_typeL = ' ' . __d('edito', 'Anonyme');
            elseif ($edito_type == 'M')
                $edito_typeL = ' ' . __d('edito', 'Membre');
    
            if (strpos($contents, '[/jour]') > 0) {
                $contentJ = substr($contents, strpos($contents, '[jour]') + 6, strpos($contents, '[/jour]') - 6);
                $contentN = substr($contents, strpos($contents, '[nuit]') + 6, strpos($contents, '[/nuit]') - 19 - strlen($contentJ));
            }
    
            if (!$contentJ and !$contentN and !strpos($contents, '[/jour]')) 
                $contentJ = $contents;
    
            echo '
            <form id="admineditomod" action="admin.php" method="post" name="adminForm">
                <fieldset>
                <legend>' . __d('edito', 'Edito') . ' :' . $edito_typeL . '</legend>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="XeditoJ">' . __d('edito', 'Le jour') . '</label>
                    <div class="col-sm-12">
                    <textarea class="tin form-control" name="XeditoJ" rows="20" >';
    
            echo htmlspecialchars($contentJ, ENT_COMPAT | ENT_SUBSTITUTE | ENT_HTML401, cur_charset);
    
            echo '</textarea>
                    </div>
                </div>';
    
            echo aff_editeur('XeditoJ', '');
    
            echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="XeditoN">' . __d('edito', 'La nuit') . '</label>';
    
            echo aff_editeur('XeditoN', '');
    
            echo '
                    <div class="col-sm-12">
                    <textarea class="tin form-control" name="XeditoN" rows="20">';
    
            echo htmlspecialchars($contentN, ENT_COMPAT | ENT_SUBSTITUTE | ENT_HTML401, cur_charset);
    
            echo '</textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label" for="aff_jours">' . __d('edito', 'Afficher pendant') . '</label>
                    <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-text">' . __d('edito', 'jour(s)') . '</span>
                        <input class="form-control" type="number" name="aff_jours" id="aff_jours" min="0" step="1" max="999" value="' . $Xaff_jours . '" data-fv-digits="true" required="required" />
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-8 ms-sm-auto">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="aff_jour" name="aff_jour" value="checked" ' . $Xaff_jour . ' />
                        <label class="form-check-label" for="aff_jour">' . __d('edito', 'Le jour') . '</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="aff_nuit" name="aff_nuit" value="checked" ' . $Xaff_nuit . ' />
                        <label class="form-check-label" for="aff_nuit">' . __d('edito', 'La nuit') . '</label>
                    </div>
                    </div>
                </div>
    
            <input type="hidden" name="op" value="Edito_save" />
            <input type="hidden" name="edito_type" value="' . $edito_type . '" />
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto ">
                    <button class="btn btn-primary col-12" type="submit" name="edito_confirm"><i class="fa fa-check fa-lg"></i>&nbsp;' . __d('edito', 'Sauver les modifications') . ' </button>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto ">
                    <a href="admin.php?op=Edito" class="btn btn-secondary col-12">' . __d('edito', 'Abandonner') . '</a>
                </div>
            </div>
            </fieldset>
            </form>';
    
            $arg1 = '
            var formulid = ["admineditomod"];
            ';
    
            $fv_parametres = '
                aff_jours: {
                validators: {
                    digits: {
                        message: "This must be a number"
                    }
                }
            },
            ';
    
            adminfoot('fv', $fv_parametres, $arg1, '');
        }
    }

}
