<?php
    
    namespace WPKit\Integrations;
    
    use WPKit\Core\Integration;

	class AdvancedCustomFieldsPro extends Integration {
    	
    	public function startIntegration( $settings ) {
        	
        	if( function_exists('acf_add_options_page') && ! empty(  $settings['options_args'] ) ) {
	        	
	        	if( ! defined( 'ACF_CONFIG_DIR' ) ) {
		        	
		        	define( 'ACF_CONFIG_DIR', CONFIG_DIR . DS . 'acf' . DS );
		        	
	        	}
				
				$settings['options_args']['icon_url'] = get_asset($settings['options_args']['icon_url']);
	
				acf_add_options_page( $settings['options_args'] );
				
				add_filter('acf/settings/save_json', function($path) {
				   
				    // return
				    return ACF_CONFIG_DIR;
					
				} );
				
				add_filter('acf/settings/load_json',  function($paths) {
				
					// update path
				    $paths[] = ACF_CONFIG_DIR;
				   
				    // return
				    return $paths;
					
				} );
				
			}
            
        }
        
       
        
    }