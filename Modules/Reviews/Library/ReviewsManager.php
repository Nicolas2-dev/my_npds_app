<?php

namespace Modules\Reviews\Library;

use Npds\Routing\Url;
use Modules\Npds\Support\Sanitize;
use Modules\Npds\Support\Facades\Hack;
use Modules\Npds\Support\Facades\Spam;
use Modules\Npds\Support\Facades\Language;
use Modules\Reviews\Contracts\ReviewsInterface;

/**
 * Undocumented class
 */
class ReviewsManager implements ReviewsInterface 
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
     * Undocumented function
     *
     * @param [type] $score
     * @return void
     */
    public function display_score($score)
    {
        $image      = '<i class="fa fa-star"></i>';
        $halfimage  = '<i class="fas fa-star-half-alt"></i>';
        $full       = '<i class="fa fa-star"></i>';
    
        if ($score == 10) {
            for ($i = 0; $i < 5; $i++) {
                echo $full;
            }
        } else if ($score % 2) {
            $score -= 1;
            $score /= 2;
    
            for ($i = 0; $i < $score; $i++) {
                echo $image;
            }
    
            echo $halfimage;
        } else {
            $score /= 2;
    
            for ($i = 0; $i < $score; $i++) {
                echo $image;
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $myrow
     * @return void
     */
    public function reversedate($myrow)
    {
        if (substr($myrow, 2, 1) == '-') {
            $day    = substr($myrow, 0, 2);
            $month  = substr($myrow, 3, 2);
            $year   = substr($myrow, 6, 4);
        } else {
            $day    = substr($myrow, 8, 2);
            $month  = substr($myrow, 5, 2);
            $year   = substr($myrow, 0, 4);
        }
    
        return ($year . '-' . $month . '-' . $day);
    }

    /**
     * case 'add_reviews': => send_review($date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id, $asb_question, $asb_reponse);
     * 
     * Undocumented function
     *
     * @param [type] $date
     * @param [type] $title
     * @param [type] $text
     * @param [type] $reviewer
     * @param [type] $email
     * @param [type] $score
     * @param [type] $cover
     * @param [type] $url
     * @param [type] $url_title
     * @param [type] $hits
     * @param [type] $id
     * @param [type] $asb_question
     * @param [type] $asb_reponse
     * @return void
     */
    public function send_review($date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id, $asb_question, $asb_reponse)
    {
        global $admin, $user;
    
        $date   = $this->reversedate($date);

        $title  = stripslashes(Sanitize::FixQuotes(strip_tags($title)));
        $text   = stripslashes(Sanitize::Fixquotes(urldecode(Hack::remove($text))));
    
        if (!$user and !$admin) {
            //anti_spambot
            if (!Spam::R_spambot($asb_question, $asb_reponse, $text)) {
                Ecr_Log('security', 'Review Anti-Spam : title=' . $title, '');
                
                Url::redirect("index.php");
                die();
            }
        }
    
        if ($id != 0) {
            echo '<h2>' . __d('reviews', 'Modification d\'une critique') . '</h2>';
        } else {
            echo '<h2>' . __d('reviews', 'Ecrire une critique') . '</h2>';
        }

        echo '
        <hr />
        <div class="alert alert-success">';
    
        if ($id != 0) {
            echo __d('reviews', 'Merci d\'avoir modifié cette critique') . '.';
        } else {
            echo __d('reviews', 'Merci d\'avoir posté cette critique') . ', ' . $reviewer;
        }

        echo '<br />';
    
        if (($admin) && ($id == 0)) {
            sql_query("INSERT INTO reviews VALUES (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$cover', '$url', '$url_title', '1')");
            
            echo __d('reviews', 'Dès maintenant disponible dans la base de données des critiques.');
        } elseif (($admin) && ($id != 0)) {
            sql_query("UPDATE reviews SET date='$date', title='$title', text='$text', reviewer='$reviewer', email='$email', score='$score', cover='$cover', url='$url', url_title='$url_title', hits='$hits' WHERE id='$id'");
            
            echo __d('reviews', 'Dès maintenant disponible dans la base de données des critiques.');
        } else {
            sql_query("INSERT INTO reviews_add VALUES (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$url', '$url_title')");
            
            echo __d('reviews', 'Nous allons vérifier votre contribution. Elle devrait bientôt être disponible !');
        }
    
        echo '
        </div>
        <a class="btn btn-secondary" href="reviews.php" title="' . __d('reviews', 'Retour à l\'index des critiques') . '"><i class="fa fa-lg fa-undo"></i>  ' . __d('reviews', 'Retour à l\'index des critiques') . '</a>';
    }

    /**
     * default:     => reviews('date', 'DESC');
     * case 'sort': => reviews($field, $order);
     * 
     * Undocumented function
     *
     * @param [type] $field
     * @param [type] $order
     * @return void
     */
    public function reviews($field, $order)
    {
        $r_result = sql_query("SELECT title, description FROM reviews_main");
        list($r_title, $r_description) = sql_fetch_row($r_result);
        
        if ($order != "ASC" and $order != "DESC") {
            $order = "ASC";
        }
        
        switch ($field) {
            case 'reviewer':
                $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM reviews ORDER BY reviewer $order");
                break;
    
            case 'score':
                $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM reviews ORDER BY score $order");
                break;
    
            case 'hits':
                $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM reviews ORDER BY hits $order");
                break;
    
            case 'date':
                $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM reviews ORDER BY id $order");
                break;
    
            default:
                $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM reviews ORDER BY title $order");
                break;
        }
    
        $numresults = sql_num_rows($result);
    
        echo '
        <h2>' . __d('reviews', 'Critiques') . '<span class="badge bg-secondary float-end" title="' . $numresults . ' ' . __d('reviews', 'Critique(s) trouvée(s).') . '" data-bs-toggle="tooltip">' . $numresults . '</span></h2>
        <hr />
        <h3>' . Language::aff_langue($r_title) . '</h3>
        <p class="lead">' . Language::aff_langue($r_description) . '</p>
        <h4><a href="reviews.php?op=write_review"><i class="fa fa-edit"></i></a>&nbsp;' . __d('reviews', 'Ecrire une critique') . '</h4><br />
        ';
    
        echo '
        <div class="dropdown">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-sort-amount-down me-2"></i>' . __d('reviews', 'Critiques') . '
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="reviews.php?op=sort&amp;field=date&amp;order=ASC"><i class="fa fa-sort-amount-down me-2"></i>' . __d('reviews', 'Date') . '</a>
                <a class="dropdown-item" href="reviews.php?op=sort&amp;field=date&amp;order=DESC"><i class="fa fa-sort-amount-up me-2"></i>' . __d('reviews', 'Date') . '</a>
                <a class="dropdown-item" href="reviews.php?op=sort&amp;field=title&amp;order=ASC"><i class="fa fa-sort-amount-down me-2"></i>' . __d('reviews', 'Titre') . '</a>
                <a class="dropdown-item" href="reviews.php?op=sort&amp;field=title&amp;order=DESC"><i class="fa fa-sort-amount-up me-2"></i>' . __d('reviews', 'Titre') . '</a>
                <a class="dropdown-item" href="reviews.php?op=sort&amp;field=reviewer&amp;order=ASC"><i class="fa fa-sort-amount-down me-2"></i>' . __d('reviews', 'Posté par') . '</a>
                <a class="dropdown-item" href="reviews.php?op=sort&amp;field=reviewer&amp;order=DESC"><i class="fa fa-sort-amount-up me-2"></i>' . __d('reviews', 'Posté par') . '</a>
                <a class="dropdown-item" href="reviews.php?op=sort&amp;field=score&amp;order=ASC"><i class="fa fa-sort-amount-down me-2"></i>Score</a>
                <a class="dropdown-item" href="reviews.php?op=sort&amp;field=score&amp;order=DESC"><i class="fa fa-sort-amount-up me-2"></i>Score</a>
                <a class="dropdown-item" href="reviews.php?op=sort&amp;field=hits&amp;order=ASC"><i class="fa fa-sort-amount-down"></i>Hits</a>
                <a class="dropdown-item" href="reviews.php?op=sort&amp;field=hits&amp;order=DESC"><i class="fa fa-sort-amount-up"></i>Hits</a>
            </div>
        </div>';
    
        if ($numresults > 0) {
            echo '
            <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">
                <thead>
                    <tr>
                    <th data-align="center">
                        <a href="reviews.php?op=sort&amp;field=date&amp;order=ASC"><i class="fa fa-sort-amount-down"></i></a> ' . __d('reviews', 'Date') . ' <a href="reviews.php?op=sort&amp;field=date&amp;order=DESC"><i class="fa fa-sort-amount-up"></i></a>
                    </th>
                    <th data-align="left" data-halign="center" data-sortable="true" data-sorter="htmlSorter">
                        <a href="reviews.php?op=sort&amp;field=title&amp;order=ASC"><i class="fa fa-sort-amount-down"></i></a> ' . __d('reviews', 'Titre') . ' <a href="reviews.php?op=sort&amp;field=title&amp;order=DESC"><i class="fa fa-sort-amount-up"></i></a>
                    </th>
                    <th data-align="center" data-sortable="true">
                        <a href="reviews.php?op=sort&amp;field=reviewer&amp;order=ASC"><i class="fa fa-sort-amount-down"></i></a> ' . __d('reviews', 'Posté par') . ' <a href="reviews.php?op=sort&amp;field=reviewer&amp;order=DESC"><i class="fa fa-sort-amount-up"></i></a>
                    </th>
                    <th class="n-t-col-xs-2" data-align="center" data-sortable="true">
                        <a href="reviews.php?op=sort&amp;field=score&amp;order=ASC"><i class="fa fa-sort-amount-down"></i></a> Score <a href="reviews.php?op=sort&amp;field=score&amp;order=DESC"><i class="fa fa-sort-amount-up"></i></a>
                    </th>
                    <th class="n-t-col-xs-2" data-align="right" data-sortable="true">
                        <a href="reviews.php?op=sort&amp;field=hits&amp;order=ASC"><i class="fa fa-sort-amount-down"></i></a> Hits <a href="reviews.php?op=sort&amp;field=hits&amp;order=DESC"><i class="fa fa-sort-amount-up"></i></a>
                    </th>
                    </tr>
            </thead>
            <tbody>';
    
            while ($myrow = sql_fetch_assoc($result)) {
                $title      = $myrow['title'];
                $id         = $myrow['id'];
                $reviewer   = $myrow['reviewer'];
                $score      = $myrow['score'];
                $hits       = $myrow['hits'];
                $date       = $myrow['date'];
    
                echo '
                <tr>
                   <td>' . $this->f_date($date) . '</td>
                   <td><a href="reviews.php?op=showcontent&amp;id=' . $id . '">' . ucfirst($title) . '</a></td>
                   <td>';
    
                if ($reviewer != '') {
                    echo $reviewer;
                }
    
                echo '</td>
                   <td><span class="text-success">';
    
                $this->display_score($score);
    
                echo '</span></td>
                   <td>' . $hits . '</td>
                </tr>';
            }
    
            echo '
                </tbody>
            </table>';
        }
    
        sql_free_result($result);
    }

    /**
     * Undocumented function
     *
     * @param [type] $xdate
     * @return void
     */
    public function f_date($xdate)
    {
        $year   = substr($xdate, 0, 4);
        $month  = substr($xdate, 5, 2);
        $day    = substr($xdate, 8, 2);

        return date(str_replace("%", '', __d('reviews', 'linksdatestring')), mktime(0, 0, 0, (int)$month, (int)$day, (int)$year));
    }

}
