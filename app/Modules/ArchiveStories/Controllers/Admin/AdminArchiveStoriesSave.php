<?php

namespace App\Modules\ArchiveStories\Controllers\Admin;

use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Session\Session;
use App\Modules\Npds\Support\ConfigSave;
use App\Modules\Npds\Core\AdminController;

class AdminArchiveStoriesSave extends AdminController
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
     * [SaveSetArchive_stories description]
     *
     * @param   [type]  $maxcount    [$maxcount description]
     * @param   [type]  $arch        [$arch description]
     * @param   [type]  $arch_titre  [$arch_titre description]
     * @param   [type]  $retcache    [$retcache description]
     * @param   [type]  $ModPath     [$ModPath description]
     * @param   [type]  $ModStart    [$ModStart description]
     *
     * @return  [type]               [return description]
     */
    public function SaveSetArchive_stories()
    {
        if (Request::post('op') == 'archive_stories_save') {

            // save config
            $line_config  = ConfigSave::block_white('Nombre de Stories par page', 'maxcount', Request::post('maxcount'));
            $line_config .= ConfigSave::block_white('Les news en ligne (arch=0;) ou les archives (arch=1;) ? ', 'arch', Request::post('arch'));
            $line_config .= ConfigSave::block_white('Titre de la liste des news (par exemple : "<h2>Les Archives</h2>") si arch_titre est vide rien ne sera affiché', 'arch_titre', Request::post('arch_titre'));
            $line_config .= ConfigSave::block_white('Temps de rétention en secondes', 'retcache', Request::post('retcache'));

            ConfigSave::save_block_white(module_path('ArchiveStories/Config/config'), $line_config);

            // save cache
            $line_cache = ConfigSave::block_white_cache('Temps de rétention cache en secondes', 'archivestsories', 'admin/archive', Request::post('retcache'));
            ConfigSave::save_block_white(module_path('ArchiveStories/Config/cache'), $line_cache);

            Session::set('message', ['type' => 'success', 'text' => 'goood test !!!']);

            Url::redirect('admin/archive#message');
        }
    }

}
