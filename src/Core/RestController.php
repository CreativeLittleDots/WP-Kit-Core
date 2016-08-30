<?php
	
	namespace WPKit\Core;
	
	class RestController extends Controller {
	    
	    public function action($params) {
		    
		    extract($params);
		    
		    if( empty( $controller ) ) {
			    
			    if( )
			    
			    wp_die("Controller is not set");
			    
		    }
		    
		    if( empty( $action ) ) {
			    
			    wp_die("Action is not set");
			    
		    }
		    
		    $controller = inflector()->camelize($controller . '_controller');
		    
		    if( ! $class = wpkit()->make($controller) ) {
			    
			    wp_die("Controller $controller does not exist");
			    
		    }
		    
		    if( ! method_exists($class, $action) ) {
			    
			    wp_die("Controller $controller does not have method $action");
			    
		    }
		    
		    return call_user_func_array(array($class, $action), array_slice($params, 2, null, true));
		    
	    }
		
	}