<?php
    
    namespace WPKit\Core;
    
    use Illuminate\Routing\Router as BaseRouter;
    
    class Router extends BaseRouter {
		
		public function map( $path, $callback, $method = 'get' ) {
			
			$methods = $method == '*' ? [
		        'GET',
		        'POST',
		        'PUT',
		        'PATCH',
		        'DELETE'
		    ] : $method;
			
			$methods = array_map( 'strtoupper', is_array( $methods ) ? $methods : array( $methods ) );
			
			return $this->addRoute( $methods, $path, $callback );
			
		}
	    
	}