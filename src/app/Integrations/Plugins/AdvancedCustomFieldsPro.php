<?php
    
    namespace WPKit\Integrations\Plugins;
    
    use WPKit\Integrations\Integration;

	class AdvancedCustomFieldsPro extends Integration {
    	
    	/**
	     * Start the integration
	     *
	     * @var WPKit\Integrations\Plugins\AdvancedCustomFieldsPro
	     */
    	public function startIntegration( $settings = array() ) {
        	
        	if( function_exists('acf_add_options_page') ) {
	        	
	        	if( ! defined( 'ACF_CONFIG_DIR' ) ) {
		        	
		        	define( 'ACF_CONFIG_DIR', CONFIG_DIR . DS . 'acf' . DS );
		        	
	        	}
				
				if( ! empty(  $settings['options_args'] ) ) {
					
					$settings['options_args']['icon_url'] = get_asset( $settings['options_args']['icon_url'] );
	
					acf_add_options_page( $settings['options_args'] );
					
				}
				
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
			
			return $this;
            
        }
        
       
        
    }