<?php
    
    namespace WPKit\Routing;
    
    use Illuminate\Container\Container as Application;
    use Illuminate\Routing\Route;
    use Illuminate\Support\Str;
    
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
            
            // If the route is routing to a controller we will parse the route action into
	        // an acceptable array format before registering it and creating this route
	        // instance itself. We need to build the Closure that will call this out.
	        if ($this->actionReferencesController($action)) {
	            $action = $this->convertToControllerAction($action);
        	}
        	
        	$route = new Route($methods, $uri, $action);
        	
        	$route->parameters['wpkit'] = true;
		    
	        return $route->setRouter( $this->app['router'] )->setContainer( $this->app );
	                    
	    }
	    
	    /**
	     * Determine if the action is routing to a controller.
	     *
	     * @param  array  $action
	     * @return bool
	     */
	    protected function actionReferencesController($action)
	    {
	        if (! $action instanceof Closure) {
	            return is_string($action) || (isset($action['uses']) && is_string($action['uses']));
	        }
	
	        return false;
	    }
	    
	    /**
	     * Add a controller based route action to the action array.
	     *
	     * @param  array|string  $action
	     * @return array
	     */
	    protected function convertToControllerAction($action) {
		    
	        if ( is_string($action) ) {
		        
		        if( ! Str::contains($action, '@' ) ) {
				    
	            	$action .= '@dispatch';
	            	
	            }
		        
	            $action = ['uses' => $action];
	            
	        }

	        $action['uses'] = $this->app->prependNamespace($action['uses']);
	
	        // Here we will set this controller name on the action array just so we always
	        // have a copy of it for reference if we need it. This can be used while we
	        // search for a controller name or do some other type of fetch operation.
	        $action['controller'] = $action['uses'];
	
	        return $action;
	        
	    }
	    
	}
