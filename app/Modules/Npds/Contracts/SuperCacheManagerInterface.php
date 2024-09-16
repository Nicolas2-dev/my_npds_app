<?php

namespace App\Modules\Npds\Contracts;



interface SuperCacheManagerInterface {

    /**
     * [get_Genereting_Output description]
     *
     * @return  [type]  [return description]
     */
    public function get_Genereting_Output();

    /**
     * [set_Genereting_Output description]
     *
     * @param   [type]  $output  [$output description]
     *
     * @return  [type]           [return description]
     */
    public function set_Genereting_Output($output);    

    /**
     * [startCachingPage description]
     *
     * @return  [type]  [return description]
     */
    public function startCachingPage();

    /**
     * [endCachingPage description]
     *
     * @return  [type]  [return description]
     */
    public function endCachingPage();
    
    /**
     * [checkCache description]
     *
     * @param   [type]  $request  [$request description]
     * @param   [type]  $refresh  [$refresh description]
     *
     * @return  [type]            [return description]
     */
    public function checkCache($request, $refresh);
    
    /**
     * [insertIntoCache description]
     *
     * @param   [type]  $content  [$content description]
     * @param   [type]  $request  [$request description]
     *
     * @return  [type]            [return description]
     */
    public function insertIntoCache($content, $request);   

    /**
     * [logVisit description]
     *
     * @param   [type]  $request  [$request description]
     * @param   [type]  $type     [$type description]
     *
     * @return  [type]            [return description]
     */
    public function logVisit($request, $type);

    /**
     * [cacheCleanup description]
     *
     * @return  [type]  [return description]
     */
    public function cacheCleanup();

    /**
     * [UsercacheCleanup description]
     *
     * @return  [type]  [return description]
     */
    public function UsercacheCleanup();

    /**
     * [startCachingBlock description]
     *
     * @param   [type]  $Xblock  [$Xblock description]
     *
     * @return  [type]           [return description]
     */
    public function startCachingBlock($Xblock);

    /**
     * [endCachingBlock description]
     *
     * @param   [type]  $Xblock  [$Xblock description]
     *
     * @return  [type]           [return description]
     */
    public function endCachingBlock($Xblock);
    
    /**
     * [CachingQuery description]
     *
     * @param   [type]  $Xquery     [$Xquery description]
     * @param   [type]  $retention  [$retention description]
     *
     * @return  [type]              [return description]
     */
    public function CachingQuery($Xquery, $retention);
    
    /**
     * [startCachingObjet description]
     *
     * @param   [type]  $Xobjet  [$Xobjet description]
     *
     * @return  [type]           [return description]
     */
    public function startCachingObjet($Xobjet);
    
    /**
     * [endCachingObjet description]
     *
     * @param   [type]  $Xobjet  [$Xobjet description]
     * @param   [type]  $Xtab    [$Xtab description]
     *
     * @return  [type]           [return description]
     */
    public function endCachingObjet($Xobjet, $Xtab);
        
}
