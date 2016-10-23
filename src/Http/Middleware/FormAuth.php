<?php
    
    namespace WPKit\Http\Middleware;
    
    use WPKit\Core\Auth;

	class FormAuth extends Auth {
    	
    	public function __construct( $settings ) {
	    	
	    	$this->settings = array_merge(array(
    			'page' => false,
    			'allow' => array(),
    			'force' => false
			), $settings);
			
			add_filter( 'login_url', array($this, 'get_login_url'), 10, 3);
			add_action( 'init', array($this, 'mask_wp_login') );
			add_action( 'wp', array($this, 'route') );
	    	
    	}
    	
    	public function get_login_url($login_url, $redirect, $force_reauth) {
        		
    		extract($this->settings);
			
			if( $page ) {
    			
    			if( is_numeric( $page ) ) {
        			
        			$page = get_post($page);
        			
    			} else {
        			
        			$page = get_page_by_path($page);
        			
    			}
    			
    			$login_url = get_permalink($page->ID);

            	if ( ! empty($redirect) )
            		$login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
            
            	if ( $force_reauth )
            		$login_url = add_query_arg('reauth', '1', $login_url);
    			
			}
    		
    		return $login_url;
    		
		}
		
		public function route() {
    			
			extract($this->settings);
			
			$is_page = false;
			
			foreach($allow as $page) {
    			
    			$is_page = is_page( $page ) ? true : $is_page;
    			
			}

            if ( ! is_user_logged_in() && ! $is_page ) {
                
                $current_url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                
                wp_redirect( wp_login_url ( $current_url ) );
                
                exit;
                
            } else if( is_user_logged_in() && ( is_wp_login() || ( $page && is_page($page) ) ) ) {
                
                wp_redirect( home_url() );
                
                exit;
            
            }
        
        }
        
        public function mask_wp_login() {
	        
	        extract($this->settings);
                        
            if( $force && is_wp_login() && empty ( $_REQUEST['interim-login'] ) && $page ) {
                
                if( is_numeric( $page ) ) {
            			
        			$page = get_post($page);
        			
    			} else {
        			
        			$page = get_page_by_path($page);
        			
    			}
                
                $current_url = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                
                wp_redirect( add_query_arg( array( 'redirect_to' => $current_url ), get_permalink($page->ID) ) );
                
            }
	        
        }
    	
    }