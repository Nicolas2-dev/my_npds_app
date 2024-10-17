<?php

namespace Modules\Module\Controllers\Admin\Install;

use Modules\Npds\Core\AdminController;
use Modules\Module\Support\Traits\ModuleInstallCopyrightTrait;


class ModuleInstall extends AdminController
{

    use ModuleInstallCopyrightTrait;

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
    protected $hlpfile = '';

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
    protected $f_meta_nom = 'modules';


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
        $this->f_titre = __d('module', 'Gestion, Installation Modules');

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
     * e3
     *
     * @param [type] $sql
     * @param [type] $name_module
     * @return void
     */
    public function nmig_AlertSql($sql, $name_module)
    {
        global $ModInstall, $display;
    
        $reqsql = '';
    
        foreach ($sql as $v) {
            preg_match('#^(CREATE TABLE |CREATE TABLE IF NOT EXISTS) (\w+)#', $v, $tables);
        }
    
        for ($i = 0; $i < count($sql); $i++) {
            for ($j = 0; $j < count($tables); $j++) {
                $sql[$i] = preg_replace("#$tables[$j]#i" . $tables[$j], $sql[$i]);
            }
    
            $reqsql .= '<pre class="language-sql"><code class="language-sql">' . $sql[$i] . '</code></pre><br />';
        }
    
        $display = '
        <hr />
        <div class="lead">' . $name_module . '</div>
        <hr />
        <div class="">
            <p class="lead">' . __d('module', 'Le programme d\'installation va maintenant exécuter le script SQL pour configurer la base de données MySql.') . '</p>
            <p>' . __d('module', 'Si vous le souhaitez, vous pouvez exécuter ce script vous même, si vous souhaitez par exemple l\'exécuter sur une autre base que celle du site. Dans ce cas, pensez à reparamétrer le fichier de configuration du module.') . '</p>
            <p>' . __d('module', 'Voici le script SQL :') . '</p>
        </div>
        ' . $reqsql . '
        <br />
        <div class="text-center">
            <a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e4" class="btn btn-primary">' . __d('module', 'Configurer MySql') . '</a>&nbsp;<a href="admin.php?op=Module-Install&amp;ModInstall=' . $ModInstall . '&amp;nmig=e5" class="btn btn-danger">' . __d('module', 'Sauter cette étape') . '</a><br />
        </div>
        <br />
        ' . $this->nmig_copyright();
    }

}
