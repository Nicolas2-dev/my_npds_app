<?php
/**
 * Npds - Modules/Sections/contracts/SectionInterface.php
 *
 * @author  Nicolas Devoy
 * @email   nicolas@nicodev.fr 
 * @version 1.0.0
 * @date    26 Septembre 2024
 */
namespace Modules\Sections\Contracts;

/**
 * Undocumented interface
 */
interface SectionInterface {

    /**
     * Undocumented function
     *
     * @param [type] $groupe
     * @return void
     */
    public function groupe($groupe);

    /**
     * Undocumented function
     *
     * @param [type] $member
     * @return void
     */
    public function droits($member);

    /**
     * Undocumented function
     *
     * @param [type] $secid
     * @return void
     */
    public function sousrub_select($secid);

    /**
     * Undocumented function
     *
     * @param [type] $secid
     * @return void
     */
    public function droits_publication($secid);

    /**
     * Undocumented function
     *
     * @param [type] $chng_aid
     * @param [type] $secid
     * @return void
     */
    public function droitsalacreation($chng_aid, $secid);

    /**
     * Undocumented function
     *
     * @param [type] $artid
     * @return void
     */
    public function verif_aff($artid);

    /**
     * Undocumented function
     *
     * @param [type] $userlevel
     * @return void
     */
    public function autorisation_section($userlevel);

}
