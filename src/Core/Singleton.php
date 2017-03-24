<?php
    
    namespace WPKit\Core;
    
    abstract class Singleton {
	    
	    /**
	     * @var array
	     */
	    public static $instances = [];

	    public static function instance() {
			
			$class = get_called_class();

	        if ( empty( static::$instances[$class] ) ) {
		        
	            static::$instances[$class] = wpkit()->make($class, func_get_args());
	            
	        }
	
	        return static::$instances[$class];
			
		}
	    
	}
