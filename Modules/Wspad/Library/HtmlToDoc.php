<?php

namespace Modules\Wspad\Library;

/**
 * Convert HTML to MS Word file for PHP 4.2.x or earlier
 * @author Dale Attree
 * @version 1.0.1
 * @name HTML_TO_DOC
 */

/**
 * Convert HTML to MS Word file
 * @author Harish Chauhan
 * @version 1.0.0
 * @name HTML_TO_DOC
 */

 /**
  * Undocumented class
  */
class HtmlToDoc
{

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $docFile = '';

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $title = '';

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $htmlHead = '';

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $htmlBody = '';


    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->title = "Untitled Document";
        $this->htmlHead = '';
        $this->htmlBody = '';
    }

    /**
     * Set the document file name
     *
     * @param [type] $docfile
     * @return void
     */
    public function setDocFileName($docfile)
    {
        //echo 'setDocFileName Entered.<br>';
        $this->docFile = $docfile;

        if (!preg_match("/\.doc$/i", $this->docFile)) {
            $this->docFile .= ".doc";
        }

        return;
    }

    /**
     * Undocumented function
     *
     * @param [type] $title
     * @return void
     */
    public function setTitle($title)
    {
        //echo 'setTitle Entered.<br>';
        $this->title = $title;
    }

    /**
     * Return header of MS Doc
     *
     * @return string
     */
    public function getHeader()
    {
        //echo 'getHeader Entered.<br>';
        $return  = <<<EOH
            <html xmlns:v="urn:schemas-microsoft-com:vml"
            xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:w="urn:schemas-microsoft-com:office:word"
            xmlns="http://www.w3.org/TR/REC-html40">
            
            <head>
            <meta http-equiv=Content-Type content="text/html; charset=utf-8">
            <meta name=ProgId content=Word.Document>
            <meta name=Generator content="Microsoft Word 9">
            <meta name=Originator content="Microsoft Word 9">
            <!--[if !mso]>
            <style>
            v\:* {behavior:url(#default#VML);}
            o\:* {behavior:url(#default#VML);}
            w\:* {behavior:url(#default#VML);}
            .shape {behavior:url(#default#VML);}
            </style>
            <![endif]-->
            <title>$this->title</title>
            <!--[if gte mso 9]><xml>
            <w:WordDocument>
            <w:View>Print</w:View>
            <w:DoNotHyphenateCaps/>
            <w:PunctuationKerning/>
            <w:DrawingGridHorizontalSpacing>9.35 pt</w:DrawingGridHorizontalSpacing>
            <w:DrawingGridVerticalSpacing>9.35 pt</w:DrawingGridVerticalSpacing>
            </w:WordDocument>
            </xml><![endif]-->
            <style>
            <!--
            /* Font Definitions */
            @font-face
                {font-family:Verdana;
                panose-1:2 11 6 4 3 5 4 4 2 4;
                mso-font-charset:0;
                mso-generic-font-family:swiss;
                mso-font-pitch:variable;
                mso-font-signature:536871559 0 0 0 415 0;}
            /* Style Definitions */
            p.MsoNormal, li.MsoNormal, div.MsoNormal
                {mso-style-parent:"";
                margin:0in;
                margin-bottom:.0001pt;
                mso-pagination:widow-orphan;
                font-size:7.5pt;
                    mso-bidi-font-size:8.0pt;
                font-family:"Verdana";
                mso-fareast-font-family:"Verdana";}
            p.small
                {mso-style-parent:"";
                margin:0in;
                margin-bottom:.0001pt;
                mso-pagination:widow-orphan;
                font-size:1.0pt;
                    mso-bidi-font-size:1.0pt;
                font-family:"Verdana";
                mso-fareast-font-family:"Verdana";}
            @page Section1
                {size:8.5in 11.0in;
                margin:1.0in 1.25in 1.0in 1.25in;
                mso-header-margin:.5in;
                mso-footer-margin:.5in;
                mso-paper-source:0;}
            div.Section1
                {page:Section1;}
            -->
            </style>
            <!--[if gte mso 9]><xml>
            <o:shapedefaults v:ext="edit" spidmax="1032">
            <o:colormenu v:ext="edit" strokecolor="none"/>
            </o:shapedefaults></xml><![endif]--><!--[if gte mso 9]><xml>
            <o:shapelayout v:ext="edit">
            <o:idmap v:ext="edit" data="1"/>
            </o:shapelayout></xml><![endif]-->
            $this->htmlHead
            </head>
            <body>
EOH;

        return $return;
    }

    /**
     * Return Document footer
     *
     * @return string
     */
    public function getFotter()
    {
        //echo 'getFotter Entered.<br>';
        return "</body></html>";
    }

    /**
     * Create The MS Word Document from given HTML
     *
     * @param string $html :: URL Name like http://www.example.com
     * @param string $file :: Document File Name
     * @param bool $download :: Wheather to download the file or save the file
     * @return bool 
     */
    public function createDocFromURL($url, $file, $download = false)
    {
        //echo 'createDocFromURL Entered.<br>';
        if (!preg_match("/^http:/", $url))
            $url = "http://" . $url;

        $f = fopen($url, 'rb');

        while (!feof($f)) {
            $html = fread($f, 8192);
        }

        return $this->createDoc($html, $file, $download);
    }

    /**
     * Create The MS Word Document from given HTML
     *
     * @param string $html :: HTML Content or HTML File Name like path/to/html/file.html
     * @param string $file :: Document File Name
     * @param bool $download :: Wheather to download the file or save the file
     * @return bool 
     */
    public function createDoc($html, $file, $download = false)
    {
        //echo 'createDoc Entered.<br>';

        // ?? can not be too long $html est il juste ??? 
        // et pourquoi ???
        if (is_file($html)) {
            $html = @file_get_contents($html);
        }

        $this->_parseHtml($html);
        $this->setDocFileName($file);

        $doc = $this->getHeader();
        $doc .= $this->htmlBody;
        $doc .= $this->getFotter();

        if ($download) {
            //$this->write_file($this->docFile,$doc);
            
            // leave blank to avoid IE errors
            header("Cache-Control: "); 

            // leave blank to avoid IE errors
            header("Pragma: "); 

            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"$this->docFile\"");

            echo $doc;

            return true;
        } else {
            return $this->write_file($this->docFile, $doc);
        }
    }

    /**
     * Parse the html and remove <head></head> part if present into html
     *
     * @param string $html
     * @return void
     */
    private function _parseHtml($html)
    {
        //echo '_parseHtml Entered.<br>';
        $html = preg_replace("/<!DOCTYPE((.|\n)*?)>/ims", "", $html);
        $html = preg_replace("/<script((.|\n)*?)>((.|\n)*?)<\/script>/ims", "", $html);

        preg_match("/<head>((.|\n)*?)<\/head>/ims", $html, $matches);

        $head = $matches[1];

        preg_match("/<title>((.|\n)*?)<\/title>/ims", $head, $matches);

        $this->title = $matches[1];

        $html = preg_replace("/<head>((.|\n)*?)<\/head>/ims", "", $html);
        $head = preg_replace("/<title>((.|\n)*?)<\/title>/ims", "", $head);
        $head = preg_replace("/<\/?head>/ims", "", $head);
        $html = preg_replace("/<\/?body((.|\n)*?)>/ims", "", $html);

        $this->htmlHead = $head;
        $this->htmlBody = $html;

        return;
    }

    /**
     * Write the content int file
     *
     * @param string $file :: File name to be save
     * @param string $content :: Content to be write
     * @param [Optional] String $mode :: Write Mode
     * @return bool
     */
    private function write_file($file, $content, $mode = "w")
    {
        //echo 'write_file entered!<br>';
        $fp = @fopen($file, $mode);

        if (!is_resource($fp)) {
            return false;
        }

        fwrite($fp, $content);
        fclose($fp);
        
        return true;
    }

}
