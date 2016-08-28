<?php
    
    namespace WPKit\Core;
    
    use Routes;
    
    class Router {
	    
	    public static $routes = [];
		
		public static function map( $route, $callback ) {
			
			Routes::map($route, $callback);
			
			self::$routes[] = [
				$route,
				$callback
			];
			
		}
		
		public static function mapped( $route, $callback ) {
			
			return array_search(array_merge(array(
				'route' => '',
				'callback' => '',
			), compact(
				'route',
				'callback'
			)), self::$routes) > -1 ? true : false;
			
		}
	    
	}
