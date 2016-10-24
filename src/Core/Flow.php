<?php
	
	namespace WPKit\Core;
	
	class Flow extends Singleton {
		
		/**
	     * @var \WPKit\Core\Application
	     */
	    protected $app;
	    
	    /**
	     * @var \WPKit\Core\Http
	     */
	    protected $http;
	   
		/**
	     * @var array
	     */
		public $routes = [];
	    
		/**
	     * Adds the action hooks for WordPress.
	     *
	     * @param \WPKit\Core\Application $app
	     */
	    public function __construct(Application $app, Http $http)
	    {
	        $this->app = $app;
	        $this->http = $http;
	    }
		
		protected function getController($callback) {
		    
		    $controller = false;
			
			if( is_string($callback) ) {
			
				$callback = stripos($callback, '\\') === 0 ? $callback : $this->app->getControllerName($callback);
				$controller = stripos($callback, '::') === false ? $callback : explode('::', $callback);
				$controller = is_array($controller) ? reset($controller) : $controller;
				$controller = $controller::instance();
			
			}
			
			return $controller;
			
		}
		
		protected function getMethod($callback) {
			
			$method = false;
			
			if( is_string($callback) ) {
			
				$method = stripos($callback, '::') === false ? 'beforeFilter' : explode('::', $callback);
				$method = is_array($method) ? end($method) : $method;
			
			}
			
			return $method;
			
		}
		
		protected function getCallback($callback) {
			
			$controller = $this->getController($callback);
			$method = $this->getMethod($callback);
			
			if( $controller && $method ) {
				
				return array($controller, $method);
				
			}
			
			return $callback;
			
		}
		
	}