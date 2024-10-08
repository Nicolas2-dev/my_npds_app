<?php

/**
 * 
 */
return [

    /**
     * The Mysql-table prefix for all THIS instance of the module
     */
    'links_DB' => '',

    /**
     * Allow to register http links (yes=>true, no=>false, both=>false-true => -1)
     */
    'links_url' => false - true,
 
    /**
     * Allow to register links with the Topic's information (true) or not (false)
     */
    'links_topic' => true,
 
    /**
     * Allow to limit the number of subcat showed in main page
     * ("limit 0,0" => no subcat / "limit 0,3" => 3 subcat ... / "" => no limit )
     * 
     * You can also do a more complexe query :
     * for exemple : $subcat_limit="ASC limit 0,5" because the $subcat_limit complete the sql query !
     */
    'subcat_limit' => '',

    /**
     * From Npds config : Is Anonymous autorise to post new links? (0=Yes 1=No)
     * 
     * You can affect other value : App authorisations :
     *      -127 : admin only
     *      -1 : anonymous only
     *      0 : All (like Npds config)
     *      1 : Member only (Npds config)
     *      2 to 126 : group' members ("gp1,gp2,gp3" is also possible)
     * 
     * exepmle : links_anonaddlinklock=-127;
     */
    'links_anonaddlinklock' => '',

];
