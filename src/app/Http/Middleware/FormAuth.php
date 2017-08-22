<?php
    
    namespace WPKit\Http\Middleware;
    
    use Closure;
	use Illuminate\Contracts\Auth\Factory as AuthFactory;

	class FormAuth {
		
		/**
	     * The guard factory instance.
	     *
	     * @var \Illuminate\Contracts\Auth\Factory
	     */
	    protected $auth;
	    
	    /**
	     * The auth settings
	     *
	     * @var array
	     */
	    protected $settings = array();
	
	    /**
	     * Create a new middleware instance.
	     *
	     * @param  \Illuminate\Contracts\Auth\Factory  $auth
	     * @return void
	     */
	    public function __construct(AuthFactory $auth, $settings = array())
	    {
	        $this->auth = $auth;
	        $this->mergeSettings($settings);
	    }
	    
	    /**
	     * Handle an incoming request.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @param  \Closure  $next
	     * @param  string|null  $guard
	     * @return mixed
	     */
	    public function handle($request, Closure $next, $guard = null)
	    {
		    
		    add_filter( 'login_url', array($this, 'get_login_url'), 10, 3);
			add_action( 'login_init', array($this, 'mask_login') );
			add_filter( 'login_redirect', array($this, 'login_redirect'), 10, 3 );
			
			nocache_headers();
			
			$is_allowed = $this->isAllowed();

            if ( ! is_user_logged_in() && ! $is_allowed ) {
                
                $current_url = get_current_url();
                
                wp_redirect( add_query_arg('redirect_to', ! is_wp_login() ? urlencode( $current_url ) : null, $this->settings['logout_redirect'] ) );
                
                exit;
                
            } else {
	            
	            $next($request);
	            
            }
	        
	    }
    	
    	/**
	     * Merge settings into middleware for use through middleware methods
	     *
	     * @param array $settings
	     * @return WPKit\Http\Middleware\FormAuth
	     */
    	public function mergeSettings($settings = array()) {
	    	
	    	$this->settings = array_merge(array(
    			'allow' => array(),
    			'disallow' => array(),
    			'logout_redirect' => '/wp-login.php',
    			'login_redirect' => home_url(),
    			'mask_wp_login' => false
			), $settings);
			
			return $this;

		}
		
		/**
	     * Check if this current route is allowed based on middleware settings
	     *
	     * @return boolean
	     */
		public function isAllowed() {
			
			extract($this->settings);
	    	
	    	if( is_wp_login() ) {
		    	
		    	return true;
		    	
	    	}
	    	
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
    	
    	/**
	     * Get login url based on middleware settings
	     *
	     * @param string $login_url
	     * @param string $redirect
	     * @param boolean $force_reauth
	     * @return string
	     */
    	public function get_login_url($login_url, $redirect, $force_reauth) {
        		
    		extract($this->settings);
			
			if( $logout_redirect && $mask_wp_login ) {
				
				$login_url = home_url($logout_redirect);

            	if ( ! empty($redirect) )
            		$login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
            
            	if ( $force_reauth )
            		$login_url = add_query_arg('reauth', '1', $login_url);
            		
            	
    			
			}
    		
    		return $login_url;
    		
		}
        
        /**
	     * Get login redirect based on request and middleware settings
	     *
	     * @return string
	     */
        public function login_redirect() {
	        
	        extract($this->settings);
			
			return ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : $login_redirect;
			
		}
        
        /**
	     * Mask login and redirect user to a given login page based on middleware settings
	     *
	     * @return void
	     */
        public function mask_login() {
	        
	        extract($this->settings);
	        
	        if( $mask_wp_login && is_wp_login() && empty ( $_REQUEST['interim-login'] ) ) {
	            
	            if( $logout_redirect == '/wp-login.php' ) {
		            
		            return;
		            
	            }
                
                wp_redirect( add_query_arg( $_REQUEST, $logout_redirect ) );
                
                exit();
                
            }
	        
        }
    	
    }