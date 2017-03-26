<?php
    
    namespace WPKit\Http\Middleware;
    
    use WPKit\Core\Application;
    use Illuminate\Http\Request;

	class Auth {
		
		/**
	     * @var Static
	     */
	    public static $instance = null;
		
		/**
	     * @var \WPKit\Application
	     */
	    protected $app;
	    
	    /**
	     * @var \WPKit\Core\Http
	     */
	    protected $http;
    	
    	/**
	     * @var array
	     */
    	public $settings = array();
    	
    	/**
	     * @var string
	     */
    	public $action = 'init';
    	
    	/**
	     * Instance function to return only once instance of the controller
	     *
	     * @return \WPKit\Http\Middleware\Auth
	     */
        public static function instance( $params, Application $app ) {
	        
	        $class = get_called_class();
	        
	        if( empty( static::$instance ) ) {
		        
		        static::$instance = $app->make($class, func_get_args()); 
		        
	        }
	        
	        return static::$instance;
	        
        }
    	
    	public function __construct($params = array(), Application $app, Request $http) {
	    	
	    	$this->app = $app;
	    	$this->http = $http;
	    	
	    	// when using REST api OPTIONS needs to return successful
			
			if ( 'OPTIONS' == $this->http->method() ) {
				
				status_header(200);
					    
		        wp_send_json_success( 'authorised' );
		        
		    }
	    	
	    	$this->mergeSettings($params);
	    	
	    	$this->beforeAuth();
			
			add_action( $this->action, array($this, 'authenticate'), 1 );
			
			if( did_action(  $this->action ) ) {
				
				$this->authenticate();
				
			}
	    	 
    	}
    	
    	public function beforeAuth() {}
    	
    	public function mergeSettings($settings = array()) {
	    	
	    	$this->settings = array_merge(array(
    			'allow' => array(),
    			'disallow' => array(),
    			'logout_redirect' => '/wp-login.php',
			), $settings);
	    	
    	}
    	
    	public function authenticate() {}
    	
    	public function isAllowed() {
	    	
	    	$is_allowed = is_user_logged_in() || is_page( $this->settings['logout_redirect'] ) || is_route( $this->settings['logout_redirect'] );
			
			if( ! $is_allowed ) {
				
				if( ! empty( $this->settings['disallow'] ) ) {
					
					$is_allowed = true;
					
					foreach($this->settings['disallow'] as $page) {
	    			
		    			$is_allowed = is_page( $page ) || is_route( BASE_PATH . $page ) ? false : $is_allowed;
		    			
		    			if( ! $is_allowed ) {
			    			
			    			break;
			    			
		    			}
		    			
					}
				
				} else {
					
					foreach($this->settings['allow'] as $page) {
	    			
		    			$is_allowed = is_page( $page ) || is_route( BASE_PATH . $page ) ? true : $is_allowed;
		    			
		    			if( $is_allowed ) {
			    			
			    			break;
			    			
		    			}
		    			
					}
					
				}
				
			}
			
			return $is_allowed;
	    	
    	}
    	
    }