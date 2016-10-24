<?php
    
    namespace WPKit\Core;
    
    use Routes;
    
    class Router extends Flow {
		
		public function map( $route, $callback, $method = 'get' ) {
			
			if( ! class_exists( 'Routes' ) ) {
				
				return;
				
			}
			
			$methods = $method == '*' ? [
		        'GET',
		        'POST',
		        'PUT',
		        'PATCH',
		        'DELETE'
		    ] : $method;
			
			$methods = is_array( $methods ) ? $methods : array_map( 'strtoupper', array( $methods ) );
			
			if( in_array( $_SERVER['REQUEST_METHOD'], $methods ) ) {
			
				$controller = $this->getController($callback);
	
				Routes::map($route, function( $params ) use($controller, $callback) {
					
					if( $controller ) {
						
						call_user_func_array(array($controller, 'beforeFilter'), $params);
						
					}
					
					call_user_func_array($this->getCallback($callback), $params);
					
				});
				
				$this->routes[] = new Route([
					$route,
					$callback,
					$method
				]);
				
			}
			
		}
		
		public function getRoutes() {
			
			return $this->routes;
			
		}
		
		public function isMapped( $route, $callback, $method = 'get' ) {
			
			$callback = $this->getCallback($callback);
			
			return array_search(new Route(array_merge(array(
				'route' => '',
				'callback' => '',
				'method' => 'get'
			), compact(
				'route',
				'callback',
				'method'
			))), $this->routes) > -1 ? true : false;
			
		}
		
		/**
	     * Magic method calling.
	     *
	     * @param       $method
	     * @param array $parameters
	     * @return mixed
	     */
	    public function __call($method, $params) {
		       
	        if (method_exists($this, $method))
	        {
	            return call_user_func_array([$this, $method], $parameters);
	        }
	
	        if (in_array(strtoupper($method), static::$methods))
	        {
	            return call_user_func([$this, 'map'], $uri, $callback, $method);
	        }
	
	        throw new InvalidArgumentException("Method {$method} not defined");
	        
	    }
	    
	}