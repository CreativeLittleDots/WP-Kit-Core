<?php
    
    namespace WPKit\Core;
    
    class Flow extends Singleton {
	    
	    protected static function getController($callback) {
		    
		    $controller = false;
			
			if( is_string($callback) ) {
			
				$callback = stripos($callback, '\\') === 0 ? $callback : "App\Controllers\\$callback";
				$controller = stripos($callback, '::') === false ? $callback : explode('::', $callback);
				$controller = is_array($controller) ? reset($controller) : $controller;
				$controller = $controller::instance();
			
			}
			
			return $controller;
			
		}
		
		protected static function getMethod($callback) {
			
			$method = false;
			
			if( is_string($callback) ) {
			
				$method = stripos($callback, '::') === false ? 'beforeFilter' : explode('::', $callback);
				$method = is_array($method) ? end($method) : $method;
			
			}
			
			return $method;
			
		}
		
		protected static function getCallback($callback) {
			
			$controller = self::getController($callback);
			$method = self::getMethod($callback);
			
			if( $controller && $method ) {
				
				return array($controller, $method);
				
			}
			
			return false;
			
		}
	    
	}
