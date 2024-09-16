<?php

namespace App\Modules\Forum\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;


class AdminPhpbbConfig extends AdminController
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
    protected $hlpfile = "";

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
    protected $f_meta_nom = '';


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
        $this->f_titre = __d('', '');

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
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    // public function __construct()
    // {
        // $f_meta_nom = 'ForumConfigAdmin';
        // $f_titre = __d('forum', 'Configuration des Forums');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        
        // $hlpfile = "language/manuels/Config::get('npds.language')/forumconfig.html";
    // }

    function ForumConfigAdmin()
    {
        $result = sql_query("SELECT * FROM config");
        list($allow_html, $allow_bbcode, $allow_sig, $posts_per_page, $hot_threshold, $topics_per_page, $allow_upload_forum, $allow_forum_hide, $forum_attachments, $rank1, $rank2, $rank3, $rank4, $rank5, $anti_flood, $solved) = sql_fetch_row($result);
        
        echo '
        <hr />
        <h3 class="mb-3">' . __d('forum', 'Configuration des Forums') . '</h3>
        <form id="phpbbconfigforum" action="admin.php" method="post">
            <div class="row">
                <label class="col-form-label col-sm-5" for="allow_html">' . __d('forum', 'Autoriser le HTML') . '</label>
                <div class="col-sm-7 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if ($allow_html == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="allow_html_y" name="allow_html" value="1" ' . $cky . ' />
                    <label class="form-check-label" for="allow_html_y">' . __d('forum', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="allow_html_n" name="allow_html" value="0" ' . $ckn . ' />
                    <label class="form-check-label" for="allow_html_n">' . __d('forum', 'Non') . '</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-form-label col-sm-5 " for="allow_bbcode">' . __d('forum', 'Autoriser les Smilies') . '</label>
                <div class="col-sm-7 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if ($allow_bbcode == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="allow_bbcode_y" name="allow_bbcode" value="1" ' . $cky . ' />
                    <label class="form-check-label" for="allow_bbcode_y">' . __d('forum', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="allow_bbcode_n" name="allow_bbcode" value="0" ' . $ckn . ' />
                    <label class="form-check-label" for="allow_bbcode_n">' . __d('forum', 'Non') . '</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-form-label col-sm-5" for="allow_sig">' . __d('forum', 'Autoriser les Signatures') . '</label>
                <div class="col-sm-7 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if ($allow_sig == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="allow_sig_y" name="allow_sig" value="1" ' . $cky . ' />
                    <label class="form-check-label" for="allow_sig_y">' . __d('forum', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="allow_sig_n" name="allow_sig" value="0" ' . $ckn . ' />
                    <label class="form-check-label" for="allow_sig_n">' . __d('forum', 'Non') . '</label>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-5" for="hot_threshold">' . __d('forum', 'Seuil pour les Sujet \'chauds\'') . '</label>
                <div class="col-sm-7">
                    <input class="form-control" type="text" min="0" id="hot_threshold" name="hot_threshold" maxlength="6" value="' . $hot_threshold . '" />
                    <span class="help-block text-end" id="countcar_hot_threshold"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-5" for="posts_per_page">' . __d('forum', 'Nombre de contributions par page') . '</label>
                <div class="col-sm-7">
                    <input class="form-control" type="text" min="0" id="posts_per_page" name="posts_per_page" maxlength="6" value="' . $posts_per_page . '" />
                    <span class="help-block">' . __d('forum', '(C\'est le nombre de contributions affichées pour chaque page relative à un Sujet)') . '<span class="float-end ms-1" id="countcar_posts_per_page"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-5" for="topics_per_page">' . __d('forum', 'Sujets par forum') . '</label>
                <div class="col-sm-7">
                    <input class="form-control" type="text" min="0" id="topics_per_page" name="topics_per_page" maxlength="6" value="' . $topics_per_page . '" />
                    <span class="help-block">' . __d('forum', '(C\'est le nombre de Sujets affichés pour chaque page relative à un Forum)') . '<span class="float-end ms-1" id="countcar_topics_per_page"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-5" for="anti_flood">' . __d('forum', 'Nombre maximum de contributions par IP et par période de 30 minutes (0=système inactif)') . '</label>
                <div class="col-sm-7">
                    <input class="form-control" type="text" min="0" id="anti_flood" name="anti_flood" maxlength="6" value="' . $anti_flood . '" />
                    <span class="help-block text-end" id="countcar_anti_flood"></span>
                </div>
            </div>
            <div class="row">
                <label class="col-form-label col-sm-5" for="solved">' . __d('forum', 'Activer le tri des contributions \'résolues\'') . '</label>
                <div class="col-sm-7 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if ($solved == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="solved_y" name="solved" value="1" ' . $cky . ' />
                    <label class="form-check-label" for="solved_y">' . __d('forum', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="solved_n" name="solved" value="0" ' . $ckn . ' />
                    <label class="form-check-label" for="solved_n">' . __d('forum', 'Non') . '</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-form-label col-sm-5" for="allow_upload_forum">' . __d('forum', 'Activer l\'upload dans les forums ?') . '</label>
                <div class="col-sm-7 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if ($allow_upload_forum == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="allow_upload_forum_y" name="allow_upload_forum" value="1" ' . $cky . ' />
                    <label class="form-check-label" for="allow_upload_forum_y">' . __d('forum', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="allow_upload_forum_n" name="allow_upload_forum" value="0" ' . $ckn . ' />
                    <label class="form-check-label" for="allow_upload_forum_n">' . __d('forum', 'Non') . '</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-form-label col-sm-5" for="allow_forum_hide">' . __d('forum', 'Activer les textes cachés') . '</label>
                <div class="col-sm-7 my-2">';
    
        $cky = '';
        $ckn = '';
    
        if ($allow_forum_hide == 1) {
            $cky = 'checked="checked"';
            $ckn = '';
        } else {
            $cky = '';
            $ckn = 'checked="checked"';
        }
    
        echo '
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="allow_forum_hide_y" name="allow_forum_hide" value="1" ' . $cky . '/>
                    <label class="form-check-label" for="allow_forum_hide_y">' . __d('forum', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="allow_forum_hide_n" name="allow_forum_hide" value="0" ' . $ckn . ' />
                    <label class="form-check-label" for="allow_forum_hide_n">' . __d('forum', 'Non') . '</label>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="col-form-label" for="rank1">' . __d('forum', 'Texte pour le rôle') . ' 1 </label>
                <textarea class="form-control" id="rank1" name="rank1" rows="3" maxlength="255">' . $rank1 . '</textarea>
                <span class="help-block text-end" id="countcar_rank1"></span>
            </div>
            <div class="mb-3">
                <label class="col-form-label" for="rank2">' . __d('forum', 'Texte pour le rôle') . ' 2 </label>
                <textarea class="form-control" id="rank2" name="rank2" rows="3" maxlength="255">' . $rank2 . '</textarea>
                <span class="help-block text-end" id="countcar_rank2"></span>
            </div>
            <div class="mb-3">
                <label class="col-form-label" for="rank3">' . __d('forum', 'Texte pour le rôle') . ' 3 </label>
                <textarea class="form-control" id="rank3" name="rank3" rows="3" maxlength="255">' . $rank3 . '</textarea>
                <span class="help-block text-end" id="countcar_rank3"></span>
            </div>
            <div class="mb-3">
                <label class="col-form-label" for="rank4">' . __d('forum', 'Texte pour le rôle') . ' 4 </label>
                <textarea class="form-control" id="rank4" name="rank4" rows="3" maxlength="255">' . $rank4 . '</textarea>
                <span class="help-block text-end" id="countcar_rank4"></span>
            </div>
            <div class="mb-3">
                <label class="col-form-label" for="rank5">' . __d('forum', 'Texte pour le rôle') . ' 5 </label>
                <textarea class="form-control" id="rank5" name="rank5" rows="3" maxlength="255">' . $rank5 . '</textarea>
                <span class="help-block text-end" id="countcar_rank5"></span>
            </div>
            <input type="hidden" name="op" value="ForumConfigChange" />
            <div class="mb-3">
                <button class="btn btn-primary" type="submit">' . __d('forum', 'Changer') . '</button>
            </div>
        </form>';
    
        $fv_parametres = '
            hot_threshold: {
                validators: {
                    regexp: {
                    regexp:/^\d{1,6}$/,
                    message: "0-9"
                    }
                }
            },
            posts_per_page: {
                validators: {
                    regexp: {
                    regexp:/^\d{1,6}$/,
                    message: "0-9"
                    }
                }
            },
            topics_per_page: {
                validators: {
                    regexp: {
                    regexp:/^\d{1,6}$/,
                    message: "0-9"
                    }
                }
            },
            anti_flood: {
                validators: {
                    regexp: {
                    regexp:/^\d{1,6}$/,
                    message: "0-9"
                    }
                }
            },
        ';
    
        $arg1 = '
        var formulid = ["phpbbconfigforum"];
        inpandfieldlen("posts_per_page",255);
        inpandfieldlen("hot_threshold",255);
        inpandfieldlen("topics_per_page",255);
        inpandfieldlen("anti_flood",255);
        inpandfieldlen("rank1",255);
        inpandfieldlen("rank2",255);
        inpandfieldlen("rank3",255);
        inpandfieldlen("rank4",255);
        inpandfieldlen("rank5",255);
        ';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
    function ForumConfigChange($allow_html, $allow_bbcode, $allow_sig, $posts_per_page, $hot_threshold, $topics_per_page, $allow_upload_forum, $allow_forum_hide, $rank1, $rank2, $rank3, $rank4, $rank5, $anti_flood, $solved)
    {
        sql_query("UPDATE config SET allow_html='$allow_html', allow_bbcode='$allow_bbcode', allow_sig='$allow_sig', posts_per_page='$posts_per_page', hot_threshold='$hot_threshold', topics_per_page='$topics_per_page', allow_upload_forum='$allow_upload_forum', allow_forum_hide='$allow_forum_hide', rank1='$rank1', rank2='$rank2', rank3='$rank3', rank4='$rank4', rank5='$rank5', anti_flood='$anti_flood', solved='$solved'");
        
        Q_Clean();
        
        Header("Location: admin.php?op=ForumConfigAdmin");
    }

}
