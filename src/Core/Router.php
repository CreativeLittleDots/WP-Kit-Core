<?php
    
    namespace WPKit\Core;
    
    use Exception;
    use Routes;
    
    class Router extends Singleton {
	    
	    /**
	     * @var \WPKit\Application
	     */
	    protected $app;
	    
	    /**
	     * @var \WPKit\Core\Http
	     */
	    protected $http;

		public function __construct(Application $app, Http $http) {
	    	
	    	$this->app = $app;
	    	$this->http = $http;
	    	
	    }
		
		public function map( $path, $callback, $method = 'get' ) {
			
			if( ! class_exists( 'Routes' ) ) {
				
				throw new Exception( 'Upstatement Routes is not installed' );
				
			}
			
			$methods = $method == '*' ? [
		        'GET',
		        'POST',
		        'PUT',
		        'PATCH',
		        'DELETE'
		    ] : $method;
			
			$methods = array_map( 'strtoupper', is_array( $methods ) ? $methods : array( $methods ) );
			
			$meta = compact( 'path', 'method' );
			
			$route = $this->app->make( 'route', compact('callback', 'meta') );
			
			if( in_array( $this->http->method(), $methods ) ) {
	
				Routes::map( $path, array($route, 'run') );
				
				$this->routes[] = $route;
				
			}
			
			return $route;
			
		}
		
		public function getRoutes() {
			
			return $this->routes;
			
		}
		
		public function isMapped( $path, $callback, $method = 'get' ) {
			
			extract(array_merge(array(
				'path' => '',
				'callback' => '',
				'method' => 'get'
			), compact(
				'path',
				'callback',
				'method'
			)));
			
			$meta = compact( 'path', 'method' );
			
			$route = $this->app->make( 'route', compact('callback', 'meta') );
			
			return array_search($route, $this->routes) > -1 ? true : false;
			
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