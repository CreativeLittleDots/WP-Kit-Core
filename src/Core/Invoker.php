<?php
    
    namespace WPKit\Core;
    
    class Invoker extends Singleton {
	    
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
			
					add_action( $action, array( $route, 'run' ), $priority );
				
				}
				
			}, $priority-1 );
			
			return $route;
			
		}
		
		public function invoke( $callback, $action = 'wp', $priority = 10 ) {
			
			$route = $this->getRoute( $callback );
			
			add_action( $action, array( $route, 'run' ), $priority );
			
			return $controllerCallback[0];
			
		}
		
		public function uninvoke( $callback, $action = 'wp', $priority = 10 ) {
			
			$route = $this->getRoute( $callback );
			
			remove_action( $action, array( $route, 'run' ), $priority );
			
			return $this;
			
		}
		
		public function getRoute( $callback ) {
			
			if( empty( $this->routes[$callback] ) ) {
				
				$this->routes[$callback] = new Route( [
			        'GET',
			        'POST',
			        'PUT',
			        'PATCH',
			        'DELETE'
			    ], '/', $callback );
				
			}
			
			return $this->routes[$callback];
			
		}
	    
	}
