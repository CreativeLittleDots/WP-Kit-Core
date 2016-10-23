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
    
    use Illuminate\Contracts\Foundation\Application as ApplicationInterface;
	use vierbergenlars\SemVer\version as SemVersion;
	use Illuminate\Container\Container;

	class Application extends Container implements ApplicationInterface {
		
		/**
	     * The application's version.
	     */
	    const VERSION = '1.2.1';
	    /**
	     * The application's version.
	     *
	     * @var \vierbergenlars\SemVer\version
	     */
	    protected $version;
	    /**
	     * @var \Herbert\Framework\Application
	     */
	    protected static $instance;
	    /**
	     * Indicates if the application has "booted".
	     *
	     * @var bool
	     */
	    protected $booted = false;
	    /**
	     * The array of booting callbacks.
	     *
	     * @var array
	     */
	    protected $bootingCallbacks = array();
	    /**
	     * The array of booted callbacks.
	     *
	     * @var array
	     */
	    protected $bootedCallbacks = array();
	    /**
	     * The array of terminating callbacks.
	     *
	     * @var array
	     */
	    protected $terminatingCallbacks = array();
	    /**
	     * All of the registered service providers.
	     *
	     * @var array
	     */
	    protected $serviceProviders = array();
	    /**
	     * The names of the loaded service providers.
	     *
	     * @var array
	     */
	    protected $loadedProviders = array();
	    /**
	     * The deferred services and their providers.
	     *
	     * @var array
	     */
	    protected $deferredServices = array();
	    /**
	     * The registered plugins.
	     *
	     * @var array
	     */
	    protected $plugins = [];
	    /**
	     * The mismatched plugins.
	     *
	     * @var array
	     */
	    protected $mismatched = [];
	    /**
	     * The matched plugins.
	     *
	     * @var array
	     */
	    protected $matched = [];
	    /**
	     * The plugin apis.
	     *
	     * @var array
	     */
	    protected $apis = [];
	    /**
	     * The plugin configurations.
	     *
	     * @var array
	     */
	    protected $configurations = [];
	    /**
	     * The view composers.
	     *
	     * @var array
	     */
	    protected $viewComposers = [];
	    /**
	     * The view globals.
	     *
	     * @var array
	     */
	    protected $viewGlobals = [];
	    /**
	     * The built view globals.
	     *
	     * @var array
	     */
	    protected $builtViewGlobals = null;
		/**
	     * The integrations.
	     *
	     * @var array
	     */
		protected $integrations = array();
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
	     * Constructs the application and ensures it's correctly setup.
	     */
	    public function __construct() {
		    
	        static::$instance = $this;
	        
	        $this->version = new SemVersion(self::VERSION);
	        $this->inWp = ( defined( 'WP_USE_THEMES' ) && WP_USE_THEMES ) || ( defined( 'WP_ADMIN' ) && WP_ADMIN );
	        
	        $this->instance('app', $this);
	        $this->instance('Illuminate\Container\Container', $this);
			$this->registerBaseProviders();
	        
	        $this->registerCoreContainerAliases();
	        $this->registerConfiguredProviders();
	        
	    }
	    
	    /**
	     * Register the core aliases.
	     *
	     * @return void
	     */
	    protected function registerCoreContainerAliases() {
		    
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
	            'WPKit\Providers\TwigServiceProvider'
	        ));
	    }

		
		/**
	     *  Added to satisfy interface
	     *
	     *  @return string
	     */
	    public function basePath() {
		    
	        return APP_ROOT;
	        
	    }
	    
	    /**
	     * Get or check the current application environment.
	     *
	     * @param  mixed
	     * @return string
	     */
	    public function environment() {
		    
	        if (func_num_args() > 0)
	        {
	            $patterns = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();
	            foreach ($patterns as $pattern)
	            {
	                if (str_is($pattern, $this['env']))
	                {
	                    return true;
	                }
	            }
	            return false;
	        }
	        return $this['env'];
	        
	    }
	    
	    /**
	     * Determine if the application is currently down for maintenance.
	     *
	     * @todo
	     * @return bool
	     */
	    public function isDownForMaintenance() {
		    
	        return false;
	        
	    }
	    
	    /**
	     * Get the version number of the application.
	     *
	     * @return string
	     */
	    public function version()
	    {
	        return static::VERSION;
	    }
	    
	    /**
		 * Register all of the configured providers.
	     *
	     * @todo
	     * @return void
	     */
	    public function registerConfiguredProviders()
	    {
	        //
	    }
	    
	    /**
	     * Register a service provider with the application.
	     *
	     * @param  \Illuminate\Support\ServiceProvider|string $provider
	     * @param  array                                      $options
	     * @param  bool                                       $force
	     * @return \Illuminate\Support\ServiceProvider
	     */
	    public function register($provider, $options = array(), $force = false) {
		    
	        if ($registered = $this->getProvider($provider) && ! $force)
	        {
	            return $registered;
	        }
	        // If the given "provider" is a string, we will resolve it, passing in the
	        // application instance automatically for the developer. This is simply
	        // a more convenient way of specifying your service provider classes.
	        if (is_string($provider))
	        {
	            $provider = $this->resolveProviderClass($provider);
	        }
	        $provider->register();
	        // Once we have registered the service we will iterate through the options
	        // and set each of them on the application so they will be available on
	        // the actual loading of the service objects and for developer usage.
	        foreach ($options as $key => $value)
	        {
	            $this[$key] = $value;
	        }
	        $this->markAsRegistered($provider);
	        // If the application has already booted, we will call this boot method on
	        // the provider class so it has an opportunity to do its boot logic and
	        // will be ready for any usage by the developer's application logics.
	        if ($this->booted)
	        {
	            $this->bootProvider($provider);
	        }
	        return $provider;
	        
	    }
	    
	    /**
	     * Register a deferred provider and service.
	     *
	     * @param  string $provider
	     * @param  string $service
	     * @return void
	     */
	    public function registerDeferredProvider($provider, $service = null) {
		    
	        // Once the provider that provides the deferred service has been registered we
	        // will remove it from our local list of the deferred services with related
	        // providers so that this container does not try to resolve it out again.
	        if ($service) unset($this->deferredServices[$service]);
	        $this->register($instance = new $provider($this));
	        if ( ! $this->booted)
	        {
	            $this->booting(function() use ($instance)
	            {
	                $this->bootProvider($instance);
	            });
	        }
	        
	    }
	    
	    /**
	     * Get the registered service provider instance if it exists.
	     *
	     * @param  \Illuminate\Support\ServiceProvider|string  $provider
	     * @return \Illuminate\Support\ServiceProvider|null
	     */
	    public function getProvider($provider) {
		    
	        $name = is_string($provider) ? $provider : get_class($provider);
	        return array_first($this->serviceProviders, function($key, $value) use ($name)
	        {
	            return $value instanceof $name;
	        });
	        
	    }
	    
	    /**
	     * Resolve a service provider instance from the class name.
	     *
	     * @param  string  $provider
	     * @return \Illuminate\Support\ServiceProvider
	     */
	    public function resolveProviderClass($provider) {
		    
	        return $this->make($provider, ['app' => $this]);
	        
	    }
	    
	    /**
	     * Mark the given provider as registered.
	     *
	     * @param  \Illuminate\Support\ServiceProvider
	     * @return void
	     */
	    protected function markAsRegistered($provider)
	    {
	        $this->serviceProviders[] = $provider;
	        $this->loadedProviders[get_class($provider)] = true;
	    }
	    
	    /**
	     * Determine if the application has booted.
	     *
	     * @return bool
	     */
	    public function isBooted() {
		    
	        return $this->booted;
	        
	    }
	    
	    /**
	     * Get the path to the cached "compiled.php" file.
	     *
	     * @return string
	     */
	    public function getCachedCompilePath() {
		    
	        return $this->basePath() . '/vendor/compiled.php';
	        
	    }
	    
	    /**
	     * Get the path to the cached services.json file.
	     *
	     * @return string
	     */
	    public function getCachedServicesPath() {
		    
	        return $this->basePath() . '/vendor/services.json';
	        
	    }
	    
	    /**
	     * Register a new "booted" listener.
	     *
	     * @param  mixed  $callback
	     * @return void
	     */
	    public function booted($callback) {
		    
	        $this->bootedCallbacks[] = $callback;
	        
	        if ( $this->isBooted() ) $this->fireAppCallbacks(array($callback));
	        
	    }
	    
	    /**
	     * Register a new boot listener.
	     *
	     * @param  mixed  $callback
	     * @return void
	     */
	    public function booting($callback) {
		    
	        $this->bootingCallbacks[] = $callback;
	        
	    }
		
		public function init() {
			
			_deprecated_function( __METHOD__, '1.3', __CLASS__ . '::boot' );
			
			$this->boot();
			
		}
		
		public function boot() {
			
			$namespace = $this->namespace;
			
			if ( $this->booted ) return;
			
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
					
					$post_type = $namespace . '\PostTypes\\' . basename($post_type, '.php');
					
					$this->make($post_type);
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'TAXONOMIES_DIR' ) && TAXONOMIES_DIR ) {
			
				foreach( glob( TAXONOMIES_DIR . DS . '*.php' ) as $taxonomy ) {
					
					$taxonomy = $namespace .'\Taxonomies\\' . basename($taxonomy, '.php');
					
					$this->make($taxonomy);
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'SHORTCODES_DIR' ) && SHORTCODES_DIR ) {
			
				foreach( glob( SHORTCODES_DIR . DS . '*.php' ) as $shortcode ) {
	    			
	    			$class = $namespace . '\Shortcodes\\' . basename($shortcode, '.php');
	    			
	    			$shortcode = $this->make($class);

					$this->shortcodes[$shortcode->base] = $shortcode;
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'WIDGETS_DIR' ) && WIDGETS_DIR ) {
			
				foreach( glob( WIDGETS_DIR . DS . '*.php' ) as $widget) {
					
					$class = $namespace .'\Widgets\\' . basename($widget, '.php');
					
					add_action( 'widgets_init', function() use ($widget, $class) {
	    				
						register_widget($class);
						
					});
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( $this->inWp ) {
			
				$this->requirePlugins();
				
				$this->addIntegration('timber-library', [
					'file' => 'timber-library/timber.php'
				]);
				
			}
			
		}
		
		public function setNamespace($namespace) {
			
			$this->namespace = $namespace;
			
			return $this;
			
		}
		
		public function requirePlugins($plugins = array()) {
    		
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
					        'default_path'		=> '', // Default absolute path to pre-packaged plugins
					        'parent_menu_slug'	=> 'themes.php', // Default parent menu slug
					        'parent_url_slug'	=> 'themes.php', // Default parent URL slug
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
		
		public function getShortcodes() {
    		
    		return $this->shortcodes;
    		
		}
		
		public function removeShortcodes( $tags ) {
			
			foreach( $tags as $tag ) {
				
				$this->remove_shortcode( $tag );
				
			}
			
		}
		
		public function removeShortcode( $tag ) {
			
			remove_shortcode ( $tag );
			
			unset( $this->shortcodes[ $tag ] );
			
		}
		
		public function addIntegrations($integrations) {
			
			if( $this->inWp ) {
			
				foreach($integrations as $integration => $settings) {
	    			
	    			$this->addIntegration($integration, $settings);
					
				}
				
			} else {
				
				// admin error
				
			}
			
		}
		
		public function add_integrations($integrations) {
			
			_deprecated_function( __METHOD__, '1.3', __CLASS__ . '::addIntegrations' );
			
			return $this->addIntegrations($integrations);
			
		}
		
		public function addIntegration($integration, $settings) {
			
			if( $this->inWp ) {
				
				$namespace = $this->namespace;
			
				if( ! $this->hasIntegration( $integration ) ) {
				
					$core_integration_class = 'WPKit\Integrations\\' . inflector()->camelize($integration);
			    		
		    		$integration_class = $namespace . '\Integrations\\' . inflector()->camelize($integration);
		    		
		    		if( class_exists( $core_integration_class ) ) {
		        		
		        		$this->integrations[$integration] = $this->make($core_integration_class, compact('settings'));
		        		
		    		} else if( class_exists( $integration_class ) ) {
		        		
		        		$this->integrations[$integration] = $this->make($integration_class, compact('settings'));
		        		
		    		}
		    	
				}
				
			} else {
				
				// admin error
				
			}
			
		}
		
		public function add_integration($integration, $settings) {
			
			_deprecated_function( __METHOD__, '1.3', __CLASS__ . '::addIntegration' );
			
			return $this->addIntegration($integrations);
			
		}
		
		public function hasIntegration($integration) {
			
			if( $this->inWp ) {
				
    			return array_key_exists($integration, $this->integrations) && $this->integrations[$integration];
    			
    		} else {
	    		
	    		// admin error
	    		
    		}
    		
		}
		
		public function ajax( $ajax, $fn, $public = true, $priority = 10 ) {
			
			if( is_string($ajax) && is_callable($fn) ) {
    					
				if( $public ) {
					
					add_action( 'wp_ajax_nopriv_' . $ajax, $fn, $priority );
					
				}
				
				add_action( 'wp_ajax_' . $ajax, $fn, $priority );
				
			}
			
		}
		
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