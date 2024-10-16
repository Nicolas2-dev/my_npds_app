<?php

namespace Modules\Npds\Library;

use Modules\Npds\Contracts\CodeInterface;


class CodeManager implements CodeInterface 
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
     * [change_cod description]
     *
     * @param   [type]  $r  [$r description]
     *
     * @return  [type]      [return description]
     */
    public function change_cod($r)
    {
        return '<' . $r[2] . ' class="language-' . $r[3] . '">' . htmlentities($r[5], ENT_COMPAT | ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, cur_charset) . '</' . $r[2] . '>';
    }

    /**
     * [af_cod description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function af_cod($ibid)
    {
        $pat = '#(\[)(\w+)\s+([^\]]*)(\])(.*?)\1/\2\4#s';

        $ibid = preg_replace_callback($pat, [CodeManager::class, 'change_cod'], $ibid, -1, $nb);
        //   $ibid= str_replace(array("\r\n", "\r", "\n"), "<br />",$ibid);

        return $ibid;
    }

    /**
     * [desaf_cod description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function desaf_cod($ibid)
    {
        $pat = '#(<)(\w+)\s+(class="language-)([^">]*)(">)(.*?)\1/\2>#';

        function rechange_cod($r)
        {
            return '[' . $r[2] . ' ' . $r[4] . ']' . $r[6] . '[/' . $r[2] . ']';
        }

        $ibid = preg_replace_callback($pat, 'rechange_cod', $ibid, -1);

        return $ibid;
    }

    /**
     * [aff_code description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function aff_code($ibid)
    {
        $pasfin = true;

        while ($pasfin) {
            $pos_deb = strpos($ibid, "[code]", 0);
            $pos_fin = strpos($ibid, "[/code]", 0);

            // ne pas confondre la position ZERO et NON TROUVE !
            if ($pos_deb === false) {
                $pos_deb = -1;
            }

            if ($pos_fin === false) {
                $pos_fin = -1;
            }

            if (($pos_deb >= 0) and ($pos_fin >= 0)) {
                ob_start();
                    highlight_string(substr($ibid, $pos_deb + 6, ($pos_fin - $pos_deb - 6)));
                    $fragment = ob_get_contents();
                ob_end_clean();

                $ibid = str_replace(substr($ibid, $pos_deb, ($pos_fin - $pos_deb + 7)), $fragment, $ibid);
            } else {
                $pasfin = false;
            }
        }

        return $ibid;
    }

}
