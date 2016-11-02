<?php
    
    namespace WPKit\Core;
    
    use ReflectionClass;
    
    class Controller extends Singleton {
	    
	    /**
	     * @var \WPKit\Application
	     */
	    protected $app;
	    
	    /**
	     * @var \WPKit\Core\Http
	     */
	    protected $http;
        
        protected $scripts = [];
        protected $scripts_action = 'wp_enqueue_scripts';
        protected $scripts_priority = 10;
	   
		public function __construct(Application $app, Http $http) {
			
			$this->app = $app;
		    $this->http = $http;
			
			// when using REST api OPTIONS needs to return successful
			
			if ( 'OPTIONS' == $this->http->method() ) {
					    
		        wp_send_json_success( 'authorised' );
		        
		    }
			
		}
        
        public function beforeFilter() {
			
			add_action( $this->scripts_action, array($this, 'enqueueScripts'), $this->scripts_priority );
			
		}
        
        protected function getScripts() {
	        
	        return $this->scripts;
	        
        }
        
        public function enqueueScripts() {
			
			foreach($this->getScripts() as $script) {
				
				$script = is_array($script) ? $script : ['file' => $script];
				
				if ( $script['file'] = $this->getScriptPath( ! empty( $script['file'] ) ? $script['file'] : '' ) ) {
						
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
		
		private function getScriptPath( $file ) {
    		
    		if( ! filter_var( $file , FILTER_VALIDATE_URL) === false ) {
        		
        		return $file;
        		
            } 
            
            else if( $file = get_asset( $file ) ) {
             
                return $file;
                
            }
            
            return false;
    		
		}
		
		protected function render( $view, $vars = array(), $echo = true ) {
			
			$reflect = new ReflectionClass($this);
			
			$path = str_replace( 'Controller', '', $reflect->getShortName() );
			
			$html = get_element( $path, $view, $vars, $echo );
			
			if( $echo ) {
				
				echo $html;
				
			} else {
				
				return $html;
				
			}
			
		}
		
		protected function renderView( $view, $vars = array(), $echo = true ) {
			
			$html = $this->render( $view, $vars, $echo );
			
			if( $echo ) {
				
				echo $html;
				
			} else {
				
				return $html;
				
			}
			
		}
		
		protected function renderComponent( $view, $vars = array(), $echo = true ) {
			
			$html = $this->render( $view, $vars, $echo );
			
			if( $echo ) {
				
				echo $html;
				
			} else {
				
				return $html;
				
			}
			
		}
        
    }