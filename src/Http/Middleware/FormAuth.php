<?php
    
    namespace WPKit\Http\Middleware;
    
    use WPKit\Core\Auth;

	class FormAuth extends Auth {
    	
    	public function beforeAuth() {
			
			add_filter( 'login_url', array($this, 'get_login_url'), 10, 3);
			add_action( 'login_init', array($this, 'mask_login') );;
			
		}
    	
    	public function mergeSettings($settings = array()) {
			
			parent::mergeSettings(array_merge(array(
    			'login_redirect' => home_url(),
    			'mask_wp_login' => false
			), $settings));

		}
    	
    	public function get_login_url($login_url, $redirect, $force_reauth) {
        		
    		extract($this->settings);
			
			if( $logout_redirect ) {

            	if ( ! empty($redirect) )
            		$logout_redirect = add_query_arg('redirect_to', urlencode($redirect), $logout_redirect);
            
            	if ( $force_reauth )
            		$logout_redirect = add_query_arg('reauth', '1', $logout_redirect);
    			
			}
    		
    		return $logout_redirect;
    		
		}
		
		public function authenticate() {
			
			nocache_headers();
			
			$is_allowed = $this->isAllowed();

            if ( ! is_user_logged_in() && ! $is_allowed ) {
                
                $current_url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                
                wp_redirect( add_query_arg('redirect_to', urlencode($current_url), $this->settings['logout_redirect']) );
                
                exit;
                
            } else if( is_user_logged_in() && $is_allowed ) {
                
                wp_redirect( $this->settings['logout_redirect'] );
                
                exit;
            
            }
	        
        }
        
        public function mask_login() {
	        
	        extract($this->settings);
	        
	        if( $mask_wp_login && is_wp_login() && empty ( $_REQUEST['interim-login'] ) ) {
	            
	            if( $logout_redirect == '/wp-login.php' ) {
		            
		            return;
		            
	            }
	            
	            $args = array();
	            
	            if( ! empty( $_REQUEST['redirect_to'] ) ) {
		            
		            $args['redirect_to'] = $_REQUEST['redirect_to'];
		            
	            }
                
                wp_redirect( add_query_arg( $args, $logout_redirect ) );
                
            }
	        
        }
    	
    }