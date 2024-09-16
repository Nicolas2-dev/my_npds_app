<?php

namespace App\Modules\News\Controllers\Admin;


use Npds\Config\Config;
use App\Modules\Npds\Core\AdminController;


class AdminTopics extends AdminController
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
        // $f_meta_nom = 'topicsmanager';
        // $f_titre = __d('news', 'Gestion des sujets');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        
        // $hlpfile = "language/manuels/Config::get('npds.language')/topics.html";

        // switch ($op) {
        //     case 'topicsmanager':
        //         topicsmanager();
        //         break;
        
        //     case 'topicedit':
        //         topicedit($topicid);
        //         break;
        
        //     case 'topicmake':
        //         topicmake($topicname, $topicimage, $topictext, $topicadmin);
        //         break;
        
        //     case 'topicdelete':
        //         topicdelete($topicid, $ok);
        //         break;
        
        //     case 'topicchange':
        //         topicchange($topicid, $topicname, $topicimage, $topictext, $topicadmin, $name, $url);
        //         break;
        
        //     case 'relatedsave':
        //         relatedsave($tid, $rid, $name, $url);
        //         break;
        
        //     case 'relatededit':
        //         relatededit($tid, $rid);
        //         break;
        
        //     case 'relateddelete':
        //         relateddelete($tid, $rid);
        //         break;
        // } 
    // }

    function topicsmanager()
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
    
    function topicedit($topicid)
    {
        $result = sql_query("SELECT topicid, topicname, topicimage, topictext, topicadmin FROM topics WHERE topicid='$topicid'");
        list($topicid, $topicname, $topicimage, $topictext, $topicadmin) = sql_fetch_row($result);

        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Editer le Sujet :') . ' <span class="text-muted">' . aff_langue($topicname) . '</span></h3>';
    
        if ($topicimage != '') {
            echo '<div class="card card-body my-4 py-3"><img class="img-fluid mx-auto d-block" src="' . Config::get('npds.tipath') . $topicimage . '" alt="image-sujet" /></div>';
        }
    
        echo '
        <form action="admin.php" method="post" id="topicchange">
            <fieldset>
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
                    <textarea id="topictext" class="form-control" rows="3" name="topictext" maxlength="250" placeholder="' . __d('news', 'ce site est génial') . '" required="required">' . $topictext . '</textarea>
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
                    <label class="col-form-label col-sm-4" for="topicadmin">' . __d('news', 'Administrateur(s) du sujet') . '</label>
                    <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-cog fa-lg"></i></span>
                        <input class="form-control" type="text" id="topicadmin" name="topicadmin" maxlength="255" value="' . $topicadmin . '" />
                    </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
            <hr />
            <h4 class="my-3">' . __d('news', 'Ajouter des Liens relatifs au Sujet') . '</h4>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="name">' . __d('news', 'Nom du site') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="name" id="name" maxlength="30" />
                    <span class="help-block">' . __d('news', 'max caractères') . ' : <span id="countcar_name"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="url">' . __d('news', 'URL') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="url" name="url" id="url" maxlength="320" placeholder="http://www.valideurl.org" />
                    <span class="help-block">' . __d('news', 'max caractères') . ' : <span id="countcar_url"></span></span>
                </div>
            </div>
            </fieldset>
            <div class="mb-3 row">
                <input type="hidden" name="topicid" value="' . $topicid . '" />
                <input type="hidden" name="op" value="topicchange" />
                <div class="col-sm-8 ms-sm-auto">
                    <button class="btn btn-primary" type="submit">' . __d('news', 'Sauver les modifications') . '</button>
                    <button class="btn btn-secondary" onclick="javascript:document.location.href=\'admin.php?op=topicsmanager\'">' . __d('news', 'Retour en arrière') . '</button>
                </div>
            </div>
        </form>';
    
        /*
        <form id="fad_deltop" action="admin.php" method="post">
            <input type="hidden" name="topicid" value="'.$topicid.'" />
            <input type="hidden" name="op" value="topicdelete" />
        </form>
        <button class="btn btn-danger"><i class="fas fa-trash fa-lg"></i>&nbsp;&nbsp;'.__d('news', 'Effacer le Sujet !').'</button>
        */
    
        echo '
        <hr />
        <h3 class="my-2">' . __d('news', 'Gérer les Liens Relatifs : ') . ' <span class="text-muted">' . aff_langue($topicname) . '</span></h3>';
    
        $res = sql_query("SELECT rid, name, url FROM related WHERE tid='$topicid'");
    
        echo '
        <table id="tad_linkrel" data-toggle="table" data-striped="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <th data-sortable="true" data-halign="center">' . __d('news', 'Nom') . '</th>
                <th data-sortable="true" data-halign="center">' . __d('news', 'Url') . '</th>
                <th class="n-t-col-xs-2" data-halign="center" data-align="right">' . __d('news', 'Fonctions') . '</th>
            </thead>
            <tbody>';
    
        while (list($rid, $name, $url) = sql_fetch_row($res)) {
            echo '
                <tr>
                    <td>' . $name . '</td>
                    <td><a href="' . $url . '" target="_blank">' . $url . '</a></td>
                    <td>
                       <a href="admin.php?op=relatededit&amp;tid=' . $topicid . '&amp;rid=' . $rid . '" ><i class="fas fa-edit fa-lg" data-bs-toggle="tooltip" title="' . __d('news', 'Editer') . '"></i></a>&nbsp;
                       <a href="' . $url . '" target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a>&nbsp;
                       <a href="admin.php?op=relateddelete&amp;tid=' . $topicid . '&amp;rid=' . $rid . '" ><i class="fas fa-trash fa-lg text-danger" data-bs-toggle="tooltip" title="' . __d('news', 'Effacer') . '"></i></a>
                    </td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>';
    
        $fv_parametres = '
        topicadmin: {
            validators: {
                callback: {
                    message: "Please choose an administrator from the provided list.",
                    callback: function(value, validator, $field) {
                    diff="";
                    var value = $field.val();
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
        topicimage: {
            validators: {
                regexp: {
                    regexp: /^[\w]+\\.(jpg|jpeg|png|gif)$/,
                    message: "This must be a valid file name with one of this extension jpg, jpeg, png, gif."
                }
            }
        },
        topicname: {
            validators: {
                regexp: {
                    regexp: /^\w+$/i,
                    message: "This must be a simple word without space."
                }
            }
        },';
    
        $arg1 = '
        var formulid = ["topicchange"];
        inpandfieldlen("topicname",20);
        inpandfieldlen("topictext",250);
        inpandfieldlen("topicimage",20);
        inpandfieldlen("name",30);
        inpandfieldlen("url",320);
        ';
    
        echo auto_complete_multi('admin', 'aid', 'authors', 'topicadmin', '');
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
    function relatededit($tid, $rid)
    {
        $result = sql_query("SELECT name, url FROM related WHERE rid='$rid'");
        list($name, $url) = sql_fetch_row($result);
    
        $result2 = sql_query("SELECT topictext, topicimage FROM topics WHERE topicid='$tid'");
        list($topictext, $topicimage) = sql_fetch_row($result2);

        echo '
        <hr />
        <h3>' . __d('news', 'Sujet : ') . ' ' . $topictext . '</h3>
        <h4>' . __d('news', 'Editer les Liens Relatifs') . '</h4>';
    
        if ($topicimage != "")
            echo '
            <div class="thumbnail">
                <img class="img-fluid " src="' . Config::get('npds.tipath') . $topicimage . '" alt="' . $topictext . '" />
            </div>';
    
        echo '
        <form class="form-horizontal" action="admin.php" method="post" id="editrelatedlink">
            <fieldset>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="name">' . __d('news', 'Nom du site') . '</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="name" id="name" value="' . $name . '" maxlength="30" required="required" />
                    <span class="help-block text-end"><span id="countcar_name"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="url">' . __d('news', 'URL') . '</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-text">
                            <a href="' . $url . '" target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a>
                        </span>
                        <input type="url" class="form-control" name="url" id="url" value="' . $url . '" maxlength="320" />
                    </div>
                    <span class="help-block text-end"><span id="countcar_url"></span></span>
                    </div>
                    <input type="hidden" name="op" value="relatedsave" />
                    <input type="hidden" name="tid" value="' . $tid . '" />
                    <input type="hidden" name="rid" value="' . $rid . '" />
                </fieldset>
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto">
                    <button class="btn btn-primary col-12" type="submit">' . __d('news', 'Sauver les modifications') . '</button>
                </div>
            </div>
        </form>';
    
        $arg1 = '
            var formulid = ["editrelatedlink"];
            inpandfieldlen("name",30);
            inpandfieldlen("url",320);
        ';
    
        adminfoot('fv', '', $arg1, '');
    }
    
    function relatedsave($tid, $rid, $name, $url)
    {
        sql_query("UPDATE related SET name='$name', url='$url' WHERE rid='$rid'");
    
        Header("Location: admin.php?op=topicedit&topicid=$tid");
    }
    
    function relateddelete($tid, $rid)
    {
        sql_query("DELETE FROM related WHERE rid='$rid'");
    
        Header("Location: admin.php?op=topicedit&topicid=$tid");
    }
    
    function topicmake($topicname, $topicimage, $topictext, $topicadmin)
    {
        $topicname = stripslashes(FixQuotes($topicname));
    
        $istopicname = sql_num_rows(sql_query("SELECT * FROM topics WHERE topicname='$topicname'"));
    
        if ($istopicname !== 0) {
            Header("Location: admin.php?op=topicsmanager&nook=nook#addtopic");
            die();
        }
    
        $topicimage = stripslashes(FixQuotes($topicimage));
        $topictext = stripslashes(FixQuotes($topictext));
    
        sql_query("INSERT INTO topics VALUES (NULL,'$topicname','$topicimage','$topictext','0', '$topicadmin')");
    
        global $aid;
        Ecr_Log("security", "topicMake ($topicname) by AID : $aid", "");
    
        $topicadminX = explode(",", $topicadmin);
        array_pop($topicadminX);
    
        for ($i = 0; $i < count($topicadminX); $i++) {
            trim($topicadminX[$i]);
    
            $nres = sql_num_rows(sql_query("SELECT * FROM droits WHERE d_aut_aid='$topicadminX[$i]' and d_droits=11112"));
    
            if ($nres == 0)
                sql_query("INSERT INTO droits VALUES ('$topicadminX[$i]', '2', '11112')");
        }
    
        Header("Location: admin.php?op=topicsmanager#addtopic");
    }
    
    function topicchange($topicid, $topicname, $topicimage, $topictext, $topicadmin, $name, $url)
    {
        $topicadminX = explode(',', $topicadmin);
        array_pop($topicadminX);
    
        $res = sql_query("SELECT * FROM droits WHERE d_droits=11112 AND d_fon_fid=2");
    
        $d = array();
        $topad = array();
    
        while ($d = sql_fetch_row($res)) {
            $topad[] = $d[0];
        }
    
        foreach ($topicadminX as $value) {
            if (!in_array($value, $topad)) 
                sql_query("INSERT INTO droits VALUES ('$value', '2', '11112')");
        }
    
        foreach ($topad as $value) { //pour chaque droit adminsujet on regarde le nom de l'adminsujet
            if (!in_array($value, $topicadminX)) { //si le nom de l'adminsujet n'est pas dans les nouveaux adminsujet
    
                //on cherche si il administre un autre sujet
                $resu =  mysqli_get_client_info() <= '8.0' ?
                    sql_query("SELECT * FROM topics WHERE topicadmin REGEXP '[[:<:]]" . $value . "[[:>:]]'") :
                    sql_query("SELECT * FROM topics WHERE topicadmin REGEXP '\\b" . $value . "\\b'");
    
                $nbrow = sql_num_rows($resu);
                list($tid) = sql_fetch_row($resu);
    
                if (($nbrow == 1) and ($topicid == $tid)) {
                    sql_query("DELETE FROM droits WHERE d_aut_aid='$value' AND d_droits=11112 AND d_fon_fid=2");
                }
            }
        }
    
        $topicname = stripslashes(FixQuotes($topicname));
        $topicimage = stripslashes(FixQuotes($topicimage));
        $topictext = stripslashes(FixQuotes($topictext));
        $name = stripslashes(FixQuotes($name));
        $url = stripslashes(FixQuotes($url));
    
        sql_query("UPDATE topics SET topicname='$topicname', topicimage='$topicimage', topictext='$topictext', topicadmin='$topicadmin' WHERE topicid='$topicid'");
        
        global $aid;
        Ecr_Log("security", "topicChange ($topicname, $topicid) by AID : $aid", "");
    
        if ($name)
            sql_query("INSERT INTO related VALUES (NULL, '$topicid','$name','$url')");
    
        Header("Location: admin.php?op=topicedit&topicid=$topicid");
    }
    
    function topicdelete($topicid, $ok = 0)
    {
        if ($ok == 1) {
            global $aid;
    
            $result = sql_query("SELECT sid FROM stories WHERE topic='$topicid'");
            list($sid) = sql_fetch_row($result);
    
            sql_query("DELETE FROM stories WHERE topic='$topicid'");
            Ecr_Log("security", "topicDelete (stories, $topicid) by AID : $aid", "");
    
            sql_query("DELETE FROM topics WHERE topicid='$topicid'");
            Ecr_Log("security", "topicDelete (topic, $topicid) by AID : $aid", "");
    
            sql_query("DELETE FROM related WHERE tid='$topicid'");
            Ecr_Log("security", "topicDelete (related, $topicid) by AID : $aid", '');
    
            // commentaires
            if (file_exists("modules/comments/article.conf.php")) {
                include("modules/comments/article.conf.php");
    
                sql_query("DELETE FROM posts WHERE forum_id='$forum' and topic_id='$topic'");
                Ecr_Log("security", "topicDelete (comments, $topicid) by AID : $aid", "");
            }
    
            Header("Location: admin.php?op=topicsmanager");
        } else {
            global $topicimag;

            $result2 = sql_query("SELECT topicimage, topicname, topictext FROM topics WHERE topicid='$topicid'");
            list($topicimage, $topicname, $topictext) = sql_fetch_row($result2);

            echo '<h3 class=""><span class="text-danger">' . __d('news', 'Effacer le Sujet') . ' : </span>' . aff_langue($topicname) . '</h3>';
            echo '<div class="alert alert-danger lead" role="alert">';
    
            if ($topicimage != "")
                echo '
                <div class="thumbnail">
                    <img class="img-fluid" src="' . Config::get('npds.tipath') . $topicimage . '" alt="logo-topic" />
                </div>';
    
            echo '
                <p>' . __d('news', 'Etes-vous sûr de vouloir effacer ce sujet ?') . ' : ' . $topicname . '</p>
                <p>' . __d('news', 'Ceci effacera tous ses articles et ses commentaires !') . '</p>
                <p><a class="btn btn-danger" href="admin.php?op=topicdelete&amp;topicid=' . $topicid . '&amp;ok=1">' . __d('news', 'Oui') . '</a>&nbsp;<a class="btn btn-primary"href="admin.php?op=topicsmanager">' . __d('news', 'Non') . '</a></p>
            </div>';
    
            adminfoot('', '', '', '');
        }
    }

}
