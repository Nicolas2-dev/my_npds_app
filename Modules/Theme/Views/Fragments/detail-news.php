<table border="0" cellpadding="2" cellspacing="0" width="100%">
    <tr>
        <td valign="top">
            <div class="article">
                <h2>!N_titre!</h2>
                <div id="header">&Eacute;crit par !N_emetteur!. Posté le !N_date! par !N_publicateur!
                    <?php
                    global $admin, $sid;
                    if ($admin) {
                        echo "&nbsp;[ <a href=\"admin.php?op=EditStory&sid=" . $sid . "\">" . __d('theme', 'Editer') . "</a> | <a href=\"admin.php?op=RemoveStory&sid=" . $sid . "\">" . __d('theme', 'Effacer') . "</a> ]\n";
                    }
                    ?>
                </div>
                <div>
                    !N_texte!
                </div>
            </div>
        </td>
        <td valign="top">
            <div id="box_article">
                <div class="bloc_title1">
                    <h3>
                        <!-- PHP -->
                        <?php
                        global $boxtitle;
                        echo $boxtitle;
                        ?>
                        <!-- PHP -->
                    </h3>
                </div>
                <br />
                <div class="bloc_content1">
                    <!-- PHP -->
                    <?php
                    global $boxstuff;
                    echo $boxstuff;
                    ?>
                    <!-- PHP -->
                </div>
                <div class="bloc_content2"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="header">Article&nbsp;&nbsp;!N_previous_article!&nbsp;&nbsp;!N_next_article!
        </td>
    </tr>
</table>
comment_system(article,!N_ID!)