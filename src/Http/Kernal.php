<?php
	
	namespace WPKit\Core;
    
    use WPKit\Core\Middleware;
	
	class Kernal extends Singleton {
		
		/**
	     * @var \WPKit\Core\Application
	     */
	    protected $app;
	    
	    /**
	     * @var \WPKit\Core\Http
	     */
	    protected $http;
	    
	    /**
	     * @var array
	     */
	    protected $middleware = [
		    'oauth' => 'WPKit\Http\Middleware\OauthAuth',
		    'form' => 'WPKit\Http\Middleware\FormAuth',
		    'basic' => 'WPKit\Http\Middleware\BasicAuth',
	    ];
	    
		/**
	     * Adds the action hooks for WordPress.
	     *
	     * @param \WPKit\Core\Application $app
	     */
	    public function __construct(Application $app, Http $http)
	    {
	        $this->app = $app;
	        $this->http = $http;
	    }
	    
	    public function getMiddleware($middleware) 
	    {
		    
		    return ! empty( $this->middleware[$middleware] ) ? $this->middleware[$middleware] : false;
		    
	    }
	    
	    public function addMiddleware($alias, $middleware) 
	    {
		    
		    $this->middleware[$alias] = $middleware;
		    
		    return $this;
		    
	    }
	    
	}