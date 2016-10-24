<?php
    
    namespace WPKit\Http\Middleware;
    
    use WPKit\Core\Auth;

	class BasicAuth extends Auth {
    	
    	public function mergeSettings($settings = array()) {
			
			parent::mergeSettings(array_merge(array(
    			'username' => 'login'
			), $settings));

		}
    	
    	public function authenticate() {
	    	
			nocache_headers();
			
			if( $is_allowed = $this->isAllowed() ) {
				
				return true;
				
			}
	
			$username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
			$password = isset($_SERVER['PHP_AUTH_PW'])   ? $_SERVER['PHP_AUTH_PW']   : '';
			
			if ( empty($username) && empty($password) && $this->http->header('Authorization') ) {
				
				list($type, $auth) = explode(' ', $this->http->header('Authorization'));
				
				if (strtolower($type) === 'basic') {
					
					list($username, $password) = explode(':', base64_decode($auth));
					
				}
				
			}
			
			$user = get_user_by( $this->settings['username'], $username );
			
			$is_authenticated = wp_authenticate($user->user_login, $password);
			
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