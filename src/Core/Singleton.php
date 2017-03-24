<?php
    
    namespace WPKit\Core;
    
    abstract class Singleton {
	    
	    /**
	     * @var array
	     */
	    public static $instances = [];

	    public static function instance($app) {
			
			$class = get_called_class();

	        if ( empty( $instances[$class] ) ) {
		        
	            $instances[$class] = wpkit()->make($class, [$app]);
	            
	        }
	
	        return $instances[$class];
			
		}
	    
	}
