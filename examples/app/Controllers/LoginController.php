<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class LoginController extends Controller {
		
		public function __construct() {
			
			add_action( 'login_form_lostpassword', array($this, 'redirect_to_custom_lostpassword') );
			
		}
		
		public function redirect_to_custom_lostpassword() {
			
	        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
		        
	            if ( is_user_logged_in() ) {
		            
	                $this->redirect_logged_in_user();
	                
	                exit;
	                
	            }
	            
	            wp_redirect( home_url( 'reset' ) );
	            
	            exit;
	            
	        }
    	}
		
	}