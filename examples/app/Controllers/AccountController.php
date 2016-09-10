<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class AccountController extends Controller {
		
		public $scripts = [
			[
				'type' => 'js',
				'handle' => 'google-map',
				'file' => 'https://maps.googleapis.com/maps/api/js?key=AIzaSyA5d_hz-1W2e1unw78RmB7k97PCOGCHlj4'
			]
		];
		
		public function getScripts() {
			
			if($postcode = get_user_meta(get_current_user_id(), 'billing_postcode', true)) {
				
				$long_lang = json_decode( file_get_contents( 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDRgQenEbBj0BF-QBdCfVDOPnWGClYX5W8&v=3&address=' . urlencode($postcode) ) );
				
				if($long_lang && count($long_lang->results) > 0) {
				
					$longitude = $long_lang->results[0]->geometry->location->lng;
			
					$latitude = $long_lang->results[0]->geometry->location->lat;
				
				}
				
			}
			
			else {
				
				$exploded_string = explode('@', get_field('google_map', 'option'));
			
				$further_exploded_string = explode(',', $exploded_string[1]);
				
				$latitude = $further_exploded_string[0];
				
				$longitude = $further_exploded_string[1];
				
			}
			
			$this->scripts[] = [
				'file' => 'map.js',
				'localize' => [
					'name' => 'map',
					'data' => [ 
						'lon' => $longitude,
						'lat' => $latitude,
						'icon' => THEME_DIR . '/img/pin.png',
					]
				]
			];
			
			return parent::getScripts();
			
		}
		
	}