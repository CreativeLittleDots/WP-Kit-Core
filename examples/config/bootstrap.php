<?php
	
	// if we don't have WPKit at this point we probably should die
	    
	if( ! class_exists('WPKit\\Application') ) {
	
	    wp_die('Creative Little WP Kit Core is not installed, try running composer', 'Dependancy Error');
	    
	}
	
	// initialise WPKit to invoke classes etc.

	wpkit()->init();
	
	// define some integrations, both WPKit Core & WPKit
	
	wpkit()->add_integrations(array(
		'advanced-custom-fields-pro' => array(
			'file' => 'advanced-custom-fields-pro/acf.php',
			'options_args' =>  array(
				'page_title' 	=> 'WP Kit Settings',
				'menu_title'	=> 'WP Kit Settings',
				'menu_slug' 	=> 'WPKit-settings',
				'capability'	=> 'edit_posts',
				'icon_url'		=> 'favicon.png',
				'redirect'		=> false
			)
		),
		'gravity_forms' => array(
			'file' => 'gravityforms/gravityforms.php',
			'find' => array(
				'gform_footer',
				'gform_body',
				'gform_fields',
				'<ul',
				'<li',
				'</ul',
				'</li',
				'gform_fields',
				'span', 
				'gfield_label', 
				'<textarea', 
				'</textarea>', 
				'gform_confirmation_wrapper'
			),
			'replace' => array(
				'gform_footer columns',
				'gform_body columns',
				'gform_fields row',
				'<div',
				'<div',
				'</div',
				'</div',
				'gform_fields',
				'p',
				'gfield_label hide',
				'<span><textarea',
				'</textarea></span>',
				'gform_confirmation_wrapper text-center'
			)
		),
		'js_composer' => array(
			'file' => 'js_composer/js_composer.php',
			'support' => array(
				'vc_row',
				'vc_row_inner',
				'vc_column',
				'vc_column_inner',
				'vc_column_text',
				'vc_single_image',
				'vc_tta_accordion',
				'vc_tta_section'
			)
		)
	));
	
	// now require some plugins, make sure the zip files are in your plugins directory, or set 'external_url'
	
	wpkit()->require_plugins(array(
	    array(
	        'name'			=> 'Visual Composer', // The plugin name
	        'slug'			=> 'js_composer', // The plugin slug (typically the folder name)
	        'source'			=> 'js_composer.zip', // The plugin source
		),
		array(
	        'name'			=> 'Advanced Custom Fields Pro', // The plugin name
	        'slug'			=> 'advanced-custom-fields-pro', // The plugin slug (typically the folder name)
	        'source'			=> 'advanced-custom-fields-pro.zip', // The plugin source
	        'version'			=> '4.3.9', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
		)
	));