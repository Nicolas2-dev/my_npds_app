<?php

namespace Modules\Twitter\Library\Oauth;


/**
 * Undocumented class
 */
class OAuthConsumer
{

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    public $key;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    public $secret;

    /**
     * 
     */
    public $callback_url;


    /**
     * Undocumented function
     *
     * @param [type] $key
     * @param [type] $secret
     * @param [type] $callback_url
     */
    public function __construct($key, $secret, $callback_url = NULL)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->callback_url = $callback_url;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function __toString()
    {
        return "OAuthConsumer[key=$this->key,secret=$this->secret]";
    }

}
