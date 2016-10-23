<?php
    
    namespace WPKit\Http\Middleware;
    
    use WPKit\Core\Auth;

	class BasicAuth extends Auth {
    	
    	public function __construct( $settings ) {
	    	
	    	parent::__construct( $settings );
	    	
	    	$this->settings = array_merge(array(
    			'username' => 'user_login'
			), $settings);
			
			add_filter( 'parse_request', array($this, 'basic_auth') );
			
    	}
    	
    	public function basic_auth() {
	    	
			nocache_headers();
			
			if ( is_user_logged_in() ) {
				
				return true;
				
			}
			
			foreach($allow as $page) {
    			
    			if( is_page( $page ) ) {
	    			
	    			return true;
	    			
    			}    			
			}
	
			$usr = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
			$pwd = isset($_SERVER['PHP_AUTH_PW'])   ? $_SERVER['PHP_AUTH_PW']   : '';
			
			if (empty($usr) && empty($pwd) && isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION']) {
				list($type, $auth) = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
				if (strtolower($type) === 'basic') {
					list($usr, $pwd) = explode(':', base64_decode($auth));
				}
			}
			
			if( $this->settings['username'] !== 'user_login' ) {
				
				$user = get_user_by( $this->settings['username'], $usr );
				
			}
	
			$is_authenticated = wp_authenticate($user->user_login, $pwd);
			
			if ( ! is_wp_error( $is_authenticated ) ) {
				
				return true;
				
			}
	
			header('WWW-Authenticate: Basic realm="Please Enter Your Password"');
			
			wp_die(
				'You need to enter a Username and a Password if you want to see this website.',
				'Authorization Required',
				array( 'response' => 401 )
			);
			
		}
    	
    }