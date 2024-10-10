<?php

namespace Modules\Npds\Support;

/**
 * Undocumented class
 */
class ConfigSave
{

    /**
     * [block_white description]
     *
     * @param   string  $description  [$description description]
     * @param   string  $key          [$key description]
     * @param   int                   [ description]
     * @param   bool                  [ description]
     * @param   string  $value        [$value description]
     *
     * @return  string                [return description]
     */
    public static function block_white(string $description, string $key, int|bool|string $value): string 
    {
        $line = "    /**\n";
        $line .= "    * " . $description ."\n";
        $line .= "    *\n";
        $line .= "    */\n";

        if ($value === 'false') {
            $line .= "    '" . $key . "'  => false,\n";
        } elseif ($value === 'true') {
            $line .= "    '" . $key . "'  => true,\n";
        } elseif (is_int($value)) {
            $line .= "    '" . $key . "'  => " . $value . ",\n";     
        } elseif (is_string($value)) {
            $line .= "    '" . $key . "'  => '" . $value . "',\n";  
        }

        $line .= "\n";

        return $line;
    }

    /**
     * [block_white_cache description]
     *
     * @param   string  $description  [$description description]
     * @param   string  $uri          [$uri description]
     * @param   string  $query        [$query description]
     * @param   int     $timing       [$timing description]
     *
     * @return  string                [return description]
     */
    public static function block_white_cache(string $description, string $uri, string $query, int $timing): string 
    {
        $line = "    /**\n";
        $line .= "    * " . $description ."\n";
        $line .= "    *\n";
        $line .= "    */\n";
        $line .= "    '" . $uri . "'  => [\n\n";
        $line .= "        //\n";
        $line .= "        'querys'  => '" . $query . "',\n\n";
        $line .= "        //\n";
        $line .= "        'timings'  => " . $timing . ",\n\n";     
        $line .= "    ],\n";  
        $line .= "\n";

        return $line;
    }

    /**
     * [start_block_white description]
     *
     * @return  string  [return description]
     */
    private static function start_block_white(): string
    {
        $line = "<?php\n";
        $line .= "\n\n";
        $line .= "return array(\n";
        $line .= "\n";

        return $line;
    }    

    /**
     * [save_block_white description]
     *
     * @param   string  $filename   [$filename description]
     * @param   string  $lineblock  [$lineblock description]
     *
     * @return  void                [return description]
     */
    public static function save_block_white(string $filename, string $lineblock): void
    {
        $file = fopen($filename. '.php', "w");

        $line = static::start_block_white();
        $line .= $lineblock;
        $line .= ");";
        $line .= "\n";

        fwrite($file, $line);
        fclose($file);
        @chmod($filename. '.php', 0666);
    } 

    /**
     * [comment description]
     *
     * @param   string  $comment  [$comment description]
     *
     * @return  string            [return description]
     */
    public static function comment(string $comment): string
    {
        $line = "\n";
        $line .= "    ## ". $comment ."\n";
        $line .= "\n";

        return $line;
    } 

}
