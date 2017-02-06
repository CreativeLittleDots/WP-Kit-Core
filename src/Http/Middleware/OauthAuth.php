<?php
    
    namespace WPKit\Http\Middleware;
    
    use WPKit\Core\Auth;

	class OauthAuth extends Auth {
		
		public function beforeAuth() {
			
			route( BASE_PATH . '/oauth/token', $this->settings['callback'], 'post' );
			
		}
		
		public function mergeSettings($settings = array()) {
			
			$settings = array_merge(array(
    			'username' => 'login',
    			'callback' => array($this, 'token'),
    			'issuer' => array(__CLASS__, 'issueToken'),
    			'limit' => 5,
    			'allow' => array()
			), $settings);
			
			$settings['allow'][] = '/oauth/token';
			
			parent::mergeSettings($settings);

		}
		
    	public function authenticate() {
	    	
			nocache_headers();
			
			if( $is_allowed = $this->isAllowed() ) {
				
				return true;
				
			}
	
			// here
			
			$token = $this->http->get('access_token');
			
			if( empty($token) && $this->http->header('Authorization') ) {
				
				list($type, $auth) = explode(' ', $this->http->header('Authorization'));
				
				if (strtolower($type) === 'bearer') {
					
					$token = base64_decode($auth);
					
				}
				
			}
			
			if( ! $token ) {
				
				status_header(401);
				
				wp_send_json_error( 'no access token provided' );
				
			}
			
			$users = get_users(array(
				'fields' => 'ids',
				'meta_key' => 'access_token',
				'meta_value' => $token
			));
			
			if( $users && ! is_wp_error( $users ) ) {
				
				$user_id = reset( $users );
				
				wp_set_current_user ( $user_id );
				
			} else {
				
				status_header(401);
				
				wp_send_json_error( 'invalid access token provided' );
				
			}
			
		}
		
		public function token() {
			
			if( ! $grant_type = $this->http->get('grant_type') ) {
				
				status_header(401);
				
				wp_send_json_error( 'The grant type was not specified in the request' );
				
			}
			
			$types = array('client_credentials', 'password');
			
			if( in_array($grant_type, $types) ) {
			
				if( ! $client_id = $this->http->get('client_id') ) {
					
					status_header(401);
					
					wp_send_json_error( 'Missing parameter: client_id' );
					
				}
				
				if( ! $client_secret = $this->http->get('client_secret') ) {
					
					status_header(401);
					
					wp_send_json_error( 'Missing parameter: client_secret' );
					
				}
			
				if( in_array($grant_type, array('password') ) ) {
				
					if( ! $username = $this->http->get('username') ) {
						
						status_header(401);
						
						wp_send_json_error( 'Missing parameter: username' );
						
					}
					
					if( ! $password = $this->http->get('password') ) {
						
						status_header(401);
						
						wp_send_json_error( 'Missing parameter: password' );
						
					}
					
				}
				
				if( is_array( $this->settings['username'] ) ) {
					
					foreach($this->settings['username'] as $property) {
						
						if( $user = get_user_by( $property, $username ) ) {
							
							break;
							
						}
						
					}
					
				} else {
				
					$user = get_user_by( $this->settings['username'], $username );
					
				}
				
				$is_authenticated = wp_authenticate($user->user_login, $password);
				
				if ( ! is_wp_error( $is_authenticated ) ) {
					
					$token = wp_generate_password( 40, false, false );
					
					$tokens = get_user_meta( $user->ID, 'access_token', false );
					
					if( count($tokens) >= 5  ) {
						
						delete_user_meta( $user->ID, 'access_token', reset($tokens) );
						
					}
					
					add_user_meta( $user->ID, 'access_token', $token );
					
					status_header(200);
				
					wp_send_json_success(call_user_func($this->settings['issuer'], $token, $user));
					
				} else {
					
					status_header(401);
					
					wp_send_json_error($is_authenticated->get_error_message());
					
				}
				
			} else {
				
				status_header(401);
				
				wp_send_json_error( 'Unsupported grant type: ' . $grant_type );
				
			}
			
		}
		
		public static function issueToken( $token, \WP_User $user ) {
			
			return array(
				'access_token' => $token,
				'expires_in' => 3600,
				'token_type' => 'Bearer',
				'scope' => 'basic',
				'refresh_token' => null
			);
			
		}
    	
    }