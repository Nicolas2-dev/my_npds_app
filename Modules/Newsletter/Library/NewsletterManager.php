<?php

namespace Modules\Newsletter\Library;


use Modules\Newsletter\Contracts\NewsletterInterface;

/**
 * Undocumented class
 */
class NewsletterManager implements NewsletterInterface 
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

    // Front

    /**
     * Undocumented function
     *
     * @param [type] $email
     * @return void
     */
    public function SuserCheck($email)
    {
        global $stop;
    
        $stop = '';
    
        if ((!$email) || ($email == '') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $email)))
            $stop = __d('newsletter', 'Erreur : Email invalide');
    
        if (strrpos($email, ' ') > 0)
            $stop = __d('newsletter', 'Erreur : une adresse Email ne peut pas contenir d\'espaces');
    
        if (checkdnsmail($email) === false)
            $stop = __d('newsletter', 'Erreur : DNS ou serveur de mail incorrect');
    
        if (sql_num_rows(sql_query("SELECT email FROM users WHERE email='$email'")) > 0)
            $stop = __d('newsletter', 'Erreur : adresse Email déjà utilisée');
    
        if (sql_num_rows(sql_query("SELECT email FROM lnl_outside_users WHERE email='$email'")) > 0) {
            if (sql_num_rows(sql_query("SELECT email FROM lnl_outside_users WHERE email='$email' AND status='NOK'")) > 0)
                sql_query("DELETE FROM lnl_outside_users WHERE email='$email'");
            else
                $stop = __d('newsletter', 'Erreur : adresse Email déjà utilisée');
        }
    
        return $stop;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function error_handler($ibid)
    {
        echo '
        <h2>' . __d('newsletter', 'La lettre') . '</h2>
        <hr />
        <p class="lead mb-2">' . __d('newsletter', 'Merci d\'entrer l\'information en fonction des spécifications') . '</p>
        <div class="alert alert-danger">' . $ibid . '</div>
        <a href="index.php" class="btn btn-outline-secondary">' . __d('newsletter', 'Retour en arrière') . '</a>';
    }

    // Admin

    /**
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function error_handler_admin($ibid)
    {
        echo "<p align=\"center\"><span class=\"rouge\">" . __d('newsletter', 'Merci d\'entrer l\'information en fonction des spécifications') . "<br /><br />";
        echo "$ibid</span><br /><a href=\"index.php\" class=\"noir\">" . __d('newsletter', 'Retour en arrière') . "</a></p>";
    }

    /**
     * 
     * Undocumented function
     *
     * @return void
     */
    public function ShowHeader()
    {
        $result = sql_query("SELECT ref, text, html FROM lnl_head_foot WHERE type='HED' ORDER BY ref ");
    
        echo '
        <table data-toggle="table" class="table-no-bordered">
            <thead class="d-none">
                <tr>
                    <th class="n-t-col-xs-1" data-align="">ID</th>
                    <th class="n-t-col-xs-8" data-align="">' . __d('newsletter', 'Entête') . '</th>
                    <th class="n-t-col-xs-1" data-align="">Type</th>
                    <th class="n-t-col-xs-2" data-align="right">' . __d('newsletter', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        while (list($ref, $text, $html) = sql_fetch_row($result)) {
            $text = nl2br(htmlspecialchars($text, ENT_COMPAT | ENT_HTML401, cur_charset));
    
            if (strlen($text) > 100)
                $text = substr($text, 0, 100) . '<span class="text-danger"> .....</span>';
    
            if ($html == 1) 
                $html = 'html';
            else 
                $html = 'txt';
    
            echo '
                <tr>
                    <td>' . $ref . '</td>
                    <td>' . $text . '</td>
                    <td><code>' . $html . '</code></td>
                    <td><a href="admin.php?op=lnl_Shw_Header&amp;Headerid=' . $ref . '" ><i class="fa fa-edit fa-lg me-2" title="' . __d('newsletter', 'Editer') . '" data-bs-toggle="tooltip" data-bs-placement="left"></i></a><a href="admin.php?op=lnl_Sup_Header&amp;Headerid=' . $ref . '" class="text-danger"><i class="fas fa-trash fa-lg" title="' . __d('newsletter', 'Effacer') . '" data-bs-toggle="tooltip" data-bs-placement="left"></i></a></td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>';
    } 

    /**
     * Undocumented function
     *
     * @return void
     */
    public function ShowBody()
    {
        $result = sql_query("SELECT ref, text, html FROM lnl_body ORDER BY ref ");
    
        echo '
        <table data-toggle="table" class="table-no-bordered">
            <thead class="d-none">
                <tr>
                    <th class="n-t-col-xs-1" data-align="">ID</th>
                    <th class="n-t-col-xs-8" data-align="">' . __d('newsletter', 'Corps de message') . '</th>
                    <th class="n-t-col-xs-1" data-align="">Type</th>
                    <th class="n-t-col-xs-2" data-align="right">' . __d('newsletter', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        while (list($ref, $text, $html) = sql_fetch_row($result)) {
            $text = nl2br(htmlspecialchars($text, ENT_COMPAT | ENT_HTML401, cur_charset));
    
            if (strlen($text) > 200)
                $text = substr($text, 0, 200) . '<span class="text-danger"> .....</span>';
    
            if ($html == 1) 
                $html = 'html';
            else 
                $html = 'txt';
    
            echo '
            <tr>
                <td>' . $ref . '</td>
                <td>' . $text . '</td>
                <td><code>' . $html . '</code></td>
                <td><a href="admin.php?op=lnl_Shw_Body&amp;Bodyid=' . $ref . '"><i class="fa fa-edit fa-lg me-2" title="' . __d('newsletter', 'Editer') . '" data-bs-toggle="tooltip" data-bs-placement="left"></i></a><a href="admin.php?op=lnl_Sup_Body&amp;Bodyid=' . $ref . '" class="text-danger"><i class="fas fa-trash fa-lg" title="' . __d('newsletter', 'Effacer') . '" data-bs-toggle="tooltip" data-bs-placement="left"></i></a></td>
            </tr>';
        }
    
        echo '
            </tbody>
        </table>';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function ShowFooter()
    {
        $result = sql_query("SELECT ref, text, html FROM lnl_head_foot WHERE type='FOT' ORDER BY ref ");
    
        echo '
        <table data-toggle="table" class="table-no-bordered">
            <thead class="d-none">
                <tr>
                    <th class="n-t-col-xs-1" data-align="">ID</th>
                    <th class="n-t-col-xs-8" data-align="">' . __d('newsletter', 'Pied') . '</th>
                    <th class="n-t-col-xs-1" data-align="">Type</th>
                    <th class="n-t-col-xs-2" data-align="right">' . __d('newsletter', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        while (list($ref, $text, $html) = sql_fetch_row($result)) {
            $text = nl2br(htmlspecialchars($text, ENT_COMPAT | ENT_HTML401, cur_charset));
    
            if (strlen($text) > 100)
                $text = substr($text, 0, 100) . '<span class="text-danger"> .....</span>';
    
            if ($html == 1) 
                $html = 'html';
            else 
                $html = 'txt';
    
            echo '
                <tr>
                    <td>' . $ref . '</td>
                    <td>' . $text . '</td>
                    <td><code>' . $html . '</code></td>
                    <td><a href="admin.php?op=lnl_Shw_Footer&amp;Footerid=' . $ref . '" ><i class="fa fa-edit fa-lg me-2" title="' . __d('newsletter', 'Editer') . '" data-bs-toggle="tooltip" data-bs-placement="left"></i></a><a href="admin.php?op=lnl_Sup_Footer&amp;Footerid=' . $ref . '" class="text-danger"><i class="fas fa-trash fa-lg" title="' . __d('newsletter', 'Effacer') . '" data-bs-toggle="tooltip" data-bs-placement="left"></i></a></td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>';
    }

}
