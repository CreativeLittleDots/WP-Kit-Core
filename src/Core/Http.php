<?php 
	
	namespace WPKit\Core;

	use Illuminate\Http\Request;

	class Http extends Request {
	   
		public function __construct() {
			
			// when using REST api OPTIONS needs to return successful
			
			if ( 'OPTIONS' == $_SERVER['REQUEST_METHOD'] ) {
					    
		        status_header(200);
		        
		        exit();
		        
		    }
		    
		    parent::__construct();
			
		}
	   
	}