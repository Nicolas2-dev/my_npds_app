<?php

namespace Modules\Fmanager\Library;

/**
 * Undocumented class
 */
class Navigator
{

    /**
     * 
     */
    public $GetDirSz;

    /**
     * 
     */
    public $CurDir;

    /**
     * 
     */
    public $DirsList  =  array(
        "Name" => array(),
        "DateM" => array(),
        "Size" => array(),
        "Perms" => array()
    );

    /**
     * 
     */
    public $FilesList =  array(
        "Name" => array(),
        "DateM" => array(),
        "Size" => array(),
        "Perms" => array(),
        "View" => array()
    );

    /**
     * 
     */
    public $Handle;

    /**
     * 
     */
    public $Errors;

    /**
     * 
     */
    public $Path; 

    /**
     * 
     */
    public $OrderArrD = array();

    /**
     * 
     */
    public $OrderArrF = array();

    /**
     * 
     */
    public $PointerPosD;

    /**
     * 
     */
    public $PointerPosF;

    /**
     * 
     */
    public $Extension = array();

    /**
     * 
     */
    public $FieldName; 

    /**
     * 
     */
    public $FieldDate; 

    /**
     * 
     */
    public $FieldSize; 

    /**
     * 
     */
    public $FieldPerms; 

    /**
     * 
     */
    public $FieldView; 


    /**
     * Undocumented function
     */
    function __construct()
    {
        //
    }

    /**
     * Undocumented function
     *
     * @param [type] $parm
     * @param string $sort_filed
     * @param string $dir
     * @param boolean $DirSize
     * @return void
     */
    public function File_Navigator($parm, $sort_filed = "N", $dir = "ASC", $DirSize = false)
    {
        if (!isset($parm)) 
            $parm = ".";

        if (is_dir($parm)) 
            $this->CurDir = $parm;
        else 
            Access_Error();

        $this->GetDirSz = $DirSize;

        if (@chdir($this->CurDir)) {
            $this->Handle = opendir(".");
            $this->LoadList();
            closedir($this->Handle);

            $this->SortListF($sort_filed, $dir);
            $this->SortListD($sort_filed, $dir);

            $this->PointerPosF = 0;
            $this->PointerPosD = 0;
            return (true);
        } else
            return (false);
    }

    /**
     * load directories list and files list
     *
     * @return void
     */
    public function LoadList()
    {
        while (false !== ($file = readdir($this->Handle))) {
            if (@is_dir($file) && $file != "." && $file != "..") {

                $this->DirsList["Name"][]  = $file;
                $this->DirsList["DateM"][] = $this->LastUpdate($file);
                $this->DirsList["Size"][]  = ($this->GetDirSz) ? $this->GetDirSize($file) : '';
                $this->DirsList["Perms"][] = $this->PresPerms($this->GetPerms($file));

            } elseif (@is_file($file) && $file != "." && $file != "..") {
                $suffix = strtoLower(substr(strrchr($file, '.'), 1));

                if ((in_array($suffix, $this->Extension)) or ($this->Extension[0] == "*")) {
                    $this->FilesList["Name"][]  = $file;
                    $this->FilesList["DateM"][] = $this->LastUpdate($file);
                    $this->FilesList["Size"][]  = filesize($file);
                    $this->FilesList["Perms"][] = $this->PresPerms($this->GetPerms($file));
                    $this->FilesList["View"][]  = $suffix;
                }
            }
        }
    }

