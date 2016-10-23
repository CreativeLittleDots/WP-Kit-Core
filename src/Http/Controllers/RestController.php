<?php
	
	namespace WPKit\Core\Controllers;
	
	use WPKit\Core\Application;
	
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
	    
	    public function action($params) {
		    
		    extract($params);
		    
		    if( empty( $controller ) ) {
			    
			    wp_die("Controller is not set");
			    
		    }
		    
		    if( empty( $action ) ) {
			    
			    $action = 'index';
			    
		    }
		    
		    $singleController = inflector()->camelize( inflector()->singularize( $controller ) . '_controller');
		    $pluralController = inflector()->camelize( inflector()->pluralize( $controller ) . '_controller');
		    
		    if( ! $class = wpkit()->make($singleController) ) {
			    
			    if( ! $class = wpkit()->make($pluralController) ) {
			    
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
		    
		    return $this->app->call(array($class, $action), array_slice($params, 2, null, true));
		    
	    }
		
	}