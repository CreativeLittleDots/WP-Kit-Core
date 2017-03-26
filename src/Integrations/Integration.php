<?php
    
    namespace WPKit\Integrations;
    
    use Illuminate\Support\ServiceProvider;

	class Integration extends ServiceProvider {
		
		protected $settings = array();
    	
    	public function register() {
	    	
	    	$this->settings = $this->getSettings();
	    	
	    	$this->startIntegration();
	    	
	    }
	    
	    protected function getSettings() {
		    
		    $property = get_called_class() . '\IntegrationSettings';
		    
		    return ! empty( $this->app[ $property ] ) ? $this->app[ $property ] : [];
		    
	    }
    	
    }