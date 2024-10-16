<?php

if (! function_exists('upload_file_type'))
{
    /**
     * Undocumented function
     *
     * @return void
     */
    function upload_file_type()
    {
        // images
        $handle = opendir("assets/images/upload/file_types");

        $att_icons = '';

        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $prefix = strtoLower(substr($file, 0, strpos($file, '.')));

                $att_icons[$prefix] = "<img src=\"assets/images/upload/file_types/" . $file . "\" border=\"0\" align=\"center\" alt=\"\" />";

                $att_icons[$prefix] = '
                    <span class="fa-stack">
                <i class="fa fa-file fa-stack-2x"></i>
                <span class="fa-stack-1x filetype-text">' . $prefix . '</span>
                </span>';
            }
        }

        closedir($handle); 
        
        return $att_icons;
    }
}

if (! function_exists('win_upload_forum'))
{
    /**
     * Undocumented function
     *
     * @param [type] $apli
     * @param [type] $IdPost
     * @param [type] $IdForum
     * @param [type] $IdTopic
     * @param [type] $typeL
     * @return string
     */
    function win_upload_forum($apli, $IdPost, $IdForum, $IdTopic, $typeL)
    {
        if ($typeL == 'win') {
            echo "
            <script type=\"text/javascript\">
            //<![CDATA[
            window.open('modules.php?ModPath=upload&ModStart=include_forum/upload_forum2&apli=$apli&IdPost=$IdPost&IdForum=$IdForum&IdTopic=$IdTopic','wtmpForum', 'menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes,resizable=yes, width=640, height=480');
            //]]>
            </script>";
        } else {
            return ("'modules.php?ModPath=upload&ModStart=include_forum/upload_forum2&apli=$apli&IdPost=$IdPost&IdForum=$IdForum&IdTopic=$IdTopic','wtmpForum', 'menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes,resizable=yes, width=640, height=480'");
        }
    }
}

if (! function_exists('minisite_win_upload'))
{
    /**
     * Undocumented function
     *
     * @param [type] $typeL
     * @return string
     */
    function minisite_win_upload($typeL)
    {
        if ($typeL == 'win') {
            echo "
            <script type=\"text/javascript\">
            //<![CDATA[
                window.open('modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=minisite-ges','wtmpMinisite', 'menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes,resizable=yes, width=780, height=500');
            //]]>
            </script>";
        } else {
            return ("'modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=minisite-ges','wtmpMinisite', 'menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes,resizable=yes, width=780, height=500'");
        }
    }
}