    /**
     * convert permission to string like -rwxr-xr-- (755)
     *
     * @param [type] $file
     * @return void
     */
    public function GetPerms($file)
    {
        switch (filetype($file)) {
            case "dir";
                $ret2 = "d";
                break;

            case "fifo";
                $ret2 = "f";
                break;

            case "char";
                $ret2 = "c";
                break;

            case "block";
                $ret2 = "b";
                break;

            case "link";
                $ret2 = "l";
                break;

            case "file";
                $ret2 = "-";
                break;

            default:
                $ret2 = "-";
                break;
        }

        $perms = fileperms($file) & 0777;
        $ret = sprintf("%b", $perms);

        ($ret[0]) ? ($ret2 .= "r") : ($ret2 .= "-");
        ($ret[1]) ? ($ret2 .= "w") : ($ret2 .= "-");
        ($ret[2]) ? ($ret2 .= "x") : ($ret2 .= "-");
        ($ret[3]) ? ($ret2 .= "r") : ($ret2 .= "-");
        ($ret[4]) ? ($ret2 .= "w") : ($ret2 .= "-");
        ($ret[5]) ? ($ret2 .= "x") : ($ret2 .= "-");
        ($ret[6]) ? ($ret2 .= "r") : ($ret2 .= "-");
        ($ret[7]) ? ($ret2 .= "w") : ($ret2 .= "-");
        ($ret[8]) ? ($ret2 .= "x") : ($ret2 .= "-");

        $mask = 0700;
        $Fdroits = "";

        for ($i = 0; $i < 3; $i++) {
            $droits = $perms & $mask;

            if ($i == 0)
                $droits = $droits >> 6;
            else if ($i == 1)
                $droits = $droits >> 3;

            $Fdroits .= $droits;
            $mask = $mask >> 3;
        }

        $tab[] = $Fdroits;
        $tab[] = $ret2;

        return ($tab);
    }

