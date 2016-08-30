<?php
    
    namespace WPKit\Core;
    
    class Invoker extends Flow {
	    
	    protected static $instance = null;
	    
	   	public static $routes = [];
	   	
	   	public static function instance() {
		   	
		   	$class = get_called_class();
	        
	        if( ! $class::$instance ) {
				
				$class::$instance = new $class();
		        
	        }
	        
	        return $class::$instance;
	        
        }
	    
	    public static function invokeByCondition( $callback, $action = 'wp', $condition = true, $priority = 20 ) {
			
			add_action( $action, function() use($callback, $action, $condition, $priority ) {
			
				if( ( is_callable($condition) && call_user_func($condition) ) || ( ! is_callable($condition) && $condition ) ) {
					
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
	    
	}
