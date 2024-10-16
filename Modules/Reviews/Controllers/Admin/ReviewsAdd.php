<?php

namespace Modules\Reviews\Controllers\Admin;

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Core\AdminController;


class ReviewsAdd extends AdminController
{
/**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = 'reviews';

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = 'reviews';


    /**
     * Call the parent construct
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
        $this->f_titre = __d('reviews', 'Critiques');

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
     * @param [type] $date
     * @param [type] $title
     * @param [type] $text
     * @param [type] $reviewer
     * @param [type] $email
     * @param [type] $score
     * @param [type] $cover
     * @param [type] $url
     * @param [type] $url_title
     * @return void
     */
    public function add_review($id, $date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title)
    {
        $title      = stripslashes(Sanitize::FixQuotes($title));
        $text       = stripslashes(Sanitize::FixQuotes($text));
        $reviewer   = stripslashes(Sanitize::FixQuotes($reviewer));
        $email      = stripslashes(Sanitize::FixQuotes($email));
    
        sql_query("INSERT INTO reviews VALUES (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$cover', '$url', '$url_title', '1')");
        sql_query("DELETE FROM reviews_add WHERE id = '$id'");
    
        Header("Location: admin.php?op=reviews");
    }

}
