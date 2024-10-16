<?php

namespace Modules\Minisites\Library;

use Npds\Routing\Url;
use Npds\Config\Config;
use Modules\Npds\Support\Facades\Language;
use Shared\Editeur\Support\Facades\Editeur;
use Modules\Minisites\Contracts\MinisiteInterface;


class MinisiteManager implements MinisiteInterface 
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
     * @param [type] $typeL
     * @return void
     */
    public function minisite_win_upload($typeL)
    {
        // Modules\Upload\Support\helper.php
        return minisite_win_upload($typeL);
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $blog_dir
     * @param [type] $op
     * @param [type] $startpage
     * @param [type] $action
     * @param [type] $adminblog
     * @return void
     */
    public function readnews($blog_dir, $op, $startpage, $action, $adminblog)
    {
        $content = '';
    
        $blog_file = $blog_dir . 'news.txt';

        if (!file_exists($blog_file)) {
            $fp = fopen($blog_file, 'w');
            fclose($fp);
        }
    
        $xnews = file($blog_file);
        $xnews = array_reverse($xnews);
        $startpage -= 1;
        $ubound = count($xnews);
    
        if ($startpage < 0 || $startpage >= $ubound / Config::get('npds.perpage')) {
            $startpage = 0;
        }
    
        $contentT = '';

        if ($ubound > Config::get('npds.perpage')) {
            $contentT .= '
                <nav>
                    <ul class="pagination pagination-sm d-flex flex-wrap my-2">';
    
            for ($j = 1; $j <= ceil($ubound / Config::get('npds.perpage')); $j++) {
                $contentT .= ($j == $startpage + 1)
                    ? '<li class=" page-item active"><a class="page-link" href="#">' . $j . '</a></li>'
                    : '<li class="page-item"><a href="minisite.php?op=' . $op . '&amp;startpage=' . $j . '" class="page-link blog_lien">' . $j . '</a></li>';
            }
    
            $contentT .= '
                    </ul>
                </nav>';
        }
    
        if ($adminblog) {
            // Suppression
            if (substr($action, 0, 1) == 'D') {
                @copy($blog_file, $blog_file . '.bak');
                $index = substr($action, 1);
                unset($xnews[$index]);
    
                $xnews = array_reverse($xnews);
                $fp = fopen($blog_file, "w");
    
                for ($j = 0; $j < count($xnews); $j++) {
                    fwrite($fp, $xnews[$j]);
                }
    
                fclose($fp);
                Url::redirect("minisite.php?op=$op");
            }
    
            // Ajouter - Ecriture
            if (substr($action, 0, 3) == 'AOK') {
                global $title, $story;
    
                @copy($blog_file, $blog_file . '.bak');
                $fp = fopen($blog_file, "a");
    
                if (!Config::get('npds.tiny_mce')) {
                    $formatted = str_replace("\r\n", '<br />', $story);
                    $formatted = str_replace('<img', '<img class="img-fluid" ', $story);
                    $formatted = str_replace("\n", '<br />', $formatted);
                } else {
                    $formatted = str_replace("\r\n", '', $story);
                    $formatted = str_replace("\n", '', $formatted);
                }
    
                $newsto = date("d m Y") . '!;!' . $title . '!;!' . $formatted;
                $newsto = dataimagetofileurl($newsto, 'storage/users_private/' . $op . '/mns');
    
                fwrite($fp, StripSlashes($newsto) . "\n");
                fclose($fp);
                Url::redirect("minisite.php?op=$op");
            }
    
            // Ajouter
            if (substr($action, 0, 1) == 'A') {
                $content .= '
                    <form name="adminForm" method="post" action="minisite.php?op=' . $op . '&action=AOK">
                        <div class="mb-3 row">
                        <label class="form-label" for="title">' . __d('Minisites', '"Titre') . '</label>
                        <div class="col-sm-12">
                            <input class="form-control" type="text" name="title" />
                        </div>
                        </div>
                        <div class="mb-3 row">
                        <label class="form-label" for="story">' . __d('Minisites', '"Texte complet') . '</label>
                        <div class="col-sm-12">
                            <textarea class="tin form-control" name="story" rows="25"></textarea>';
    
                $content .= "&nbsp;!blog_editeur!";
    
                $content .= '
                        </div>
                        </div>
                        <div class="mb-3 row">
                        <div class="col-sm-12">
                            <input class="btn btn-primary" type="submit" name="submit" value="' . __d('Minisites', '"Valider') . '" />
                        </div>
                        </div>
                    </form>';
            }
    
            // Modifier - Ecriture
            if (substr($action, 0, 3) == 'MOK') {
                global $title, $story, $index;
    
                @copy($blog_file, $blog_file . ".bak");
    
                if (!Config::get('npds.tiny_mce')) {
                    $formatted = str_replace("\r\n", '<br />', $story);
                    $formatted = str_replace('<img', '<img class="img-fluid" ', $story); // a revoir ??
                    $formatted = str_replace("\n", '<br />', $formatted);
                } else {
                    $formatted = str_replace("\r\n", '', $story);
                    $formatted = str_replace("\n", '', $formatted);
                }
    
                $newsto = date("d m Y") . '!;!' . $title . '!;!' . $formatted;
                $newsto = dataimagetofileurl($newsto, 'storage/users_private/' . $op . '/mns');
                $xnews[$index] = StripSlashes($newsto) . "\n";
                $xnews = array_reverse($xnews);
                $fp = fopen($blog_file, "w");
    
                for ($j = 0; $j < count($xnews); $j++) {
                    fwrite($fp, $xnews[$j]);
                }
    
                fclose($fp);
                Url::redirect("minisite.php?op=$op");
            }
    
            // Modifier
            if (substr($action, 0, 1) == 'M') {
                $index = substr($action, 1);
                $crtsplit = explode("!;!", $xnews[$index]);
                $videoprovider = array('yt', 'vm', 'dm');
    
                foreach ($videoprovider as $v) {
                    $crtsplit[2] = preg_replace('#(' . $v . ')_(video)\((.*[^\)])\)#m', '[\2_\1]\3[/\2_\1]', $crtsplit[2]);
                }
    
                $content .= '
                    <form name="adminForm" method="post" action="minisite.php?op=' . $op . '&action=MOK&index=' . $index . '">
                        <div class="mb-3">
                        <label class="form-label" for="title">' . __d('Minisites', '"Titre') . '</label>
                        <input class="form-control" type="text" name="title" value="' . $crtsplit[1] . '" />
                        </div>
                        <div class="mb-3">
                        <label class="form-label" for="story" >' . __d('Minisites', '"Texte complet') . '</label>
                        <textarea class="tin form-control" name="story" rows="25">' . str_replace("\n", "", $crtsplit[2]) . '</textarea>';
    
                $content .= "&nbsp;!blog_editeur!";
    
                $content .= '
                        </div>
                        <div class="mb-3">
                        <input class="btn btn-primary" type="submit" name="submit" value="' . __d('Minisites', '"Valider') . '" />
                        </div>
                    </form>
                    #v_yt#';
            }
        }
    
        // Output
        $new_pages = false;
        for ($i = $startpage * Config::get('npds.perpage'); $i < $startpage * Config::get('npds.perpage') + Config::get('npds.perpage') && $i < $ubound; $i++) {
            $crtsplit = explode('!;!', $xnews[$i]);
            $actionM = '<a class="" href="minisite.php?op=' . $op . '&amp;action=M' . $i . '" title="' . __d('Minisites', '"Modifier') . '" data-bs-toggle="tooltip" ><i class="fa fa-edit fa-lg me-1"></i></a>';
            $actionD = '<a class="" href="minisite.php?op=' . $op . '&amp;action=D' . $i . '" title="' . __d('Minisites', '"Effacer') . '" data-bs-toggle="tooltip"><i class="fas fa-trash fa-lg text-danger"></i></a>';
    
            $content .= '
                <div class="card mb-3">
                    <div class="card-body">
                        <h2 class="card-title">' . Language::aff_langue($crtsplit[1]) . '</h2>
                        <h6 class="card-subtitle text-muted">' . __d('Minisites', '"Posté le ') . ' ' . $crtsplit[0] . '</h6>
                    </div>
                    <div class=" card-body">' . $this->convert_ressources($crtsplit[2]) . '</div>';
    
            if ($adminblog) {
                $content .= '
                    <div class="card-footer">
                        ' . $actionM . '&nbsp;' . $actionD . '
                    </div>';
            }
    
            $content .= '
                </div>';
        }
    
        if (substr($contentT, 13) != '') {
            $content .= substr($contentT, 13);
        }
    
        $content .= "\n";
    
        return $content;
    }    

    /**
     * Undocumented function
     *
     * @param [type] $Xstring
     * @return void
     */
    public function MNSremoveHack($Xstring)
    {
        global $op, $userdata, $tiny_mce_theme, $tiny_mce_relurl;
    
        static $blog_editor;
        
        if (Config::get('npds.tiny_mce')) {
            if (!$blog_editor) {
                $tiny_mce_theme = 'full';
                $blog_editor = Editeur::aff_editeur('tiny_mce', 'begin') . Editeur::aff_editeur('story', 'false') . Editeur::aff_editeur('tiny_mce', 'end');
            }
        }
    
        $App_forbidden_words = array(
            // NCRs 2 premières séquences = NCR (dec|hexa) correspondant aux caractères latin de la table ascii (code ascii entre 33 et 126)
            //      2 dernières séquences = NCR (dec|hexa) correspondant aux caractères latin du bloc unicode Halfwidth and Fullwidth Forms.
            //        Leur signification est identique à celle des caractères latin de la table ascii dont le code ascii est entre 33 et 126.
            // JPB for App 2005
            "'&#(33|x21|65281|xFF01);'i" => chr(33),
            "'&#(34|x22|65282|xFF02);'i" => chr(34),
            "'&#(35|x23|65283|xFF03);'i" => chr(35),
            "'&#(36|x24|65284|xFF04);'i" => chr(36),
            "'&#(37|x25|65285|xFF05);'i" => chr(37),
            "'&#(38|x26|65286|xFF06);'i" => chr(38),
            "'&#(39|x27|65287|xFF07);'i" => chr(39),
            "'&#(40|x28|65288|xFF08);'i" => chr(40),
            "'&#(41|x29|65289|xFF09);'i" => chr(41),
            "'&#(42|x2A|65290|xFF0A);'i" => chr(42),
            "'&#(43|x2B|65291|xFF0B);'i" => chr(43),
            "'&#(44|x2C|65292|xFF0C);'i" => chr(44),
            "'&#(45|x2D|65293|xFF0D);'i" => chr(45),
            "'&#(46|x2E|65294|xFF0E);'i" => chr(46),
            "'&#(47|x2F|65295|xFF0F);'i" => chr(47),
            "'&#(48|x30|65296|xFF10);'i" => chr(48),
            "'&#(49|x31|65297|xFF11);'i" => chr(49),
            "'&#(50|x32|65298|xFF12);'i" => chr(50),
            "'&#(51|x33|65299|xFF13);'i" => chr(51),
            "'&#(52|x34|65300|xFF14);'i" => chr(52),
            "'&#(53|x35|65301|xFF15);'i" => chr(53),
            "'&#(54|x36|65302|xFF16);'i" => chr(54),
            "'&#(55|x37|65303|xFF17);'i" => chr(55),
            "'&#(56|x38|65304|xFF18);'i" => chr(56),
            "'&#(57|x39|65305|xFF19);'i" => chr(57),
            "'&#(58|x3A|65306|xFF1A);'i" => chr(58),
            "'&#(59|x3B|65307|xFF1B);'i" => chr(59),
            "'&#(60|x3C|65308|xFF1C);'i" => chr(60),
            "'&#(61|x3D|65309|xFF1D);'i" => chr(61),
            "'&#(62|x3E|65310|xFF1E);'i" => chr(62),
            "'&#(63|x3F|65311|xFF1F);'i" => chr(63),
            "'&#(64|x40|65312|xFF20);'i" => chr(64),
            "'&#(65|x41|65313|xFF21);'i" => chr(65),
            "'&#(66|x42|65314|xFF22);'i" => chr(66),
            "'&#(67|x43|65315|xFF23);'i" => chr(67),
            "'&#(68|x44|65316|xFF24);'i" => chr(68),
            "'&#(69|x45|65317|xFF25);'i" => chr(69),
            "'&#(70|x46|65318|xFF26);'i" => chr(70),
            "'&#(71|x47|65319|xFF27);'i" => chr(71),
            "'&#(72|x48|65320|xFF28);'i" => chr(72),
            "'&#(73|x49|65321|xFF29);'i" => chr(73),
            "'&#(74|x4A|65322|xFF2A);'i" => chr(74),
            "'&#(75|x4B|65323|xFF2B);'i" => chr(75),
            "'&#(76|x4C|65324|xFF2C);'i" => chr(76),
            "'&#(77|x4D|65325|xFF2D);'i" => chr(77),
            "'&#(78|x4E|65326|xFF2E);'i" => chr(78),
            "'&#(79|x4F|65327|xFF2F);'i" => chr(79),
            "'&#(80|x50|65328|xFF30);'i" => chr(80),
            "'&#(81|x51|65329|xFF31);'i" => chr(81),
            "'&#(82|x52|65330|xFF32);'i" => chr(82),
            "'&#(83|x53|65331|xFF33);'i" => chr(83),
            "'&#(84|x54|65332|xFF34);'i" => chr(84),
            "'&#(85|x55|65333|xFF35);'i" => chr(85),
            "'&#(86|x56|65334|xFF36);'i" => chr(86),
            "'&#(87|x57|65335|xFF37);'i" => chr(87),
            "'&#(88|x58|65336|xFF38);'i" => chr(88),
            "'&#(89|x59|65337|xFF39);'i" => chr(89),
            "'&#(90|x5A|65338|xFF3A);'i" => chr(90),
            "'&#(91|x5B|65339|xFF3B);'i" => chr(91),
            "'&#(92|x5C|65340|xFF3C);'i" => chr(92),
            "'&#(93|x5D|65341|xFF3D);'i" => chr(93),
            "'&#(94|x5E|65342|xFF3E);'i" => chr(94),
            "'&#(95|x5F|65343|xFF3F);'i" => chr(95),
            "'&#(96|x60|65344|xFF40);'i" => chr(96),
            "'&#(97|x61|65345|xFF41);'i" => chr(97),
            "'&#(98|x62|65346|xFF42);'i" => chr(98),
            "'&#(99|x63|65347|xFF43);'i" => chr(99),
            "'&#(100|x64|65348|xFF44);'i" => chr(100),
            "'&#(101|x65|65349|xFF45);'i" => chr(101),
            "'&#(102|x66|65350|xFF46);'i" => chr(102),
            "'&#(103|x67|65351|xFF47);'i" => chr(103),
            "'&#(104|x68|65352|xFF48);'i" => chr(104),
            "'&#(105|x69|65353|xFF49);'i" => chr(105),
            "'&#(106|x6A|65354|xFF4A);'i" => chr(106),
            "'&#(107|x6B|65355|xFF4B);'i" => chr(107),
            "'&#(108|x6C|65356|xFF4C);'i" => chr(108),
            "'&#(109|x6D|65357|xFF4D);'i" => chr(109),
            "'&#(110|x6E|65358|xFF4E);'i" => chr(110),
            "'&#(111|x6F|65359|xFF4F);'i" => chr(111),
            "'&#(112|x70|65360|xFF50);'i" => chr(112),
            "'&#(113|x71|65361|xFF51);'i" => chr(113),
            "'&#(114|x72|65362|xFF52);'i" => chr(114),
            "'&#(115|x73|65363|xFF53);'i" => chr(115),
            "'&#(116|x74|65364|xFF54);'i" => chr(116),
            "'&#(117|x75|65365|xFF55);'i" => chr(117),
            "'&#(118|x76|65366|xFF56);'i" => chr(118),
            "'&#(119|x77|65367|xFF57);'i" => chr(119),
            "'&#(120|x78|65368|xFF58);'i" => chr(120),
            "'&#(121|x79|65369|xFF59);'i" => chr(121),
            "'&#(122|x7A|65370|xFF5A);'i" => chr(122),
            "'&#(123|x7B|65371|xFF5B);'i" => chr(123),
            "'&#(124|x7C|65372|xFF5C);'i" => chr(124),
            "'&#(125|x7D|65373|xFF5D);'i" => chr(125),
            "'&#(126|x7E|65374|xFF5E);'i" => chr(126),
            // Fin des NCRs
    
            //"'&#'i"=>"&_#", // JPB remplacement pour l'extension chinoise en prenant
            // en compte => http://www.w3.org/TR/2003/NOTE-unicode-xml-20030613/#Suitable
            "'&#(8232|x2028);'i" => "_",
            "'&#(8233|x2029);'i" => "_",
    
            "'&#(8234|x202A);'i" => "_",
            "'&#(8235|x202B);'i" => "_",
            "'&#(8236|x202C);'i" => "_",
            "'&#(8237|x202D);'i" => "_",
            "'&#(8238|x202E);'i" => "_",
    
            "'&#(8298|x206A);'i" => "_",
            "'&#(8299|x206B);'i" => "_",
            "'&#(8300|x206C);'i" => "_",
            "'&#(8301|x206D);'i" => "_",
            "'&#(8302|x206E);'i" => "_",
            "'&#(8303|x206F);'i" => "_",
    
            "'&#(65529|xFFF9);'i" => "_",
            "'&#(65530|xFFFA);'i" => "_",
            "'&#(65531|xFFFB);'i" => "_",
            "'&#(65532|xFFFC);'i" => "_",
            "'&#(65279|xFEFF);'i" => "&#x2060;",
    
            "'&#(119155|x1D173);'i" => "_",
            "'&#(119156|x1D174);'i" => "_",
            "'&#(119157|x1D175);'i" => "_",
            "'&#(119158|x1D176);'i" => "_",
            "'&#(119159|x1D177);'i" => "_",
            "'&#(119160|x1D178);'i" => "_",
            "'&#(119161|x1D179);'i" => "_",
            "'&#(119162|x1D17A);'i" => "_",
    
            "'&#xE000(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i" => "_",
            "'&#xE001(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i" => "_",
            "'&#xE002(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i" => "_",
            "'&#xE003(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i" => "_",
            "'&#xE004(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i" => "_",
            "'&#xE005(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i" => "_",
            "'&#xE006(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i" => "_",
            "'&#xE007(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i" => "_",
    
            "'&#91750(4|5|6|7|8|9);'i" => "_",
            "'&#91751(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91752(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91753(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91754(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91755(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91756(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91757(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91758(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91759(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91760(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91761(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91762(0|1|2|3|4|5|6|7|8|9);'i" => "_",
            "'&#91763(0|1|);'i" => "_",
            // Fin
    
            "'from:'i" => "!from:!",
            "'subject:'i" => "!subject:!",
            "'bcc:'i" => "!bcc:!",
            "'mime-version:'i" => "!mime-version:!",
            "'base64'i" => "base_64",
            "'content-type:'i" => "!content-type:!",
            "'content-transfer-encoding:'i" => "!content-transfer-encoding:!",
            "'content-disposition:'i" => "!content-disposition:!",
            "'content-location:'i" => "!content-location:!",
            "'include'i" => "!include!",
            "'<script'i" => "&lt;script",
            "'</script'i" => "&lt;/script",
            "'javascript'i" => "!javascript!",
            "'embed'i" => "!embed!",
            "'iframe'i" => "!iframe!",
            "'refresh'i" => "!refresh!",
            "'document\.cookie'i" => "!document.cookie!",
            "'onload'i" => "!onload!",
            "'onstart'i" => "!onstart!",
            "'onerror'i" => "!onerror!",
            "'onkey'i" => "!onkey!",
            "'onmouse'i" => "!onmouse!",
            "'onclick'i" => "!onclick!",
            "'ondblclick'i" => "!ondblclick!",
            "'onhelp'i" => "!onhelp!",
            "'onmousedown'i" => "!onmousedown!",
            "'onmousemove'i" => "!onmousemove!",
            "'onmouseout'i" => "!onmouseout!",
            "'onmouseover'i" => "!onmouseover!",
            "'onmouseup'i" => "!onmouseup!",
            "'onblur'i" => "!onblur!",
            "'onafterupdate'i" => "!onafterupdate!",
            "'onbeforeupdate'i" => "!onbeforeupdate!",
            "'onkeydown'i" => "!onkeydown!",
            "'onkeypress'i" => "!onkeypress!",
            "'onkeyup'i" => "!onkeyup!",
            "'onfocus'i" => "!onfocus!",
            "'onunload'i" => "!onunload!",
            "'jscript'i" => "!jscript!",
            "'vbscript'i" => "!vbscript!",
            "'pearlscript'i" => "!pearlscript!",
            "'&#(8216|x2018);'i" => chr(39),
            "'&#(8217|x2019);'i" => chr(39),
            "'&#39;'i" => '\\\'',
            "'&#(8220|x201C);'i" => chr(34),
            "'&#(8221|x201D);'i" => chr(34),
            "'&#160;'i" => '&nbsp;',
            "'.htaccess'i" => "",
            "'!blog_editeur!'i" => $blog_editor,
            "'!l_blog_ajouterOK!'i" => '<a class="list-group-item list-group-item-action" href="minisite.php?op=' . $op . '&amp;action=A"><i class="fas fa-pencil-alt fa-lg me-2"></i> ' . __d('minisite', 'Ajouter un article') . '</a>',
            "'\<\?php'i" => "&lt;?php",
            "'\<\?'i" => "&lt;?",
            "'\?\>'i" => "?&gt;",
            "'\<\%'i" => "&lt;%",
            "'\%\>'i" => "%&gt;"
        );
    
        $Xstring = preg_replace(array_keys($App_forbidden_words), array_values($App_forbidden_words), $Xstring);
    
        return $Xstring;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $Xcontent
     * @return void
     */
    public function convert_ressources($Xcontent)
    {
        global $op;
    
        for ($i = 0; $i < strlen($Xcontent); $i++) {
            if (strtoupper(substr($Xcontent, $i, 4)) == "src=") {
                if ((strtoupper(substr($Xcontent, $i + 4, 3)) != "HTT") and (strtoupper(substr($Xcontent, $i + 4, 4)) != "\"HTT")) {
                    $Xcontent = substr_replace($Xcontent, "src=getfile.php?att_id=$op&amp;apli=minisite&amp;att_type=&amp;att_size=&amp;att_name=", $i, 4);
                }
                $i = $i + 4;
            }
        }
    
        return Language::aff_langue($Xcontent);
    }

}
