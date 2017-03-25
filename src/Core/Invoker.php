<?php
    
    namespace WPKit\Core;
    
    class Invoker extends Singleton {
	    
	    /**
	     * @var \WPKit\Application
	     */
	    protected $app;

		public function __construct(Application $app) {
	    	
	    	$this->app = $app;
	    	
	    }
	    
	    public function invokeByCondition( $callback, $action = 'wp', $condition = true, $priority = 10 ) {
			
			add_action( $action, function() use($callback, $action, $condition, $priority ) {
			
				if( ( is_callable($condition) && $this->app->call($condition) ) || ( ! is_callable($condition) && $condition ) ) {
					
					$this->invoke( $callback, $action, $priority );
				
				}
				
			}, $priority-1 );
			
			$meta = compact( 'action', 'priority' );
			
			$route = $this->app->make( 'route', compact('callback', 'meta') );
			
			return $route;
			
		}
		
		public function invoke( $callback, $action = 'wp', $priority = 10 ) {
			
			$meta = compact( 'action', 'priority' );
			
			$route = $this->app->make( 'route', compact('callback', 'meta') );
						
			$this->routes[] = $route;
			
			add_action( $action, array( $route, 'run' ), $priority );
			
			return $route;
			
		}
		
		public function isInvoked( $callback, $action = 'wp', $priority = 10 ) {
			
			extract(array_merge(array(
				'callback' => '',
				'action' => 'wp',
				'priority' => 20
			), compact(
				'callback',
				'action',
				'priority'
			)));
			
			$meta = compact( 'action', 'priority' );
			
			$route = $this->app->make( 'route', compact('callback', 'meta') );
			
			$index = array_search($route, $this->routes);
			
			return $index > -1 ? (object) $this->routes[$index] : false;
			
		}
		
		public function uninvoke( $callback, $action = 'wp', $priority = 10 ) {
			
			extract(array_merge(array(
				'callback' => '',
				'action' => 'wp',
				'priority' => 20
			), compact(
				'callback',
				'action',
				'priority'
			)));
			
			$meta = compact( 'action', 'priority' );
			
			$route = $this->app->make( 'route', compact('callback', 'meta') );
			
			$index = array_search($route, $this->routes);
			
			if( $index > -1 ) {
				
				unset( $this->routes[ $index ] );
				
			}
			
			remove_action( $action, $method, $priority );
			
			return $this;
			
		}
	    
	}
