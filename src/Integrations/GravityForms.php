<?php
	
	namespace WPKit\Integrations;
	
	use WPKit\Core\Integration;
	use GFCommon;
	use GFFormsModel;
	use Timber;

	class GravityForms extends Integration {
		
		public function __construct( $settings ) {
    		
    		$this->settings = is_array($settings) ? array_merge($this->settings, $settings) : array();
			
			add_filter( 'gform_cdata_open', array($this, 'wrap_gform_cdata_open') );
			add_filter( 'gform_cdata_close', array($this, 'wrap_gform_cdata_close') );
		
			add_filter( 'gform_get_form_filter', array($this, 'custom_markup'), 10, 2 );
			
			add_filter( 'gform_form_settings', array($this, 'form_button_settings'), 10, 2);
			add_filter( 'gform_form_settings', array($this, 'form_label_settings'), 10, 2);
			add_filter( 'gform_pre_form_settings_save', array($this, 'save_form_button_settings') );
			add_filter( 'gform_pre_form_settings_save', array($this, 'save_form_label_settings') );
			
			add_filter( 'gform_notification', array($this, 'change_notification_format'), 10, 3);
			
			add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
			add_filter( 'gform_enable_field_label_position_settings', '__return_true' );
            add_action( 'gform_field_appearance_settings', array($this, 'field_label_settings'), 10, 2 );
            add_action( 'gform_editor_js', array($this, 'field_label_settings_script') );
            add_filter( 'gform_field_css_class', array($this, 'field_label_position_class'), 10, 3 );

            add_filter( 'gform_add_field_buttons', array( $this, 'nesting_add_field' ) );
            add_filter( 'gform_field_type_title' , array( $this, 'nesting_title' ), 10, 2 );
            add_action( 'gform_editor_js', array( $this, 'nesting_custom_scripts' ) );
            add_filter( 'gform_field_content', array( $this, 'nesting_display_field' ), 10, 5 );
            
            add_filter( 'gform_field_value_user_first_name', array($this, 'parameter_user_first_name' ) );
            add_filter( 'gform_field_value_user_last_name', array($this, 'parameter_user_last_name' ) );
            add_filter( 'gform_field_value_user_full_name', array($this, 'parameter_user_full_name' ) );
            add_filter( 'gform_field_value_user_email', array($this, 'parameter_user_email' ) );
			
		}
		
		public function wrap_gform_cdata_open( $content = '' ) {
			
        	$content = 'document.addEventListener( "DOMContentLoaded", function() { ';
        	
        	return $content;
        	
        }
        
        public function wrap_gform_cdata_close( $content = '' ) {
	        
        	$content = ' }, false );';
        	
        	return $content;
        	
        }
		
		public function change_notification_format( $notification, $form, $entry ) {
		
			// is_plugin_active is not availble on front end
			if( !is_admin() )
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			// does WP Better Emails exists and activated ?
			if( !is_plugin_active('wp-better-emails/wpbe.php') )
				return $notification;
		
			// change notification format to text from the default html
		    $notification['message_format'] = "text";
			// disable auto formatting so you don't get double line breaks
			$notification['disableAutoformat'] = true;
		
		    return $notification;
		    
		}
		
		public function form_button_settings($settings, $form) {
			
		    $settings['Form Button'] = array_merge(
		        $settings['Form Button'],
		        array(
    		        'form_button_wrapper_class' => '<tr>
        	            <th><label for="button_wrapper_class">Button Wrapper Class</label></th>
        	            <td><input type="text" value="' . rgar($form, 'button_wrapper_class') . '" name="button_wrapper_class" class="fieldwidth-3"></td>
        	        </tr>',
		            'form_button_class' => '<tr>
        	            <th><label for="button_class">Button Class</label></th>
        	            <td><input type="text" value="' . rgar($form, 'button_class') . '" name="button_class" class="fieldwidth-3"></td>
        	        </tr>',
                    'form_button_icon' => '<tr>
    		            <th><label for="button_icon">Button Icon</label></th>
    		            <td><input type="text" value="' . rgar($form, 'button_icon') . '" name="button_icon" class="fieldwidth-3"></td>
    		        </tr>',
    		        'form_button_icon_class' => '<tr>
    		            <th><label for="button_icon_class">Button Icon Class</label></th>
    		            <td><input type="text" value="' . rgar($form, 'button_icon_class') . '" name="button_icon_class" class="fieldwidth-3"></td>
    		        </tr>'
                )
            );
		
		    return $settings;
		    
		}
		
		public function form_label_settings($settings, $form) {
			
		    $settings['Form Layout'] = array_merge(
		        $settings['Form Layout'],
		        array(
		            'form_label_position' => '<tr>
        	            <th>
        	                <label for="form_label_position">Label Position</label>
                        </th>
        	            <td>
        	                <select id="form_label_position" name="form_label_position">
        	                    <option value="above" '  . selected(rgar($form, 'labelPosition'), 'above', false) . '>Above inputs</option>
        						<option value="below" ' . selected(rgar($form, 'labelPosition'), 'below', false) . '>Below inputs</option>
        					</select>
        				</td>
        	        </tr>'
                )
            );
		
		    return $settings;
		    
		}
		
		public function save_form_button_settings($form) {
			
			$form['button_wrapper_class'] = rgpost('button_wrapper_class');
		    $form['button_class'] = rgpost('button_class');
            $form['button_icon'] = rgpost('button_icon');
            $form['button_icon_class'] = rgpost('button_icon_class');
		    
		    return $form;
		    
		}
		
		public function save_form_label_settings($form) {
			
		    $form['labelPosition'] = rgpost('form_label_position');
		    
		    return $form;
		    
		}
		
		public function field_label_settings($position, $form_id) {
    		
    		if ( $position == 50 ) {
        		
        		echo '
        		<li class="label_placement_setting label_position_setting field_setting">
                    <label for="field_admin_label">' . __("Field Label Position", "gravityforms") . '</label>
                    <select id="field_label_position" onChange="SetFieldProperty(\'field_label_position\', this.value);">
						<option value="">Use Form Setting</option>
						<option value="below">Below inputs</option>
						<option value="above">Above inputs</option>
					</select>
                </li>
                <li class="label_placement_setting label_class_setting field_setting">
                    <label for="field_admin_label">' . __("Field Label Class", "gravityforms") . '</label>
                    <input type="text" id="field_label_class" onChange="SetFieldProperty(\'field_label_class\', this.value);" />
                </li>';
                
            }
    		
		}
		
		public function field_label_settings_script() {
    		
            ?>
            <script type='text/javascript'>
                //adding setting to fields of type "text"
                fieldSettings["select"] += ", .label_position_setting";
                fieldSettings["text"] += ", .label_class_setting";
        
                //binding to the load field settings event to initialize the checkbox
                jQuery(document).bind("gform_load_field_settings", function(event, field, form){
                    jQuery("#field_label_position").val(field["field_label_position"]);
                    jQuery("#field_label_class").val(field["field_label_class"]);
                });
            </script>
            <?php
    		
		}
		
		public function field_label_position_class($classes, $field, $form) {
    		
    		$label_position_class = 'field_label_above';
    		
    		$form = (object) $form;
    		
    		if( ( property_exists($field, 'field_label_position') && $field->field_label_position && $field->field_label_position === 'below' ) || ( ( ! property_exists($field, 'field_label_position') || ! $field->field_label_position ) && property_exists($form, 'labelPosition') && $form->labelPosition === 'below' ) ) {
        		
        		$label_position_class = 'field_label_below';
        		
    		}
    		
    		$classes .= ' ' . $label_position_class;
    		
    		return $classes;
    		
		}
		
		public function custom_markup($html, $form) {
			
			if( is_admin() ) {
        		return $html;
    		}
			
			libxml_use_internal_errors(true);
			
			$find = apply_filters('wpkit_gforms_find', ! empty( $this->settings['find'] ) ? $this->settings['find'] : array());
			
			$replace = apply_filters('wpkit_gforms_replace', ! empty( $this->settings['replace'] ) ? $this->settings['replace'] : array());
			
			$timber = apply_filters('wpkit_gforms_timber', ! empty( $this->settings['timber'] ) ? $this->settings['timber'] : array());
			
			$gform = qp(str_replace($find, $replace, $html));  
			 
			$gform->find('.gfield')->each(function($i) use (&$gform, $form) {
				
    			$field = $gform->find('.gfield')->eq($i);
    			
    			if($field->hasClass('field_label_below')) {
	    			
        			$field->children('.gfield_label')->first()->insertAfter($field->children('.ginput_container')->last()->children('input, select, textarea')->last())->remove();
        			
    			} else {
	    			
        			$children = $field->children('.ginput_container')->first()->children('input, textarea, select')->first();
        			
        			if($children->length) {
	        			
        			    $field->children('.gfield_label')->first()->insertBefore($children)->remove();
        			    
                    }
                    
    			}

			});
			
			return Timber::compile_string($gform->html(), $timber);
			
		}
		
		
		/**
		 * Create a new fields group in the Gravity Forms forms editor and add our nesting 'fields' to it.
		 */
		
		public function nesting_add_field( $field_groups ) {
			
			// add begin nesting button
			
			$nesting_begin_field = array(
				
				'class'		=> 'button',
				'value'		=> __( 'Begin Nesting', 'gravity-forms-integration' ),
				'data-type'	=> 'NestBegin',
				'onclick'	=> 'StartAddField( \'NestBegin\' );'
				
			);
			
			// add end nesting button
			
			$nesting_end_field = array(
				
				'class'		=> 'button',
				'value'		=> __( 'End Nesting', 'gravity-forms-integration' ),
				'data-type'	=> 'NestEnd',
				'onclick'	=> 'StartAddField( \'NestEnd\' );'
				
			);

			foreach ( $field_groups as &$group ) :
				
				$raak_fields_active = false;

				if ( $group["name"] === "nesting" ) :
					
					$raak_fields_active = true;
					
					$group["fields"][] = $nesting_begin_field;
					$group["fields"][] = $nesting_end_field;
					
				endif;

			endforeach;

			if ( !$raak_fields_active ) :
				
				$field_groups[] = array(
					
					'name'		=> 'nesting',
					'label'		=> __( 'Nesting', 'gravity-forms-integration' ),
					'fields'	=> array( $nesting_begin_field, $nesting_end_field )
					
				);
				
			endif;

			return $field_groups;
			
		}
		
		
		/**
		 * Add title to nesting, displayed in Gravity Forms forms editor
		 */
		
		public function nesting_title( $title, $field_type ) {
			
			if ( $field_type === "NestBegin" ) :
				
				return __( 'Nest Begin', 'gravity-forms-integration' );
				
			elseif ( $field_type === "NestEnd" ) :
			
				return __( 'Nest End', 'gravity-forms-integration' );
				
			else :
			
				return __( 'Unknown', 'gravity-forms-integration' );
				
			endif;
			
		}
		
		
		/**
		 * JavaSript to add field options to nesting fields in the Gravity forms editor
		 */
		
		public function nesting_custom_scripts() {
			
			wp_register_script( 'gform-nesting', get_asset('admin/jquery.gform.nesting.js'), array('jquery'), '1.0.0', true );
			
			wp_localize_script( 'gform-nesting', 'rg_delete_field', array(
				'nonce' => wp_create_nonce( 'rg_delete_field' )
			));
			
			wp_enqueue_script( 'gform-nesting' );
			
		}

		public function nesting_display_field( $content, $field, $value, $lead_id, $form_id) {
			
			if ( ( ! is_admin() ) && ( $field['type'] == 'NestBegin') ) {
				
				$content = '<ul><li>';

			} elseif ( ( !is_admin() ) && ( $field['type'] == 'NestEnd' ) ) {
				
				$content = '</li></ul>';
				
			}

			return $content;
			
		}
		
		public function parameter_user_first_name() {
    		
    		return wp_get_current_user()->user_firstname;
    		
		}
		
		public function parameter_user_last_name() {
    		
    		return wp_get_current_user()->user_lastname;
    		
		}
		
		public function parameter_user_full_name() {
    		
    		return wp_get_current_user()->user_firstname . ( wp_get_current_user()->user_lastname ? ' ' . wp_get_current_user()->user_lastname : '' );
    		
		}
		
		public function parameter_user_email() {
    		
    		return wp_get_current_user()->user_email;
    		
		}

	}
	
?>