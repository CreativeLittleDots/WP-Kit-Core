<?php
    
    namespace WPKit\Core;
    
    use Routes;
    
    class Router extends Flow {
	    
	    protected static $instance = null;
	    
	   	public static $routes = [];
	   	
	   	public static function instance() {
		   	
		   	$class = get_called_class();
	        
	        if( ! $class::$instance ) {

				$class::$instance = new $class();
		        
	        }
	        
	        return $class::$instance;
	        
        }
		
		public static function map( $route, $callback ) {
			
			$callback = self::getCallback($callback);
			
			Routes::map($route, $callback);
			
			self::$routes[] = [
				$route,
				$callback
			];
			
		}
		
		public function defaultToRest() {
			
			$restController = new RestController();
			
			Routes::map('/:controller/:action/:id', array($restController, 'action'));
			Routes::map('/:controller/:action', array($restController, 'action'));
			
		}
		
		public function isMapped( $route, $callback ) {
			
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
