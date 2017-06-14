<?php
    
    namespace WPKit\Http\Middleware;
    
    use Closure;
	use Illuminate\Contracts\Auth\Factory as AuthFactory;
	use Illuminate\Support\Facades\Route;
	use Illuminate\Http\Request;

	class TokenAuth {
		
		/**
	     * The guard factory instance.
	     *
	     * @var \Illuminate\Contracts\Auth\Factory
	     */
	    protected $auth;
	    
	    protected $settings = array();
	
	    /**
	     * Create a new middleware instance.
	     *
	     * @param  \Illuminate\Contracts\Auth\Factory  $auth
	     * @return void
	     */
	    public function __construct(AuthFactory $auth)
	    {
	        $this->auth = $auth; 
	    }
	    
	    /**
	     * Handle an incoming request.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @param  \Closure  $next
	     * @param  string|null  $guard
	     * @return mixed
	     */
	    public function handle(Request $request, Closure $next, $guard = null)
	    {
			
			nocache_headers();
			
			if( $is_allowed = $this->isAllowed() ) {
				
				$next($request);
				
				return true;
				
			}
	
			// here
			
			$token = $request->get('access_token');
			
			if( empty($token) && $request->bearerToken() ) {
				
				$token = base64_decode( $request->bearerToken() );
				
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
				
				$next($request);
				
			} else {
				
				status_header(401);
				
				wp_send_json_error( 'invalid access token provided' );
				
			}
	        
	    }
    	
    	public function mergeSettings($settings = array()) {
	    	
	    	$this->settings = array_merge(array(
    			'username' => 'login',
    			'callback' => self::class . '@token',
    			'issuer' => array( __CLASS__, 'issueToken'),
    			'limit' => 5,
    			'allow' => array()
			), $settings);
			
			$this->settings['allow'][] = '/oauth/token';
			
			Route::post( 'oauth/token', $this->settings['callback'] );
			
			return $this;

		}
		
		public function isAllowed() {
			
			extract($this->settings);
			
			$is_allowed = is_user_logged_in();
			
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
    	
    	public function token(Request $request) {
	    	
	    	if( ! $username = wpkit('http')->get('username') ) {
						
				status_header(401);
				
				wp_send_json_error( 'Missing parameter: username' );
				
			}
			
			if( ! $password = wpkit('http')->get('password') ) {
				
				status_header(401);
				
				wp_send_json_error( 'Missing parameter: password' );
				
			}
			
			if( is_array( wpkit('auth.token')->settings['username'] ) ) {
					
				foreach( wpkit('auth.token')->settings['username'] as $property ) {
					
					if( $user = get_user_by( $property, $username ) ) {
						
						break;
						
					}
					
				}
				
			} else {
				
				$indentifiers = is_array( wpkit('auth.token')->settings['username'] ) ? wpkit('auth.token')->settings['username'] : array( wpkit('auth.token')->settings['username'] );
				
				foreach( $indentifiers as $indentifier ) {
			
					if( $user = get_user_by( $indentifier, $username ) ) {
						
						break;
						
					}
					
				}
				
			}
			
			$is_authenticated = wp_authenticate($user ? $user->user_login : false, $password);
			
			if ( ! is_wp_error( $is_authenticated ) ) {
				
				$token = wp_generate_password( 40, false, false );
				
				$tokens = get_user_meta( $user->ID, 'access_token', false );
				
				if( count($tokens) >= 5  ) {
					
					delete_user_meta( $user->ID, 'access_token', reset($tokens) );
					
				}
				
				add_user_meta( $user->ID, 'access_token', $token );
				
				status_header(200);
			
				wp_send_json_success( call_user_func( wpkit('auth.token')->settings['issuer'], $token, $user ) );
				
			} else {
				
				status_header(401);
				
				wp_send_json_error($is_authenticated->get_error_message());
				
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