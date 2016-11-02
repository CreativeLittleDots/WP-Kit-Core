<?php
    
    namespace WPKit\Core;

	class Auth extends Singleton {
		
		/**
	     * @var \WPKit\Application
	     */
	    protected $app;
	    
	    /**
	     * @var \WPKit\Core\Http
	     */
	    protected $http;
    	
    	public $settings = array();
    	
    	public function __construct($params = array(), Application $app, Http $http) {
	    	
	    	$this->app = $app;
	    	$this->http = $http;
	    	
	    	$this->mergeSettings($params);
	    	
	    	$this->beforeAuth();
			
			add_action( 'init', array($this, 'authenticate'), 1 );
			
			if( did_action( 'init' ) ) {
				
				$this->authenticate();
				
			}
	    	 
    	}
    	
    	public function beforeAuth() {}
    	
    	public function mergeSettings($settings = array()) {
	    	
	    	$this->settings = array_merge(array(
    			'allow' => array(),
    			'logout_redirect' => '/wp-login.php',
			), $settings);
	    	
    	}
    	
    	public function authenticate() {}
    	
    	public function isAllowed() {
	    	
	    	$is_allowed = is_user_logged_in() || is_page( $this->settings['logout_redirect'] ) || is_route( $this->settings['logout_redirect'] );
			
			if( ! $is_allowed ) {
			
				foreach($this->settings['allow'] as $page) {
	    			
	    			$is_allowed = is_page( $page ) || is_route( BASE_PATH . $page ) ? true : $is_allowed;
	    			
				}
				
			}
			
			return $is_allowed;
	    	
    	}
    	
    }