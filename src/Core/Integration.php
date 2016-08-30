<?php
    
    namespace WPKit\Core;

	class Integration {
    	
    	protected static $instance = null;
    	
    	public $settings = array();
    	
    	public static function instance( $settings = array() ) {
			
			$class = get_called_class();
			
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			
			if( ! empty( $settings['file'] ) ) {
			
				if( is_plugin_active( $settings['file'] ) ) {
					
					return new $class( $settings );
					
				} else {
					
					// admin error
					
				}
				
			} else {
				
				wp_die( "Please define the plugin file for WP Kit Integration: $class" );
				
			}
			
		}
    	
    }