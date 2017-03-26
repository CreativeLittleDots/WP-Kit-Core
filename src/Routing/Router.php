<?php
    
    namespace WPKit\Core;
    
    use Illuminate\Http\Request;
    use Illuminate\Contracts\Events\Dispatcher;
    use Illuminate\Container\Container;
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
		public function map( $path, $callback, $method = 'get' ) {
			
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