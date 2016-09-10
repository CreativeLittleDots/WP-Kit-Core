<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class ContactController extends Controller {
		
		public $scripts = [
			[
				'type' => 'js',
				'handle' => 'google-map',
				'file' => 'https://maps.googleapis.com/maps/api/js?key=AIzaSyA5d_hz-1W2e1unw78RmB7k97PCOGCHlj4'
			]
		];
		
		public function getScripts() {
			
			$exploded_string = explode('@', get_field('google_map', 'option'));
			
			$further_exploded_string = explode(',', $exploded_string[1]);
			
			$latitude = $further_exploded_string[0];
			
			$longitude = $further_exploded_string[1];
			
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