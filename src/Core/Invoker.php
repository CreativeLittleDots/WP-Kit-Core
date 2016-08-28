<?php
    
    namespace WPKit\Core;
    
    class Invoker {
	    
	    public static $routes = [];
		
		public static function invoke_by_condition( $callback, $action = 'wp', $condition, $priority = 20 ) {
			
			add_action( 'init', function() use($callback, $action, $condition, $priority ) {
			
				if( ( is_callable($condition) && call_user_func($condition) ) || ( ! is_callable($condition) && $condition ) ) {
					
					self::invoke_by_action( $callback, $action, $priority );
				
				}
				
			});
			
		}
		
		public static function invoke_by_action( $callback, $action = 'wp', $priority = 20 ) {
			
			$callback = stripos($callback, '\\') === 0 ? $callback : "App\Controllers\\$callback";
			$callback = stripos($callback, '::') === 0 ? $callback : array($callback, 'init');
			
			self::$routes[] = [
				$callback,
				$action,
				$priority
			];
			
			add_action( $action, $callback, $priority );
			
		}
		
		public static function invoked( $callback, $action = 'wp', $priority = 20 ) {
			
			$callback = stripos($callback, '::') === 0 ? $callback : array($callback, 'init');
			
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
			
			$callback = stripos($callback, '::') === 0 ? $callback : array($callback, 'init');
			
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
