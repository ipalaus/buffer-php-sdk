<?php

namespace Ipalaus\Buffer;

use Guzzle\Http\Message\Request;

interface AuthorizationInterface
{

    /**
     * Add the authoritzation credentials to a request.
     *
     * @param  \Guzzle\Http\Message\Request  $request
     * @return \Guzzle\Http\Message\Request
     */
    public function addCredentialsToRequest(Request $request);

}
