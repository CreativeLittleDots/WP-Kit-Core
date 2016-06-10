<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class EventController extends Controller {
		
		public function __construct() {
			
			add_action( 'acf/save_post', array($this, 'invite_users'), 10, 2 );
			add_action( 'after_event_archive_title', array($this, 'archive_detail_event_display_meta') );
			add_action( 'archive_loop_event_display_meta', array($this, 'archive_loop_event_display_meta') );
			add_action( 'gform_pre_render_5', array($this, 'populate_user_events_field') );
			add_action( 'gform_after_submission_7', array($this, 'user_accept_event'), 1 );
			add_action( 'gform_after_submission_12', array($this, 'user_decline_event'), 1 );
			add_action( 'gform_after_submission_5', array($this, 'user_feedback_event'), 1 );
			add_action( 'gform_field_value_first_event', array($this, 'first_event_id'), 1 );
			add_action( 'gform_replace_merge_tags', array($this, 'replace_event_info'), 10, 7 );
			add_action( 'gform_custom_merge_tags', array($this, 'custom_event_merge_tags'), 10, 4 );
			add_action( 'index_args_event', array($this, 'index_args') );
			
		}
		
		public function invite_users($post_id) {
    	
	    	$users_invited = get_field('users_invited', $post_id);
	    	
	    	if($users_invited) {
	        	
	        	$event = get_post($post_id);
	        	$subject = "You have been invited an the event";
	        	$message = "You have been invited the the event {$event->post_title}";
	        	$url = get_term_link( get_term_by('slug', 'rsvp', 'event_category') );
	        	
	        	$users_to_notify = array();
	        	
	        	foreach($users_invited as $user) {
	            	
	            	$user = (object) $user;
	            	
	            	if( ! in_metadata_serialized($post_id, 'user', $user->ID, 'invited_to_events') ) {
	                	
	                	add_filter( 'wp_mail_content_type', array($this, 'set_content_type' ) );
	                	
	                	if( wp_mail( 
	                	    $user->user_email, 
	                	    $subject, 
	                	    Timber::compile(EMAILS_VIEW_ABS_DIR . DIRECTORY_SEPARATOR . 'invite_users.twig', compact('user', 'event', 'url', 'message')) 
	                    ) ) {
	                        
	                        // send request to browser notifications
	                        
	                        $users_to_notify[] = $user->ID;
	                    	
	                    	update_metadata_serialized('user', $user->ID, 'invited_to_events', (string) $post_id);
	                    	
	                	}
	                	
	                	remove_filter( 'wp_mail_content_type', array($this, 'set_content_type' ) );
	                	
	            	}
	
	        	}
	        	
	        	$this->send_desktop_notifications($users_to_notify, $subject, $message);
	        	
	    	}
	    	
		}
		
		public function after_event_archive_title() {
	        
	        get_component('elements', 'event-archive-controls');
	        
	    }
	    
	    public function archive_detail_event_display_meta() {
		    
		    get_component('loop', 'post-date-meta');
		    
		    get_component('loop', 'event-details-meta');
		    
	    }
	    
	    public function archive_loop_event_display_meta() {
		    
		    get_component('loop', 'post-loop-date-meta');
		    
	    }
	    
	    public function populate_user_events_field(  $form ) {
	        
	        foreach( $form['fields'] as &$field ) {
	            
	            if( $field['label'] == 'Event' ) {
	                
	                $field['choices'] = array_map(function($post) {
	                    return array(
	                        'text' => $post->post_title,
	                        'value' => $post->ID
	                    );
	                }, get_user_attended_events_without_feedback());
	                
	            }  
	            
	        }
	        
	        return $form;
	        
	    }
	    
	    public function user_accept_event( $entry ) {
				
			if( ! empty( $entry[24] ) ) {
	    		
	    		$post_id = $entry[24];
	    		
	    		GFAPI::update_entry_property( $entry['id'], 'post_id', $post_id );
				
				$event = get_post($post_id);
				
				if( $event && $event->post_type === 'event' && ! in_metadata_serialized(get_current_user_id(), 'post', $post_id, 'attended_by_user') ) {
			
	                remove_metadata_serialized('post', $post_id, 'declined_by_user', get_current_user_id());
	                
	                remove_metadata_serialized('user', get_current_user_id(), 'declined_event', $post_id);
	                
	                update_metadata_serialized('post', $post_id, 'attended_by_user', (string) get_current_user_id());
	                
	                update_metadata_serialized('post', $post_id, 'actioned_by_user', (string) get_current_user_id());
	                
	                update_metadata_serialized('user', get_current_user_id(), 'attending_event', (string) $post_id);
	                
	            }
	            
	        }
	
		}
		
		public function user_decline_event( $entry ) {
			
			if( ! empty( $entry[24] ) ) {
	    		
	    		$post_id = $entry[24];
	    		
	    		GFAPI::update_entry_property( $entry['id'], 'post_id', $post_id );
				
				$event = get_post($post_id);
				
				if( $event && $event->post_type === 'event' && ! in_metadata_serialized(get_current_user_id(), 'post', $post_id, 'declined_by_user') ) {
					
					remove_metadata_serialized('post', $post_id, 'attended_by_user', get_current_user_id());	
					
					remove_metadata_serialized('user', get_current_user_id(), 'attending_event', $post_id);
					
					update_metadata_serialized('post', $post_id, 'declined_by_user', (string) get_current_user_id());
					
					update_metadata_serialized('post', $post_id, 'actioned_by_user', (string) get_current_user_id());	
					
					update_metadata_serialized('user', get_current_user_id(), 'declined_event', (string) $post_id);
					
				}
				
			}
			
		}
		
		public function user_feedback_event( $entry ) {
			
			if( ! empty( $entry[1] ) ) {
	    		
	    		$post_id = $entry[1];
	    		
	    		GFAPI::update_entry_property( $entry['id'], 'post_id', $post_id );
				
				$event = get_post($post_id);
				
				if( $event && $event->post_type === 'event' ) {
					
					update_post_meta($post_id, 'user_' . get_current_user_id() . '_feedback_entry_id', $entry['id']);
					
				}
				
			}
			
		}
	    
	    public function first_event_id() {
	        
	        global $wp_query;
	        
	        return $wp_query->posts ? reset($wp_query->posts)->ID : false;
	        
	    }
	    
	    public function set_content_type() {
	        
	        return 'text/html';
	        
	    }
	    
	    public function replace_event_info( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
	        
	        $tag_format = '/{event:(?P<name>\w+)}/';
	        
	        preg_match_all($tag_format, $text, $matches);
	        
	        $event = ! empty( $entry[24] ) ? get_post($entry[24]) : false;
	        
	        if( ! empty( $matches[1]) ) {
	        
	            foreach($matches[1] as $i => $match) {
	                
	                if( $event ) {
	                    
	                    $var = get_field($match, $event->ID) ? get_field($match, $event->ID) : $event->$match;
	                    
	                    $text = str_replace($matches[0][$i], $var, $text);
	                    
	                } else {
	                    
	                    $text = str_replace($matches[0][$i], '', $text);
	                    
	                }
	                
	            } 
	            
	        }
	        
	        return $text;
	        
	    }
	    
	    public function custom_event_merge_tags($merge_tags, $form_id, $fields, $element_id) {
	        
	        if(array_filter($fields, function($field) {
	            
	            return $field->label == 'Event ID';
	            
	        })) {
	        
	            $merge_tags[] = array( 'tag' => '{event:title}', 'label' => esc_html__( 'Event Title', 'gravityforms' ) );
	            
	            $fields = get_posts(array(
	                'post_type' => 'acf-field', 
	                'post_parent' => get_page_by_title('Event Details', OBJECT, 'acf-field-group')->ID
	                )
	            );
	            
	            foreach( $fields as $field ) {
	                
	                $merge_tags[] = array( 'tag' => '{event:' . $field->post_excerpt . '}', 'label' => esc_html__( 'Event ' . $field->post_title, 'gravityforms' ) );
	                
	            }
	            
	        }
	        
	        return $merge_tags;
	        
	    }
	    
	    public function index_args($args) {
	        
	        $args['event_category'] = 'previous';
	        
	        return $args;
	        
	    }
	    
	    private function send_desktop_notifications($users, $heading, $content) {
	        
	        if( $users && $onesignal_app_id = get_field('onesignal_app_id', 'option') && $onesignal_api_key = get_field('onesignal_api_key', 'option') ) {
	            
	            $onesignal_device_ids = array_filter(array_map(function($user) {
	                
	                $rows = get_field('onesignal_device_ids', 'user_' . $user);
	                
	                $rows = array_map(function($row) {
	                    
	                    return $row['onesignal_device_id'];
	                    
	                }, $rows ? $rows : array());
	            
	                return $rows;
	                
	            }, $users));
	            
	            $onesignal_device_ids = $onesignal_device_ids ? call_user_func_array('array_merge', $onesignal_device_ids) : array();
	            
	        }
	        
	    }
		
	}