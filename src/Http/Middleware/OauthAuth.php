<?php
    
    namespace WPKit\Http\Middleware;
    
    use WPKit\Core\Auth;

	class OauthAuth extends Auth {
    	
    	public function __construct( $settings ) {
	    	
	    	parent::__construct( $settings );
			
			add_filter( 'parse_request', array($this, 'oauth') );
			
    	}
    	
    	public function oauth() {
	    	
			nocache_headers();
			
			if ( is_user_logged_in() ) {
				
				return true;
				
			}
			
			foreach($allow as $page) {
    			
    			if( is_page( $page ) ) {
	    			
	    			return true;
	    			
    			}    			
			}
	
			// here
			
		}
    	
    }