<?php

namespace OpenSky\Bundle\GigyaBundle\Socializer\Buzz;

use Buzz\Message\Response;

use Buzz\Message\Request;
use Symfony\Component\Routing\RouterInterface;

class MessageFactory
{
    private $router;
    private $key;
    private $host;
    private $redirect;
    private $secret;

    public function __construct(RouterInterface $router, $key, $secret, $host, $redirect)
    {
        $this->router   = $router;
        $this->key      = $key;
        $this->secret   = $secret;
        $this->host     = $host;
        $this->redirect = $redirect;
    }

    public function getLoginRequest($provider)
    {
        $request = new Request(Request::METHOD_POST, '/socialize.login', $this->host);

        $request->setHeaders(array(
            'Content-Type' => 'application/x-www-form-urlencoded'
        ));

        $request->setContent(http_build_query(array(
            'x_provider'    => $provider,
            'client_id'     => $this->key,
            'redirect_uri'  => $this->router->generate($this->redirect, array()),
            'response_type' => 'token'
        )));

        return $request;
    }

    public function getAccessTokenRequest()
    {
        $request = new Request(Request::METHOD_POST, '/socialize.getToken', $this->host);

        $request->setHeaders(array(
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic '.base64_encode($this->key.':'.$this->secret),
        ));

        $request->setContent(http_build_query(array(
            'grant_type'    => 'none',
        )));

        return $request;
    }

    public function getResponse()
    {
        return new Response();
    }
}
