<?php

namespace WPKit\Routing;

use Illuminate\Routing\Router as BaseRouter;
use WPKit\Http\Response;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Router extends BaseRouter
{
    /**
     * Create a response instance from the given value.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  mixed  $response
     * @return \Illuminate\Http\Response
     */
    public function prepareResponse($request, $response)
    {
        if ($response instanceof PsrResponseInterface) {
            $response = (new HttpFoundationFactory)->createResponse($response);
        } elseif (! $response instanceof SymfonyResponse) {
            $response = new Response($response);
        }

        return $response->prepare($request);
    }
}
