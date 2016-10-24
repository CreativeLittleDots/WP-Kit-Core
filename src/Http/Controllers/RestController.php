<?php
	
	namespace WPKit\Http\Controllers;
	
	use WPKit\Core\Application;
	use WPKit\Core\Controller;
	
	class RestController extends Controller {
		
		/**
	     * @var \WPKit\Application
	     */
	    protected $app;
	    
	    /**
	     * Adds the action hooks for WordPress.
	     *
	     * @param \WPKit\Core\Application $app
	     */
	    public function __construct(Application $app)
	    {
	        $this->app = $app;
	    }
	    
	    public function action($controller, $action = '', $id = '') {
		    
		    if( empty( $controller ) ) {
			    
			    wp_die("Controller is not set");
			    
		    }
		    
		    if( empty( $action ) ) {
			    
			    $action = 'index';
			    
		    }
		    
		    $singleController = inflector()->camelize( inflector()->singularize( $controller ) . '_controller');
		    $pluralController = inflector()->camelize( inflector()->pluralize( $controller ) . '_controller');
		    
		    if( ! class_exists( $class = $this->app->getControllerName($singleController) ) ) {
			    
			    if( ! class_exists( $class = $this->app->getControllerName($pluralController) ) ) {
			    
			    	wp_die("Controllers $singleController and $pluralController do not exist");
			    	
			    } else {
				    
				   $controller = $pluralController; 
				    
			    }
			    
		    } else {
			    
			    $controller = $singleController;
			    
		    }
		    
		    if( ! method_exists($class, $action) ) {
			    
			    wp_die("Controller $controller does not have method $action");
			    
		    }
		    
		    return $this->app->call(array($this->app->make($class), $action), compact('id'));
		    
	    }
		
	}