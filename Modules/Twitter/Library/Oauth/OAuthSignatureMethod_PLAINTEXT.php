<?php

namespace Modules\Twitter\Library\Oauth;


/**
 * The PLAINTEXT method does not provide any security protection and SHOULD only be used 
 * over a secure channel such as HTTPS. It does not use the Signature Base String.
 *   - Chapter 9.4 ("PLAINTEXT")
 */
class OAuthSignatureMethod_PLAINTEXT extends OAuthSignatureMethod
{

    /**
     * Undocumented function
     *
     * @return string
     */
    public function get_name()
    {
        return "PLAINTEXT";
    }

    /**
     * oauth_signature is set to the concatenated encoded values of the Consumer Secret and 
     * Token Secret, separated by a '&' character (ASCII code 38), even if either secret is 
     * empty. The result MUST be encoded again.
     *   - Chapter 9.4.1 ("Generating Signatures")
     *
     * Please note that the second encoding MUST NOT happen in the SignatureMethod, as
     * OAuthRequest handles this!
     * 
     * @return string
     */
    public function build_signature($request, $consumer, $token)
    {
        $key_parts = array(
            $consumer->secret,
            ($token) ? $token->secret : ""
        );

        $key_parts = OAuthUtil::urlencode_rfc3986($key_parts);
        $key = implode('&', $key_parts);
        $request->base_string = $key;

        return $key;
    }

}
