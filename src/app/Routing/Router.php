<?php
    
    namespace WPKit\Routing;
    
    use Illuminate\Http\Request;
    use Illuminate\Contracts\Events\Dispatcher;
    use Illuminate\Container\Container;
    use Illuminate\Routing\Events\RouteMatched;
    use Illuminate\Routing\Router as BaseRouter;
    
    class Router extends BaseRouter {
		
		 /**
	     * Create a new Router instance.
	     *
	     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	     * @param  \Illuminate\Container\Container  $container
	     * @return void
	     */
	    public function __construct(Dispatcher $events, Container $container = null)
	    {
	        $this->events = $events;
	        $this->routes = new RouteCollection;
	        $this->container = $container ?: new Container;
	
	        $this->bind('_missing', function ($v) {
	            return explode('/', $v);
	        });
	    }
    
		 /**
	     * Register a new route with the given verbs.
	     *
	     * @param  string  $path
	     * @param  \Closure|array|string|null  $callback
	     * @param  array|string  $methods
	     * @return \Illuminate\Routing\Route
	     */
		public function map( $path, $callback, $method = 'get' ) 
		{
			
			$methods = $method == '*' ? [
		        'GET',
		        'POST',
		        'PUT',
		        'PATCH',
		        'DELETE'
		    ] : $method;
			
			$methods = array_map( 'strtoupper', is_array( $methods ) ? $methods : array( $methods ) );
			
			return $this->addRoute( $methods, $path, $callback );
			
		}
		
		/**
	     * Dispatch the request to the application.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @return \Illuminate\Http\Response
	     */
	    public function dispatch(Request $request)
	    {
	        $this->currentRequest = $request;
	        if($response = $this->dispatchToRoute($request)) {
	        	return $this->prepareResponse($request, $response);
	        }
	    }
	    
	    /**
	     * Run the given route within a Stack "onion" instance.
	     *
	     * @param  \Illuminate\Routing\Route  $route
	     * @param  \Illuminate\Http\Request  $request
	     * @return mixed
	     */
	    protected function runRouteWithinStack(\Illuminate\Routing\Route $route, Request $request)
	    {
	        $shouldSkipMiddleware = $this->container->bound('middleware.disable') &&
                                $this->container->make('middleware.disable') === true;

	        $middleware = $shouldSkipMiddleware ? [] : $this->gatherRouteMiddlewares($route);
	
	        return (new Pipeline($this->container))
	                        ->send($request)
	                        ->through($middleware)
	                        ->then(function ($request) use ($route) {
	                            return $this->prepareResponse(
	                                $request,
	                                $route->run($request)
	                            );
	                        });
	    }
		
		/**
	     * Dispatch the request to a route and return the response.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @return mixed
	     */
	    public function dispatchToRoute(Request $request)
	    {
	        // First we will find a route that matches this request. We will also set the
	        // route resolver on the request so middlewares assigned to the route will
	        // receive access to this route instance for checking of the parameters.
	        if($route = $this->findRoute($request)) {
		        $request->setRouteResolver(function () use ($route) {
		            return $route;
		        });
		        $this->events->fire(new RouteMatched($route, $request));
		        $response = $this->runRouteWithinStack($route, $request);
		        return $this->prepareResponse($request, $response);
			}
	    }
		
		/**
	     * Find the route matching a given request.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @return \Illuminate\Routing\Route
	     */
	    protected function findRoute($request)
	    {
	        if($this->current = $route = $this->routes->match($request)) {
	        	$this->container->instance('WPKit\Routing\Route', $route);
				return $this->substituteBindings($route);
			}
	    }
		
		/**
	     * Add a route to the underlying route collection.
	     *
	     * @param  array|string  $methods
	     * @param  string  $uri
	     * @param  \Closure|array|string  $action
	     * @return \Illuminate\Routing\Route
	     */
	    public function forceAddRoute(Route $route)
	    {
	        return $this->routes->add($route);
	    }
	    
	    /**
	     * Create a new Route object.
	     *
	     * @param  array|string  $methods
	     * @param  string  $uri
	     * @param  mixed   $action
	     * @return \Illuminate\Routing\Route
	     */
	    protected function newRoute($methods, $uri, $action)
	    {
	        return (new Route($methods, $uri, $action))
	                    ->setRouter($this)
	                    ->setContainer($this->container)
	                    ->reparseAction();
	    }
	    
	}