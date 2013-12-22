<?php

namespace Ipalaus\Buffer;

use Guzzle\Http\Message\Request;

class TokenAuthorization implements AuthorizationInterface
{

    /**
     * Authorization token.
     *
     * @var string
     */
    protected $token;

    /**
     * Create a new token authoritzation instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Add the authoritzation credentials to a request.
     *
     * @param  \Guzzle\Http\Message\Request  $request
     * @return \Guzzle\Http\Message\Request
     */
    public function addCredentialsToRequest(Request $request)
    {
        return $request->addHeader('Authorization', 'Bearer '.$this->token);
    }

}
