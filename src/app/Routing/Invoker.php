<?php
    
    namespace WPKit\Routing;
    
    use Illuminate\Container\Container as Application;
    
    class Invoker {
	    
	    /**
	     * @var \WPKit\Application
	     */
	    protected $app;
	    
	     /**
	     * @var array
	     */
	    protected $routes = array();

		public function __construct(Application $app) {
	    	
	    	$this->app = $app;
	    	
	    }
	    
	    public function invokeByCondition( $callback, $action = 'wp', $condition = true, $priority = 10 ) {
		    
		    $route = $this->getRoute( $callback );
			
			add_action( $action, function() use( $action, $route, $condition, $priority ) {
			
				if( ( is_callable( $condition ) && $this->app->call( $condition ) ) || ( ! is_callable( $condition ) && $condition ) ) {
			
					add_action( $action, function() use ( $route ) {
					
						$this->app->call( array( $route, 'run' ) );
						
					}, $priority );
				
				}
				
			}, $priority-1 );
			
			return $route;
			
		}
		
		public function invoke( $callback, $action = 'wp', $priority = 10 ) {
			
			$this->routes[$callback] = $route = $this->getRoute( $callback );
			
			add_action( $action, function() use ( $route ) {
									
				$this->app->call( array( $route, 'run' ) );
				
			}, $priority );
			
			return $route;
			
		}
		
		public function getRoute( $callback ) {
			
			if( empty( $this->routes[$callback] ) ) {
				
				$this->routes[$callback] = $this->newRoute( [
			        'GET',
			        'POST',
			        'PUT',
			        'PATCH',
			        'DELETE'
			    ], '/', $callback );
				
			}
			
			return $this->routes[$callback];
			
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
	                    ->setRouter($this->app['router'])
	                    ->setContainer($this->app)
	                    ->reparseAction();
	    }
	    
	}
