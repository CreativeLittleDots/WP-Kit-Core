<?php
    
    namespace WPKit\Integrations;
    
    use Illuminate\Support\ServiceProvider;

	class Integration extends ServiceProvider {
		
		/**
	     * The auth settings
	     *
	     * @var array
	     */
		protected $settings = array();
	    
	    /**
	     * Start the integration
	     *
	     * @param array $settings
	     * @return WPKit\Integrations\Integration
	     */
	    public function startIntegration( $settings = array() ) {
		    
		    return $this;
		    
	    }
    	
    }