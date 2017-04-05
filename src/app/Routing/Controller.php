<?php
    
    namespace WPKit\Routing;
    
    use Illuminate\Container\Container as Application;
    use Illuminate\Bus\Queueable;
    use Illuminate\Http\Request;
	use Illuminate\Routing\Controller as BaseController;
	use Illuminate\Validation\ValidatesWhenResolvedTrait;
	use Illuminate\Auth\Access\HandlesAuthorization;
	use Illuminate\Support\Facades\Auth;
    
    class Controller extends BaseController {
	    
	    use HandlesAuthorization, Queueable, ValidatesWhenResolvedTrait;
	    
	    /**
	     * @var \WPKit\Application
	     */
	    protected $app;
	    
	    /**
	     * @var \Illuminate\Http\Request
	     */
	    protected $http;
	    
	    /**
	     * @var Static
	     */
	    public static $instance = null;
        
        /**
	     * @var array
	     */
        protected $scripts = [];
        
        /**
	     * @var string
	     */
        protected $scripts_action = 'wp_enqueue_scripts';
        
        /**
	     * @var int
	     */
        protected $scripts_priority = 10;
        
        /**
	     * Instance function to return only once instance of the controller
	     *
	     * @return \WPKit\Core\Controller
	     */
        public function instance( $app ) {
	        
	        $class = get_called_class();
	        
	        if( empty( static::$instance ) ) {
		        
		        static::$instance = $app->make($class, func_get_args()); 
		        
	        }
	        
	        return static::$instance;
	        
        }
		
		/**
	     * Controller constructor
	     *
	     * @param  \WPKit\Core\Application  $app
	     * @return void
	     */
		public function __construct(Application $app, Request $http) {
			
			$this->app = $app;
			$this->http = $http;
			
		}
        
        /**
	     * Before filter method used before every action
	     *
	     * @return void
	     */
        protected function beforeFilter() {
			
			add_action( $this->scripts_action, array($this, 'enqueueScripts'), $this->scripts_priority );
			
		}
		
		/**
	     * Default controller action should the controller be invoked
	     *
	     * @return void
	     */
		public function dispatch() {
			
		}
		
		/**
	     * Execute an action on the controller.
	     *
	     * @param  string  $method
	     * @param  array   $parameters
	     * @return \Symfony\Component\HttpFoundation\Response
	     */
	    public function callAction($method, $parameters) {
		    
		    call_user_func_array([$this, 'beforeFilter'], $parameters);
		    
	        return call_user_func_array([$this, $method], $parameters);
	        
	    }
        
        /**
	     * Get scripts for controller
	     *
	     * @return array
	     */
        protected function getScripts() {
	        
	        return $this->scripts;
	        
        }
        
        /**
	     * Enqueue scripts for controller
	     *
	     * @return void
	     */
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
			
			$path = str_replace( 'Controller', '', implode( '/', explode( '\\', str_replace( $this->app->getNamespace() . '\Controllers\\', '', get_called_class() ) ) ) );
			
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