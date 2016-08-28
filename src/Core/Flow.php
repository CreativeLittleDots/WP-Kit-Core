<?php
    
    namespace WPKit\Core;
    
    class Flow {
		
		protected static function getCallback($callback) {
			
			$callback = stripos($callback, '\\') === 0 ? $callback : "App\Controllers\\$callback";
			$callback = stripos($callback, '::') === false ? $callback . '::init' : $callback;
			
			return explode('::', $callback);
			
		}
	    
	}
