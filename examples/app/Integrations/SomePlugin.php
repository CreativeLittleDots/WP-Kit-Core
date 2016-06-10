<?php
	
	namespace App\Integrations;
	
	use WPKit\Framework\Classes\Integration;

	class SomePlugin extends Integration {
		
		public function __construct( $settings ) {
    		
    		$this->settings = is_array($settings) ? array_merge($this->settings, $settings) : array();
			
			add_filter( 'some_plugin_array', array($this, 'customise_some_plugin_array') );
			
		}
		
		public function customise_some_plugin_array($array) {
    		
    		$array[] = 'foo';
    		
    		return $array;
    		
		}

	}
	
?>