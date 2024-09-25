<?php

namespace App\Modules\Npds\Core;

use Npds\view\View;
use Npds\Config\Config;
use App\Modules\Npds\Support\AlertNpds;
use App\Controllers\Core\BaseController;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Npds\Support\Facades\Language;


class AdminController extends BaseController
{

    /**
     * [$layout description]
     *
     * @var [type]
     */
    protected $layout = 'backend';

    /**
     * [$template description]
     *
     * @var [type]
     */
    protected $template = 'Npdsboost_sk';

    /**
     * [$admintest description]
     *
     * @var [type]
     */
    protected $admintest = false;
    
    /**
     * [$super_admintest description]
     *
     * @var [type]
     */
    protected $super_admintest = false;

    /**
     * [$radminsuper description]
     *
     * @var [type]
     */
    protected $radminsuper = false;

    /**
     * Undocumented variable
     *
     * @var boolean
     */
    protected $admin = false;

    /**
     * Undocumented variable
     *
     * @var boolean
     */
    protected $admin_cookie = false;

    /**
     * [$aid description]
     *
     * @var [type]
     */
    protected $aid = null;

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
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom;

    /**
     * [$f_titre description]
     *
     * @var [type]
     */
    protected $f_titre;


    /**
     * Call the parent construct
     */
    public function __construct()
    {
        // 
        $this->admin = Auth::guard('admin');

        //
        $this->admin_cookie = auth::check('admin');

        //
        $this->admin_test();

        parent::__construct();
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        //
        $this->admindroits();

        if ($this->radminsuper != 1) {
            Access_Error();
        }

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

        //
        $this->GraphicAdmin();

        //
        if($this->adminhead) {
            $this->adminhead();
        }

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    // Admin est, Admin Droits

    /**
     * [admin_test description]
     *
     * @return  [type]  [return description]
     */
    private function admin_test()
    {
        if (isset($this->admin) and ($this->admin != '')) {
            
            $Xadmin = base64_decode($this->admin_cookie);
            $Xadmin = explode(':', $Xadmin);

            $aid    = urlencode($Xadmin[0]);
            $AIpwd  = $Xadmin[1];
        
            if ($aid == '' or $AIpwd == '') {
                $this->admin_alert('Null Aid or Passwd');
            }
        
            $result = sql_query("SELECT pwd, radminsuper FROM authors WHERE aid = '$aid'");
        
            if (!$result) {
                $this->admin_alert("DB not ready #2 : $aid / $AIpwd");

            } else {
                list($AIpass, $Xsuper_admintest) = sql_fetch_row($result);
        
                if (md5($AIpass) == $AIpwd and $AIpass != '') {
                    $this->admintest        = true;
                    $this->super_admintest  = $Xsuper_admintest;
                    $this->aid              = $aid;
                } else {
                    $this->admin_alert("Password in Cookies not Good #1 : $aid / $AIpwd");
                }
            }
        
            unset($AIpass);
            unset($AIpwd);
            unset($Xadmin);

            unset($Xsuper_admintest);
        }        
    }

    /**
     * [admindroits description]
     *
     * @return  [type]  [return description]
     */
    protected function admindroits()
    {
        $res = sql_query("SELECT fnom, radminsuper 
                        FROM authors a 
                        LEFT JOIN droits d 
                        ON a.aid = d.d_aut_aid 
                        LEFT JOIN fonctions f 
                        ON d.d_fon_fid = f.fdroits1 
                        WHERE a.aid='$this->aid'");
        
        $foncts = array();
        $supers = array();
        
        while ($data = sql_fetch_row($res)) {
            $foncts[] = $data[0];
            $supers[] = $data[1];
        }
    
        if ((!in_array('1', $supers)) and (!in_array($this->f_meta_nom, $foncts))) {
            $this->access_error();
        }
    
        $this->radminsuper = $supers[0];
    }
    
    // Graphic Admin, Admin Head

    /**
     * [adminhead description]
     *
     * @return  [type]  [return description]
     */
    protected function adminhead()
    {
        global $adm_img_mod, $ModPath;
        
        list($furlscript, $ficone) = sql_fetch_row(sql_query("SELECT furlscript, ficone FROM fonctions WHERE fnom='$this->f_meta_nom'"));
        
        if (file_exists(Config::get('npds.adminimg') . $ficone . '.' . Config::get('npds.admf_ext'))) {
            $img_adm = '<img src="' . site_url(Config::get('npds.adminimg') . $ficone . '.' . Config::get('npds.admf_ext')) . '" class="vam " alt="' . $this->f_titre . '" />';
        
        // A revoir 
        } elseif (stristr($_SERVER['QUERY_STRING'], "Extend-Admin-SubModule") || $adm_img_mod == 1) {
        // } elseif ($adm_img_mod == 1) {
            
            if (file_exists('modules/' . $ModPath . '/' . $ModPath . '.' . Config::get('npds.admf_ext'))) {
                $img_adm = '<img src="'. site_url('modules/' . $ModPath . '/' . $ModPath . '.' . Config::get('npds.admf_ext')) . '" class="vam" alt="' . $this->f_titre . '" />';
            } else {
                $img_adm = '';
            }
        } else {
            $img_adm = '';
        }
    
        $entete_adm = '<div id="message"></div><div id="adm_workarea" class="adm_workarea">' . "\n" . '   <h2><a href="' . site_url($furlscript) . '" >' . $img_adm . '&nbsp;' . $this->f_titre . '</a></h2>';
        
        //echo $entete_adm;

        View::share('adminhead', $entete_adm);

    }

    /**
     * [GraphicAdmin description]
     *
     * @return  [type]  [return description]
     */
    protected function GraphicAdmin()
    {

        // construction des blocs menu : selection de fonctions actives ayant une interface graphique de premier niveau et dont l'administrateur 
        // connecté en posséde les droits d'accès
        //vd($this->admin, $this->admin_cookie);

        $bloc_foncts = '';
        $bloc_foncts_A = '';

        $Q = sql_fetch_assoc(sql_query("SELECT * FROM authors WHERE aid='$this->aid' LIMIT 1"));

        // on prend tout ce qui a une interface 
        $R = $Q['radminsuper'] == 1 ?
            sql_query("SELECT * FROM fonctions f WHERE f.finterface =1 AND f.fetat != '0' ORDER BY f.fcategorie, f.fordre") :
            sql_query("SELECT * FROM fonctions f LEFT JOIN droits d ON f.fdroits1 = d.d_fon_fid LEFT JOIN authors a ON d.d_aut_aid =a.aid WHERE f.finterface =1 AND fetat!=0 AND d.d_aut_aid='$aid' AND d.d_droits REGEXP'^1' ORDER BY f.fcategorie, f.fordre");
        
        $j = 0;
    
        while ($SAQ = sql_fetch_assoc($R)) {
    
            $cat[]      = $SAQ['fcategorie'];
            $cat_n[]    = $SAQ['fcategorie_nom'];
            $fid_ar[]   = $SAQ['fid'];

            // if ($SAQ['fcategorie'] == 6 or ($SAQ['fcategorie'] == 9 and strstr($SAQ['furlscript'], "op=Extend-Admin-SubModule"))) {
            //if ($SAQ['fcategorie'] == 6 and ($SAQ['fcategorie'] != 9)) {

            if ($SAQ['fcategorie'] == 6) {

                if (file_exists(module_path($SAQ['fnom'] . '/'. Config::get('npds.adminimg') . $SAQ['ficone'] . '.' . Config::get('npds.admf_ext')))) {
                    $adminico = 'modules/'.$SAQ['fnom'] . '/'. Config::get('npds.adminimg') . $SAQ['ficone'] . '.' . Config::get('npds.admf_ext');
                } else {
                    $adminico = Config::get('npds.adminimg') . 'module.' . Config::get('npds.admf_ext');
                }
            } else {

                if (file_exists(module_path( $SAQ['fnom'] . '/'. Config::get('npds.adminimg') . $SAQ['ficone'] . '.' . Config::get('npds.admf_ext')))) {
                    $adminico = 'modules/'. $SAQ['fnom'] . '/'. Config::get('npds.adminimg') . $SAQ['ficone'] . '.' . Config::get('npds.admf_ext');
                } else {
                    $adminico = Config::get('npds.adminimg') . $SAQ['ficone'] . '.' . Config::get('npds.admf_ext');
                }
            }
    
            if ($SAQ['fcategorie'] == 9) {

                $bloc_foncts_A .= AlertNpds::check($SAQ, $adminico, $this->aid, $Q['radminsuper']);
    
                //array_pop($cat_n);
            } else {
                $ul_o = '
                <h4 class="text-muted"><a class="tog" id="hide_' . strtolower(substr($SAQ['fcategorie_nom'], 0, 3)) . '" title="' . __d('npds', 'Replier la liste') . '" style="clear:left;"><i id="i_' . strtolower(substr($SAQ['fcategorie_nom'], 0, 3)) . '" class="fa fa-caret-up fa-lg text-primary" ></i></a>&nbsp;' . $SAQ['fcategorie_nom'] . '</h4>
                <ul id="' . strtolower(substr($SAQ['fcategorie_nom'], 0, 3)) . '" class="list" style="clear:left;">';
                
                $li_c = '
                <li id="' . $SAQ['fid'] . '"  data-bs-toggle="tooltip" data-bs-placement="top" title="';
    
                $li_c .= $SAQ['fcategorie'] == 6 ? $SAQ['fnom_affich'] : $SAQ['fnom_affich'];
    
                // lancement du FileManager 
                $blank = '';
                if ($SAQ['fnom'] == "FileManager") {
                    if (file_exists("modules/f-manager/users/" . strtolower($this->aid) . ".conf.php")) {
    
                        include("modules/f-manager/users/" . strtolower($this->aid) . ".conf.php");
                        
                        if (!$npds_fma) {
                            $blank = ' target="_blank"';
                        }
                    }
                }
    
                $li_c .= '"><a class="btn btn-outline-primary" href="' . site_url($SAQ['furlscript']) .'" '. $blank . '>';
    
                if (Config::get('npds.admingraphic') == 1) {
                    $li_c .= '<img class="adm_img" src="' . site_url($adminico) . '" alt="icon_' . $SAQ['fnom_affich'] . '" loading="lazy" />';
                } else {
                    $li_c .= $SAQ['fcategorie'] == 6 ? $SAQ['fnom_affich'] : $SAQ['fnom_affich'];
                }

                $li_c .= '</a></li>';
    
                $ul_f = '';
                if ($j !== 0) {
                    $ul_f = '
                    </ul>
                    <script type="text/javascript">
                    //<![CDATA[
                        $( document ).ready(function() {
                        tog(\'' . strtolower(substr($cat_n[($j - 1)], 0, 3)) . '\',\'show_' . strtolower(substr($cat_n[$j - 1], 0, 3)) . '\',\'hide_' . strtolower(substr($cat_n[$j - 1], 0, 3)) . '\');
                        })
                    //]]>
                    </script>';
                }

                if ($j == 0) {
                    $bloc_foncts .= $ul_o . $li_c;
                } else {
                    if ($j > 0 and $cat[$j] > $cat[$j - 1]) {
                        $bloc_foncts .= $ul_f . $ul_o . $li_c;
                    } else {
                        $bloc_foncts .= $li_c;
                    }
                }
            }
            $j++;
        }
    
        if (isset($cat_n)) {
            $ca = array();
            $ca = array_unique($cat_n);
            $ca = array_pop($ca);
    
            $bloc_foncts .= '
            </ul>
            <script type="text/javascript">
                //<![CDATA[
                $( document ).ready(function() {
                    tog(\'' . strtolower(substr($ca, 0, 3)) . '\',\'show_' . strtolower(substr($ca, 0, 3)) . '\',\'hide_' . strtolower(substr($ca, 0, 3)) . '\');
                })
                //]]>
            </script>';
        }
    
        $GraphicAdmin = "
        <script type=\"text/javascript\">
        //<![CDATA[
        /* 
        $( document ).ready(function () {
            $( '#lst_men_main ul' ).each(function() {
                var idi= $(this).attr('id'),
                    eu= Cookies.get('eu_'+idi),
                    eb= Cookies.get('eb_'+idi),
                    es= Cookies.get('es_'+idi),
                    et= Cookies.get('et_'+idi);
                $('#i_'+idi).attr('class',eb);
                $(this).attr('style',eu);
                $('a.tog[id$=\"'+idi+'\"]').attr('id',es);
                $('a.tog[id$=\"'+idi+'\"]').attr('title',et);
            });
        });
        
        $( window ).unload(function() {
            $( '#lst_men_main ul' ).each(function( index ) {
                var idi= $(this).attr('id'),
                    sty= $(this).attr('style'),
                    idsp= $('a.tog[id$=\"'+idi+'\"]').attr('id'),
                    tisp= $('a.tog[id$=\"'+idi+'\"]').attr('title'),
                    cla= $('#i_'+idi).attr('class');
                Cookies.set('et_'+idi,tisp);
                Cookies.set('es_'+idi,idsp);
                Cookies.set('eu_'+idi,sty);
                Cookies.set('eb_'+idi,cla);
            });
        });
        */
        function openwindow(){
            window.open (\"$this->hlpfile\",\"Help\",\"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=400\");
        }
    
            $( document ).ready(function () {
                $('ul.list').sortable({
                    update: function(){
                Cookies.set('items', getItems('#lst_men_main'));
                console.log(Cookies.get('items'));
            }
    
        });
    
        var htmmll=[];
        // Get all items from a container
        function getItems(container) {
            var columns = [];
            $(container+ ' ul.list').each(function(){
            columns.push($(this).sortable('toArray').join(','));
            htmmll.push($(this).html());
            });
            return columns.join('|');
    
        }
        var itemStr = getItems('#lst_men_main');
        //console.log(htmmll);
    
                $('[data-bs-toggle=\"tooltip\"]').tooltip();
                $('[data-bs-toggle=\"popover\"]').popover();
                $('table').on('all.bs.table', function (e, name, args) {
                    $('[data-bs-toggle=\"tooltip\"]').tooltip();
                    $('[data-bs-toggle=\"popover\"]').popover();
                });
            });
    
            //==>date d'expiration connection admin
            $(function () {
                var dae = Cookies.get('adm_exp')*1000,
                dajs = new Date(dae);
                console.log(Cookies.get('adm_exp'));//
    
            $('#adm_connect_status').attr('title', 'Connexion ouverte jusqu\'au : '+dajs.getDate()+'/'+ (dajs.getMonth()+1) +'/'+ dajs.getFullYear() +'/'+ dajs.getHours() +':'+ dajs.getMinutes()+':'+ dajs.getSeconds()+' GMT');
    
            deCompte= function() {
            var date1 = new Date(), sec = (dae - date1) / 1000, n = 24 * 3600;
                if (sec > 0) {
                j = Math.floor (sec / n);
                h = Math.floor ((sec - (j * n)) / 3600);
                mn = Math.floor ((sec - ((j * n + h * 3600))) / 60);
                sec = Math.floor (sec - ((j * n + h * 3600 + mn * 60)));
                $('#tempsconnection').text(j +'j '+ h +':'+ mn +':'+sec);
                }
            t_deCompte=setTimeout (deCompte, 1000);
            }
            deCompte();
            })
            //<== date d'expiration connection admin
        
        tog = function(lst,sho,hid){
            $('#adm_men, #adm_workarea').on('click', 'a.tog', function() {
                var buttonID = $(this).attr('id');
                lst_id = $('#'+lst);
                i_id=$('#i_'+lst);
                btn_show=$('#'+sho);
                btn_hide=$('#'+hid);
                if (buttonID == sho) {
                    lst_id.fadeIn(1000);//show();
                    btn_show.attr('id',hid)
                    btn_show.attr('title','" . __d('npds', 'Replier la liste') . "');
                    i_id.attr('class','fa fa-caret-up fa-lg text-primary me-1');
                } else if (buttonID == hid) {
                    lst_id.fadeOut(1000);//hide();
                    btn_hide=$('#'+hid);
                    btn_hide.attr('id',sho);
                    btn_hide.attr('title','" . html_entity_decode(__d('npds', 'Déplier la liste'), ENT_QUOTES | ENT_HTML401, cur_charset) . "');
                    i_id.attr('class','fa fa-caret-down fa-lg text-primary me-1');
                }
            });
        };
        //]]>
        </script>\n";
    
        $adm_ent = '';
    
        $adm_ent .= '
        <div id="adm_tit" class="row">
            <div id="adm_tit_l" class="col-12">
                <h1>' . __d('npds', 'Administration') . '</h1>
            </div>
        </div>
        <div id ="adm_men" class="mb-4">
            <div id="adm_header" class="row justify-content-end">
                <div class="col-6 col-lg-6 men_tit align-self-center">
                    <h2><a href="admin.php">' . __d('npds', 'Menu') . '</a></h2>
                </div>
                <div id="adm_men_man" class="col-6 col-lg-6 men_man text-end">
                    <ul class="liste" id="lst_men_top">
                    <li data-bs-toggle="tooltip" title="' . __d('npds', 'Déconnexion') . '" >
                        <a class="btn btn-outline-danger btn-sm" href="admin.php?op=logout" >
                            <i class="fas fa-sign-out-alt fa-2x">
                        </i>
                    </a>
                </li>';
        
        if ($this->hlpfile) {
            $adm_ent .= '
            <li class="ms-2" data-bs-toggle="tooltip" title="' . __d('npds', 'Manuel en ligne') . '">
                <a class="btn btn-outline-primary btn-sm" href="javascript:openwindow();">
                    <i class="fa fa-question-circle fa-2x"></i>
                </a>
            </li>';
        }
    
        $adm_ent .= '
                    </ul>
                </div>
            </div>
            <div id="adm_men_dial" class="border rounded px-2 py-2" >
                <div id="adm_men_alert" >
                    <div id="alertes">
                    ' . Language::aff_langue($bloc_foncts_A) . '
                    </div>
                </div>
            </div>  
        '. AlertNpds::display();
    
        $GraphicAdmin .= $adm_ent;
    
        if ($this->short_menu_admin != false) {
            $GraphicAdmin .= '</div>';
            
            View::share('GraphicAdmin', $GraphicAdmin);
            return;
        }
    
        $GraphicAdmin .= '
            <div id="adm_men_corps" class="my-3" >
                <div id="lst_men_main">
                    ' . $bloc_foncts . '
                </div>
            </div>
        </div> ';
    
        $this->radminsuper;

        View::share('GraphicAdmin', $GraphicAdmin);
    }

    // Admin Error, Allert, Denied

    /**
     * Undocumented function
     *
     * @param [type] $motif
     * @return void
     */
    protected function admin_alert($motif)
    {
        global $admin;

        Cookie::set("admin", null);

        unset($admin);

        unset($this->admin);
        unset($this->admin_cookie);

        Ecr_Log('security', 'auth.inc.php/Admin_alert : ' . $motif, '');

        $Titlesitename = 'Npds';

        if (file_exists("storage/meta/meta.php"))
            include("storage/meta/meta.php");

        echo '
            </head>
            <body>
                <br /><br /><br />
                <p style="font-size: 24px; font-family: Tahoma, Arial; color: red; text-align:center;"><strong>.: ' . __d('npds', 'Votre adresse Ip est enregistrée') . ' :.</strong></p>
            </body>
        </html>';

        die();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function access_error()
    {
        //include(module_path("Npds/Controllers/Admin/Die.php"));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function access_denied()
    {
        //include(module_path("Npds/Controllers/Admin/Die.php"));
    }

    // Getters

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function get_radminsuper()
    {
        return $this->radminsuper;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function get_aid()
    {
        return $this->aid;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function get_admin()
    {
        return $this->admin;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function get_admin_cookie()
    {
        return $this->admin_cookie;
    }

}
