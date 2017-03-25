<?php
    
    namespace WPKit\Core;
    
    class Route extends Singleton {
	    
	    /**
	     * @var \WPKit\Core\Application
	     */
	    protected $app;
	    
	    /**
	     * @var \WPKit\Core\Kernal
	     */
	    protected $kernal;
		
		/**
	     * @var array
	     */
	    protected $middleware = [];
	    
	    /**
	     * @var string
	     */
	    protected $callback;
	    
	    /**
	     * @var array
	     */
	    protected $meta;
	    
	    public function __construct(Application $app, Kernal $kernal, $callback, $meta = array()) 
	    {
		    $this->app = $app;
		    $this->kernal = $kernal;
		    $this->callback = $callback;
		    $this->meta = $meta;
		    
	    }
	    
	    /**
	     * Run the route.
	    */
	    public function run( $params = array() ) {
		    
		    $params = is_array( $params ) ? $params : array();
			
			if( $middleware = $this->getMiddleware() ) {
				
				if( is_callable( $middleware ) ) {
			    
				    call_user_func( $middleware );
				    
			    } else {
		
					foreach( $middleware as $m ) {
				
						$this->app->call( array( $m['middleware'], 'instance' ), $m['options'] );
						
					}
					
				}
				
			}
			
			if( $controller = $this->getController() ) {
				
				$this->app->call( array( $controller, 'dispatch' ), compact('params') );
			
				if( $this->getMethod() !== 'dispatch' ) {
				
					$this->app->call( $this->getCallback(), $params );
					
				}
				
			}
		    
	    }
	    
	     /**
	     * Register middleware on the route.
	     *
	     * @param  array|string|\Closure  $middleware
	     * @param  array   $options
	     * @return void
	     */
	    public function middleware( $middleware ) 
	    {
		    
		    if( is_callable( $middleware ) ) {
			    
			    $this->middleware = $middleware;
			    
		    } else {
			
				$middleware = is_array( $middleware ) ? $middleware : array(
					$middleware => array_slice(func_get_args(), 1)
				);
				
				foreach($middleware as $m => $options) {
					
					$class = $this->kernal->getMiddleware( $m ) ? $this->kernal->getMiddleware( $m ) : false;
					
					if( $class ) {
					
						$this->middleware[] = [
							'middleware' => $class,
							'options' => $options
						];
						
					}
					
				}
				
			}
			
		}
		
		/**
	     * Get the middleware assigned to the controller.
	     *
	     * @return array
	     */
	    public function getMiddleware()
	    {
	        return $this->middleware;
	    }
	    
	    /**
	     * Get callback.
	     *
	     * @return callable
	     */
	    public function getCallback() 
	    {
			
			$callback = $this->callback;
			
			$controller = $this->getController();
			$method = $this->getMethod();
			
			if( $controller && $method ) {
				
				return array($controller, $method);
				
			}
			
			return $callback;
			
		}
		
		/**
	     * Get controller.
	     *
	     * @return WPKit\Core\Controller
	     */
		public function getController() {
			
			$callback = $this->callback;
		    
		    $controller = false;
			
			if( is_string($callback) ) {
			
				$callback = stripos($callback, '\\') === 0 ? $callback : $this->app->getControllerName($callback);
				$controller = stripos($callback, '::') === false ? $callback : explode('::', $callback);
				$controller = is_array($controller) ? reset($controller) : $controller;
				$controller = $this->app->call(array($controller, 'instance'), [$this->app]);
			
			}
			
			return $controller;
			
		}
		
		/**
	     * Get controller method.
	     *
	     * @return string
	     */
		public function getMethod() {
			
			$callback = $this->callback;
			
			$method = false;
			
			if( is_string($callback) ) {
			
				$method = stripos($callback, '::') === false ? 'dispatch' : explode('::', $callback);
				$method = is_array($method) ? end($method) : $method;
			
			}
			
			return $method;
			
		}
	    
    }