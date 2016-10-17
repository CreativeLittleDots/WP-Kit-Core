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
		
		public static function map( $route, $callback, $method = 'get' ) {
			
			if( ! class_exists( 'Routes' ) ) {
				
				return;
				
			}
			
			$methods = is_array( $method ) ? $method : array_map( 'strtolower', array( $method ) );
			
			if( in_array( strtolower( $_SERVER['REQUEST_METHOD'] ), $methods ) ) {
			
				$controller = self::getController($callback);
	
				Routes::map($route, function( $params ) use($controller, $callback) {
					
					if( $controller ) {
						
						call_user_func_array(array($controller, 'beforeFilter'), $params);
						
					}
					
					call_user_func_array(self::getCallback($callback), $params);
					
				});
				
				self::$routes[] = new Route([
					$route,
					$callback,
					$method
				]);
				
			}
			
		}
		
		public static function getRoutes() {
			
			return self::$routes;
			
		}
		
		public function defaultToRest() {
			
			$restController = new RestController();
			
			Routes::map( BASE_PATH . '/:controller/:action/:id', array( $restController, 'action' ) );
			Routes::map( BASE_PATH . '/:controller/:action', array( $restController, 'action' ) );
			Routes::map( BASE_PATH . '/:controller', array( $restController, 'action' ) );
			
		}
		
		public function isMapped( $route, $callback, $method = 'get' ) {
			
			$callback = self::getCallback($callback);
			
			return array_search(new Route(array_merge(array(
				'route' => '',
				'callback' => '',
				'method' => 'get'
			), compact(
				'route',
				'callback',
				'method'
			))), self::$routes) > -1 ? true : false;
			
		}
	    
	}