    /**
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function PresPerms($ibid)
    {
        if ($ibid[0] == 766 or $ibid[0] == 777) 
            $ibid[0] = '<span class="text-success">' . $ibid[0] . '</span>';

        return ("$ibid[0]&nbsp;($ibid[1])");
    }

    /**
     * Undocumented function
     *
     * @param [type] $file
     * @return void
     */
    public function LastUpdate($file)
    {
        return date(DateFormat, filemtime($file));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function NextFile()
    {
        if (isset($this->OrderArrF)) {
            $keyz = array_keys($this->OrderArrF);

            if (array_key_exists($this->PointerPosF, $keyz)) {

                //if (isset($this->OrderArrF[$keyz[$this->PointerPosF]])) {
                $this->FieldName  = $this->FilesList["Name"][$this->OrderArrF[$keyz[$this->PointerPosF]]];
                $this->FieldDate  = $this->FilesList["DateM"][$this->OrderArrF[$keyz[$this->PointerPosF]]];
                $this->FieldSize  = $this->FilesList["Size"][$this->OrderArrF[$keyz[$this->PointerPosF]]];
                $this->FieldPerms = $this->FilesList["Perms"][$this->OrderArrF[$keyz[$this->PointerPosF]]];
                $this->FieldView  = $this->FilesList["View"][$this->OrderArrF[$keyz[$this->PointerPosF]]];

                $this->PointerPosF++;

                return true;
            } else return false;
        } else return false;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function NextDir()
    {
        if (isset($this->OrderArrD)) {
            $keyz = array_keys($this->OrderArrD);

            if (array_key_exists($this->PointerPosD, $keyz)) {

                //if (isset($this->OrderArrD[$keyz[$this->PointerPosD]])) {
                $this->FieldName  = $this->DirsList["Name"][$this->OrderArrD[$keyz[$this->PointerPosD]]];
                $this->FieldDate  = $this->DirsList["DateM"][$this->OrderArrD[$keyz[$this->PointerPosD]]];
                $this->FieldSize  = ($this->GetDirSz) ? $this->DirsList["Size"][$this->OrderArrD[$keyz[$this->PointerPosD]]] : '';
                $this->FieldPerms = $this->DirsList["Perms"][$this->OrderArrD[$keyz[$this->PointerPosD]]];

                $this->PointerPosD++;
                return true;
            } else return false;
        } else return false;
    }

    /**
     * sort the files list
     *
     * @param [type] $what
     * @param string $direction
     * @return void
     */
    public function SortListF($what, $direction = "ASC")
    {
        unset($this->OrderArrF);

        switch ($what) {
            case "D"; //Date
                $i = 0;

                reset($this->FilesList["DateM"]);

                $ibid = count($this->FilesList["DateM"]);

                while ($i < $ibid) {
                    $tmp = explode(' ', $this->FilesList["DateM"][$i]);
                    $key1 = explode('-', $tmp[0]);
                    $key2 = explode(':', $tmp[1]);

                    $key = mktime($key2[0], $key2[1], $key2[2], $key1[1], $key1[0], $key1[2]);
                    $this->OrderArrF[$key . '.' . $i] = $i;
                    $i++;
                }

                if ($direction == "ASC" and isset($this->OrderArrF)) 
                    ksort($this->OrderArrF);
                elseif (isset($this->OrderArrF)) 
                    krsort($this->OrderArrF);
                break;
                //----------------------------------

            case "S"; //Size
                $i = 0;

                reset($this->FilesList["Size"]);

                $ibid = count($this->FilesList["Size"]);

                while ($i < $ibid) {
                    $this->OrderArrF[$this->FilesList["Size"][$i] . '.' . $i] = $i;
                    $i++;
                }

                if ($direction == "ASC"  and isset($this->OrderArrF)) 
                    ksort($this->OrderArrF);
                elseif (isset($this->OrderArrF)) 
                    krsort($this->OrderArrF);
                break;
                //----------------------------------

            default:
                $i = 0;

                reset($this->FilesList["Name"]);

                $ibid = count($this->FilesList["Name"]);

                while ($i < $ibid) {
                    $this->OrderArrF[strtolower($this->FilesList["Name"][$i])] = $i;
                    $i++;
                }

                if ($direction == "ASC"  and isset($this->OrderArrF)) 
                    ksort($this->OrderArrF);
                elseif (isset($this->OrderArrF)) 
                    krsort($this->OrderArrF);
                break;
        }
    }
 
    /**
     * sort the dirs list
     *
     * @param [type] $what
     * @param string $direction
     * @return void
     */
    public function SortListD($what, $direction = "ASC")
    {
        unset($this->OrderArrD);

        switch ($what) {
            case "D"; //Date
                $i = 0;

                reset($this->DirsList["DateM"]);

                foreach ($this->DirsList["DateM"] as $key => $val) {
                    $tmp = explode(' ', $this->DirsList["DateM"][$i]);
                    $key1 = (int) explode('-', $tmp[0]);
                    $key2 = (int) explode(':', $tmp[1]);

                    $key = mktime($key2[0], $key2[1], $key2[2], $key1[1], $key1[0], $key1[2]);
                    $this->OrderArrD[$key . '.' . $i] = $i;
                    $i++;
                }

                if ($direction == "ASC"  and isset($this->OrderArrD)) 
                    ksort($this->OrderArrD);
                elseif (isset($this->OrderArrD)) 
                    krsort($this->OrderArrD);
                break;
                //----------------------------------

            case "S"; //Size
                $i = 0;

                reset($this->DirsList["Size"]);

                while ($i < count($this->DirsList["Size"])) {
                    $this->OrderArrD[$this->DirsList["Size"][$i] . '.' . $i] = $i;
                    $i++;
                }

                if ($direction == "ASC"  and isset($this->OrderArrD)) 
                    ksort($this->OrderArrD);
                elseif (isset($this->OrderArrD)) 
                    krsort($this->OrderArrD);
                break;
                //----------------------------------

            default:
                $i = 0;

                reset($this->DirsList["Name"]);

                while ($i < count($this->DirsList["Name"])) {
                    $this->OrderArrD[strtolower($this->DirsList["Name"][$i])] = $i;
                    $i++;
                }

                if ($direction == "ASC"  and isset($this->OrderArrD)) 
                    ksort($this->OrderArrD);
                elseif (isset($this->OrderArrD)) 
                    krsort($this->OrderArrD);
                break;
                //----------------------------------
        }
    }
 
    /**
     * return element's number  what: d total dirs, f: total files
     *
     * @param string $what
     * @return string
     */
    public function Count($what = "")
    {
        switch ($what) {
            case "d":
                return count($this->DirsList["Name"]);
                break;

            case "f":
                return count($this->FilesList["Name"]);
                break;

            default:
                return  count($this->DirsList["Name"]) + count($this->FilesList["Name"]);
                break;
        }
    }

    /**
     * current directory
     *
     * @return string
     */
    public function Pwd()
    {
        return getcwd();
    }

    /**
     * get all directory size
     *
     * @param [type] $dir
     * @return string
     */
    public function GetDirSize($dir)
    {
        $total = 0;

        if ($this->GetDirSz) {
            $dossier = @opendir($dir);

            while (false !== ($fichier = readdir($dossier))) {
                $l = array('.', '..');

                if (!in_array($fichier, $l)) {
                    if (is_dir($dir . "/" . $fichier)) {
                        $total += $this->GetDirSize($dir . "/" . $fichier);
                    } else {
                        $total += filesize($dir . "/" . $fichier);
                    }
                }
            }
        }

        return $total;
    }

    /**
     * get all files in all directoies that contain $search
     *
     * @param [type] $dir
     * @param [type] $search
     * @return void
     */
    public function SearchFile($dir, $search)
    {
        $dossier = opendir($dir);
        $list = "";

        while ($fichier = readdir($dossier)) {
            $l = array('.', '..');

            if (!in_array($fichier, $l)) {

                if (is_dir($dir . "/" . $fichier)) {
                    $list .= $this->SearchFile($dir . "/" . $fichier, $search);
                } else {
                    if ($search != "") {

                        $pos = strpos(strtoupper($fichier), strtoupper($search));

                        if ($pos === FALSE) {
                            if ($search == "*")
                                $list .= $dir . "/" . $fichier . "|";
                        } else
                            $list .= $dir . "/" . $fichier . "|";
                    }
                }
            }
        }

        return $list;
    }

    /**
     * get all directory in a string separated by |
     *
     * @param [type] $dir
     * @return void
     */    
    public function GetDirArbo($dir)
    {
        $dossier = @opendir($dir);
        $ibid = "";

        while (false !== ($fichier = readdir($dossier))) {
            $l = array('.', '..');

            if (!in_array($fichier, $l)) {
                if (is_dir($dir . "/" . $fichier)) {
                    $ibid .= $dir . "/" . $fichier . "|";
                    $ibid .= $this->GetDirArbo($dir . "/" . $fichier);
                }
            }
        }

        return ($ibid);
    }

    /**
     * chmod
     *
     * @param [type] $file
     * @param string $perms
     * @return void
     */
    public function ChgPerms($file, $perms = '644')
    {
        if ($perms == 400)
            $ibid = @chmod($file, 0400);

        if ($perms == 444)
            $ibid = @chmod($file, 0444);

        if ($perms == 500)
            $ibid = @chmod($file, 0500);

        if ($perms == 544)
            $ibid = @chmod($file, 0544);

        if ($perms == 600)
            $ibid = @chmod($file, 0600);

        if ($perms == 644)
            $ibid = @chmod($file, 0644);

        if ($perms == 655)
            $ibid = @chmod($file, 0655);

        if ($perms == 666)
            $ibid = @chmod($file, 0666);

        if ($perms == 700)
            $ibid = @chmod($file, 0700);

        if ($perms == 744)
            $ibid = @chmod($file, 0744);

        if ($perms == 755)
            $ibid = @chmod($file, 0755);

        if ($perms == 766)
            $ibid = @chmod($file, 0766);

        if ($perms == 777)
            $ibid = @chmod($file, 0777);

        if (!$ibid)
            $this->Errors = __d('fmanager', 'Impossible d\'appliquer le chmod');

        if ($this->Errors != "") 
            return (false);
        else 
            return (true);
    }

    /**
     * renaming
     *
     * @param [type] $old
     * @param [type] $new
     * @return void
     */
    public function Rename($old, $new)
    {
        if (file_exists($old)) {
            if (!file_exists($new)) 
                rename($old, $new);
            else 
                $this->Errors = basename($new) . " : " . __d('fmanager', 'Impossible de renommer');
        } else {
            $this->Errors = basename($old) . " : " . __d('fmanager', 'Le fichier n\'existe pas');
        }

        if ($this->Errors != "") 
            return (false);
        else 
            return (true);
    }

    /**
     * moving
     *
     * @param [type] $old
     * @param [type] $new
     * @return void
     */
    public function Move($old, $new)
    {
        if (file_exists($old)) {
            if (!file_exists($new)) 
                rename($old, $new);
            else 
                $this->Errors = basename($new) . " : " . __d('fmanager', 'Impossible de déplacer');
        } else {
            $this->Errors = basename($old) . " : " . __d('fmanager', 'Le fichier n\'existe pas');
        }

        if ($this->Errors != "") 
            return (false);
        else 
            return (true);
    }

    /**
     * copying
     *
     * @param [type] $old
     * @param [type] $new
     * @return void
     */
    public function Copy($old, $new)
    {
        if (file_exists($old)) {
            if (!file_exists($new)) 
                $noerr = copy($old, $new);
            else {
                $new = str_replace(basename($new), __d('fmanager', 'Copie de ') . basename($new), $new);
                $noerr = copy($old, $new);
            }

            if (!$noerr) 
                $this->Errors = basename($new) . " : " . __d('fmanager', 'Impossible de copier');
        } else {
            $this->Errors = basename($old) . " : " . __d('fmanager', 'Le fichier n\'existe pas');
        }

        if ($this->Errors != "") 
            return (false);
        else 
            return (true);
    }

    /**
     * Create file/dir
     *
     * @param [type] $what
     * @param [type] $name
     * @return void
     */
    public function Create($what, $name)
    {
        @umask("0000");

        switch ($what) {
            case "f":
                if (!file_exists($name)) {
                    if (!$fp = fopen($name, "w")) 
                        $this->Errors = __d('fmanager', 'Impossible de créer') . " : " . basename($name);
                    else 
                        fclose($fp);
                } else
                    $this->Errors = basename($name) . " : " . __d('fmanager', 'existe déjà');
                break;

            case "d":
                if (!file_exists($name)) {
                    if (!mkdir($name, 0777)) 
                        $this->Errors = __d('fmanager', 'Impossible de créer') . " : " . basename($name);
                } else
                    $this->Errors = basename($name) . " : " . __d('fmanager', 'existe déjà');
                break;
        }

        if ($this->Errors != "") 
            return (false);
        else 
            return (true);
    }

    /**
     * remove a file
     *
     * @param [type] $file
     * @return void
     */
    public function Remove($file)
    {
        if (is_file($file)) {
            if (!@unlink($file))
                $this->Errors = __d('fmanager', 'Impossible de supprimer') . " : " . basename($file);
        }

        if ($this->Errors != "") 
            return (false);
        else 
            return (true);
    }

    /**
     * remove directory
     *
     * @param [type] $dir
     * @return void
     */
    public function RemoveDir($dir)
    {
        if ($handle = @opendir($dir)) {
            closedir($handle);

            if (!@rmdir($dir))
                $this->Errors = __d('fmanager', 'Impossible de supprimer') . " : " . basename($dir);
        }

        if ($this->Errors != "") 
            return (false);
        else 
            return (true);
    }
 
    /**
     * convert size to KB, MB, GB
     *
     * @param [type] $sz
     * @return void
     */
    public function ConvertSize($sz)
    {
        if ($sz >= 1073741824) {
            $sz = round($sz / 1073741824 * 100) / 100 . " Gb";
        } elseif ($sz >= 1048576) {
            $sz = round($sz / 1048576 * 100)    / 100 . " Mb";
        } elseif ($sz >= 1024) {
            $sz = round($sz / 1024 * 100)       / 100 . " Kb";
        } else {
            $sz = $sz . " b";
        }
        
        return $sz;
    }
    
}
