<?php
	
	if ( ! defined('DS') ) {
		
		/**
	     * Shorthand constant helper
	     *
	     * @var  string 
	     */
	    define( 'DS', DIRECTORY_SEPARATOR );
	    
	}
	
	if( ! defined( 'THEME_DIR' ) ) {
	
		/**
	     * Shorthand theme path helper
	     *
	     * @var  string 
	     */
		define( 'THEME_DIR', get_stylesheet_directory() );
		
	}
    
    if( ! defined( 'THEME_URI' ) ) {
    
    	/**
	     * Shorthand theme uri helper
	     *
	     * @var  string 
	     */
    	define( 'THEME_URI', get_stylesheet_directory_uri() );	
    	
    }
    
    if( ! defined( 'BASE_PATH' ) ) {
	    
	    /**
	     * Base path for sub-directories
	     *
	     * @var  string 
	     */
	    define( 'BASE_PATH', '' );
	    
    }
    
    if( ! defined( 'APP_ROOT' ) ) {
	
		/**
	     * Short hand app root, for agnostic environments
	     *
	     * @var  string 
	     */
    	define( 'APP_ROOT', THEME_DIR );
    	
    }
    
    if( ! defined( 'APP_ROOT_URI' ) ) {
	
		/**
	     * Short hand app root uri, for agnostic environments
	     *
	     * @var  string 
	     */
    	define( 'APP_ROOT_URI', THEME_URI );
    	
    }
    
    if( ! defined( 'APP' ) ) {
	
		/**
	     * Path to app folder in wp-kit install
	     *
	     * @var  string 
	     */
    	define( 'APP', APP_ROOT . DS . 'app' );
    	
    }
    
    if( ! defined( 'APP_URI' ) ) {
    
    	/**
	     * Uri to app folder in wp-kit install
	     *
	     * @var  string 
	     */
    	define( 'APP_URI', APP_ROOT_URI . 'app' );
    	
    }
    
    if( ! defined( 'CONFIG_DIR' ) ) {
    
    	/**
	     * Path to config folder in wp-kit install
	     *
	     * @var  string 
	     */
	    define( 'CONFIG_DIR', APP_ROOT . DS . 'config' );
	    
	}
	
	if( defined( 'PLUGINS_FOLDER' ) && APP_PLUGINS_FOLDER ) {
    
    	/**
	     * Path to plugins folder in wp-kit install
	     *
	     * @var  string 
	     */
	    define( 'PLUGINS_DIR', APP . DS . PLUGINS_FOLDER );
	    
	    /**
	     * Uri to plugins folder in wp-kit install
	     *
	     * @var  string 
	     */
	    define( 'PLUGINS_DIR_URI', APP_URI . DS . PLUGINS_FOLDER );
	    
	}
	
	if( ! defined( 'DEFAULT_LOCALE' ) ) {
		
		/**
	     * Default locale for wp-kit
	     *
	     * @var  string 
	     */
		define('DEFAULT_LOCALE', 'EN' );
		
	}
	
	if( ! defined( 'DEFAULT_CURRENCY' ) ) {
		
		/**
	     * Default currency for currency helper
	     *
	     * @var  string 
	     */
		define('DEFAULT_CURRENCY', 'GBP' );
		
	}
	
	if( ! defined( 'ASSET_DIRS' ) ) {
		
		/**
	     * Array of folder to check in asset helpers
	     *
	     * @var  array 
	     */
		define( 'ASSET_DIRS', implode(',', [
	        'styles',
	        'scripts',
	        'images'
	    ] ) );
		
	}
	
	if( ! defined( 'COMPONENTS_FOLDER' ) ) {
    
    	/**
	     * Folder name for components
	     *
	     * @var  string 
	     */
    	define( 'COMPONENTS_FOLDER', 'Components' );
    	
    }
    
    if( ! defined( 'COMPONENTS_DIR' ) ) {
    
    	/**
	     * Path to components folder in wp-kit install
	     *
	     * @var  string 
	     */
    	define( 'COMPONENTS_DIR', APP . DS . COMPONENTS_FOLDER );
    	
    }
    
    if( ! defined( 'VIEWS_FOLDER' ) ) {
	    
	    /**
	     * Folder name for views
	     *
	     * @var  string 
	     */
	    define( 'VIEWS_FOLDER', 'Views' );
	    
    }
    
    if( ! defined( 'VIEWS_DIR' ) ) {
	    
	    /**
	     * Path to views folder in wp-kit install
	     *
	     * @var  string 
	     */
	    define( 'VIEWS_DIR', APP . DS . VIEWS_FOLDER );
	    
    }
    
    if( ! defined( 'FUNCTIONS_DIR' ) ) {
    
    	/**
	     * Path to functions folder in wp-kit install
	     *
	     * @var  string 
	     */
    	define( 'FUNCTIONS_DIR', APP . DS . 'Functions' );
    	
    }
    
    if( ! defined( 'POST_TYPES_DIR' ) ) {
    
    	/**
	     * Path to post types folder in wp-kit install
	     *
	     * @var  string 
	     */
    	define( 'POST_TYPES_DIR', APP . DS . 'PostTypes' );
    	
    }
    
    if( ! defined( 'TAXONOMIES_DIR' ) ) {
    
    	/**
	     * Path to taxonomies folder in wp-kit install
	     *
	     * @var  string 
	     */
    	define( 'TAXONOMIES_DIR', APP . DS . 'Taxonomies' );
    	
    }
    
    if( ! defined( 'SHORTCODES_DIR' ) ) {
    
    	/**
	     * Path to shortcodes folder in wp-kit install
	     *
	     * @var  string 
	     */
    	define( 'SHORTCODES_DIR', APP . DS . 'Shortcodes' );
    	
    }
    
    if( ! defined( 'WIDGETS_DIR' ) ) {
    
    	/**
	     * Path to widgets folder folder in wp-kit install
	     *
	     * @var  string 
	     */
    	define( 'WIDGETS_DIR', APP . DS . 'Widgets' );
    	
    }
    
    if( ! defined( 'WPKIT_DEBUG' ) ) {
	    
	    /**
	     * Boolean to display error messages in wp-kit
	     *
	     * @var  boolean 
	     */
	    define( 'WPKIT_DEBUG', false );
	    
    }
    
    if( ! defined( 'INFLECTOR_DEFAULT_LOCALE' ) ) {
    
    	/**
	     * Default locale for inflector
	     *
	     * @var  string 
	     */
    	define( 'INFLECTOR_DEFAULT_LOCALE', strtolower( DEFAULT_LOCALE ) );
    	
    }
    