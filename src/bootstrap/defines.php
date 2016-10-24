<?php
	
	if ( ! defined('DS') ) {
		
	    define( 'DS', DIRECTORY_SEPARATOR );
	    
	}
	
	if( ! defined( 'THEME_DIR' ) ) {
	
		define( 'THEME_DIR', get_stylesheet_directory() );
		
	}
    
    if( ! defined( 'THEME_URI' ) ) {
    
    	define( 'THEME_URI', get_stylesheet_directory_uri() );	
    	
    }
    
    if( ! defined( 'BASE_PATH' ) ) {
	    
	    define( 'BASE_PATH', '' );
	    
    }
    
    if( ! defined( 'APP_ROOT' ) ) {
	
    	define( 'APP_ROOT', THEME_DIR );
    	
    }
    
    if( ! defined( 'APP_ROOT_URI' ) ) {
	
    	define( 'APP_ROOT_URI', THEME_URI );
    	
    }
    
    if( ! defined( 'APP' ) ) {
	
    	define( 'APP', APP_ROOT . DS . 'app' );
    	
    }
    
    if( ! defined( 'APP_URI' ) ) {
    
    	define( 'APP_URI', APP_ROOT_URI . 'app' );
    	
    }
    
    if( ! defined( 'CONFIG_DIR' ) ) {
    
	    define( 'CONFIG_DIR', APP_ROOT . DS . 'config' );
	    
	}
	
	if( defined( 'PLUGINS_FOLDER' ) && APP_PLUGINS_FOLDER ) {
    
	    define( 'PLUGINS_DIR', APP . DS . PLUGINS_FOLDER );
	    define( 'PLUGINS_DIR_URI', APP_URI . DS . PLUGINS_FOLDER );
	    
	}
	
	if( ! defined( 'DEFAULT_LOCALE' ) ) {
		
		define('DEFAULT_LOCALE', 'EN' );
		
	}
	
	if( ! defined( 'DEFAULT_CURRENCY' ) ) {
		
		define('DEFAULT_CURRENCY', 'GBP' );
		
	}
	
	if( ! defined( 'ASSET_DIRS' ) ) {
		
		define( 'ASSET_DIRS', implode(',', [
	        'styles',
	        'scripts',
	        'images'
	    ] ) );
		
	}
	
	if( ! defined( 'COMPONENTS_FOLDER' ) ) {
    
    	define( 'COMPONENTS_FOLDER', 'Components' );
    	
    }
    
    if( ! defined( 'COMPONENTS_DIR' ) ) {
    
    	define( 'COMPONENTS_DIR', APP . DS . COMPONENTS_FOLDER );
    	
    }
    
    if( ! defined( 'VIEWS_FOLDER' ) ) {
	    
	    define( 'VIEWS_FOLDER', 'Views' );
	    
    }
    
    if( ! defined( 'VIEWS_DIR' ) ) {
	    
	    define( 'VIEWS_DIR', APP . DS . VIEWS_FOLDER );
	    
    }
    
    if( ! defined( 'TIMBER_VIEWS_FOLDER' ) ) {
	    
	    define( 'TIMBER_VIEWS_FOLDER', COMPONENTS_FOLDER );
	    
    }
    
    if( ! defined( 'TIMBER_VIEW_DIR' ) ) {
	    
	    define( 'TIMBER_VIEWS_DIR', APP . DS . TIMBER_VIEWS_FOLDER );
	    
    }
    
    if( ! defined( 'FUNCTIONS_DIR' ) ) {
    
    	define( 'FUNCTIONS_DIR', APP . DS . 'Functions' );
    	
    }
    
    if( ! defined( 'POST_TYPES_DIR' ) ) {
    
    	define( 'POST_TYPES_DIR', APP . DS . 'PostTypes' );
    	
    }
    
    if( ! defined( 'TAXONOMIES_DIR' ) ) {
    
    	define( 'TAXONOMIES_DIR', APP . DS . 'Taxonomies' );
    	
    }
    
    if( ! defined( 'SHORTCODES_DIR' ) ) {
    
    	define( 'SHORTCODES_DIR', APP . DS . 'Shortcodes' );
    	
    }
    
    if( ! defined( 'WIDGETS_DIR' ) ) {
    
    	define( 'WIDGETS_DIR', APP . DS . 'Widgets' );
    	
    }
    
    if( ! defined( 'WPKIT_DEBUG' ) ) {
	    
	    define( 'WPKIT_DEBUG', false );
	    
    }
    
    if( ! defined( 'INFLECTOR_DEFAULT_LOCALE' ) ) {
    
    	define( 'INFLECTOR_DEFAULT_LOCALE', strtolower( DEFAULT_LOCALE ) );
    	
    }
    