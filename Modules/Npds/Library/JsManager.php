<?php

namespace Modules\Npds\Library;

use Modules\Npds\Contracts\JsInterface;


class JsManager implements JsInterface 
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
     * [auto_complete description]
     *
     * @param   [type]  $nom_array_js  [$nom_array_js description]
     * @param   [type]  $nom_champ     [$nom_champ description]
     * @param   [type]  $nom_tabl      [$nom_tabl description]
     * @param   [type]  $id_inpu       [$id_inpu description]
     * @param   [type]  $temps_cache   [$temps_cache description]
     *
     * @return  [type]                 [return description]
     */
    public function auto_complete($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $temps_cache)
    {
        $list_json = '';
        $list_json .= 'var ' . $nom_array_js . ' = [';
        $res = Q_select("SELECT " . $nom_champ . " FROM " . $nom_tabl, $temps_cache);

        foreach ($res as $ar_data) {
            foreach ($ar_data as $val_champ) {
                if ($id_inpu == '') {
                    $list_json .= '"' . base64_encode($val_champ) . '",';
                } else {
                    $list_json .= '"' . $val_champ . '",';
                }
            }
        }

        $list_json = rtrim($list_json, ',');
        $list_json .= '];';
        $scri_js = '';

        if ($id_inpu == '') {
            $scri_js .= $list_json;
        } else {
            $scri_js .= '
            <script type="text/javascript">
            //<![CDATA[
                $(function() {
                ' . $list_json;
                    if ($id_inpu != '')
                        $scri_js .= '
                $( "#' . $id_inpu . '" ).autocomplete({
                    source: ' . $nom_array_js . '
                });';
                    $scri_js .= '
                });
            //]]>
            </script>';
        }

        return $scri_js;
    }

    /**
     * [auto_complete_multi description]
     *
     * @param   [type]  $nom_array_js  [$nom_array_js description]
     * @param   [type]  $nom_champ     [$nom_champ description]
     * @param   [type]  $nom_tabl      [$nom_tabl description]
     * @param   [type]  $id_inpu       [$id_inpu description]
     * @param   [type]  $req           [$req description]
     *
     * @return  [type]                 [return description]
     */
    public function auto_complete_multi($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $req)
    {
        $list_json = '';
        $list_json .= $nom_array_js . ' = [';

        $res = sql_query("SELECT " . $nom_champ . " FROM " . $nom_tabl . " " . $req);
        while (list($nom_champ) = sql_fetch_row($res)) {
            $list_json .= '\'' . $nom_champ . '\',';
        }

        $list_json = rtrim($list_json, ',');
        $list_json .= '];';
        $scri_js = '';
        $scri_js .= '
        <script type="text/javascript">
            //<![CDATA[
                var ' . $nom_array_js . ';
                $(function() {
                    ' . $list_json . '
                    function split( val ) {
                    return val.split( /,\s*/ );
                    }
                    function extractLast( term ) {
                    return split( term ).pop();
                    }
                    $( "#' . $id_inpu . '" )
                    // dont navigate away from the field on tab when selecting an item
                    .bind( "keydown", function( event ) {
                        if ( event.keyCode === $.ui.keyCode.TAB &&
                            $( this ).autocomplete( "instance" ).menu.active ) {
                        event.preventDefault();
                        }
                    })
                    .autocomplete({
                        minLength: 0,
                        source: function( request, response ) {
                        response( $.ui.autocomplete.filter(
                            ' . $nom_array_js . ', extractLast( request.term ) ) );
                        },
                        focus: function() {
                        return false;
                        },
                        select: function( event, ui ) {
                        var terms = split( this.value );
                        terms.pop();
                        terms.push( ui.item.value );
                        terms.push( "" );
                        this.value = terms.join( ", " );
                        return false;
                        }
                    });
                });
            //]]>
        </script>' . "\n";

        return $scri_js;
    }

}
