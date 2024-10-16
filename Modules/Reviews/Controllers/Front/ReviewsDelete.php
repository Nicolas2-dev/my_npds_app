<?php

namespace Modules\Reviews\Controllers\Front;

use Npds\Routing\Url;
use Modules\Npds\Core\FrontController;


class ReviewsDelete extends FrontController
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
     * @param [type] $id_del
     * @return void
     */
    public function del_review($id_del)
    {
        global $admin;
    
        if ($admin) {
            sql_query("DELETE FROM reviews WHERE id='$id_del'");
    
            // commentaires
            if (file_exists("modules/comments/reviews.conf.php")) {
                include("modules/comments/reviews.conf.php");
    
                sql_query("DELETE FROM posts WHERE forum_id='$forum' AND topic_id='$id_del'");
            }
        }

        Url::redirect("reviews.php");
    }

}
