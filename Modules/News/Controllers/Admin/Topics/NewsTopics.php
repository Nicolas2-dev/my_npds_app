<?php

namespace Modules\News\Controllers\Admin;


use Npds\Config\Config;
use Modules\Npds\Core\AdminController;


class NewsTopics extends AdminController
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
    protected $hlpfile = 'topics';

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
    protected $f_meta_nom = 'topicsmanager';


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
        $this->f_titre = __d('news', 'Gestion des sujets');

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
    * @param [type] $topicid
    * @param integer $ok
    * @return void
    */
    public function topicsmanager()
    {
        global $nook;

        $result = sql_query("SELECT topicid, topicname, topicimage, topictext FROM topics ORDER BY topicname");

        settype($topicadmin, 'string');
    
        if (sql_num_rows($result) > 0) {
            echo '
            <hr />
            <h3 class="my-3">' . __d('news', 'Sujets actifs') . '<span class="badge bg-secondary float-end">' . sql_num_rows($result) . '</span></h3>';
    
            while (list($topicid, $topicname, $topicimage, $topictext) = sql_fetch_row($result)) {
                echo '
                <div class="card card-body mb-2" id="top_' . $topicid . '">
                    <div class=" topi">
                        <div class="">';
    
                if (($topicimage) or ($topicimage != ''))
                    echo '<a href="admin.php?op=topicedit&amp;topicid=' . $topicid . '"><img class="img-thumbnail" style="height:80px;  max-width:120px" src="' . Config::get('npds.tipath') . $topicimage . '" data-bs-toggle="tooltip" title="ID : ' . $topicid . '" alt="' . $topicname . '" /></a>';
                else
                    echo '<a href="admin.php?op=topicedit&amp;topicid=' . $topicid . '"><img class="img-thumbnail" style="height:80px;  max-width:120px" src="' . Config::get('npds.tipath') . 'topics.png" data-bs-toggle="tooltip" title="ID : ' . $topicid . '" alt="' . $topicname . '" /></a>';
                
                echo '
                        </div>
                        <div class="">
                            <h4 class="my-3"><a href="admin.php?op=topicedit&amp;topicid=' . $topicid . '" ><i class="fa fa-edit me-1 align-middle"></i>' . aff_langue($topicname) . '</a></h4>
                            <p>' . aff_langue($topictext) . '</p>
                            <div id="shortcut-tools_' . $topicid . '" class="n-shortcut-tools" style="display:none;"><a class="text-danger btn" href="admin.php?op=topicdelete&amp;topicid=' . $topicid . '&amp;ok=0" ><i class="fas fa-trash fa-2x"></i></a></div>
                        </div>
                    </div>
                </div>';
            }
        }
    
        echo '
        <hr />
        <a name="addtopic"></a>';
    
        if (isset($nook))
            echo '<div class="alert alert-danger alert-dismissible fade show">' . __d('news', 'Le nom de ce sujet existe déjà !') . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        
        echo '
        <h3 class="my-4">' . __d('news', 'Ajouter un nouveau Sujet') . '</h3>
        <form action="admin.php" method="post" id="topicmake">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="topicname">' . __d('news', 'Intitulé') . '</label>
                <div class="col-sm-8">
                    <input id="topicname" class="form-control" type="text" name="topicname" maxlength="20" value="' . $topicname . '" placeholder="' . __d('news', 'cesiteestgénial') . '" required="required" />
                    <span class="help-block">' . __d('news', '(un simple nom sans espaces)') . ' - ' . __d('news', 'max caractères') . ' : <span id="countcar_topicname"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="topictext">' . __d('news', 'Texte') . '</label>
                <div class="col-sm-8">
                    <textarea id="topictext" class="form-control" rows="3" name="topictext" maxlength="250" placeholder="' . __d('news', 'ce site est génial') . '" required="required" >' . $topictext . '</textarea>
                    <span class="help-block">' . __d('news', '(description ou nom complet du sujet)') . ' - ' . __d('news', 'max caractères') . ' : <span id="countcar_topictext"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="topicimage">' . __d('news', 'Image') . '</label>
                <div class="col-sm-8">
                    <input id="topicimage" class="form-control" type="text" name="topicimage" maxlength="20" value="' . $topicimage . '" placeholder="genial.png" />
                    <span class="help-block">' . __d('news', '(nom de l\'image + extension)') . ' (' . Config::get('npds.tipath') . '). - ' . __d('news', 'max caractères') . ' : <span id="countcar_topicimage"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="topicadmin">' . __d('news', 'Administrateur(s)') . '</label>
                <div class="col-sm-8">
                    <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-cog fa-lg"></i></span>
                    <input class="form-control" type="text" id="topicadmin" name="topicadmin" maxlength="255" value="' . $topicadmin . '" required="required" />
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto">
                    <input type="hidden" name="op" value="topicmake" />
                    <button class="btn btn-primary" type="submit" ><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('news', 'Ajouter un Sujet') . '</button>
                </div>
            </div>
        </form>';
    
        echo '
        <script type="text/javascript">
            //<![CDATA[
                var topid="";
                $(".topi").hover(function(){
                    topid = $(this).parent().attr("id");
                    topid=topid.substr (topid.search(/\d/))
                    $button=$("#shortcut-tools_"+topid);
                    $button.show();
                }, function(){
                $button.hide();
                });
            //]]>
        </script>';
    
        // le validateur pour topicadmin ne fonctionne pas ?!!
        $fv_parametres = '
        topicadmin: {
            validators: {
                callback: {
                    message: "Please choose an administrator FROM the provided list.",
                    callback: function(value, validator, $field) {
                    diff="";
                    var value = $field.val();
                                console.log(value);//
    
                    if (value === "") {return true;}
                    function split( n ) {
                    return n.split( /,\s*/ );
                    }
                    diff = $(split(value)).not(admin).get();
                    console.log(diff);
                    if (diff!="") {return false;}
                    return true;
                    }
                }
            }
        },
    
        topicname: {
            validators: {
                regexp: {
                    regexp: /^\w+$/i,
                    message: "' . __d('news', 'Doit être un mot sans espace.') . '"
                }
            }
        },
    
        topicimage: {
            validators: {
                regexp: {
                    regexp: /^[\w]+\\.(jpg|jpeg|png|gif)$/,
                    message: "' . __d('news', 'Doit être un nom de fichier valide avec une de ces extensions : jpg, jpeg, png, gif.') . '"
                }
            }
        },
        ';
    
        $arg1 = '
        var formulid = ["topicmake"];
        inpandfieldlen("topicname",20);
        inpandfieldlen("topictext",250);
        inpandfieldlen("topicimage",20);
        inpandfieldlen("topicadmin",255);
        ';
    
        echo auto_complete_multi('admin', 'aid', 'authors', 'topicadmin', '');
    
        sql_free_result($result);
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
