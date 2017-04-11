<?php
    
    namespace WPKit\Integrations;
    
    use Illuminate\Support\ServiceProvider;

	class Integration extends ServiceProvider {
		
		protected $settings = array();
	    
	    public function startIntegration( $settings = array() ) {
		    
		    return $this;
		    
	    }
    	
    }