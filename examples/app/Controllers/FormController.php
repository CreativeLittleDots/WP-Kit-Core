<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class FormController extends Controller {
        
        public function beforeFilter() {
	        
	        add_filter( 'gform_form_settings', array($this, 'form_terms_settings'), 10, 2);
			
			add_filter( 'gform_pre_form_settings_save', array($this, 'save_form_terms_settings') );
			
			add_filter( 'gform_username', array($this, 'clean_username'), 10, 4 );
            
            add_filter( 'gform_field_content', array($this, 'upload_field_setting_button_text'), 10, 3 );
            
            add_action( 'gform_pre_render', array($this, 'display_terms_popup') );
            
            add_filter( 'gform_file_upload_markup', array($this, 'upload_field_upload_markup'), 10, 4 );
			
			add_filter( 'gform_ajax_spinner_url', array($this, 'replace_gforms_spinner') );
			
			add_filter( 'gform_submit_button', array($this, 'form_submit_button'), 10, 2);
			
			add_filter( 'gform_field_css_class', array( $this, 'nesting_custom_class' ), 10, 3 );
			
			add_filter( 'gform_field_content', array( $this, 'nesting_display_field' ), 20, 5 );
			
			add_filter( 'gform_get_form_filter', array($this, 'custom_markup'), 20, 2 );
			
			add_filter( 'gform_init_scripts_footer', '__return_true' );
			
			add_filter( 'gform_validation_message', array($this, 'change_message'), 10, 2);
			
			parent::beforeFilter();
            
        }
		
		public function change_message($message, $form) {
		
			return $this-render('validation-message', compact('message'), false);
		  
		}
		
		public function form_button_settings($settings, $form) {
    		
    		$settings['Form Basics'] = array_merge(
		        $settings['Form Basics'],
		        array(
    		        'form_terms_pdf' => '<tr>
        	            <th><label for="terms_pdf">Terms PDF</label></th>
        	            <td><input type="text" value="' . rgar($form, 'terms_pdf') . '" name="terms_pdf" class="fieldwidth-3"></td>
        	        </tr>',
        	        'form_terms_title' => '<tr>
        	            <th><label for="terms_title">Terms Title</label></th>
        	            <td><input type="text" value="' . rgar($form, 'terms_title') . '" name="terms_title" class="fieldwidth-3"></td>
        	        </tr>'
                )
            );
		
		    return $settings;
		    
		}
		
		public function save_form_button_settings($form) {
			
			$form['terms_pdf'] = rgpost('terms_pdf');
			$form['terms_title'] = rgpost('terms_title');
		    
		    return $form;
		    
		}
		
		public function clean_username($username, $feed, $form, $entry) {
			
			while(4 > strlen($username)) {
	    		
	    		$username = $username . rand(3, 10);
	    		
    		}
    		
    		$cache = $username;
    		
    		while(username_exists($username)) {
        		
        		$username = $cache . rand(3, 10);
        		
    		}    		
    		
    		return $username;
    		
		}
		
		public function upload_field_setting_button_text($field_content, $field, $value) {
    		
    		if ( $field->type === 'fileupload' ) {
        		
        		$field_content = str_replace("'Select files'", "'Upload files'", $field_content);
	    		
	    		$field_content = str_replace('Select files', $field->label, $field_content);
	    		
	    		$label = '{{upload_svg|raw}}<span>' . $field->label . '</span>';
        		
        		$field_content = str_replace($field->label, $label, $field_content);
                
            }
            
            return $field_content;
    		
		}
		
		public function display_terms_popup($form) {
			
			if( ! empty( $form['terms_pdf'] ) ) {
				
				add_action( 'after_app_main', function() use($form) {
					
					echo get_component('elements', 'gform-terms-popup', compact('form'));
					
				});
	    		
	    	}
    		
    		return $form;
    		
		}
		
		public function upload_field_upload_markup($markup, $file, $form_id, $id) {
			
			$file_path = GFFormsModel::get_file_upload_path( $form_id, $file['uploaded_filename'] );
			
			$url = explode('/', $file_path['url']);
			
			array_pop($url);
			
			array_push($url, $file['uploaded_filename']);
			
			$file['url'] = implode('/', $url);
			
			return get_component('twig', 'uploaded-file', compact('file', 'form_id', 'id'));
			
		}
		
		public function replace_gforms_spinner( $src ) {
		        
	        return THEME_DIR . '/images/loader.gif';

	    }
	    
	    public function form_submit_button($button, $form) {
    		
    		$button = str_replace(array('input', 'gform_button button', '/>'), array('button', 'gform_button button radius '.(isset($form['button_class']) ? $form['button_class'] : ''), '>'), $button);
    		
    		if( ! empty( $form['button_icon'] ) ) {
        		$button .= icon($form['button_icon'], ! empty( $form['button_icon_class'] ) ? $form['button_icon_class'] : '', false);
        		$button .= "<span class='button__text'>";
    		} 
            
            $button .= $form['button']['text'];
            
            if( ! empty( $form['button_icon'] ) ) {
                
                $button .= "</span>";
                
            }
            
            return $button;
		    
		}
		
		/**
		 * Displays nesting
		 */
		
		public $nesting_depth = 0;
		
		/**
		 * Add custom classes to nesting fields, controls CSS applied to field
		 */
		
		public function nesting_custom_class($classes, $field, $form) {
			
			if ( $field['type'] === 'NestBegin' ) {
				
				$classes .= ' gform_nesting_begin gform_nesting' . ( $this->nesting_depth > 0 ? '' : ' panel' );
				
			} elseif ($field['type'] === 'NestEnd') {
			
				$classes .= ' gform_nesting_end gform_nesting';
				
			}

			return $classes;
			
		}
		
		public function nesting_display_field( $content, $field, $value, $lead_id, $form_id) {
			
			if ( ( !is_admin() ) && ( $field['type'] == 'NestBegin') ) {
				
				$content = '';
				
				$label = ( isset( $field['label'] ) && trim( $field['label'] ) !== '' ) ? '<' . ( $this->nesting_depth > 0 ? 'h6' : 'h2' ) . ' class="' . ( ! empty( $field['labelClass'] ) ? $field['labelClass'] : 'panel__title' . ( $this->nesting_depth > 0 ? ' strong panel__title--nested' : '' ) ) . '">' . trim( $field['label'] ) . '</' . ( $this->nesting_depth > 0 ? 'h6' : 'h2' ) . '>' : '';
				
				if( $this->nesting_depth > 0 ) {
					
					$content .= '<div class="panel panel--nested">';
					
					$content .= $label;
					
					$content .= '<div class="panel__content">';
					
					$content .= '<ul>';
					
				} else {
					
					$content .= $label;

					$content .= '<ul class="grid grid--nest panel__content">';
					
				}
				
				$content .= '<li>';
				
				$this->nesting_depth++;

			} elseif ( ( !is_admin() ) && ( $field['type'] == 'NestEnd' ) ) {
				
				$content = '';
				
				$content .= '</li>';
				
				$content .= '</ul>';
				
				if( $this->nesting_depth > 1 ) {
				
					$content .= '</div>';
					
					$content .= '</div>';
					
				}
				
				$this->nesting_depth--;
				
			}

			return $content;
			
		}
		
		public function custom_markup($html, $form) {
			
			$gform = qp($html);  
			 
			$gform->find('.gfield')->each(function($i) use (&$gform, $form) {
				
				$field = $gform->find('.gfield')->eq($i);
    			
    			if( $field->children('.ginput_container')->first()->hasClass('panel') ) {
	    			
        			$field->children('.ginput_container')->first()->children('.gfield_label')->first()->addClass('panel__title strong panel__title--nested'); // make this related to label class
        			
    			}
    			
			});
			
			$gform->find('.panel__content .ginput_container_textarea.panel')->addClass('panel--nested');
			
			return $gform->html();
			
		}
		
	}