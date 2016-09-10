<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class AdminController extends Controller {
		
		public $scripts = [
			'admin.css'
		];
		
		public function beforeFilter() {
			
			add_filter( 'acf/update_value/name=welcome_message', array($this, 'set_welcome_id'), 10, 3);
		    
		    add_action( 'admin_menu', array($this, 'change_post_menu_label') );
		    
		    add_filter( 'wpmu_signup_user_notification_subject', array($this, 'signup_user_notification_subject'), 10, 5);
	
			add_filter( 'wpmu_signup_user_notification_email', array($this, 'signup_user_notification_email'), 10, 5);
			
			global $wp_post_types;
	    	
	    	$labels = &$wp_post_types['post']->labels;
	    	$labels->name = 'News';
	    	$labels->singular_name = 'News';
	    	$labels->add_new = 'Add News';
	    	$labels->add_new_item = 'Add News';
	    	$labels->edit_item = 'Edit News';
	    	$labels->new_item = 'News';
	    	$labels->view_item = 'View News';
	    	$labels->search_items = 'Search News';
	    	$labels->not_found = 'No News found';
	    	$labels->not_found_in_trash = 'No News found in Trash';
	    	
	    	parent::beforeFilter();
			
		}
		
		public function signup_user_notification_subject($subject, $user, $user_email, $key, $meta) {
    	
	    	$from_name = get_site_option( 'site_name' ) == '' ? 'WordPress' : esc_html( get_site_option( 'site_name' ) );
	    	
	    	return sprintf( $subject, $from_name, 'Your Account' );
	    	
		}
		
		public function signup_user_notification_email($message, $user, $user_email, $key, $meta) {

			return sprintf( $message, esc_url_raw( add_query_arg( array( 'key' => $key ), get_permalink( get_page_by_title('Login')->ID ) ) ) );
	    	
		}
		
		public function set_welcome_id($value, $post_id, $field) {
	
			if( get_field('welcome_message', 'option') !== $value ) {
				
				update_option('_welcome_id', substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8));
				
			}
			
			return $value;
			
		}
		
		public function change_post_menu_label() {
		        
	    	global $menu;
	    	global $submenu;
	    	
	    	$menu[5][0] = 'News';
	    	$submenu['edit.php'][5][0] = 'News';
	    	$submenu['edit.php'][10][0] = 'Add News';
	    	$submenu['edit.php'][16][0] = 'News Tags';
	    	
	    	echo '';
	    	
	    }
		
	}