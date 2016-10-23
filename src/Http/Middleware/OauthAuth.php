<?php
    
    namespace WPKit\Http\Middleware;
    
    use WPKit\Core\Auth;

	class OauthAuth extends Auth {
    	
    	public function authenticate() {
	    	
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