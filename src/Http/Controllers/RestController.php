<?php
	
	namespace WPKit\Http\Controllers;
	
	use WPKit\Core\Application;
	use WPKit\Core\Controller;
	
	class RestController extends Controller {
	    
	    public function action($controller, $action = '', $id = '') {
		    
		    if( empty( $controller ) ) {
			    
			    wp_send_json_error("Controller is not set");
			    
		    }
		    
		    if( empty( $action ) ) {
			    
			    $action = 'index';
			    
		    }
		    
		    $singleController = inflector()->camelize( inflector()->singularize( $controller ) . '_controller');
		    $pluralController = inflector()->camelize( inflector()->pluralize( $controller ) . '_controller');
		    
		    if( ! class_exists( $class = $this->app->getControllerName($singleController) ) ) {
			    
			    if( ! class_exists( $class = $this->app->getControllerName($pluralController) ) ) {
			    
			    	wp_send_json_error("Controllers $singleController and $pluralController do not exist");
			    	
			    } else {
				    
				   $controller = $pluralController; 
				    
			    }
			    
		    } else {
			    
			    $controller = $singleController;
			    
		    }
		    
		    if( ! method_exists($class, $action) ) {
			    
			    wp_send_json_error("Controller $controller does not have method $action", 400);
			    
		    }
		    
		    $this->app->call(array($this->app->make($class), 'beforeFilter'));
		    
		    $this->app->call(array($this->app->make($class), $action), compact('id'));
		    
	    }
		
	}