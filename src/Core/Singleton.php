<?php
    
    namespace WPKit\Core;
    
    abstract class Singleton {
	    
	    /**
	     * @var array
	     */
	    public static $instances = [];

	    public static function instance($app) {
			
			$class = get_called_class();

	        if ( empty( static::$instances[$class] ) ) {
		        
	            static::$instances[$class] = $app->make($class, func_get_args());
	            
	        }
	
	        return static::$instances[$class];
			
		}
	    
	}
