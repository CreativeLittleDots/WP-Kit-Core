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
     
    namespace WPKit;
     
	 // Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    use WPKit\Core\Singleton;
    use WPKit\Core\Auth;
    use WPKit\Core\Cache;
    use WPKit\Core\Invoker;

	class Application extends Singleton {
		
		protected static $integrations = array();
		
		protected static $shortcodes = array();
		
		protected static $plugins = array( 
			array(
	            'name'			    => 'Timber', // The plugin name
	            'slug'			    => 'timber-library', // The plugin slug (typically the folder name)
	            'version'			=> '1.0.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented,
	            'external_url'      => 'https://wordpress.org/plugins/timber-library/',
			)
		);
		
		public static function make($facade = null, $args = array()) {
			
			$class = null;
			
			$facade = inflector()->camelize($facade);
			
			switch($facade) {
				
				default:
				
					if( stripos($facade, 'Controller') > 0 ) {
						
						if( class_exists( $className = 'App\Controllers\\' . $facade ) ) {
							
							$class = $className::instance($args);
							
						}
						
					} else if( class_exists( $className = "WPKit\Core\\$facade") ) {
						
						$class = $className::instance($args);
						
					} else if ( class_exists( $facade ) ) {
						
						$class = $facade::instance($args);
						
					}
					
				break;
				
			}
			
			return $class;
			
		}
		
		public function __construct() {
			
			require_once( 'defines.php' );
			
			if( ! defined( 'INFLECTOR_DEFAULT_LOCALE' ) ) {
    
		    	define( 'INFLECTOR_DEFAULT_LOCALE', strtolower( DEFAULT_LOCALE ) );
		    	
		    }
		    
		    // other errors if things are missing, like inflector

		}
		
		public static function init() {
			
			$class = get_called_class();
			
			$instance = $class::instance();
			
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
					
					$post_type = 'App\PostTypes\\' . basename($post_type, '.php');
					
					$instance::make($post_type);
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'TAXONOMIES_DIR' ) && TAXONOMIES_DIR ) {
			
				foreach( glob( TAXONOMIES_DIR . DS . '*.php' ) as $taxonomy ) {
					
					$taxonomy = 'App\Taxonomies\\' . basename($taxonomy, '.php');
					
					$instance::make($taxonomy);
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'SHORTCODES_DIR' ) && SHORTCODES_DIR ) {
			
				foreach( glob( SHORTCODES_DIR . DS . '*.php' ) as $shortcode ) {
	    			
	    			$class = 'App\Shortcodes\\' . basename($shortcode, '.php');
	    			
	    			$shortcode = $instance::make($class);

					$instance::$shortcodes[$shortcode->base] = $shortcode;
					
				}
				
			} else {
				
				// admin error
				
			}
			
			if( defined( 'WIDGETS_DIR' ) && WIDGETS_DIR ) {
			
				foreach( glob( WIDGETS_DIR . DS . '*.php' ) as $widget) {
					
					$class = 'App\Widgets\\' . basename($widget, '.php');
					
					add_action( 'widgets_init', function() use ($widget, $class) {
	    				
						register_widget($class);
						
					});
					
				}
				
			} else {
				
				// admin error
				
			}
			
			$instance::require_plugins();
			
			$instance::add_integration('timber-library', [
				'file' => 'timber-library/timber.php'
			]);
			
		}
		
		public static function require_plugins($plugins = array()) {
    		
    		$plugins = array_merge(self::$plugins, $plugins);
    		
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
		
		public static function get_shortcodes() {
    		
    		return self::$shortcodes;
    		
		}
		
		public static function remove_shortcodes( $tags ) {
			
			foreach( $tags as $tag ) {
				
				self::remove_shortcode( $tag );
				
			}
			
		}
		
		public static function remove_shortcode( $tag ) {
			
			remove_shortcode ( $tag );
			
			unset( self::$shortcodes[ $tag ] );
			
		}
		
		public static function add_integrations($integrations) {
			
			foreach($integrations as $integration => $settings) {
    			
    			self::add_integration($integration, $settings);
				
			}
			
		}
		
		public static function add_integration($integration, $settings) {
			
			if( ! self::has_integration( $integration ) ) {
			
				$core_integration_class = 'WPKit\Integrations\\' . inflector()->camelize($integration);
		    		
	    		$integration_class = 'App\Integrations\\' . inflector()->camelize($integration);
	    		
	    		if( class_exists( $core_integration_class ) ) {
	        		
	        		self::$integrations[$integration] = self::make($core_integration_class, $settings);
	        		
	    		} else if( class_exists( $integration_class ) ) {
	        		
	        		self::$integrations[$integration] = self::make($integration_class, $settings);
	        		
	    		}
	    	
			}
			
		}
		
		public static function has_integration($integration) {
    		
    		return array_key_exists($integration, self::$integrations) && self::$integrations[$integration];
    		
		}
		
		public static function ajax( $ajax, $fn, $public = true, $priority = 10 ) {
			
			if( is_string($ajax) && is_callable($fn) ) {
    					
				if( $public ) {
					
					add_action( 'wp_ajax_nopriv_' . $ajax, $fn, $priority );
					
				}
				
				add_action( 'wp_ajax_' . $ajax, $fn, $priority );
				
			}
			
		}
		
		public static function unajax( $ajax, $fn, $public = true, $priority = 10 ) {
			
			if( is_string($ajax) && is_callable($fn) ) {
    					
				if( $public ) {
					
					remove_action( 'wp_ajax_nopriv_' . $ajax, $fn, $priority );
					
				}
				
				remove_action( 'wp_ajax_' . $ajax, $fn, $priority );
				
			}
			
		}
		
	}
	
?>