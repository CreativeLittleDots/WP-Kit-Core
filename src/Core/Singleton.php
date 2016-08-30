<?php
    
    namespace WPKit\Core;
    
    abstract class Singleton {
	    
	    public static $instances = [];
	    
	    public static function instance() {
			
			$class = get_called_class();

	        if ( empty( $instances[$class] ) ) {
		        
	            $instances[$class] = new $class();
	            
	        }
	
	        return $instances[$class];
			
		}
	    
	}
