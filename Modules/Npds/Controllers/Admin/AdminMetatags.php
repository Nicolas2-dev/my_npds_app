<?php

namespace Modules\Npds\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class AdminMetatags extends AdminController
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
        // $f_meta_nom = 'MetaTagAdmin';
        // $f_titre = __d('npds', 'Administration des MétaTags');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit

        // include("admin/settings_save.php");
        

        // $hlpfile = "language/manuels/Config::get('npds.language')/metatags.html";
        
        // settype($meta_saved, 'bool');

        // switch ($op) {
        //     case 'MetaTagSave':
        //         $meta_saved = MetaTagSave("storage/meta/meta.php", $newtag);
        
        //         header("location: admin.php?op=MetaTagAdmin&meta_saved=$meta_saved");
        //         break;
        
        //     case 'MetaTagAdmin':
        //         MetaTagAdmin($meta_saved);
        //         break;
        // }        
    // }

    function MetaTagAdmin(bool $meta_saved = false)
    {
        $tags = GetMetaTags("storage/meta/meta.php");

        $sel = ' selected="selected"';
    
        echo '
        <hr />';
    
        if ($meta_saved)
            echo '
            <div class="alert alert-success">
                ' . __d('npds', 'Vos MétaTags ont été modifiés avec succès !') . '
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    
        echo '
        <form id="metatagsadm" action="admin.php" method="post">
            <div class="form-floating mb-3">
                <input class="form-control" id="newtagauthor" type="text" name="newtag[author]" value="' . $tags['author'] . '" maxlength="100">
                <label for="newtagauthor">' . __d('npds', 'Auteur(s)') . '</label>
                <span class="help-block">' . __d('npds', '(Ex. : nom du webmaster)') . '<span class="float-end ms-1" id="countcar_newtagauthor"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="newtagowner" type="text" name="newtag[owner]" value="' . $tags['owner'] . '" maxlength="100" />
                <label for="newtagowner">' . __d('npds', 'Propriétaire') . '</label>
                <span class="help-block">' . __d('npds', '(Ex. : nom de votre compagnie/service)') . '<span class="float-end ms-1" id="countcar_newtagowner"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="newtagreplyto" type="email" name="newtag[reply-to]" value="' . $tags['reply-to'] . '" maxlength="100" />
                <label for="newtagreplyto">' . __d('npds', 'Adresse e-mail principale') . '</label>
                <span class="help-block">' . __d('npds', '(Ex. : l\'adresse e-mail du webmaster)') . '<span class="float-end ms-1" id="countcar_newtagreplyto"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="newtagdescription" type="text" name="newtag[description]" value="' . $tags['description'] . '" maxlength="200" />
                <label for="newtagdescription">' . __d('npds', 'Description') . '</label>
                <span class="help-block">' . __d('npds', '(Brève description des centres d\'intérêt du site. 200 caractères maxi.)') . '<span class="float-end ms-1" id="countcar_newtagdescription"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="newtagkeywords" type="text" name="newtag[keywords]" value="' . $tags['keywords'] . '" maxlength="1000" />
                <label for="newtagkeywords">' . __d('npds', 'Mot(s) clé(s)') . '</label>
                <span class="help-block">' . __d('npds', '(Définissez un ou plusieurs mot(s) clé(s). 1000 caractères maxi. Remarques : une lettre accentuée équivaut le plus souvent à 8 caractères. La majorité des moteurs de recherche font la distinction minuscule/majuscule. Séparez vos mots par une virgule)') . '<span class="float-end ms-1" id="countcar_newtagkeywords"></span></span>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="newtagrating" name="newtag[rating]">
                    <option value="general"' . (!strcasecmp($tags['rating'], 'general') ? $sel : '') . '>' . __d('npds', 'Tout public') . '</option>
                    <option value="mature"' . (!strcasecmp($tags['rating'], 'mature') ? $sel : '') . '>' . __d('npds', 'Adulte') . '</option>
                    <option value="restricted"' . (!strcasecmp($tags['rating'], 'restricted') ? $sel : '') . '>' . __d('npds', 'Accés restreint') . '</option>
                    <option value="14 years"' . (!strcasecmp($tags['rating'], '14 years') ? $sel : '') . '>' . __d('npds', '14 ans') . '</option>
                </select>
                <label for="newtagrating">' . __d('npds', 'Audience') . '</label>
                <span class="help-block">' . __d('npds', '(Définissez le public intéressé par votre site)') . '</span>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="newtagdistribution" name="newtag[distribution]">
                    <option value="global"' . (!strcasecmp($tags['distribution'], 'global') ? $sel : '') . '>' . __d('npds', 'Large') . '</option>
                    <option value="local"' . (!strcasecmp($tags['distribution'], 'local') ? $sel : '') . '>' . __d('npds', 'Restreinte') . '</option>
                </select>
                <label for="newtagdistribution">' . __d('npds', 'Distribution') . '</label>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="newtagcopyright" type="text" name="newtag[copyright]" value="' . $tags['copyright'] . '" maxlength="100" />
                <label for="newtagcopyright">' . __d('npds', 'Copyright') . '</label>
                <span class="help-block">' . __d('npds', '(Informations légales)') . '<span class="float-end ms-1" id="countcar_newtagcopyright"></span></span>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="newtagrobots" name="newtag[robots]">
                    <option value="all"' . (!strcasecmp($tags['robots'], 'all') ? $sel : '') . '>' . __d('npds', 'Tout contenu (page/liens/etc)') . '</option>
                    <option value="none"' . (!strcasecmp($tags['robots'], 'none') ? $sel : '') . '>' . __d('npds', 'Aucune indexation') . '</option>
                    <option value="index,nofollow"' . (!strcasecmp($tags['robots'], 'index,nofollow') ? $sel : '') . '>' . __d('npds', 'Page courante sans liens locaux') . '</option>
                    <option value="noindex,follow"' . (!strcasecmp($tags['robots'], 'noindex,follow') ? $sel : '') . '>' . __d('npds', 'Liens locaux sauf page courante') . '</option>
                    <option value="noarchive"' . (!strcasecmp($tags['robots'], 'noarchive') ? $sel : '') . '>' . __d('npds', 'Pas d\'affichage du cache') . '</option>
                    <option value="noodp,noydir"' . (!strcasecmp($tags['robots'], 'noodp,noydir') ? $sel : '') . '>' . __d('npds', 'Pas d\'utilisation des descriptions ODP ou YDIR') . '</option>
                </select>
                <label for="newtagrobots">' . __d('npds', 'Robots/Spiders') . '</label>
                <span class="help-block">' . __d('npds', '(Définissez la méthode d\'analyse que doivent adopter les robots des moteurs de recherche)') . '</span>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="newtagrevisitafter" type="text" name="newtag[revisit-after]" value="' . $tags['revisit-after'] . '" maxlength="30" />
                <label for="newtagrevisitafter">' . __d('npds', 'Fréquence de visite des Robots/Spiders') . '</label>
                <span class="help-block">' . __d('npds', '(Ex. : 16 days. Remarque : ne définissez pas de fréquence inférieure à 14 jours !)') . '<span class="float-end ms-1" id="countcar_newtagrevisitafter"></span></span>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                    <select class="form-select" id="newtagdoctype" name="newtag[doctype]">
                        <option value="XHTML 1.0 Transitional"' . (!strcasecmp(doctype, 'XHTML 1.0 Transitional') ? $sel : '') . '>XHTML 1.0 ' . __d('npds', 'Transitional') . '</option>
                        <option value="XHTML 1.0 Strict"' . (!strcasecmp(doctype, 'XHTML 1.0 Strict') ? $sel : '') . '>XHTML 1.0 ' . __d('npds', 'Strict') . '</option>
                        <option value="HTML 5.1"' . (!strcasecmp(doctype, 'HTML 5.1') ? $sel : '') . '>HTML 5.1</option>
                    </select>
                    <label for="newtagdoctype">DOCTYPE</label>
                    </div>
                </div>
            </div>
            <input type="hidden" name="op" value="MetaTagSave" />
            <button class="btn btn-primary my-3" type="submit">' . __d('npds', 'Enregistrer') . '</button>
        </form>';
    
        $arg1 = '
        var formulid = ["metatagsadm"];
        inpandfieldlen("newtagauthor",100);
        inpandfieldlen("newtagowner",100);
        inpandfieldlen("newtagreplyto",100);
        inpandfieldlen("newtagdescription",200);
        inpandfieldlen("newtagkeywords",1000);
        inpandfieldlen("newtagcopyright",100);
        inpandfieldlen("newtagrevisitafter",30);';
    
        adminfoot('fv', '', $arg1, '');
    }
    
    function GetMetaTags($filename)
    {
        if (file_exists($filename)) {
            $temp = file($filename);
    
            foreach ($temp as $line) {
                $aline = trim(stripslashes($line));
    
                if (preg_match("#<meta (name|http-equiv|property)=\"([^\"]*)\" content=\"([^\"]*)\"#i", $aline, $regs)) {
                    $regs[2] = strtolower($regs[2]);
                    $tags[$regs[2]] = $regs[3];
                } elseif (preg_match("#<meta (charset)=\"([^\"]*)\"#i", $aline, $regs)) {
                    $regs[1] = strtolower($regs[1]);
                    $tags[$regs[1]] = $regs[2];
                } elseif (preg_match("#<meta (content-type)=\"([^\"]*)\" content=\"([^\"]*)\"#i", $aline, $regs)) {
                    $regs[2] = strtolower($regs[2]);
                    $tags[$regs[2]] = $regs[3];
                } elseif (preg_match("#<html (lang)=\"([^\"]*)\"#i", $aline, $regs)) {
                    $regs[1] = strtolower($regs[1]);
                    $tags[$regs[1]] = $regs[2];
                } elseif (preg_match("#<doctype (lang)=\"([^\"]*)\"#i", $aline, $regs)) {
                    $regs[1] = strtolower($regs[1]);
                    $tags[$regs[1]] = $regs[2];
                }
            }
        }
    
        return $tags;
    }
    
    function MetaTagMakeSingleTag($name, $content, $type = 'name')
    {
        if ($content != "humans.txt") {
            if ($content != "")
                return "\$l_meta.=\"<meta $type=\\\"" . $name . "\\\" content=\\\"" . $content . "\\\" />\\n\";\n";
            else
                return "\$l_meta.=\"<meta $type=\\\"" . $name . "\\\" />\\n\";\n";
        } else
            return "\$l_meta.=\"<link type=\"text/plain\" rel=\"author\" href=\"http://humanstxt.org/humans.txt\" />\";\n";
    }
    
    function MetaTagSave($filename, $tags)
    {
        if (!is_array($tags)) 
            return false;
    
        $fh = fopen($filename, "w");
    
        if ($fh) {
            $content = "<?php\n/* Do not change anything in this file manually. Use the administration interface. */\n";
            $content .= "/* généré le : " . date("d-m-Y H:i:s") . " */\n";
            $content .= "global \Config::get('npds.nuke_url');\n";
            $content .= "\$meta_doctype = isset(\$meta_doctype) ? \$meta_doctype : '' ;\n";
            $content .= "\$nuke_url = isset(\Config::get('npds.nuke_url')) ? \Config::get('npds.nuke_url') : '' ;\n";
            $content .= "\$meta_doctype = isset(\$meta_doctype) ? \$meta_doctype : '' ;\n";
            $content .= "\$meta_op = isset(\$meta_op) ? \$meta_op : '' ;\n";
            $content .= "\$m_description = isset(\$m_description) ? \$m_description : '' ;\n";
            $content .= "\$m_keywords = isset(\$m_keywords) ? \$m_keywords : '' ;\n";
            $content .= "\$lang = language_iso(1, '', 0);\n";
            $content .= "if (\$meta_doctype==\"\")\n";
    
            if (!empty($tags['doctype'])) {
                if ($tags['doctype'] == "XHTML 1.0 Transitional")
                    $content .= "   \$l_meta=\"<!DOCTYPE html PUBLIC \\\"-//W3C//DTD XHTML 1.0 Transitional//EN\\\" \\\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\\\">\\n<html lang=\\\"\$lang\\\" xml:lang=\\\"\$lang\\\" xmlns=\\\"http://www.w3.org/1999/xhtml\\\">\\n<head>\\n\";\n";
                
                if ($tags['doctype'] == "XHTML 1.0 Strict")
                    $content .= "   \$l_meta=\"<!DOCTYPE html PUBLIC \\\"-//W3C//DTD XHTML 1.0 Strict//EN\\\" \\\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\\\">\\n<html lang=\\\"\$lang\\\" xml:lang=\\\"\$lang\\\" xmlns=\\\"http://www.w3.org/1999/xhtml\\\">\\n<head>\\n\";\n";
                
                if ($tags['doctype'] == "HTML 5.1")
                    $content .= "   \$l_meta=\"<!DOCTYPE html>\\n<html lang=\\\"\$lang\\\">\\n<head>\\n\";\n";
            } else {
                $tags['doctype'] = "HTML 5.1";
                $content .= "   \$l_meta=\"<!DOCTYPE html>\\n<html lang=\\\"\$lang\\\">\\n<head>\\n\";\n";
            }
    
            $content .= "else\n";
            $content .= "   \$l_meta=\$meta_doctype.\"\\n<html lang=\\\"\$lang\\\">\\n<head>\\n\";\n";
    
            if (!empty($tags['content-type'])) {
                $tags['content-type'] = htmlspecialchars(stripslashes($tags['content-type']), ENT_COMPAT | ENT_HTML401, cur_charset);
                $fp = fopen("storage/meta/cur_charset.php", "w");
                
                if ($fp) {
                    fwrite($fp, "<?php\nif (!defined(\"cur_charset\"))\n   define ('cur_charset', \"" . substr($tags['content-type'], strpos($tags['content-type'], "charset=") + 8) . "\");\n");
                    fwrite($fp, "if (!defined(\"doctype\"))\n   define ('doctype', \"" . $tags['doctype'] . "\");\n?>");
                }
    
                fclose($fp);
    
                if ($tags['doctype'] == "HTML 5.1")
                    $content .= MetaTagMakeSingleTag('utf-8', '', 'charset');
                else
                    $content .= MetaTagMakeSingleTag('content-type', $tags['content-type'], 'http-equiv');
            } else {
                $fp = fopen("storage/meta/cur_charset.php", "w");
    
                if ($fp) {
                    fwrite($fp, "<?php\nif (!defined(\"cur_charset\"))\n   define ('cur_charset', \"utf-8\");\n");
                    fwrite($fp, "if (!defined(\"doctype\"))\n   define ('doctype', \"" . $tags['doctype'] . "\");\n?>");
                }
    
                fclose($fp);
    
                if ($tags['doctype'] == "XHTML 1.0 Transitional" || $tags['doctype'] == "XHTML 1.0 Strict") {
                    $content .= MetaTagMakeSingleTag('content-type', 'text/html; charset=utf-8', 'http-equiv');
                } else {
                    $content .= MetaTagMakeSingleTag('utf-8', '', 'charset');
                }
            }
    
            $content .= "\$l_meta.=\"<title>\"".Config::get('npds.Titlesitename')."\"</title>\\n\";\n";
            $content .= MetaTagMakeSingleTag('viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no');
            $content .= MetaTagMakeSingleTag('content-script-type', 'text/javascript', 'http-equiv');
            $content .= MetaTagMakeSingleTag('content-style-type', 'text/css', 'http-equiv');
            $content .= MetaTagMakeSingleTag('expires', '0', 'http-equiv');
            $content .= MetaTagMakeSingleTag('pragma', 'no-cache', 'http-equiv');
            $content .= MetaTagMakeSingleTag('cache-control', 'no-cache', 'http-equiv');
            $content .= MetaTagMakeSingleTag('identifier-url', Config::get('npds.nuke_url'), 'http-equiv');
    
            if (!empty($tags['author'])) {
                $tags['author'] = htmlspecialchars(stripslashes($tags['author']), ENT_COMPAT | ENT_HTML401, cur_charset);
                $content .= MetaTagMakeSingleTag('author', $tags['author']);
            }
    
            if (!empty($tags['owner'])) {
                $tags['owner'] = htmlspecialchars(stripslashes($tags['owner']), ENT_COMPAT | ENT_HTML401, cur_charset);
                $content .= MetaTagMakeSingleTag('owner', $tags['owner']);
            }
    
            if (!empty($tags['reply-to'])) {
                $tags['reply-to'] = htmlspecialchars(stripslashes($tags['reply-to']), ENT_COMPAT | ENT_HTML401, cur_charset);
                $content .= MetaTagMakeSingleTag('reply-to', $tags['reply-to']);
            } else
                $content .= MetaTagMakeSingleTag('reply-to', Config::get('npds.adminmail'));
    
            if (!empty($tags['description'])) {
                $tags['description'] = htmlspecialchars(stripslashes($tags['description']), ENT_COMPAT | ENT_HTML401, cur_charset);
                $content .= "if (\$m_description!=\"\")\n";
                $content .= "   \$l_meta.=\"<meta name=\\\"description\\\" content=\\\"\$m_description\\\" />\\n\";\n";
                $content .= "else\n";
                $content .= "   " . MetaTagMakeSingleTag('description', $tags['description']);
            }
    
            if (!empty($tags['keywords'])) {
                $tags['keywords'] = htmlspecialchars(stripslashes($tags['keywords']), ENT_COMPAT | ENT_HTML401, cur_charset);
                $content .= "if (\$m_keywords!=\"\")\n";
                $content .= "   \$l_meta.=\"<meta name=\\\"keywords\\\" content=\\\"\$m_keywords\\\" />\\n\";\n";
                $content .= "else\n";
                $content .= "   " . MetaTagMakeSingleTag('keywords', $tags['keywords']);
            }
    
            if (!empty($tags['rating'])) {
                $tags['rating'] = htmlspecialchars(stripslashes($tags['rating']), ENT_COMPAT | ENT_HTML401, cur_charset);
                $content .= MetaTagMakeSingleTag('rating', $tags['rating']);
            }
    
            if (!empty($tags['distribution'])) {
                $tags['distribution'] = htmlspecialchars(stripslashes($tags['distribution']), ENT_COMPAT | ENT_HTML401, cur_charset);
                $content .= MetaTagMakeSingleTag('distribution', $tags['distribution']);
            }
    
            if (!empty($tags['copyright'])) {
                $tags['copyright'] = htmlspecialchars(stripslashes($tags['copyright']), ENT_COMPAT | ENT_HTML401, cur_charset);
                $content .= MetaTagMakeSingleTag('copyright', $tags['copyright']);
            }
    
            if (!empty($tags['revisit-after'])) {
                $tags['revisit-after'] = htmlspecialchars(stripslashes($tags['revisit-after']), ENT_COMPAT | ENT_HTML401, cur_charset);
                $content .= MetaTagMakeSingleTag('revisit-after', $tags['revisit-after']);
            } else
                $content .= MetaTagMakeSingleTag('revisit-after', "14 days");
    
            $content .= MetaTagMakeSingleTag('resource-type', "document");
            $content .= MetaTagMakeSingleTag('robots', $tags['robots']);
            $content .= MetaTagMakeSingleTag('generator', "Config::get('npds.Version_Id') Config::get('npds.Version_Num') Config::get('npds.Version_Sub')");
    
            //==> OpenGraph Meta Tags
            $content .= MetaTagMakeSingleTag('og:type', 'website', 'property');
            $content .= MetaTagMakeSingleTag('og:url', Config::get('npds.nuke_url'), 'property');
            $content .= MetaTagMakeSingleTag('og:title', Config::get('npds.Titlesitename'), 'property');
            $content .= MetaTagMakeSingleTag('og:description', $tags['description'], 'property');
            $content .= MetaTagMakeSingleTag('og:image', Config::get('npds.nuke_url').'/images/ogimg.jpg', 'property');
            $content .= MetaTagMakeSingleTag('twitter:card', 'summary', 'property');
    
            //<== OpenGraph Meta Tags
            $content .= "if (\$meta_op==\"\") echo \$l_meta; else \$l_meta=str_replace(\"\\n\",\"\",str_replace(\"\\\"\",\"'\",\$l_meta));\n?>";
            fwrite($fh, $content);
            fclose($fh);
    
            global $aid;
            Ecr_Log('security', "MetaTagsave() by AID : $aid", '');
    
            return true;
        }
    
        return false;
    }

}
