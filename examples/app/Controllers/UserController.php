<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class UserController extends Controller {
	    
	    public function beforeFilter() {
			
			wpkit()->ajax( 'user_login', array($this, 'user_login') );
			wpkit()->ajax( 'validate_user_email', array($this, 'validate_user_email') );
			wpkit()->ajax( 'user_accept_notifications', array($this, 'user_accept_notifications'), false );
			wpkit()->ajax( 'user_action_event', array($this, 'user_action_event'), false );
			wpkit()->ajax( 'user_get_unactioned_events', array($this, 'user_get_unactioned_events'), false );
			wpkit()->ajax( 'user_like_post', array($this, 'user_like_post'), false );
			wpkit()->ajax( 'user_unlike_post', array($this, 'user_unlike_post'), false );
			
			add_filter('show_admin_bar', array($this, 'show_admin_bar') );
			
			parent::beforeFilter();
	        
	    }
	    
	    public function show_admin_bar($show_admin_bar) {
	    
		    if( ! current_user_can('publish_posts') ) {
			    
			    $show_admin_bar = false;
			    
		    }
		    
		    return $show_admin_bar;
		    
	    }
	    
	    public function user_login() {
		    
	        $response = array(
	            'redirect' => false,
	            'request' => $_POST,
	        );
	        
	        //Check for empty fields
	        if(empty($_POST['email']) || empty($_POST['pwd'])){   
	                 
	            //create new error object and add errors to it.
	            $error = new WP_Error();
	            
	            if(empty($email)){ //No email
		            
	                $error->add('empty_username', __('<strong>ERROR</strong>: Email field is empty.'));
	                
	            }
	            
	            else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ //Invalid Email
		            
	                $error->add('invalid_username', __('<strong>ERROR</strong>: Email is invalid.'));
	                
	            }
	            
	            if(empty($meta['password'])){ //No password
		            
	                $error->add('empty_password', __('<strong>ERROR</strong>: Password field is empty.'));
	                
	            }
	            
	            $response['errors'] = $error;
	            
	        }
	        
	        if(empty($response['errors'])) {
		        
	            $email = $_POST['email'];
	            
	            $meta['password'] = $_POST['pwd'];
	            
	            //Check if user exists in WordPress database
	            
	            $user = get_user_by('email', $email);
	            
	            //bad email
	            
	            if(!$user) {
		            
	                $error = new WP_Error();
	                
	                $error->add('invalid', __('<strong>ERROR</strong>: Either the email or password you entered is invalid.'));
	                
	                $response['errors'] = $error;
	                
	            }
	            
	            else{ //check password
		            
	                if( ! wp_check_password($meta['password'], $user->user_pass, $user->ID) ) { //bad password
		                
	                    $error = new WP_Error();
	                    
	                    $error->add('invalid', __('<strong>ERROR</strong>: Either the email or password you entered is invalid.'));
	                    
	                    $response['errors'] = $error;
	                    
	                } else {
		                
	                    wp_clear_auth_cookie();
	                    
	                    wp_set_current_user ( $user->ID );
	                    
	                    wp_set_auth_cookie  ( $user->ID );
	                    
	                    $response['redirect'] = ! empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : home_url();
	                    
	                    $response['user'] = $user;
	                    
	                }
	                
	            }
	            
	        }
	        
	        echo json_encode($response);
	        
	        exit();
	        
	    }
	    
	    public function validate_user_email() {
		    
	        $response = array(
	            'request' => $_POST,
	        );
	        
	        if( ! empty($_POST['email']) ) {
		        
	            $user = get_user_by( 'email', $_POST['email'] );
	            
	            if( ! $user ) {
		            
	                $error = new WP_Error();
	                
	                $error->add('invalid', __('<strong>ERROR</strong>: The email address provided is not valid.'));
	                
	                $response['errors'] = $error;
	                
	            } else {
		            
	                $user->avatar = get_user_avatar($user->ID);
	                
	                $response['user'] = $user;
	                
	            }   
	                     
	        }
	        
	        echo json_encode($response);
	        
	        exit();
	    }
	    
	    public function user_accept_notifications() {
		    
	        $response = array(
	            'request' => $_POST,
	        );
	        
	        if( ! empty($_POST['onesignal_device_id']) ) {
		        
	            update_metadata_serialized('user', get_current_user_id(), 'onesignal_device_ids', (string) $_POST['onesignal_device_id']);   
	                   
	        }
	        
	        echo json_encode($response);
	        
	        exit();
	        
	    }
	    
	    public function user_action_event() {
    	
	    	$response = array(
	    		'request' => $_POST
			);
			
			if( ! empty( $_POST['post_id'] ) && is_user_logged_in() ) {
				
				$post_id = $_POST['post_id'];
				
				$event = get_post($post_id);
				
				if( $event && $event->post_type === 'event' && ! in_metadata_serialized(get_current_user_id(), 'post', $post_id, 'actioned_by_user') ) {
					
					update_metadata_serialized('post', $post_id, 'actioned_by_user', (string) get_current_user_id());	
					
					$response['success'] = true;
					
				}
				
			}
			
			echo json_encode($response);
			
			exit();
	    	
		}
		
		public function user_get_unactioned_events() {
    	
	    	$response = array(
	    		'request' => $_POST,
	    		'events' => array(),
			);
	    	
	    	if( ! empty( $_REQUEST['user_id'] ) ) {
	        	
	        	$response['events'] = get_user_unactioned_events( $_REQUEST['user_id'] );
	        	
	    	}
	    	
	    	echo json_encode($response);
			
			exit();
	    	
		}
		
		public function user_like_post() {
			
			$response = array(
	            'request' => $_REQUEST,
	        );
			
			if( ! empty( $_REQUEST['post_id'] ) && is_user_logged_in() ) {
				
				if( get_post($_REQUEST['post_id']) && ! in_metadata_serialized(get_current_user_id(), 'post', $_REQUEST['post_id'], 'liked_by_user') ) {
					
					update_metadata_serialized('post', $_REQUEST['post_id'], 'liked_by_user', (string) get_current_user_id());
					
					update_metadata_serialized('user', get_current_user_id(), 'likes_post', (string) $_REQUEST['post_id']);	
					
					$response['like_changed'] = true;
					
				}
				
			}
			
			echo json_encode($response);
			
	        exit();
			
		}
		
		public function user_unlike_post() {
					
			$response = array(
	            'request' => $_REQUEST,
	        );
			
			if( ! empty( $_REQUEST['post_id'] ) && is_user_logged_in() ) {
	    		
	    		if( get_post($_REQUEST['post_id']) && in_metadata_serialized(get_current_user_id(), 'post', $_REQUEST['post_id'], 'liked_by_user') ) {
					
	    			remove_metadata_serialized('post', $_REQUEST['post_id'], 'liked_by_user', get_current_user_id());
	    			
	    			remove_metadata_serialized('user', get_current_user_id(), 'likes_post', $_REQUEST['post_id']);	
	    				
	    			$response['like_changed'] = true;
	    			
	            }
				
			}
			
			echo json_encode($response);
			
	        exit();
			
		}
	    
	    public function authenticate_user() {
	        
	        if( ! empty( $_REQUEST['key'] ) ) {
	            
	            // include GF User Registration functionality
	            require_once( gf_user_registration()->get_base_path() . '/includes/signups.php' );
	            
	            $result = GFUserSignups::activate_signup( $_REQUEST['key'] );
	            
	            if ( is_wp_error($result) ) {
	                
	                if ( 'already_active' == $result->get_error_code() ) {
	                    
	                    $signup = $result->get_error_data();
	                    
	                    $message = sprintf( __( 'Your account is already active with the email address %5$s'), $signup->user_login );
	                    
	                } else {
	                    
	                    $message = $result->get_error_message();
	                    
	                }
	                
	            } else {
	                
	               $message = __('Your account is now activated. Please login below.');
	                
	            }
	            
	            the_component('Elements', 'alert', compact('message'));
	            
	        }
	        
	    }
		
	}