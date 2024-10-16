<?php

namespace Modules\Reviews\Controllers\Front;

use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Spam;
use Modules\Reviews\Support\Facades\Reviews as LReviews;


class ReviewsShowContent extends FrontController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * [after description]
     *
     * @param   [type]  $result  [$result description]
     *
     * @return  [type]           [return description]
     */
    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function showcontent($id)
    {
        global $admin;
    
        sql_query("UPDATE reviews SET hits=hits+1 WHERE id='$id'");
    
        $result = sql_query("SELECT * FROM reviews WHERE id='$id'");
        $myrow  = sql_fetch_assoc($result);
    
        $id         =  $myrow['id'];
        $fdate      = LReviews::f_date($myrow['date']);
        $title      = $myrow['title'];
        $text       = $myrow['text'];
        $cover      = $myrow['cover'];
        $reviewer   = $myrow['reviewer'];
        $email      = $myrow['email'];
        $hits       = $myrow['hits'];
        $url        = $myrow['url'];
        $url_title  = $myrow['url_title'];
        $score      = $myrow['score'];
    
        echo '
        <h2>' . __d('reviews', 'Critiques') . '</h2>
        <hr />
        <a href="reviews.php">' . __d('reviews', 'Retour à l\'index des critiques') . '</a>
        <div class="card card-body my-3">
            <div class="card-text text-muted text-end small">
        ' . __d('reviews', 'Ajouté :') . ' ' . $fdate . '<br />
            </div>
        <hr />
        <h3 class="mb-3">' . $title . '</h3><br />';
    
        if ($cover != '') {
            echo '<img class="img-fluid" src="assets/images/reviews/' . $cover . '" />';
        }
    
        echo $text;
    
        echo '
            <br /><br />
            <div class="card card-body mb-3">';
    
        if ($reviewer != '') {
            echo '<div class="mb-2"><strong>' . __d('reviews', 'Le critique') . ' :</strong> <a href="mailto:' . Spam::anti_spam($email, 1) . '" >' . $reviewer . '</a></div>';
        }

        if ($score != '') {
            echo '<div class="mb-2"><strong>' . __d('reviews', 'Note') . ' : </strong>';
        }

        echo '<span class="text-success">';
    
        LReviews::display_score($score);
    
        echo '</span>
        </div>';
    
        if ($url != '') {
            echo '<div class="mb-2"><strong>' . __d('reviews', 'Lien relatif') . ' : </strong> <a href="' . $url . '" target="_blank">' . $url_title . '</a></div>';
        }

        echo '<div><strong>' . __d('reviews', 'Hits : ') . '</strong><span class="badge bg-secondary">' . $hits . '</span></div>
        </div>';
    
        if ($admin) {
            echo '
            <nav class="d-flex justify-content-center">
                <ul class="pagination pagination-sm">
                    <li class="page-item disabled">
                    <a class="page-link" href="#"><i class="fa fa-cogs fa-lg"></i><span class="ms-2 d-none d-lg-inline">' . __d('reviews', 'Outils administrateur') . '</span></a>
                    </li>
                    <li class="page-item">
                    <a class="page-link" role="button" href="reviews.php?op=mod_review&amp;id=' . $id . '" title="' . __d('reviews', 'Editer') . '" data-bs-toggle="tooltip" ><i class="fa fa-lg fa-edit" ></i></a>
                    </li>
                    <li class="page-item">
                    <a class="page-link text-danger" role="button" href="reviews.php?op=del_review&amp;id_del=' . $id . '" title="' . __d('reviews', 'Effacer') . '" data-bs-toggle="tooltip" ><i class="fas fa-trash fa-lg" ></i></a>
                    </li>
                </ul>
            </nav>';
        }
    
        echo '</div>';
    
        sql_free_result($result);
    
        global $user;
    
        if (file_exists("modules/comments/reviews.conf.php")) {
            include("modules/comments/reviews.conf.php");
            include("modules/comments/comments.php");
        }
    }

}
