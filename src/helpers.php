<?php
	
	use WPKit\Foundation\Application;
	
	/*----------------------------------------------*\
    	#SERVICE WRAPPERS
    \*----------------------------------------------*/

	if ( ! function_exists('app') ) {
		
	    /**
	     * Helper function to quickly retrieve an instance.
	     *
	     * @param null  $abstract   The abstract instance name.
	     * @param array $parameters
	     *
	     * @return mixed
	     */
	    function app($abstract = null, array $parameters = [])
	    {
	        if (is_null($abstract)) {
	            return Application::getInstance();
	        }
	        return Application::getInstance()->make($abstract, $parameters);
	    }
	    
	}