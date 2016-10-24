<?php
    
    namespace WPKit\Core;
    
    class Invoker extends Flow {
	    
	    public function invokeByCondition( $callback, $action = 'wp', $condition = true, $priority = 20 ) {
			
			add_action( $action, function() use($callback, $action, $condition, $priority ) {
			
				if( ( is_callable($condition) && $this->app->call($condition) ) || ( ! is_callable($condition) && $condition ) ) {
					
					$this->invoke( $callback, $action, $priority );
				
				}
				
			});
			
		}
		
		public function invoke( $callback, $action = 'wp', $priority = 20 ) {
			
			$callback = $this->getCallback($callback);
			
			$this->routes[] = [
				$callback,
				$action,
				$priority
			];
			
			add_action( $action, $callback, $priority );
			
		}
		
		public function isInvoked( $callback, $action = 'wp', $priority = 20 ) {
			
			$callback = $this->getCallback($callback);
			
			$index = array_search(array_merge(array(
				'callback' => '',
				'action' => 'wp',
				'priority' => 20
			), compact(
				'callback',
				'action',
				'priority'
			)), $this->routes);
			
			return $index > -1 ? (object) $this->routes[$index] : false;
			
		}
		
		public function uninvoke( $callback, $action = 'wp', $priority = 20 ) {
			
			$callback = $this->getCallback($callback);
			
			$index = array_search(array_merge(array(
				'callback' => '',
				'action' => 'wp',
				'priority' => 20
			), compact(
				'callback',
				'action',
				'priority'
			)), $this->routes);
			
			if( $index > -1 ) {
				
				unset( $this->routes[$index] );
				
			}
			
			remove_action( $action, $method, $priority );
			
		}
	    
	}
