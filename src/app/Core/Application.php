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
    
    use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
    use Illuminate\Config\Repository as Config;
    use Illuminate\Support\Facades\Facade;
    use PDO;

	class Application extends BaseApplication {
		
		/**
	     * The application's version.
	     */
	    const VERSION = '1.5.6';

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
	     * Should be auto intgegrate Timber
	     *
	     * @var boolean
	     */
		public $autoTimber = true;
	    
	    /**
	     * Register the basic bindings into the container.
	     *
	     * @return void
	     */
	    protected function registerBaseBindings()
	    {
		    $this->singleton('config', function () {
			    global $wpdb;
	            return new Config([
		            'app' => [
		                'providers' => [          
			                'WPKit\Providers\EloquentServiceProvider',
			                'WPKit\Providers\TwigServiceProvider',
			                'WPKit\Providers\NotificationServiceProvider'
		                ]
		            ],
		            'session' => [
			            'path' => '/',
			            'domain' => get_site_url(),
			            'secure' => is_ssl(),
			            'driver' => 'file',
			            'lifetime' => 120,
			            'files' => $this->bootstrapPath('sessions'),
		            ],
		            'database' => [

					    /*
					    |--------------------------------------------------------------------------
					    | PDO Fetch Style
					    |--------------------------------------------------------------------------
					    |
					    | By default, database results will be returned as instances of the PHP
					    | stdClass object; however, you may desire to retrieve records in an
					    | array format for simplicity. Here you can tweak the fetch style.
					    |
					    */
					
					    'fetch' => PDO::FETCH_OBJ,
					
					    /*
					    |--------------------------------------------------------------------------
					    | Default Database Connection Name
					    |--------------------------------------------------------------------------
					    |
					    | Here you may specify which of the database connections below you wish
					    | to use as your default connection for all database work. Of course
					    | you may use many connections at once using the Database library.
					    |
					    */
					
					    'default' => 'mysql',
					
					    /*
					    |--------------------------------------------------------------------------
					    | Database Connections
					    |--------------------------------------------------------------------------
					    |
					    | Here are each of the database connections setup for your application.
					    | Of course, examples of configuring each database platform that is
					    | supported by Laravel is shown below to make development simple.
					    |
					    |
					    | All database work in Laravel is done through the PHP PDO facilities
					    | so make sure you have the driver for your particular database of
					    | choice installed on your machine before you begin development.
					    |
					    */
					
					    'connections' => [
					
					        'mysql' => [
					            'driver' => 'mysql',
					            'host' => DB_HOST,
					            'database' => DB_NAME,
					            'username' => DB_USER,
					            'password' => DB_PASSWORD,
					            'charset' => DB_CHARSET,
					            'collation' => DB_COLLATE ?: $wpdb->collate,
					            'prefix' => $wpdb->prefix,
					            'strict' => false,
					            'engine' => null,
					        ],
					
					    ],
					
					    /*
					    |--------------------------------------------------------------------------
					    | Migration Repository Table
					    |--------------------------------------------------------------------------
					    |
					    | This table keeps track of all the migrations that have already run for
					    | your application. Using this information, we can determine which of
					    | the migrations on disk haven't actually been run in the database.
					    |
					    */
					
					    'migrations' => 'migrations'
					
					]
		        ]);
	        });
		    parent::registerBaseBindings();
		    $this->inWp = ( defined( 'WPINC' ) && WPINC );
	        $this->basePath = APP_ROOT;
	        $this->bootedCallbacks = [
		        [$this, 'bootServices']
	        ];
	    }
	    
	    /**
	     * Register the core aliases.
	     *
	     * @return void
	     */
	    public function registerCoreContainerAliases() {
		    
	        $aliases = [
	            'app'                  => [\Illuminate\Foundation\Application::class, \Illuminate\Contracts\Container\Container::class, \Illuminate\Contracts\Foundation\Application::class],
	            'auth'                 => [\Illuminate\Auth\AuthManager::class, \Illuminate\Contracts\Auth\Factory::class],
				'auth.driver'          => [\Illuminate\Contracts\Auth\Guard::class]
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
	    public static function getInstance($params = null) {
		    
		    
	        if (is_null(static::$instance))
	        {
	            static::$instance = new static($params);
	        }
	        return static::$instance;
	        
	    }
	    
	    /**
	     * Register the base providers.
	     *
	     * @return void
	     */
	    protected function registerBaseServiceProviders()
	    {
		    
		    Facade::setFacadeApplication($this);
		    
		    $this->register($this->resolveProvider(
	            'Illuminate\Events\EventServiceProvider'
	        ));
	        $this->register($this->resolveProvider(
	            'WPKit\Providers\WPKitServiceProvider'
	        ));
	        $this->register($this->resolveProvider(
	        	'Illuminate\Database\DatabaseServiceProvider'
	        ));
	        $this->register($this->resolveProvider(
	            'WPKit\Providers\HttpServiceProvider'
	        ));
	        $this->register($this->resolveProvider(
	            'WPKit\Providers\RoutingServiceProvider'
	        ));
	        $this->register($this->resolveProvider(
	            'Illuminate\Cache\CacheServiceProvider'
	        ));
	        $this->register($this->resolveProvider(
	            'WPKit\Providers\StoreServiceProvider'
	        ));
	        $this->register($this->resolveProvider(
	            'Illuminate\Session\SessionServiceProvider'
	        ));
	        $this->register($this->resolveProvider(
	            'Illuminate\Filesystem\FilesystemServiceProvider'
	        ));
	        $this->register($this->resolveProvider(
	            'Illuminate\Cookie\CookieServiceProvider'
	        ));
	        $this->register($this->resolveProvider(
	            'WPKit\Providers\HashServiceProvider'
	        ));
	        $this->register($this->resolveProvider(
	            'WPKit\Providers\AuthServiceProvider'
	        ));
	        	        
	        
	    }
	    
	    /**
	     * Get the path to the bootstrap directory.
	     *
	     * @return string
	     */
	    public function bootstrapPath($path = '')
	    {
	        return $this->basePath.DIRECTORY_SEPARATOR.'config'.($path ? DIRECTORY_SEPARATOR.$path : $path);
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
					
					$post_type = $this->getNamespace() . 'PostTypes\\' . basename($post_type, '.php');
					
					$this->make($post_type);
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'TAXONOMIES_DIR' ) && TAXONOMIES_DIR ) {
			
				foreach( glob( TAXONOMIES_DIR . DS . '*.php' ) as $taxonomy ) {
					
					$taxonomy = $this->getNamespace() . 'Taxonomies\\' . basename($taxonomy, '.php');
					
					$this->make($taxonomy);
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'SHORTCODES_DIR' ) && SHORTCODES_DIR ) {
			
				foreach( glob( SHORTCODES_DIR . DS . '*.php' ) as $shortcode ) {
	    			
	    			$class = $this->getNamespace() . 'Shortcodes\\' . basename($shortcode, '.php');
	    			
	    			$shortcode = $this->make($class);
	    			
	    			$this->addShortcode($shortcode->base, $shortcode);
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'WIDGETS_DIR' ) && WIDGETS_DIR ) {
			
				foreach( glob( WIDGETS_DIR . DS . '*.php' ) as $widget) {
					
					$class = $this->getNamespace() . 'Widgets\\' . basename($widget, '.php');
					
					add_action( 'widgets_init', function() use ($widget, $class) {
	    				
						register_widget($class);
						
					});
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( $this->inWp() ) {
			
				$this->requirePlugins();
				
				if( $this->autoTimber ) {
					
					$this->addIntegration('timber-library', [
						'file' => 'timber-library/timber.php'
					]);
					
				}
				
				add_action( 'init', function() {
					
					do_action( 'wpkit_init' );
					
				} );
				
			}
			
			$this->instance(\Illuminate\Database\Connection::class, $this->app->make( 'db.connection' ) );
			
			if( php_sapi_name() !== 'cli' ) {
			
				$this->make(HttpKernelContract::class)->bootstrap();
				
			}

		}
		
		public function send() {
			
			$response = $this->handle( $this['http'] );
			
			if( $response ) {
			
				$response->send();
			
				$this[HttpKernelContract::class]->terminate($this['http'], $response);
				
			}
			
		}
				
		/**
		 * Set namespace of App
	     *
	     * @return \WPKit\Core\Application
	     */
		public function setNamespace($namespace) {
						
			return $this;
			
		}
		
		/**
		 * Get controller name
	     *
	     * @return string
	     */
		public function getControllerName($controller) {
			
			return $this->getNamespace() . "Controllers\\$controller";
			
		}
		
		/**
	     * Prepend the last group namespace onto the use clause.
	     *
	     * @param  string  $class
	     * @return string
	     */
	    public function prependNamespace($class)
	    {
	       	$class = \Illuminate\Support\Str::parseCallback( $class, 'dispatch' );
            $class[0] = stripos( $class[0], '\\' ) === 0 ? $class[0] : $this->getControllerName( $class[0] );
            $class = implode( '@', $class );
            
            return $class;
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
	     * Save a Shortcode
	     *
	     * @return array
	     */
		public function addShortcode($tag, $shortcode) {
			
			$shortcodes = $this->getShortcodes();
			
			$shortcodes[$tag] = $shortcode;
			
			$this->make('store')->set('shortcodes', $shortcodes);
			
		}
		
		/**
	     * Get all loaded shortcodes.
	     *
	     * @return array
	     */
		public function getShortcodes() {
    		
    		return $this->make('store')->get('shortcodes');
    		
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
			    		
			    		$integration = $this->register( $this->resolveProvider($class) );
			    		
			    		if( $integration instanceof \WPKit\Integrations\Integration ) {
			    		
			    			$integration->startIntegration($settings);
			    			
			    		}
		        		
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
