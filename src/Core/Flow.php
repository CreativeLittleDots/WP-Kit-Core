<?php
    
    namespace WPKit\Core;
    
    class Flow extends Singleton {
		
		protected static function getCallback($callback) {
			
			if( is_string($callback) ) {
			
				$callback = stripos($callback, '\\') === 0 ? $callback : "App\Controllers\\$callback";
				$callback = stripos($callback, '::') === false ? $callback . '::beforeFilter' : $callback;
				$callback = explode('::', $callback);
				
				$class = $callback[0];
				
				if( class_exists( $class ) ) {
					
					$callback[0] = $class::instance();
					
				}
			
			}
			
			return $callback;
			
		}
	    
	}
