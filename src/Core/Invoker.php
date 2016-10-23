<?php
    
    namespace WPKit\Core;
    
    class Invoker extends Singleton {
	    
	    /**
	     * @var \WPKit\Application
	     */
	    protected $app;
	    
		protected static $instance = null;
		
		public static $routes = [];
		
		/**
	     * Adds the action hooks for WordPress.
	     *
	     * @param \WPKit\Core\Application $app
	     */
	    public function __construct(Application $app)
	    {
	        $this->app = $app;
	    }
	   	
	   	public static function instance() {
		   	
		   	$class = get_called_class();
	        
	        if( ! $class::$instance ) {
				
				$class::$instance = new $class();
		        
	        }
	        
	        return $class::$instance;
	        
        }
	    
	    public static function invokeByCondition( $callback, $action = 'wp', $condition = true, $priority = 20 ) {
			
			add_action( $action, function() use($callback, $action, $condition, $priority ) {
			
				if( ( is_callable($condition) && $this->app->call($condition) ) || ( ! is_callable($condition) && $condition ) ) {
					
					self::invoke( $callback, $action, $priority );
				
				}
				
			});
			
		}
		
		public static function invoke( $callback, $action = 'wp', $priority = 20 ) {
			
			$callback = self::getCallback($callback);
			
			self::$routes[] = [
				$callback,
				$action,
				$priority
			];
			
			add_action( $action, $callback, $priority );
			
		}
		
		public static function isInvoked( $callback, $action = 'wp', $priority = 20 ) {
			
			$callback = self::getCallback($callback);
			
			$index = array_search(array_merge(array(
				'callback' => '',
				'action' => 'wp',
				'priority' => 20
			), compact(
				'callback',
				'action',
				'priority'
			)), self::$routes);
			
			return $index > -1 ? (object) self::$routes[$index] : false;
			
		}
		
		public static function uninvoke( $callback, $action = 'wp', $priority = 20 ) {
			
			$callback = self::getCallback($callback);
			
			$index = array_search(array_merge(array(
				'callback' => '',
				'action' => 'wp',
				'priority' => 20
			), compact(
				'callback',
				'action',
				'priority'
			)), self::$routes);
			
			if( $index > -1 ) {
				
				unset( self::$routes[$index] );
				
			}
			
			remove_action( $action, $method, $priority );
			
		}
		
		protected static function getController($callback) {
		    
		    $controller = false;
			
			if( is_string($callback) ) {
			
				$callback = stripos($callback, '\\') === 0 ? $callback : "App\Controllers\\$callback";
				$controller = stripos($callback, '::') === false ? $callback : explode('::', $callback);
				$controller = is_array($controller) ? reset($controller) : $controller;
				$controller = $controller::instance();
			
			}
			
			return $controller;
			
		}
		
		protected static function getMethod($callback) {
			
			$method = false;
			
			if( is_string($callback) ) {
			
				$method = stripos($callback, '::') === false ? 'beforeFilter' : explode('::', $callback);
				$method = is_array($method) ? end($method) : $method;
			
			}
			
			return $method;
			
		}
		
		protected static function getCallback($callback) {
			
			$controller = self::getController($callback);
			$method = self::getMethod($callback);
			
			if( $controller && $method ) {
				
				return array($controller, $method);
				
			}
			
			return $callback;
			
		}
	    
	}
