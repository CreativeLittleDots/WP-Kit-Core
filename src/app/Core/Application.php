<?php
	
	/**
     * Creative Little WP Kit is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 2 of the License, or
     * any later version.
     * Creative Little WP Kit is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     * GNU General Public License for more details.
     * You should have received a copy of the GNU General Public License
     * along with Creative Little WP Kit. If not, see <http://www.gnu.org/licenses/>.
     *
     * @package     creative-little-wp-kit-core
     * @author      Creative Little Dots
     * @version     1.0.0
     */
     
    namespace WPKit\Core;
     
	 // Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    use WPKit\Foundation\Application as BaseApplication;

	class Application extends BaseApplication {
		
		/**
	     * The application's version.
	     */
	    const VERSION = '1.3';

	    /**
	     * The registered plugins.
	     *
	     * @var array
	     */
	    protected $plugins = [];
		/**
	     * The shortcodes.
	     *
	     * @var array
	     */
		protected $shortcodes = array();
		/**
	     * Are we in WP.
	     *
	     * @var boolean
	     */
		protected $inWp = false;
		/**
	     * Namespace of App.
	     *
	     * @var string
	     */
		protected $namespace = 'App';
	    
	    /**
	     * Register the basic bindings into the container.
	     *
	     * @return void
	     */
	    protected function registerBaseBindings()
	    {
		    parent::registerBaseBindings();
		    $this->inWp = ( defined( 'WPINC' ) && WPINC );
	        $this->basePath = APP_ROOT;
	        $this->bootedCallbacks = [
		        [$this, 'bootServices']
	        ];
	        $this->registerBaseProviders();
	    }
	    
	    /**
	     * Register the core aliases.
	     *
	     * @return void
	     */
	    public function registerCoreContainerAliases() {
		    
	        $aliases = [
	            'app' => [
	                'Illuminate\Foundation\Application',
	                'Illuminate\Contracts\Container\Container',
	                'Illuminate\Contracts\Foundation\Application'
	            ]
	        ];
	        foreach ($aliases as $key => $aliases)
	        {
	            foreach ((array) $aliases as $alias)
	            {
	                $this->alias($key, $alias);
	            }
	        }
	        
	    }
	    
	    /**
	     * Get the global container instance.
	     *
	     * @return static
	     */
	    public static function getInstance() {
		    
	        if (is_null(static::$instance))
	        {
	            static::$instance = new static;
	        }
	        return static::$instance;
	        
	    }
	    
	    /**
	     * Register the base providers.
	     *
	     * @return void
	     */
	    protected function registerBaseProviders()
	    {
	        $this->register($this->resolveProviderClass(
	            'WPKit\Providers\WPKitServiceProvider'
	        ));
	        $this->register($this->resolveProviderClass(
	            'WPKit\Providers\CacheServiceProvider'
	        ));
	        $this->register($this->resolveProviderClass(
	            'WPKit\Providers\TwigServiceProvider'
	        ));
	        $this->register($this->resolveProviderClass(
	            'WPKit\Providers\RoutingServiceProvider'
	        ));
	        $this->register($this->resolveProviderClass(
	            'WPKit\Providers\NotificationServiceProvider'
	        ));
	        
	    }
		
		/**
		 * Boot several services
	     *
	     * @return void
	     */
		public function bootServices() {
			
			
			if( defined( 'FUNCTIONS_DIR' ) && FUNCTIONS_DIR ) {
			
				foreach( glob( FUNCTIONS_DIR . DS . '*.php' ) as $functions ) {
					
					include($functions);
					
				}
				
			}
				
			else {
				
				// admin error
				
			}
			
			if( defined( 'POST_TYPES_DIR' ) && POST_TYPES_DIR ) {
			
				foreach( glob( POST_TYPES_DIR . DS . '*.php' ) as $post_type ) {
					
					$post_type = $this->getNamespace() . '\PostTypes\\' . basename($post_type, '.php');
					
					$this->make($post_type);
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'TAXONOMIES_DIR' ) && TAXONOMIES_DIR ) {
			
				foreach( glob( TAXONOMIES_DIR . DS . '*.php' ) as $taxonomy ) {
					
					$taxonomy = $this->getNamespace() . '\Taxonomies\\' . basename($taxonomy, '.php');
					
					$this->make($taxonomy);
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'SHORTCODES_DIR' ) && SHORTCODES_DIR ) {
			
				foreach( glob( SHORTCODES_DIR . DS . '*.php' ) as $shortcode ) {
	    			
	    			$class = $this->getNamespace() . '\Shortcodes\\' . basename($shortcode, '.php');
	    			
	    			$shortcode = $this->make($class);

					$this->shortcodes[$shortcode->base] = $shortcode;
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'WIDGETS_DIR' ) && WIDGETS_DIR ) {
			
				foreach( glob( WIDGETS_DIR . DS . '*.php' ) as $widget) {
					
					$class = $this->getNamespace() . '\Widgets\\' . basename($widget, '.php');
					
					add_action( 'widgets_init', function() use ($widget, $class) {
	    				
						register_widget($class);
						
					});
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( $this->inWp() ) {
			
				$this->requirePlugins();
				
				$this->addIntegration('timber-library', [
					'file' => 'timber-library/timber.php'
				]);
				
			}
			
		}
		
		/**
		 * Set namespace of App
	     *
	     * @return \WPKit\Core\Application
	     */
		public function setNamespace($namespace) {
			
			$this->namespace = $namespace;
			
			return $this;
			
		}
		
		/**
		 * Get namespace of App
	     *
	     * @return string
	     */
		public function getNamespace() {
			
			return $this->namespace;
			
		}
		
		/**
		 * Get controller name
	     *
	     * @return string
	     */
		public function getControllerName($controller) {
			
			return $this->getNamespace() . "\Controllers\\$controller";
			
		}
		
		/**
		 * Detect if in Wordpress environment
	     *
	     * @return boolean
	     */
		public function inWp() {
			
			return $this->inWp;
			
		}
		
		/**
	     * Get all loaded plugins.
	     *
	     * @return array
	     */
	    public function getPlugins() {
		    
	        return $this->plugins;
	        
	    }
		
		/**
	     * Automatically require plugins
	     *
	     * @return void
	     */
		protected function requirePlugins($plugins = array()) {
    		
    		$plugins = array_merge(array( 
				array(
		            'name'			    => 'Timber', // The plugin name
		            'slug'			    => 'timber-library', // The plugin slug (typically the folder name)
		            'version'			=> '1.0.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented,
		            'external_url'      => 'https://wordpress.org/plugins/timber-library/',
				)
			), $plugins);
    		
    		if( ! empty( $plugins ) && count( $plugins ) > 0 ) {
			
				add_action( 'tgmpa_register', function() use($plugins) {
    				
    				$plugins = array_map(function($plugin) {
        				
        				return array_merge(array(
            				'name'			=> '', // The plugin name
                            'slug'			=> '', // The plugin slug (typically the folder name)
                            'source'			=> '', // The plugin source
                            'required'			=> true, // If false, the plugin is only 'recommended' instead of required
                            'version'			=> '1.0.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                            'force_activation'		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                            'force_deactivation'	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                            'external_url'		=> '', // If set, overrides default API URL and points to an external URL
        				), $plugin);
        				
    				}, $plugins);
					
					foreach($plugins as &$plugin) {
    					
    					if( ! empty( $plugin['source'] ) && file_exists( WPKIT_PLUGINS_DIR_URI . DS . $plugin['source'] ) ) {
    					
        					$plugin['source'] = WPKIT_PLUGINS_DIR_URI . DS . $plugin['source'];
        					
                        }
						
					}
				
					tgmpa( $plugins, 
						array(
					        'domain'		=> 'wpkit', // Text domain - likely want to be the same as your theme.
					        'menu'			=> 'install-required-plugins', // Menu slug
					        'has_notices'		=> true, // Show admin notices or not
					        'is_automatic'		=> false, // Automatically activate plugins after installation or not
					        'message'		=> '', // Message to output right before the plugins table
					        'strings'		=> array(
					            'page_title'			=> __( 'Install Required Plugins', 'wpkit' ),
					            'menu_title'			=> __( 'Install Plugins', 'wpkit' ),
					            'installing'			=> __( 'Installing Plugin: %s', 'wpkit' ), // %1$s = plugin name
					            'oops'				=> __( 'Something went wrong with the plugin API.', 'wpkit' ),
					            'notice_can_install_required'	=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
					            'notice_can_install_recommended'	=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
					            'notice_cannot_install'		=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
					            'notice_can_activate_required'	=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
					            'notice_can_activate_recommended'	=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
					            'notice_cannot_activate'		=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
					            'notice_ask_to_update'		=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
					            'notice_cannot_update'		=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
					            'install_link'			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
					            'activate_link'			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
					            'return'				=> __( 'Return to Required Plugins Installer', 'wpkit' ),
					            'plugin_activated'			=> __( 'Plugin activated successfully.', 'wpkit' ),
					            'complete'				=> __( 'All plugins installed and activated successfully. %s', 'wpkit' ), // %1$s = dashboard link
					            'nag_type'				=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
					        )
					    ) 
					);
				    
				});
				
			}
    		
		}
		
		/**
	     * Get all loaded shortcodes.
	     *
	     * @return array
	     */
		public function getShortcodes() {
    		
    		return $this->shortcodes;
    		
		}
		
		/**
	     * Remove a set of shortcodes
	     *
	     * @return void
	     */
		public function removeShortcodes( $tags ) {
			
			foreach( $tags as $tag ) {
				
				$this->remove_shortcode( $tag );
				
			}
			
		}
		
		/**
	     * Remove a single shortcode
	     *
	     * @return void
	     */
		public function removeShortcode( $tag ) {
			
			remove_shortcode ( $tag );
			
			unset( $this->shortcodes[ $tag ] );
			
		}
		
		/**
	     * Add a set of Integrations
	     *
	     * @return void
	     */
		public function addIntegrations($integrations) {
			
			if( $this->inWp() ) {
			
				foreach($integrations as $integration => $settings) {
	    			
	    			$this->addIntegration($integration, $settings);
					
				}
				
			}
			
		}
		
		/**
	     * Get Integration classname
	     *
	     * @return string
	     */
		private function getIntegrationClass($integration) {
			
			return 'WPKit\Integrations\Plugins\\' . inflector()->camelize($integration);
			
		}
		
		/**
	     * Add a single integration
	     *
	     * @return void
	     */
		public function addIntegration($integration, $settings) {
			
			if( $this->inWp() ) {
			
				if( ! $this->hasIntegration( $integration ) ) {
				
					$class = $this->getIntegrationClass($integration);
		    		
		    		if( class_exists( $class ) ) {
			    		
			    		$property = $class . '\IntegrationSettings';
			    		
			    		$this->register($this->resolveProviderClass($class), [
			    			$property => $settings
			    		]);
		        		
		    		}
		    	
				}
				
			}
			
		}
		
		/**
	     * Check if has integration
	     *
	     * @return boolean
	     */
		public function hasIntegration($integration) {
			
			return $this->getProvider($this->getIntegrationClass($integration));
    		
		}
		
		/**
	     * Add an Ajax callback
	     *
	     * @return void
	     */
		public function ajax( $ajax, $fn, $public = true, $priority = 10 ) {
			
			if( is_string($ajax) && is_callable($fn) ) {
    					
				if( $public ) {
					
					add_action( 'wp_ajax_nopriv_' . $ajax, $fn, $priority );
					
				}
				
				add_action( 'wp_ajax_' . $ajax, $fn, $priority );
				
			}
			
		}
		
		/**
	     * Remove an Ajax callback
	     *
	     * @return void
	     */
		public function unAjax( $ajax, $fn, $public = true, $priority = 10 ) {
			
			if( is_string($ajax) && is_callable($fn) ) {
    					
				if( $public ) {
					
					remove_action( 'wp_ajax_nopriv_' . $ajax, $fn, $priority );
					
				}
				
				remove_action( 'wp_ajax_' . $ajax, $fn, $priority );
				
			}
			
		}
		
	}
	
?>