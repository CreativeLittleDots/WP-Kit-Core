<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class FeedbackController extends Controller {
		
		public function beforeFilter() {
			
			add_filter( 'gform_pre_render_5', array($this, 'form_render'), 12 );
            add_filter( 'gform_pre_validation_5', array($this, 'form_render') );
            add_filter( 'gform_pre_submission_filter_5', array($this, 'form_render') );
            add_filter( 'gform_admin_pre_render_5', array($this, 'form_render') );
            
            parent::beforeFilter();
			
		}
		
		public function form_render($form) {
	                    
            foreach ( $form['fields'] as $i => &$field ) {
				
				if( $field->id == 1 ) {
		            
		            $field->choices = array_map(function($event) {
				           
			        	return array('value' => $event->ID, 'text' => $event->post_title); 
			            
		            }, get_user_attended_events_without_feedback());
                    
                }
            
            }
            
            return $form;
            
        }
		
	}