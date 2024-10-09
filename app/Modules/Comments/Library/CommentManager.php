<?php

namespace App\Modules\Comments\Library;

use App\Modules\Comments\Contracts\CommentInterface;

/**
 * Undocumented class
 */
class CommentManager implements CommentInterface 
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
     * @param [type] $topic
     * @param [type] $file_name
     * @param [type] $archive
     * @return void
     */
    public function Caff_pub($topic, $file_name, $archive)
    {
        return '<a href="modules.php?ModPath=comments&ModStart=reply&topic=' . $topic . '&file_name=' . $file_name . '&archive=' . $archive . '" class="btn btn-primary btn-sm" role="button">' . __d('comments', 'Commentaire') . '</a>';
    }

}
