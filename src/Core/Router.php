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
			
			if( in_array( $this->http->method(), $methods ) ) {
			
				$controller = $this->getController($callback);
	
				Routes::map($route, function( $params ) use($controller, $callback) {
					
					if( $controller ) {
						
						$this->app->call(array($controller, 'beforeFilter'), compact('params'));
						
					}
					
					$this->app->call($this->getCallback($callback), $params);
					
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
	            return $this->app->call([$this, $method], compact('parameters'));
	        }
	
	        if (in_array(strtoupper($method), static::$methods))
	        {
	            return $this->app->call([$this, 'map'], $uri, compact('callback', 'method'));
	        }
	
	        throw new InvalidArgumentException("Method {$method} not defined");
	        
	    }
	    
	}