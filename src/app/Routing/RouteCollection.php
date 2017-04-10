<?php

	namespace WPKit\Routing;
	
	use Illuminate\Http\Request;
	use Illuminate\Routing\RouteCollection as BaseRouteCollection;
	
	class RouteCollection extends BaseRouteCollection {
	    
	    /**
	     * Find the first route matching a given request.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @return \Illuminate\Routing\Route
	     *
	     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	     */
	    public function match(Request $request)
	    {
	        $routes = $this->get($request->getMethod());
	
	        // First, we will see if we can find a matching route for this current request
	        // method. If we can, great, we can just return it so that it can be called
	        // by the consumer. Otherwise we will check for routes with another verb.
	        $route = $this->matchAgainstRoutes($routes, $request);
	
	        if (! is_null($route)) {
	            return $route->bind($request);
	        }
	
	        // If no route was found we will now check if a matching route is specified by
	        // another HTTP verb. If it is we will need to throw a MethodNotAllowed and
	        // inform the user agent of which HTTP verb it should use for this route.
	        $others = $this->checkForAlternateVerbs($request);
	
	        if (count($others) > 0) {
	            return $this->getRouteForMethods($request, $others);
	        }

	    }
	   
	}