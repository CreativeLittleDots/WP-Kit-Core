<?php
    
    namespace WPKit\Core;
    
    class Invoker extends Flow {
	    
	    public static $routes = [];
		
		public static function invokeByCondition( $callback, $action = 'wp', $condition, $priority = 20 ) {
			
			add_action( 'init', function() use($callback, $action, $condition, $priority ) {
			
				if( ( is_callable($condition) && call_user_func($condition) ) || ( ! is_callable($condition) && $condition ) ) {
					
					self::invokeByAction( $callback, $action, $priority );
				
				}
				
			});
			
		}
		
		public static function invokeByAction( $callback, $action = 'wp', $priority = 20 ) {
			
			$callback = self::getCallback($callback);
			
			self::$routes[] = [
				$callback,
				$action,
				$priority
			];
			
			add_action( $action, $callback, $priority );
			
		}
		
		public static function invoked( $callback, $action = 'wp', $priority = 20 ) {
			
			$callback = self::getCallback($callback);
			
			return array_search(array_merge(array(
				'callback' => '',
				'action' => 'wp',
				'priority' => 20
			), compact(
				'callback',
				'action',
				'priority'
			)), self::$routes) > -1 ? true : false;
			
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
