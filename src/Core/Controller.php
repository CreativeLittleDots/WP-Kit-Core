<?php
    
    namespace WPKit\Core;
    
    use WPKit;
    
    class Controller {
        
        public static $scripts = [];
        public static $scripts_action = 'wp_enqueue_scripts';
        
        public static function init() {
	        
	        $class = get_called_class();
	        
	        if( wpkit()->invoked( $class, current_filter() ) ) {
		        
		        return false;
		        
	        }
			
			add_action( $class::$scripts_action, array($class, 'enqueue_scripts') );

			return new $class();
			
		}
        
        public static function get_scripts() {
            
            $class = get_called_class();
	        
	        return $class::$scripts;
	        
        }
        
        public static function enqueue_scripts() {
    			
			self::_enqueue_scripts( call_user_func( array( get_called_class(), 'get_scripts') ) ); 
			
        }
        
        private static function _enqueue_scripts($scripts) {
			
			foreach($scripts as $script) {
				
				$script = is_array($script) ? $script : ['file' => $script];
				
				if ( $script['file'] = self::get_script_path( $script['file'] ) ) {
						
    				$info = pathinfo( $script['file'] );
    				
    				$extension = ! empty( $info['extension'] ) ? $info['extension'] : ( ! empty( $script['type'] ) ? $script['type'] : false );
    				
					switch( $extension ) {
						
						case 'css' :
						
							$script = array_merge(array(
								'dependencies' => array(),
								'version' => '1.0.0',
								'media' => 'all',
								'enqueue' => true
							), $script, array(
								'handle' => ! empty( $script['handle'] ) ? $script['handle'] : $info['filename']
							));
							
							if( wp_style_is( $script['handle'], 'registered' ) ) {
    							
    							wp_deregister_style( $script['handle'] );
    							
							}
							
							wp_register_style(
								$script['handle'], 
								$script['file'], 
								$script['dependencies'], 
								$script['version'], 
								$script['media']
							);
							
							if( $script['enqueue'] ) {
								
								wp_enqueue_style($script['handle']);
								
							}
						
						break;
						
						default :
						
							$script = array_merge(array(
								'dependencies' => array(),
								'version' => '1.0.0',
								'in_footer' => true,
								'localize' => false,
								'enqueue' => true
							), $script, array(
								'handle' => ! empty( $script['handle'] ) ? $script['handle'] : $info['filename']
							));
							
							if( wp_script_is( $script['handle'], 'registered' ) ) {
    							
    							wp_deregister_script( $script['handle'] );
    							
							}
							
							wp_register_script(
								$script['handle'], 
								$script['file'], 
								$script['dependencies'], 
								$script['version'], 
								$script['in_footer']
							);
							
							if( $script['localize'] ) {
								
								wp_localize_script($script['handle'], $script['localize']['name'], $script['localize']['data']);
								
							}
							
							if( $script['enqueue'] ) {
							
								wp_enqueue_script($script['handle']);
								
							}
						
						break;
						
					}
    				
                }
				
			}
			
		}
		
		private static function get_script_path( $file ) {
    		
    		if( ! filter_var( $file , FILTER_VALIDATE_URL) === false ) {
        		
        		return $file;
        		
            } 
            
            else if( $file = get_asset( $file ) ) {
             
                return $file;
                
            }
            
            return false;
    		
		}
		
		public function render( $view, $vars = array(), $echo = true ) {
			
			$path = str_replace( 'Controller', '', implode( '/', explode( '\\', str_replace( 'App\Controllers\\', '', get_called_class() ) ) ) );
			
			$html = get_component( $path, $view, $vars, $echo );
			
			if( $echo ) {
				
				echo $html;
				
			} else {
				
				return $html;
				
			}
			
		}
        
    }