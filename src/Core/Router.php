<?php
    
    namespace WPKit\Core;
    
    use Routes;
    
    class Router extends Flow {
	    
	    public static $routes = [];
		
		public static function map( $route, $callback ) {
			
			$callback = self::getCallback($callback);
			
			Routes::map($route, $callback);
			
			self::$routes[] = [
				$route,
				$callback
			];
			
		}
		
		public static function isMapped( $route, $callback ) {
			
			$callback = self::getCallback($callback);
			
			return array_search(array_merge(array(
				'route' => '',
				'callback' => '',
			), compact(
				'route',
				'callback'
			)), self::$routes) > -1 ? true : false;
			
		}
	    
	}
