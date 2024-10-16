<?php

namespace Modules\Twitter\Library\Oauth;


/**
 * Undocumented class
 */
class OAuthDataStore
{

    /**
     * Undocumented function
     *
     * @param [type] $consumer_key
     * @return void
     */
    public function lookup_consumer($consumer_key)
    {
        // implement me
    }

    /**
     * Undocumented function
     *
     * @param [type] $consumer
     * @param [type] $token_type
     * @param [type] $token
     * @return void
     */
    public function lookup_token($consumer, $token_type, $token)
    {
        // implement me
    }

    /**
     * Undocumented function
     *
     * @param [type] $consumer
     * @param [type] $token
     * @param [type] $nonce
     * @param [type] $timestamp
     * @return void
     */
    public function lookup_nonce($consumer, $token, $nonce, $timestamp)
    {
        // implement me
    }

    /**
     * Undocumented function
     *
     * @param [type] $consumer
     * @param [type] $callback
     * @return void
     */
    public function new_request_token($consumer, $callback = null)
    {
        // return a new token attached to this consumer
    }

    /**
     * Undocumented function
     *
     * @param [type] $token
     * @param [type] $consumer
     * @param [type] $verifier
     * @return void
     */
    public function new_access_token($token, $consumer, $verifier = null)
    {
        // return a new access token attached to this consumer
        // for the user associated with this token if the request token
        // is authorized
        // should also invalidate the request token
    }

}
