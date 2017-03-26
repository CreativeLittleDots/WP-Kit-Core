<?php
    
    namespace WPKit\Core;
    
    use Illuminate\Http\Request;
    use Illuminate\Routing\Router as BaseRouter;
    
    class Router extends BaseRouter {
		
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
	     * Dispatch the request to the application.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
	     */
	    public function dispatch(Request $request) {
		    
		    add_action( 'init', function() {
			    
			    $this->currentRequest = $request;
			    
				return $this->dispatchToRoute($request);
			    
		    });
	        
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