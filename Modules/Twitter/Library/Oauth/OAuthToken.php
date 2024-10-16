<?php

namespace Modules\Twitter\Library\Oauth;

/**
 * Undocumented class
 */
class OAuthToken
{

    /**
     * access tokens and request tokens
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
     * key = the token
     * secret = the token secret
     *
     * @param [type] $key
     * @param [type] $secret
     */
    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * generates the basic string serialization of a token that a server
     * would respond to request_token and access_token calls with
     *
     * @return string
     */
    public function to_string()
    {
        return "oauth_token=" .
            OAuthUtil::urlencode_rfc3986($this->key) .
            "&oauth_token_secret=" .
            OAuthUtil::urlencode_rfc3986($this->secret);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function __toString()
    {
        return $this->to_string();
    }

}
