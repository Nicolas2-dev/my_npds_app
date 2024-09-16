<?php

use App\Modules\News\Library\NewsManager;
use App\Modules\NewsLibrary\Compress\GzFile;
use App\Modules\News\Library\News\Compress\ZipFile;


if (! function_exists('ultramode'))
{
    /**
     * [ultramode description]
     *
     * @return  [type]  [return description]
     */
    function ultramode()
    {
        return NewsManager::getInstance()->ultramode();
    }
}

if (! function_exists('automatednews'))
{
    /**
     * [automatednews description]
     *
     * @return  [type]  [return description]
     */
    function automatednews()
    {
        return NewsManager::getInstance()->automatednews();
    }
}

if (! function_exists('ctrl_aff'))
{
    /**
     * [ctrl_aff description]
     *
     * @param   [type]  $ihome  [$ihome description]
     * @param   [type]  $catid  [$catid description]
     *
     * @return  [type]          [return description]
     */
    function ctrl_aff($ihome, $catid = 0)
    {
        return NewsManager::getInstance()->ctrl_aff($ihome, $catid);
    }
}

if (! function_exists('aff_news'))
{
    /**
     * [aff_news description]
     *
     * @param   [type]  $op       [$op description]
     * @param   [type]  $catid    [$catid description]
     * @param   [type]  $marqeur  [$marqeur description]
     *
     * @return  [type]            [return description]
     */
    function aff_news($op, $catid, $marqeur)
    {
        return NewsManager::getInstance()->aff_news($op, $catid, $marqeur);
    }
}

if (! function_exists('news_aff'))
{
    /**
     * [news_aff description]
     *
     * @param   [type]  $type_req  [$type_req description]
     * @param   [type]  $sel       [$sel description]
     * @param   [type]  $storynum  [$storynum description]
     * @param   [type]  $oldnum    [$oldnum description]
     *
     * @return  [type]             [return description]
     */
    function news_aff($type_req, $sel, $storynum, $oldnum)
    {
        return NewsManager::getInstance()->news_aff($type_req, $sel, $storynum, $oldnum);
    }
}

if (! function_exists('prepa_aff_news'))
{
    /**
     * [prepa_aff_news description]
     *
     * @param   [type]  $op       [$op description]
     * @param   [type]  $catid    [$catid description]
     * @param   [type]  $marqeur  [$marqeur description]
     *
     * @return  [type]            [return description]
     */
    function prepa_aff_news($op, $catid, $marqeur)
    {
        return NewsManager::getInstance()->prepa_aff_news($op, $catid, $marqeur);
    }
}

if (! function_exists('getTopics'))
{
    /**
     * [getTopics description]
     *
     * @param   [type]  $s_sid  [$s_sid description]
     *
     * @return  [type]          [return description]
     */
    function getTopics($s_sid)
    {
        return NewsManager::getInstance()->getTopics($s_sid);
    }
}

if (! function_exists('send_file'))
{    
    /**
     * [send_file description]
     *
     * @param   [type]  $line       [$line description]
     * @param   [type]  $filename   [$filename description]
     * @param   [type]  $extension  [$extension description]
     * @param   [type]  $MSos       [$MSos description]
     *
     * @return  [type]              [return description]
     */
    function send_file($line, $filename, $extension, $MSos)
    {
        $compressed = false;

        if (file_exists("Library/News/Archive.php")) {
            if (function_exists("gzcompress")) {
                $compressed = true;
            }
        }

        if ($compressed) {
            if ($MSos) {
                $arc = new ZipFile();
                $filez = $filename . ".zip";
            } else {
                $arc = new GzFile();
                $filez = $filename . ".gz";
            }

            $arc->addfile($line, $filename . "." . $extension, "");
            $arc->arc_getdata();
            $arc->filedownload($filez);

        } else {
            if ($MSos) {
                header("Content-Type: application/octetstream");
            } else {
                header("Content-Type: application/octet-stream");
            }

            header("Content-Disposition: attachment; filename=\"$filename." . "$extension\"");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo $line;
        }
    }
}

if (! function_exists('send_tofile'))
{    
    /**
     * [send_tofile description]
     *
     * @param   [type]  $line        [$line description]
     * @param   [type]  $repertoire  [$repertoire description]
     * @param   [type]  $filename    [$filename description]
     * @param   [type]  $extension   [$extension description]
     * @param   [type]  $MSos        [$MSos description]
     *
     * @return  [type]               [return description]
     */
    function send_tofile($line, $repertoire, $filename, $extension, $MSos)
    {
        $compressed = false;

        if (file_exists("Library/News/Archive.php")) {
            if (function_exists("gzcompress")) {
                $compressed = true;
            }
        }

        if ($compressed) {
            if ($MSos) {
                $arc = new ZipFile();
                $filez = $filename . ".zip";
            } else {
                $arc = new GzFile();
                $filez = $filename . ".gz";
            }

            $arc->addfile($line, $filename . "." . $extension, "");
            $arc->arc_getdata();

            if (file_exists($repertoire . "/" . $filez)) 
                unlink($repertoire . "/" . $filez);

            $arc->filewrite($repertoire . "/" . $filez, $perms = null);
        } else {
            if ($MSos) {
                header("Content-Type: application/octetstream");
            } else {
                header("Content-Type: application/octet-stream");
            }

            header("Content-Disposition: attachment; filename=\"$filename." . "$extension\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            echo $line;
        }
    }
}
