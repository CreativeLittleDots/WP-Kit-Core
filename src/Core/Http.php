<?php 
	
	namespace WPKit\Core;
;
	use Illuminate\Http\Request;

	class Http extends Request {
	   
		public function header($key = null, $default = null) {
			
			$headers = apache_request_headers();
			
			if( ! empty( $headers[$key] ) ) {
				
				return $headers[$key];
				
			}
			
			return parent::header($key, $default);
			
		}
	   
	}