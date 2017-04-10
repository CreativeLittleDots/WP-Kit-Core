<?php
	
	namespace App\Integrations;
	
	use WPKit\Integrations\Integration;

	class SomePlugin extends Integration {
		
		public function startIntegration( ) {
			
			add_filter( 'some_plugin_array', array($this, 'customise_some_plugin_array') );
			
		}
		
		public function customise_some_plugin_array($array) {
    		
    		$array[] = 'foo';
    		
    		return $array;
    		
		}

	}
	
?>