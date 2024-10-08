<?php

namespace App\Modules\ArchiveStories\Controllers\Admin;

use Npds\Config\Config;
use Npds\Session\Session;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\AdminController;

class ArchiveStories extends AdminController
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
    protected $hlpfile = "mod-archive-stories";

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
    protected $f_meta_nom = 'archive-stories';


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
        $this->f_titre = __d('authors', 'Administrateurs');

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
     * [configureArchive description]
     *
     * @return  [type]  [return description]
     */
    public function configureArchive()
    {
        $this->set('message', Session::message('message'));

        $fv_parametres = '
        maxcount: {
            validators: {
                regexp: {
                    regexp:/^[1-10](\d{0,2})$/,
                    message: "0-10"
                },
                between: {
                    min: 0,
                    max: 500,
                    message: "1 ... 500"
                }
            }
        },
        retcache: {
            validators: {
                regexp: {
                    regexp:/^[1-9]\d{0,6}$/,
                    message: "0-9"
                }
            }
        },';
    
        $arg1 = '
        var formulid=["archiveadm"];
        inpandfieldlen("arch_titre",400);';
    
        $this->title(__d('authors', 'Administration login'));

        $this->set('arch_titre',    Config::get('archivestories.config.arch_titre')); 
        $this->set('arch',          Config::get('archivestories.config.arch')); 
        $this->set('retcache',      Config::get('archivestories.config.retcache')); 
        $this->set('maxcount',      Config::get('archivestories.config.maxcount')); 
        $this->set('adminfoot',     Css::adminfoot('fv', $fv_parametres, $arg1, ''));
    }
    
}
