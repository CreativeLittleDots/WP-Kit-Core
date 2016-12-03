<?php
    
    namespace WPKit\Core;

	class Integration {
		
		 /**
	     * @var \WPKit\Application
	     */
	    protected $app;

    	public $settings = array();
    	
    	public function __construct( Application $app, $settings ) {
	    
	    	$this->app = $app;
	    
	    	$this->startIntegration($settings);
	    	
	    }
    	
    }