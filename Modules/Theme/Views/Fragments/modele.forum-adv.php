<script language="javascript" src="<?= site_url('assets/js/cookies.js'); ?>"></script>
<script language="javascript">
    //<![CDATA[
    if (!getCookie('forum_cat'))
        setCookie('forum_cat', 'cat1', 30);
    //]]>
</script>

<table border="0" width="100%">
    <tr>
        <td style="vertical-align:top;">
            <a href="forum.php#"
                onclick="document.getElementById('forum_cat1').style.display='block';
           document.getElementById('forum_cat2').style.display='none';
           document.getElementById('forum_cat3').style.display='none';
           groupe_text(" 2,3,4,5,6")
                document.getElementById('forum_cat9').style.display='none' ;
                !/!
                setCookie('forum_cat', 'cat1' , 30);">theme_img(forum/ong-App.gif)</a>
        </td>
        <td style="vertical-align:top;">
            <a href="forum.php#"
                onclick="document.getElementById('forum_cat2').style.display='block';
           document.getElementById('forum_cat1').style.display='none';
           document.getElementById('forum_cat3').style.display='none';
           groupe_text(" 2,3,4,5,6")
                document.getElementById('forum_cat9').style.display='none' ;
                !/!
                setCookie('forum_cat', 'cat2' , 30);">theme_img(forum/ong-docus.gif)</a>
        </td>
        <td style="vertical-align:top;">
            <a href="forum.php#"
                onclick="document.getElementById('forum_cat3').style.display='block';
           document.getElementById('forum_cat1').style.display='none';
           document.getElementById('forum_cat2').style.display='none';
           groupe_text(" 2,3,4,5,6")
                document.getElementById('forum_cat9').style.display='none' ;
                !/!
                setCookie('forum_cat', 'cat3' , 30);">theme_img(forum/ong-styles.gif)</a>
        </td>

        groupe_text("2,3,4,5,6")
        <td style="vertical-align:top;">
            <a href="forum.php#"
                onclick="document.getElementById('forum_cat9').style.display='block';
            document.getElementById('forum_cat1').style.display='none';
            document.getElementById('forum_cat2').style.display='none';
            document.getElementById('forum_cat3').style.display='none';
            setCookie('forum_cat', 'cat9', 30);">theme_img(forum/ong-team.gif)</a>
        </td>
        !/!

        <td width="60%">&nbsp;
        </td>
    </tr>forum_subscribeON()
</table>

<div id="forum_cat1" style="display: none;">
    <div class="detnews">Les forums généraux de App</div>
    <table width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
            <td colspan="2" width="45%">&nbsp;</td>
            forum_bouton_subscribe()
            <td width="15%" class="header" style="text-align: center;">[english]Type[/english][french]Type[/french]</td>
            <td width="10%" class="header" style="text-align: center;">[english]Topics[/english][french]Sujets[/french]</td>
            <td width="5%" class="header" style="text-align: center;">[english]NB[/english][french]NB[/french]</td>
            <td width="15%" class="header" style="text-align: center;">[english]Last Posts[/english][french]Derni�re contribution[/french]</td>
        </tr>
        forum_categorie("1,2,3,4") forum_message()
    </table>
</div>

<div id="forum_cat2" style="display: none;">
    <div class="detnews">Les forums relatifs à la documentation<br />En cours de mise en place</div>
    <table width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
            <td colspan="2" width="45%">&nbsp;</td>
            forum_bouton_subscribe()
            <td align="center" width="15%" class="header" style="text-align: center;">[english]Type[/english][french]Type[/french]</td>
            <td align="center" width="10%" class="header" style="text-align: center;">[english]Topics[/english][french]Sujets[/french]</td>
            <td align="center" width="5%" class="header" style="text-align: center;">[english]NB[/english][french]NB[/french]</td>
            <td align="center" width="15%" class="header" style="text-align: center;">[english]Last Posts[/english][french]Derni�re contribution[/french]</td>
        </tr>
    </table>
</div>

<div id="forum_cat3" style="display: none;">
    <div class="detnews">Les forums relatifs aux thèmes, styles, ...<br />En cours de mise en place</div>
    <table width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
            <td colspan="2" width="45%">&nbsp;</td>
            forum_bouton_subscribe()
            <td align="center" width="15%" class="header" style="text-align: center;">[english]Type[/english][french]Type[/french]</td>
            <td align="center" width="10%" class="header" style="text-align: center;">[english]Topics[/english][french]Sujets[/french]</td>
            <td align="center" width="5%" class="header" style="text-align: center;">[english]NB[/english][french]NB[/french]</td>
            <td align="center" width="15%" class="header" style="text-align: center;">[english]Last Posts[/english][french]Derni�re contribution[/french]</td>
        </tr>
    </table>
</div>

groupe_text("2,3,4,5,6")
<div id="forum_cat9" style="display: none;">
    <div class="detnews">Les forums de la team</div>
    <table width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
            <td colspan="2" width="45%">&nbsp;</td>
            forum_bouton_subscribe()
            <td align="center" width="15%" class="header" style="text-align: center;">[english]Type[/english][french]Type[/french]</td>
            <td align="center" width="10%" class="header" style="text-align: center;">[english]Topics[/english][french]Sujets[/french]</td>
            <td align="center" width="5%" class="header" style="text-align: center;">[english]NB[/english][french]NB[/french]</td>
            <td align="center" width="15%" class="header" style="text-align: center;">[english]Last Posts[/english][french]Derni�re contribution[/french]</td>
        </tr>
        forum_categorie(5) forum_message()
    </table>
</div>
!/!

forum_subscribeOFF()
<table width="100%" cellspacing="0" cellpadding="4" border="0">
    <tr>
        <td>
            forum_recherche()
        </td>
    </tr>
</table>
<br />
forum_icones()

<script language="JavaScript">
    //<![CDATA[
    var cook = getCookie('forum_cat');
    if (cook == 'cat1') document.getElementById('forum_cat1').style.display = 'block';
    if (cook == 'cat2') document.getElementById('forum_cat2').style.display = 'block';
    if (cook == 'cat3') document.getElementById('forum_cat3').style.display = 'block';
    if (cook == 'cat9') document.getElementById('forum_cat9').style.display = 'block';
    //]]>
</script>

<br /><br />