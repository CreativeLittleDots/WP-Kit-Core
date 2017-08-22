<?php
	
	namespace WPKit\Http\Controllers;
	
	use WPKit\Routing\Controller;
	
	class RestController extends Controller {
	    
	    /**
	     * The action used in Route for RestController, it takes the current route and tried to find a 
	     * suitable controller and action by matching the pattern in the url e.g.
	     * GET /houses/get/12 -> HouseController::get
	     * POST /houses/add -> HouseController::save
	     * GET /houses -> HouseController::get
	     *
	     * @param string $controller
	     * @param string $action
	     * @param int $id
	     * @var void
	     */
	    public function action($controller, $action = '', $id = '') {
		    
		    if( empty( $controller ) ) {
			    
			    wp_send_json_error("Controller is not set");
			    
		    }
		    
		    if( empty( $action ) ) {
			    
				$action = $this->http->method() == 'POST' ? 'save' : 'get';
			    
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
		    
		    if( method_exists($class, 'beforeFilter') ) {
		    
		    	$this->app->call(array($this->app->make($class), 'beforeFilter'));
		    	
		    }
		    
		    $this->app->call(array($this->app->make($class), $action), compact('id'));
		    
	    }
		
	}