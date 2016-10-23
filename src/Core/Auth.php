<?php
    
    namespace WPKit\Core;

	class Auth extends Singleton {
    	
    	public $settings = array();
    	
    	public function __construct( $settings ) {
	    	
	    	$this->settings = array_merge(array(
    			'allow' => array()
			), $settings);
			
			add_action( 'parse_request', array($this, 'authenticate') );
	    	
    	}
    	
    	public function authenticate() {}
    	
    }