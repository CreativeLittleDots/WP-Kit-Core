<?php
    
    namespace WPKit\Core;
    
    use Illuminate\Routing\Route as BaseRoute;
    
    class Route extends BaseRoute {
	    
	    /**
	     * @var \WPKit\Core\Application
	     */
	    protected $app;
	    
	    public function __construct($methods, $uri, $action, Application $app) 
	    {
		    $this->app = $app;
		    parent::__construct($methods, $uri, $action);
		    
	    }
	    
	    /**
		 * Get the controller instance for the route.
	     *
	     * @return mixed
	     */
	    public function getController()
	    {
	        $class = $this->parseControllerCallback()[0];
	        if (! $this->controller) {
	            $this->controller = $this->app->call(array($class, 'instance'), [$this->app]);
	        }
	        return $this->controller;
	    }
		
		/**
	     * Parse the controller.
	     *
	     * @return array
	     */
	    protected function parseControllerCallback()
	    {
		    $callback = Str::parseCallback( str_replace( '::', '@', $this->action['uses'] ), 'dispatch' );
		    $callback[0] = stripos( $callback[0], '\\' ) === 0 ? $callback[0] : $this->app->getControllerName( $callback[0] );
	        return $callback;
	    }
	    
    }