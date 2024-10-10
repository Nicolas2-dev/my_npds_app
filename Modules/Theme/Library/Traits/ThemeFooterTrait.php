<?php

namespace Modules\Theme\Library\Traits;

use Npds\Config\Config;
use Shared\Editeur\Support\Facades\Editeur;
use Modules\Blocks\Support\Facades\Block;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;


trait ThemefooterTrait
{

    /**
     * [footmsg description]
     *
     * @return  [type]  [return description]
     */
    public function footmsg()
    {
        $foot = '<p align="center">';
    
        if (Config::get('npds.foot1')) {
            $foot .= stripslashes(Config::get('npds.foot1')) . '<br />';
        }
    
        if (Config::get('npds.foot2')) {
            $foot .= stripslashes(Config::get('npds.foot2')) . '<br />';
        }
    
        if (Config::get('npds.foot3')) {
            $foot .= stripslashes(Config::get('npds.foot3')) . '<br />';
        }
    
        if (Config::get('npds.foot4')) {
            $foot .= stripslashes(Config::get('npds.foot4'));
        }
    
        $foot .= '</p>';
    
        echo Language::aff_langue($foot);
    }
    
    /**
     * [foot description]
     *
     * @return  [type]  [return description]
     */
    public function foot()
    {
        $ContainerGlobal = '</div>';

        $rep = false;

        settype($ContainerGlobal, 'string');

        $theme_dir = with(get_instance())->template_dir();

        if (file_exists(theme_path($theme_dir .'/'. $this->theme . "/Fragments/footer.php"))) {
            $rep = theme_path($theme_dir .'/'. $this->theme);
        } elseif (file_exists(module_path("Theme/Views/Fragments/footer.php"))) {
            $rep = module_path('Theme/Views');
        } else {
            echo "Fragments/footer.php manquant / not find !<br />";
            die();
        }

        if ($rep) {
            ob_start();
                include($rep . "/Fragments/footer.php");
                $Xcontent = ob_get_contents();
            ob_end_clean();

            if ($ContainerGlobal) {
                $Xcontent .= $ContainerGlobal;
            }
            
            echo Metalang::meta_lang(Language::aff_langue($Xcontent));
        }
    }

    /**
     * [theme_footer description]
     *
     * @return  [type]  [return description]
     */
    public function theme_footer($pdst)
    {
        $moreclass = 'col-12';

        switch ($pdst) {
            case '-1':
            case '3':
            case '5':
                echo '
                        </div>
                    </div>
                </div>';
                break;
        
            case '1':
            case '2':
                echo '</div>';
                $this->colsyst('#col_RB');
                echo '
                    <div id="col_RB" class="collapse show col-lg-3 ">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-1">';
                Block::rightblocks($moreclass);
                echo '
                            </div>
                        </div>
                    </div>
                </div>';
                break;
        
            case '4':
                echo '</div>';
                $this->colsyst('#col_LB');
                echo '
                    <div id="col_LB" class="collapse show col-lg-3">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-1">';
                Block::leftblocks($moreclass);
                echo '
                    </div>
                </div>';
                $this->colsyst('#col_RB');
                echo '
                    <div id="col_RB" class="collapse show col-lg-3">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-1">';
                Block::rightblocks($moreclass);
                echo '
                            </div>
                        </div>
                    </div>
                </div>';
                break;
        
            case '6':
                echo '</div>';
                $this->colsyst('#col_LB');
                echo '
                <div id="col_LB" class="collapse show col-lg-3">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-1">';
                Block::leftblocks($moreclass);
                echo '
                        </div>
                    </div>
                </div>
                </div>';
                break;
            default:
                echo '
                        </div>
                    </div>
                </div>';
                break;
        }

        $tiny_mce_init=true;
        $tiny_mce=true;
        $tiny_mce_theme="full"; //  "short"
        $tiny_mce_relurl="";

        if (Config::get('npds.tiny_mce')) {
            echo Editeur::aff_editeur('tiny_mce', 'end');
        }

        // include externe file from themes/default/include for functions, codes ...
        if (file_exists(module_path("Theme/Support/Include/footer_before.php"))) {
            include(module_path("Theme/Support/Include/footer_before.php"));
        }

        $this->foot();

        $theme_dir = with(get_instance())->template_dir();

        // include externe file from modules/themes include for functions, codes ...
        if (isset($user)) {
            global $user, $cookie9;
    
            if ($user) {
                $user2 = base64_decode($user);
                $cookie = explode(':', $user2);
        
                if ($cookie[9] == '') {
                    $cookie[9] = Config::get('npds.Default_Theme');
                }
        
                $ibix = explode('+', urldecode($cookie[9]));
                $cookie9 = $ibix[0];
            }

            if (file_exists(theme_path($theme_dir .'/'. $cookie9."/Support/Include/footer_after.php"))) {
                include(theme_path($theme_dir .'/'. $cookie9."/Support/Include/footer_after.php"));
            } else {
                if (file_exists(module_path("Theme/Support/Include/footer_after.php"))) {
                    include(module_path("Theme/Support/Include/footer_after.php"));
                }
            }
        } else {
            if (file_exists(theme_path($theme_dir .'/'. $this->theme."/Support/Include/footer_after.php"))) {
                include(theme_path($theme_dir .'/'. $this->theme."/Support/Include/footer_after.php"));
            } else {
                if (file_exists(module_path("Theme/Support/Include/footer_after.php"))) {
                    include(module_path("Theme/Support/Include/footer_after.php"));
                }
            }
        }
        
        //include("sitemap.php");

        global $dblink;
        if (!Config::get('npds.mysql_p')) {
            sql_close($dblink);
        }
    }

}
